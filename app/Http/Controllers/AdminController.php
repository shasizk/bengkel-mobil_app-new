<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Sparepart;
use App\Models\Transaction;
use App\Models\TransactionSparepart;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = backend_user();
        $newBookingCount = Schema::hasTable('bookings') ? Booking::where('status', 'pending')->count() : 0;

        $pendingIncome = Transaction::query()
            ->whereDoesntHave('payment', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->sum('grand_total');

        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'progress_bookings' => Booking::where('status', 'in_progress')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'vehicles' => Vehicle::count(),
            'transactions' => Transaction::count(),
            'paid_income' => (float) Payment::where('payment_status', 'paid')->sum('amount_paid'),
            'pending_income' => (float) $pendingIncome,
            'sparepart_outgoing' => (float) TransactionSparepart::sum('subtotal'),
            'low_stock' => Sparepart::where('stock', '<=', 5)->count(),
        ];

        $recentBookings = Booking::with(['user', 'vehicle', 'services.service'])->latest()->take(5)->get();
        $recentTransactions = Transaction::with(['booking.vehicle.user', 'payment', 'mekanik', 'kasir'])->latest()->take(5)->get();

        $topVehicleBrands = DB::table('bookings')
            ->join('vehicles', 'vehicles.id', '=', 'bookings.vehicle_id')
            ->select('vehicles.brand', DB::raw('COUNT(*) as total'))
            ->groupBy('vehicles.brand')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topServices = DB::table('booking_service')
            ->join('services', 'services.id', '=', 'booking_service.service_id')
            ->select('services.service_name', DB::raw('COUNT(*) as total'))
            ->groupBy('services.service_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topSpareparts = DB::table('transaction_spareparts')
            ->join('spareparts', 'spareparts.id', '=', 'transaction_spareparts.sparepart_id')
            ->select('spareparts.name', DB::raw('SUM(transaction_spareparts.qty) as total_qty'))
            ->groupBy('spareparts.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $staffSummary = User::select('role', DB::raw('COUNT(*) as total'))
            ->whereIn('role', ['admin', 'mekanik', 'kasir', 'owner', 'customer'])
            ->groupBy('role')
            ->pluck('total', 'role');

        $monthBuckets = collect(range(5, 0))->map(function (int $monthsAgo) {
            return Carbon::now()->startOfMonth()->subMonths($monthsAgo);
        })->push(Carbon::now()->startOfMonth());

        $bookingTrendRaw = Booking::selectRaw("DATE_FORMAT(booking_date, '%Y-%m') as period, COUNT(*) as total")
            ->whereDate('booking_date', '>=', Carbon::now()->startOfMonth()->subMonths(5))
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $incomeTrendRaw = Transaction::selectRaw("DATE_FORMAT(transactions.created_at, '%Y-%m') as period")
            ->selectRaw("SUM(CASE WHEN payments.payment_status = 'paid' THEN payments.amount_paid ELSE 0 END) as total")
            ->leftJoin('payments', 'payments.transaction_id', '=', 'transactions.id')
            ->whereDate('transactions.created_at', '>=', Carbon::now()->startOfMonth()->subMonths(5))
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $chartData = [
            'months' => $monthBuckets->map(fn (Carbon $month) => $month->translatedFormat('M Y'))->values()->all(),
            'booking_trend' => $monthBuckets->map(fn (Carbon $month) => (int) ($bookingTrendRaw[$month->format('Y-m')] ?? 0))->values()->all(),
            'income_trend' => $monthBuckets->map(fn (Carbon $month) => (float) ($incomeTrendRaw[$month->format('Y-m')] ?? 0))->values()->all(),
            'booking_status_labels' => ['Pending', 'Confirmed', 'In Progress', 'Completed', 'Cancelled'],
            'booking_status_values' => [
                (int) Booking::where('status', 'pending')->count(),
                (int) Booking::where('status', 'confirmed')->count(),
                (int) Booking::where('status', 'in_progress')->count(),
                (int) Booking::where('status', 'completed')->count(),
                (int) Booking::where('status', 'cancelled')->count(),
            ],
            'role_labels' => ['Admin', 'Mekanik', 'Kasir', 'Owner', 'Customer'],
            'role_values' => [
                (int) ($staffSummary['admin'] ?? 0),
                (int) ($staffSummary['mekanik'] ?? 0),
                (int) ($staffSummary['kasir'] ?? 0),
                (int) ($staffSummary['owner'] ?? 0),
                (int) ($staffSummary['customer'] ?? 0),
            ],
        ];

        return view('be.dashboard.index', [
            'title' => 'Dashboard',
            'newBookingCount' => $newBookingCount,
            'currentBackendRole' => $currentUser?->role,
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'recentTransactions' => $recentTransactions,
            'topVehicleBrands' => $topVehicleBrands,
            'topServices' => $topServices,
            'topSpareparts' => $topSpareparts,
            'staffSummary' => $staffSummary,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
