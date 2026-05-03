@extends('be.master')

@section('Transaction')
<div class="container-fluid" style="margin-top: 30px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">{{ ($backendAuthUser->role ?? null) === 'kasir' ? 'Data Payment' : 'Riwayat Transaksi' }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select class="form-select form-select-sm" data-table-filter="payment-status" style="min-width: 160px;">
                    <option value="">Semua Status</option>
                    <option value="paid">Lunas</option>
                    <option value="partial">Partial</option>
                    <option value="unpaid">Belum Bayar</option>
                </select>
                <select class="form-select form-select-sm" data-table-filter="payment-method" style="min-width: 180px;">
                    <option value="">Semua Metode</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer / Debit</option>
                    <option value="qris">QRIS</option>
                </select>
                @if(in_array($backendAuthUser->role ?? null, ['admin', 'kasir', 'owner'], true))
                    <a href="{{ backend_route('admin.transactions.create') }}" class="btn btn-primary btn-round">+ Transaksi Baru</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Kode Bayar</th>
                            <th>Pelanggan / Kendaraan</th>
                            <th>Mekanik</th>
                            <th>Grand Total</th>
                            <th>Status Bayar</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
                        <tr
                            data-search-row="1"
                            data-search-text="#TRX-{{ $t->id }} {{ $t->booking->vehicle->user->name ?? '' }} {{ $t->booking->vehicle->license_plate ?? '' }} {{ $t->booking->vehicle->brand ?? '' }} {{ $t->booking->vehicle->model ?? '' }} {{ $t->mekanik->name ?? ($t->booking->mechanic->name ?? '') }} {{ $t->payment->payment_code ?? '' }}"
                            data-payment-status="{{ $t->payment->payment_status ?? 'unpaid' }}"
                            data-payment-method="{{ $t->payment->payment_method ?? 'cash' }}"
                        >
                            <td><span class="badge badge-info">#TRX-{{ $t->id }}</span></td>
                            <td><span class="fw-bold">{{ $t->payment->payment_code ?? '-' }}</span></td>
                            <td>
                                <strong>{{ $t->booking->vehicle->user->name }}</strong><br>
                                <small>{{ $t->booking->vehicle->brand }} - {{ $t->booking->vehicle->license_plate }}</small>
                            </td>
                            <td>{{ $t->mekanik->name ?? ($t->booking->mechanic->name ?? '-') }}</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                            <td>
                                @if($t->payment && $t->payment->payment_status == 'paid')
                                    <span class="badge bg-success text-white">LUNAS</span>
                                @elseif($t->payment && $t->payment->payment_status == 'partial')
                                    <span class="badge bg-warning text-dark">PARTIAL</span>
                                @else
                                    <span class="badge bg-warning text-dark">BELUM BAYAR</span>
                                @endif
                            </td>
                            <td><span class="text-uppercase small fw-bold">{{ $t->payment->payment_method ?? '-' }}</span></td>
                            <td>
                                <a href="{{ backend_route('admin.transactions.show', [$t->id]) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-printer"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
