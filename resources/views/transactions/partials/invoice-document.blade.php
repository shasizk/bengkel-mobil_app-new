@php($payment = $transaction->payment)
@php($serviceRows = $transaction->booking->services ?? collect())
@php($sparepartRows = $transaction->details ?? collect())

<div class="invoice-paper" style="overflow-x: auto;">
    <table>
        <tr>
            <td style="width: 68%; border-right: none; border-bottom: none;">
                <div style="font-weight: 700; font-size: 17px; letter-spacing: .04em;">RAXY GARAGE</div>
                <div style="font-size: 11px; color: #4b5563;">Jl. KH Usman Dhomiri No. 59, Cimahi</div>
                <div style="font-size: 11px; color: #4b5563;">Telp: {{ $transaction->booking->vehicle->user->phone ?? '-' }}</div>
            </td>
            <td style="width: 32%; text-align: right; border-left: none; border-bottom: none;">
                <div style="display: inline-block; border: 1px solid #111827; font-weight: 700; font-size: 10px; padding: 2px 8px; margin-bottom: 8px;">INVOICE</div>
                <div style="font-size: 11px;"><strong>No. Invoice:</strong> #{{ $transaction->id }}</div>
                <div style="font-size: 11px;"><strong>Tgl:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                <div style="font-size: 11px;"><strong>Kode Bayar:</strong> {{ $payment->payment_code ?? '-' }}</div>
            </td>
        </tr>
    </table>

    <table style="margin-top: 8px;">
        <tr>
            <td style="width: 50%;">
                <div class="line-title">Pelanggan</div>
                <div><strong>{{ $transaction->booking->vehicle->user->name ?? '-' }}</strong></div>
                <div>{{ $transaction->booking->vehicle->brand ?? '-' }} / {{ $transaction->booking->vehicle->license_plate ?? '-' }}</div>
                <div>Tahun: {{ $transaction->booking->vehicle->year ?? '-' }}</div>
            </td>
            <td style="width: 50%;">
                <div class="line-title">Info Pengerjaan</div>
                <div>Mekanik: <strong>{{ $transaction->mekanik->name ?? '-' }}</strong></div>
                <div>Kasir: <strong>{{ $transaction->kasir->name ?? '-' }}</strong></div>
                <div>Metode: <strong>{{ strtoupper($payment->payment_method ?? '-') }}</strong></div>
                <div>Status: <strong>{{ strtoupper($payment->payment_status ?? 'UNPAID') }}</strong></div>
            </td>
        </tr>
    </table>

    <table style="margin-top: 8px;">
        <tr class="table-title">
            <td style="width: 8%; text-align: center;">No</td>
            <td>Item Jasa/Layanan</td>
            <td style="width: 22%; text-align: right;">Subtotal</td>
        </tr>
        @forelse($serviceRows as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->service->service_name ?? 'Jasa Service' }}</td>
                <td style="text-align: right;">Rp {{ number_format((float) ($item->price ?? 0), 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td style="text-align: center;">1</td>
                <td>Jasa Service / Perbaikan</td>
                <td style="text-align: right;">Rp {{ number_format((float) $transaction->total_service, 0, ',', '.') }}</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="2" style="text-align: right;"><strong>Subtotal Jasa</strong></td>
            <td style="text-align: right;"><strong>Rp {{ number_format((float) $transaction->total_service, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <table style="margin-top: 8px;">
        <tr class="table-title">
            <td style="width: 8%; text-align: center;">No</td>
            <td>Item Sparepart</td>
            <td style="width: 12%; text-align: center;">Qty</td>
            <td style="width: 18%; text-align: right;">Harga</td>
            <td style="width: 22%; text-align: right;">Subtotal</td>
        </tr>
        @forelse($sparepartRows as $index => $detail)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $detail->sparepart->name ?? 'Sparepart' }}</td>
                <td style="text-align: center;">{{ (int) $detail->qty }}</td>
                <td style="text-align: right;">Rp {{ number_format((float) $detail->price, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td style="text-align: center;">-</td>
                <td colspan="4" style="text-align: center;">Tidak ada sparepart</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="4" style="text-align: right;"><strong>Subtotal Sparepart</strong></td>
            <td style="text-align: right;"><strong>Rp {{ number_format((float) $transaction->total_sparepart, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <table style="margin-top: 8px; width: 52%; margin-left: auto;">
        <tr>
            <td style="width: 56%;"><strong>SUBTOTAL</strong></td>
            <td style="text-align: right;">Rp {{ number_format((float) ($transaction->total_service + $transaction->total_sparepart), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>PPN</strong></td>
            <td style="text-align: right;">Rp 0</td>
        </tr>
        <tr>
            <td><strong>TOTAL TAGIHAN</strong></td>
            <td style="text-align: right;">Rp {{ number_format((float) $transaction->grand_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>DISKON</strong></td>
            <td style="text-align: right;">Rp 0</td>
        </tr>
        <tr>
            <td><strong>BAYAR & KEMBALI</strong></td>
            <td style="text-align: right;">Rp {{ number_format((float) ($payment->amount_paid ?? 0), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>PAYMENT METHOD</strong></td>
            <td style="text-align: right;">{{ strtoupper($payment->payment_method ?? '-') }}</td>
        </tr>
    </table>

    <table style="margin-top: 10px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div class="line-title">Customer</div>
                <div style="margin-top: 22px; font-weight: 600;">({{ $transaction->booking->vehicle->user->name ?? '-' }})</div>
            </td>
            <td style="width: 50%; text-align: center;">
                <div class="line-title">Admin/Kasir</div>
                <div style="margin-top: 22px; font-weight: 600;">({{ $transaction->kasir->name ?? '-' }})</div>
            </td>
        </tr>
    </table>
</div>
