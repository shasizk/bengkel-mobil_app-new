<?php

namespace App\Http\Controllers;

use App\Mail\TransactionPaidInvoiceMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Sparepart;
use App\Models\Transaction;
use App\Models\TransactionSparepart;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class TransactionController extends Controller
{
    public function __construct()
    {
        view()->share('title', 'Transaction');
    }

    public function index()
    {
        $transactions = Transaction::with(['booking.vehicle.user', 'booking.mechanic', 'mekanik', 'kasir', 'payment'])->latest()->get();

        return view('transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $selectedId = $request->query('booking_id');

        $bookings = Booking::with(['vehicle.user', 'services.service', 'mechanic'])
            ->where(function ($query) use ($selectedId) {
                $query->where('status', 'completed')
                    ->whereDoesntHave('transaction.payment', function ($paymentQuery) {
                        $paymentQuery->where('payment_status', 'paid');
                    });

                if ($selectedId) {
                    $query->orWhere('id', $selectedId);
                }
            })
            ->get()
            ->map(function ($booking) {
                $booking->service_total = (float) $booking->services->sum('price');
                $booking->service_summary = $booking->services
                    ->map(fn ($item) => $item->service?->service_name)
                    ->filter()
                    ->values()
                    ->all();
                $booking->mechanic_name = $booking->mechanic?->name;

                return $booking;
            });
        $spareparts = Sparepart::where('stock', '>', 0)->get();

        return view('transactions.create', [
            'bookings' => $bookings,
            'spareparts' => $spareparts,
            'selected_id' => $selectedId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'mekanik_id' => 'nullable|exists:users,id',
            'total_service' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,qris',
            'payment_status' => 'required|in:unpaid,partial,paid',
        ]);

        $existingTransaction = Transaction::with('payment')
            ->where('booking_id', $request->booking_id)
            ->latest('id')
            ->first();

        if ($existingTransaction) {
            $existingPaymentStatus = $existingTransaction->payment?->payment_status;

            if ($existingPaymentStatus === 'paid') {
                return redirect()
                    ->to(backend_route('admin.transactions.show', [$existingTransaction->id]))
                    ->with('error', 'Booking ini sudah lunas. Invoice terbaru sudah tersedia dan tidak bisa dibuat pembayaran ulang.');
            }

            return redirect()
                ->to(backend_route('admin.transactions.show', [$existingTransaction->id]))
                ->with('warning', 'Booking ini sudah memiliki invoice aktif. Silakan lanjutkan pembayaran pada invoice tersebut.');
        }

        DB::beginTransaction();

        try {
            $booking = Booking::with(['user', 'services.service', 'mechanic'])->findOrFail($request->booking_id);
            $defaultService = (float) $booking->services->sum('price');
            $totalService = $request->filled('total_service')
                ? (float) $request->total_service
                : $defaultService;
            $assignedMechanicId = $booking->mekanik_id ?: $request->mekanik_id;

            if (! $assignedMechanicId) {
                throw new \RuntimeException('Booking belum memiliki mekanik penanggung jawab.');
            }
            $totalSparepart = 0;

            $transaction = Transaction::create([
                'booking_id' => $request->booking_id,
                'mekanik_id' => $assignedMechanicId,
                'kasir_id' => backend_user()?->id,
                'total_service' => $totalService,
                'total_sparepart' => 0,
                'grand_total' => 0,
            ]);

            if ($request->has('sparepart_id')) {
                foreach ($request->sparepart_id as $key => $sparepartId) {
                    if (! $sparepartId) {
                        continue;
                    }

                    $qty = (int) ($request->qty[$key] ?? 0);
                    $price = (float) ($request->price[$key] ?? 0);

                    if ($qty <= 0) {
                        continue;
                    }

                    $sparepart = Sparepart::findOrFail($sparepartId);

                    if ($sparepart->stock < $qty) {
                        throw new \RuntimeException("Stok {$sparepart->name} tidak cukup.");
                    }

                    TransactionSparepart::create([
                        'transaction_id' => $transaction->id,
                        'sparepart_id' => $sparepartId,
                        'qty' => $qty,
                        'price' => $price,
                        'subtotal' => $qty * $price,
                    ]);

                    $sparepart->decrement('stock', $qty);
                    $totalSparepart += $qty * $price;
                }
            }

            $grandTotal = $totalService + $totalSparepart;

            $transaction->update([
                'total_sparepart' => $totalSparepart,
                'grand_total' => $grandTotal,
            ]);

            $isGatewayPayment = $request->payment_method !== 'cash';
            $paymentCode = 'PAY-' . strtoupper(bin2hex(random_bytes(4)));
            $paymentStatus = $isGatewayPayment ? 'unpaid' : $request->payment_status;

            $payment = Payment::create([
                'transaction_id' => $transaction->id,
                'payment_code' => $paymentCode,
                'amount_paid' => $paymentStatus === 'paid' ? $grandTotal : 0,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'gateway_status' => $isGatewayPayment ? 'pending' : null,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
            ]);

            if ($paymentStatus === 'paid') {
                $this->sendPaidInvoiceEmail($payment->fresh('transaction.booking.user', 'transaction.booking.vehicle', 'transaction.booking.services.service', 'transaction.mekanik', 'transaction.kasir', 'transaction.details.sparepart', 'transaction.payment'));
                $this->finalizeBookingOnPaid($payment->fresh('transaction.booking'));
            }

            DB::commit();

            if ($isGatewayPayment) {
                try {
                    $freshTransaction = $transaction->fresh(['booking.user', 'details.sparepart', 'payment']);
                    $gatewayPayload = $this->createMidtransPayload($freshTransaction);

                    $this->updatePaymentGatewayData($payment->fresh(), [
                        'snap_token' => $gatewayPayload['token'] ?? null,
                        'payment_url' => $gatewayPayload['redirect_url'] ?? null,
                        'gateway_status' => 'pending',
                        'gateway_response' => json_encode($gatewayPayload['raw'] ?? [], JSON_UNESCAPED_SLASHES),
                    ]);

                    return redirect()
                        ->to(backend_route('admin.transactions.show', [$transaction->id]))
                        ->with('success', 'Transaksi gateway berhasil dibuat. Silakan lanjutkan pembayaran dari invoice.');
                } catch (\Throwable $gatewayException) {
                    $this->updatePaymentGatewayData($payment->fresh(), [
                        'gateway_status' => 'failed',
                        'gateway_response' => $gatewayException->getMessage(),
                    ]);

                    return redirect()
                        ->to(backend_route('admin.transactions.show', [$transaction->id]))
                        ->with('error', 'Transaksi tersimpan, tetapi koneksi Midtrans gagal: ' . $gatewayException->getMessage());
                }
            }

            return redirect()
                ->to(backend_route('admin.transactions.show', [$transaction->id]))
                ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with(['booking.vehicle.user', 'booking.services.service', 'mekanik', 'kasir', 'details.sparepart', 'payment'])->findOrFail($id);
        $midtransClientKey = config('services.midtrans.clientKey');

        return view('transactions.show', compact('transaction', 'midtransClientKey'));
    }

    public function pdf($id)
    {
        $transaction = Transaction::with(['booking.vehicle.user', 'booking.services.service', 'mekanik', 'kasir', 'details.sparepart', 'payment'])->findOrFail($id);

        $pdf = Pdf::loadView('transactions.pdf', compact('transaction'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-'.$transaction->id.'.pdf');
    }

    public function callback(Request $request): JsonResponse
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (! $orderId) {
            return response()->json(['message' => 'order_id tidak ditemukan.'], 422);
        }

        $payment = Payment::where('payment_code', $orderId)->first();

        if (! $payment) {
            return response()->json(['message' => 'Payment tidak ditemukan.'], 404);
        }

        $signatureKey = $payload['signature_key'] ?? null;
        $serverKey = (string) config('services.midtrans.serverKey');
        $expectedSignature = hash('sha512', $orderId . ($payload['status_code'] ?? '') . ($payload['gross_amount'] ?? '') . $serverKey);

        if ($serverKey && $signatureKey && ! hash_equals($expectedSignature, $signatureKey)) {
            return response()->json(['message' => 'Signature callback Midtrans tidak valid.'], 403);
        }

        [$paymentStatus, $gatewayStatus] = $this->resolveGatewayStatus($transactionStatus, $fraudStatus);

        $this->applyGatewayStatus($payment, $payload, $paymentStatus, $gatewayStatus);

        return response()->json([
            'message' => 'Callback berhasil diproses.',
            'payment_status' => $paymentStatus,
            'gateway_status' => $gatewayStatus,
        ]);
    }

    public function clientUpdateStatus(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'order_id' => 'required|string',
            'transaction_status' => 'required|string',
            'payment_type' => 'nullable|string',
            'fraud_status' => 'nullable|string',
            'gross_amount' => 'nullable',
        ]);

        $payment = Payment::where('payment_code', $payload['order_id'])->first();

        if (! $payment) {
            return response()->json(['message' => 'Payment tidak ditemukan.'], 404);
        }

        [$paymentStatus, $gatewayStatus] = $this->resolveGatewayStatus($payload['transaction_status'], $payload['fraud_status'] ?? null);
        $this->applyGatewayStatus($payment, $payload, $paymentStatus, $gatewayStatus);

        return response()->json([
            'message' => 'Status pembayaran berhasil disinkronkan.',
            'payment_status' => $paymentStatus,
        ]);
    }

    public function paymentFinish(Request $request): RedirectResponse
    {
        $client = auth('client')->user();
        $orderId = (string) $request->query('order_id', '');

        if (! $client || $orderId === '') {
            return redirect()
                ->route('client.history')
                ->with('error', 'Data pembayaran tidak ditemukan saat kembali dari Midtrans.');
        }

        $payment = Payment::with('transaction.booking')
            ->where('payment_code', $orderId)
            ->first();

        if (! $payment || (int) ($payment->transaction?->booking?->user_id ?? 0) !== (int) $client->id) {
            return redirect()
                ->route('client.history')
                ->with('error', 'Pembayaran tidak valid untuk akun ini.');
        }

        $payload = [
            'order_id' => $orderId,
            'transaction_status' => (string) $request->query('transaction_status', ''),
            'fraud_status' => (string) $request->query('fraud_status', ''),
            'payment_type' => (string) $request->query('payment_type', ''),
            'gross_amount' => (string) $request->query('gross_amount', ''),
        ];

        if ($payload['transaction_status'] === '' || $payload['transaction_status'] === 'pending') {
            $payload = $this->fetchMidtransStatusPayload($orderId, $payload);
        }

        [$paymentStatus, $gatewayStatus] = $this->resolveGatewayStatus($payload['transaction_status'] ?: 'pending', $payload['fraud_status'] ?: null);
        $this->applyGatewayStatus($payment, $payload, $paymentStatus, $gatewayStatus);

        $flashType = $paymentStatus === 'paid' ? 'success' : 'warning';
        $flashMessage = $paymentStatus === 'paid'
            ? 'Pembayaran berhasil. Status invoice sudah diperbarui menjadi lunas.'
            : 'Pembayaran Anda masih diproses. Silakan cek status di riwayat booking.';

        return redirect()->route('client.history')->with($flashType, $flashMessage);
    }

    protected function createMidtransPayload(Transaction $transaction): array
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        if (! Config::$serverKey) {
            throw new \RuntimeException('Server Key Midtrans belum dikonfigurasi.');
        }

        $itemDetails = [[
            'id' => 'SERVICE-' . $transaction->booking_id,
            'price' => (int) $transaction->total_service,
            'quantity' => 1,
            'name' => 'Jasa Servis #' . $transaction->booking_id,
        ]];

        foreach ($transaction->details as $detail) {
            $itemDetails[] = [
                'id' => 'SP-' . $detail->sparepart_id,
                'price' => (int) $detail->price,
                'quantity' => (int) $detail->qty,
                'name' => substr($detail->sparepart->name ?? 'Sparepart', 0, 50),
            ];
        }

        $response = Snap::createTransaction([
            'transaction_details' => [
                'order_id' => $transaction->payment->payment_code,
                'gross_amount' => (int) $transaction->grand_total,
            ],
            'customer_details' => [
                'first_name' => $transaction->booking->user->name ?? 'Customer',
                'email' => $transaction->booking->user->email ?? 'customer@mail.com',
                'phone' => $transaction->booking->user->phone ?? '',
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('client.payment.finish'),
                'unfinish' => route('client.payment.finish'),
                'error' => route('client.payment.finish'),
            ],
        ]);

        return [
            'token' => $response->token ?? null,
            'redirect_url' => $response->redirect_url ?? null,
            'raw' => (array) $response,
        ];
    }

    protected function resolveGatewayStatus(?string $transactionStatus, ?string $fraudStatus): array
    {
        return match ($transactionStatus) {
            'capture' => [$fraudStatus === 'challenge' ? 'unpaid' : 'paid', $fraudStatus === 'challenge' ? 'challenge' : 'capture'],
            'settlement' => ['paid', 'settlement'],
            'pending' => ['unpaid', 'pending'],
            'deny' => ['unpaid', 'deny'],
            'cancel' => ['unpaid', 'cancel'],
            'expire' => ['unpaid', 'expire'],
            'failure' => ['unpaid', 'failure'],
            default => ['unpaid', (string) ($transactionStatus ?? 'unknown')],
        };
    }

    protected function normalizeGatewayMethod(?string $paymentType): string
    {
        return match ($paymentType) {
            'bank_transfer' => 'transfer',
            'credit_card', 'debit_card' => 'transfer',
            'qris', 'gopay', 'shopeepay', 'echannel', 'cstore', 'akulaku' => $paymentType,
            default => $paymentType ?: 'qris',
        };
    }

    protected function updatePaymentGatewayData(Payment $payment, array $data): void
    {
        $allowedColumns = collect(array_keys($data))
            ->filter(fn (string $column) => Schema::hasColumn('payments', $column))
            ->values()
            ->all();

        if ($allowedColumns !== []) {
            $payment->forceFill(collect($data)->only($allowedColumns)->all())->save();
        }
    }

    protected function applyGatewayStatus(Payment $payment, array $payload, string $paymentStatus, string $gatewayStatus): void
    {
        $previousStatus = $payment->payment_status;

        $data = [
            'payment_method' => $this->normalizeGatewayMethod($payload['payment_type'] ?? $payment->payment_method),
            'payment_status' => $paymentStatus,
            'gateway_status' => $gatewayStatus,
            'gateway_response' => json_encode($payload, JSON_UNESCAPED_SLASHES),
            'amount_paid' => $paymentStatus === 'paid'
                ? (float) ($payload['gross_amount'] ?? $payment->transaction?->grand_total ?? $payment->amount_paid)
                : ($paymentStatus === 'partial' ? $payment->amount_paid : 0),
            'paid_at' => $paymentStatus === 'paid' ? Carbon::now() : null,
        ];

        $this->updatePaymentGatewayData($payment, $data);

        if ($previousStatus !== 'paid' && $paymentStatus === 'paid') {
            $this->sendPaidInvoiceEmail($payment->fresh('transaction.booking.user', 'transaction.booking.vehicle', 'transaction.booking.services.service', 'transaction.mekanik', 'transaction.kasir', 'transaction.details.sparepart', 'transaction.payment'));
            $this->finalizeBookingOnPaid($payment->fresh('transaction.booking'));
        }
    }

    protected function finalizeBookingOnPaid(?Payment $payment): void
    {
        $booking = $payment?->transaction?->booking;

        if (! $booking) {
            return;
        }

        if ($booking->status !== 'completed' && $booking->status !== 'cancelled') {
            $booking->update([
                'status' => 'completed',
            ]);
        }
    }

    protected function fetchMidtransStatusPayload(string $orderId, array $fallbackPayload): array
    {
        try {
            Config::$serverKey = config('services.midtrans.serverKey');
            Config::$isProduction = config('services.midtrans.isProduction');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            if (! Config::$serverKey) {
                return $fallbackPayload;
            }

            $status = MidtransTransaction::status($orderId);

            return [
                'order_id' => $orderId,
                'transaction_status' => (string) ($status->transaction_status ?? ''),
                'fraud_status' => (string) ($status->fraud_status ?? ''),
                'payment_type' => (string) ($status->payment_type ?? ''),
                'gross_amount' => (string) ($status->gross_amount ?? ''),
                'raw' => (array) $status,
            ];
        } catch (\Throwable $e) {
            Log::warning('Gagal mengambil status Midtrans saat return client.', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return $fallbackPayload;
        }
    }

    protected function sendPaidInvoiceEmail(?Payment $payment): void
    {
        $transaction = $payment?->transaction;
        $email = $transaction?->booking?->user?->email;

        if (! $transaction || ! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new TransactionPaidInvoiceMail($transaction));
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email invoice pembayaran.', [
                'transaction_id' => $transaction->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
