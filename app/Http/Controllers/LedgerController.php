<?php

namespace App\Http\Controllers;

use App\Models\LedgerEntry;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LedgerController extends Controller
{
    public function __construct()
    {
        view()->share('title', 'Ledger');
    }

    public function index()
    {
        if (! Schema::hasTable('ledger_entries')) {
            return view('ledger.index', [
                'entries' => collect(),
                'summary' => [
                    'paid_income' => 0,
                    'manual_income' => 0,
                    'expenses' => 0,
                    'balance' => 0,
                    'equipment_expense' => 0,
                ],
                'incomeByMethod' => collect(),
                'expenseByCategory' => collect(),
                'equipmentExpenses' => collect(),
                'monthlyLedger' => collect(),
                'paidTransactions' => collect(),
                'ledgerTableMissing' => true,
            ]);
        }

        $entries = LedgerEntry::with('creator')->latest('entry_date')->latest('id')->get();
        $paidIncome = (float) Payment::where('payment_status', 'paid')->sum('amount_paid');
        $manualIncome = (float) LedgerEntry::where('type', 'income')->sum('amount');
        $expenses = (float) LedgerEntry::where('type', 'expense')->sum('amount');

        $incomeByMethod = Payment::select('payment_method', DB::raw('SUM(amount_paid) as total'))
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $expenseByCategory = LedgerEntry::select('category', DB::raw('SUM(amount) as total'))
            ->where('type', 'expense')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $equipmentExpenses = LedgerEntry::where('type', 'expense')
            ->where(function ($query) {
                $query->where('category', 'like', '%alat%')
                    ->orWhere('category', 'like', '%peralatan%')
                    ->orWhere('category', 'like', '%tools%')
                    ->orWhere('description', 'like', '%alat%')
                    ->orWhere('description', 'like', '%peralatan%')
                    ->orWhere('description', 'like', '%tools%');
            })
            ->latest('entry_date')
            ->latest('id')
            ->get();

        $monthlyLedger = LedgerEntry::selectRaw("DATE_FORMAT(entry_date, '%Y-%m') as period")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income_total")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense_total")
            ->groupBy('period')
            ->orderByDesc('period')
            ->limit(6)
            ->get();

        $paidTransactions = Transaction::with(['booking.vehicle.user', 'payment'])
            ->whereHas('payment', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->latest()
            ->take(10)
            ->get();

        $summary = [
            'paid_income' => $paidIncome,
            'manual_income' => $manualIncome,
            'expenses' => $expenses,
            'balance' => ($paidIncome + $manualIncome) - $expenses,
            'equipment_expense' => (float) $equipmentExpenses->sum('amount'),
        ];

        return view('ledger.index', compact(
            'entries',
            'summary',
            'incomeByMethod',
            'expenseByCategory',
            'equipmentExpenses',
            'monthlyLedger',
            'paidTransactions'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        LedgerEntry::create([
            'entry_date' => $request->entry_date,
            'type' => $request->type,
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'created_by' => backend_user()?->id,
        ]);

        return redirect()->to(backend_route('admin.ledger.index'))->with('success', 'Pembukuan berhasil ditambahkan.');
    }
}
