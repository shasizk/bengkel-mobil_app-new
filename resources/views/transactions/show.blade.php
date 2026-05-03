@extends('be.master')
@section('Transaction')
@php($payment = $transaction->payment)
@php($isGatewayPayment = in_array($payment->payment_method ?? null, ['qris', 'transfer', 'bank_transfer', 'credit_card', 'gopay', 'shopeepay'], true) || filled($payment->payment_url) || filled($payment->snap_token))
@php($paymentStatus = $payment->payment_status ?? 'unpaid')
<style>
@page {
    size: A4;
    margin: 3mm;
}

.invoice-paper {
    background: #fff;
    border: 1px solid #d1d5db;
    padding: 14px;
    font-size: 12px;
    color: #111827;
}

.invoice-paper .line-title {
    font-size: 11px;
    color: #4b5563;
    text-transform: uppercase;
    letter-spacing: .03em;
}

.invoice-paper table {
    width: 100%;
    border-collapse: collapse;
}

.invoice-paper table th,
.invoice-paper table td {
    border: 1px solid #d1d5db;
    padding: 6px 8px;
    vertical-align: top;
}

.invoice-paper .table-title {
    background: #f3f4f6;
    font-weight: 700;
    text-transform: uppercase;
}

@media print {
    @page {
        size: A4;
        margin: 3mm;
    }

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        width: 210mm;
        background: #fff !important;
    }

    body * {
        visibility: hidden;
    }

    #invoice-card, #invoice-card * {
        visibility: visible;
    }

    #invoice-card {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        width: 204mm;
        max-width: 204mm;
        margin: 0 auto !important;
        padding: 0;
        box-shadow: none !important;
        border: none !important;
        background: #fff !important;
    }

    .container,
    .page-inner,
    .card-body {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .invoice-paper {
        border: 1px solid #111827;
        min-height: 0;
        padding: 6mm 7mm;
        font-size: 10px;
        box-sizing: border-box;
        page-break-inside: avoid;
        break-inside: avoid;
        transform: scale(.99);
        transform-origin: top center;
    }

    .invoice-paper table td,
    .invoice-paper table th {
        padding: 5px 7px;
    }

    .d-print-none {
        display: none !important;
    }
}
</style>
<div class="container">
    <div class="page-inner">
        <div class="card border-0 shadow-sm col-xl-10 mx-auto overflow-hidden" id="invoice-card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap border-bottom d-print-none">
                <div>
                    <h4 class="mb-1">Invoice #{{ $transaction->id }}</h4>
                    <small class="text-muted">Kode bayar: {{ $payment->payment_code ?? '-' }}</small>
                </div>
                <span class="text-muted">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="card-body">
                @if($isGatewayPayment)
                    <div class="alert {{ $paymentStatus === 'paid' ? 'alert-success' : 'alert-warning' }} d-print-none">
                        <strong>Status Gateway:</strong> {{ strtoupper($paymentStatus) }}
                        @if($paymentStatus !== 'paid')
                            <span class="d-block mt-1">Selesaikan pembayaran melalui Midtrans agar status menjadi lunas.</span>
                            <span class="d-block mt-1">Halaman ini akan memuat ulang otomatis untuk sinkron status terbaru dari callback.</span>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3 d-print-none">
                        @if(!empty($payment->payment_url))
                            <a href="{{ $payment->payment_url }}" target="_blank" class="btn btn-outline-primary btn-sm">Buka Halaman Pembayaran</a>
                        @endif
                        @if($paymentStatus !== 'paid' && !empty($payment->snap_token) && !empty($midtransClientKey) && !str_contains($midtransClientKey, 'xxxxxxxx'))
                            <button type="button" class="btn btn-primary btn-sm" id="show-qris-button">Buka Midtrans Snap</button>
                        @endif
                    </div>
                @endif

                @include('transactions.partials.invoice-document', ['transaction' => $transaction])
            </div>
            <div class="card-footer d-flex justify-content-between d-print-none">
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-secondary"><i class="fa fa-print"></i> Cetak</button>
                    <a href="{{ backend_route('admin.transactions.pdf', [$transaction->id]) }}" class="btn btn-primary"><i class="fa fa-file-pdf"></i> Download PDF</a>
                </div>
                <a href="{{ backend_route('admin.transactions.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

@if($isGatewayPayment && $paymentStatus !== 'paid' && !empty($payment->snap_token) && !empty($midtransClientKey) && !str_contains($midtransClientKey, 'xxxxxxxx'))
    <script src="https://app{{ config('services.midtrans.isProduction') ? '' : '.sandbox' }}.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        async function syncGatewayResult(result) {
            await fetch(@json(route('midtrans.client-update')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(result)
            });
        }

        document.getElementById('show-qris-button')?.addEventListener('click', function () {
            window.snap.pay(@json($payment->snap_token), {
                onSuccess: async function (result) {
                    await syncGatewayResult(result);
                    window.location.reload();
                },
                onPending: async function (result) {
                    await syncGatewayResult(result);
                    window.location.reload();
                },
                onClose: function () {
                    console.log('Snap popup ditutup sebelum pembayaran selesai.');
                }
            });
        });

        setInterval(function () {
            if (!document.hidden) {
                window.location.reload();
            }
        }, 20000);
    </script>
@endif
@endsection
