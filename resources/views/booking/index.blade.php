@extends('be.master')

@section('Booking')
<div class="page-wrapper booking-page" style="margin-top: 30px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 bg-white">
                        <h3 class="card-title text-primary fw-bold">Booking List</h3>
                        <div class="booking-toolbar d-flex align-items-center gap-2 flex-wrap justify-content-start justify-content-md-end">
                            <select class="form-select form-select-sm" data-table-filter="status" style="min-width: 160px;">
                                <option value="">Semua Status</option>
                                <option value="pending">Menunggu</option>
                                <option value="confirmed">Diterima</option>
                                <option value="in_progress">Dikerjakan</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Batal</option>
                            </select>
                            <span class="badge bg-soft-primary text-primary">Manajemen Layanan</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">ID</th>
                                        <th>Customer Name</th>
                                        <th>Vehicle Detail</th>
                                        <th>Layanan</th>
                                        <th>Schedule</th>
                                        <th>Mekanik</th>
                                        <th>Payment</th>
                                        <th class="text-center">Status</th>
                                        <th>Complaint</th>
                                        <th class="text-center" width="200">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        @php
                                            $paymentMethod = $booking->transaction?->payment?->payment_method ?? ($booking->payment_preference ?? 'cash');
                                            $paymentStatus = $booking->transaction?->payment?->payment_status ?? 'unpaid';
                                        @endphp
                                        <tr
                                            data-search-row="1"
                                            data-search-text="#{{ $booking->id }} {{ $booking->user->name ?? 'N/A' }} {{ $booking->vehicle->license_plate ?? 'N/A' }} {{ $booking->vehicle->brand ?? '' }} {{ $booking->vehicle->model ?? '' }} {{ $booking->complaint ?? '' }} {{ $booking->services->pluck('service.service_name')->filter()->implode(' ') }} {{ $booking->mechanic->name ?? '' }}"
                                            data-status="{{ $booking->status }}"
                                            data-payment-method="{{ $paymentMethod }}"
                                            data-payment-status="{{ $paymentStatus }}"
                                        >
                                        <td><strong>#{{ $booking->id }}</strong></td>
                                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $booking->vehicle->license_plate ?? 'N/A' }}</span><br>
                                            <small class="text-muted text-uppercase">{{ $booking->vehicle->brand ?? '' }} {{ $booking->vehicle->model ?? '' }}</small>
                                        </td>
                                        <td>
                                            @forelse($booking->services as $item)
                                                <span class="badge bg-light text-dark border mb-1">{{ $item->service->service_name ?? 'Layanan' }}</span>
                                            @empty
                                                <span class="text-muted small">Belum ada layanan</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}<br>
                                            <i class="mdi mdi-clock-outline"></i> {{ $booking->booking_time }}
                                        </td>
                                        <td>
                                            @if($booking->mechanic)
                                                <span class="fw-semibold">{{ $booking->mechanic->name }}</span>
                                            @else
                                                <span class="text-muted small">Belum ditentukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark text-uppercase border">{{ str_replace('_', ' ', $paymentMethod) }}</span><br>
                                            <small class="text-muted">{{ strtoupper($paymentStatus) }}</small>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $badgeClass = [
                                                    'pending'     => 'warning',
                                                    'confirmed'   => 'info',
                                                    'in_progress' => 'primary',
                                                    'completed'   => 'success',
                                                    'cancelled'   => 'danger'
                                                ][$booking->status] ?? 'secondary';

                                                $statusText = [
                                                    'pending'     => 'Menunggu',
                                                    'confirmed'   => 'Diterima',
                                                    'in_progress' => 'Dikerjakan',
                                                    'completed'   => 'Selesai',
                                                    'cancelled'   => 'Batal'
                                                ][$booking->status] ?? $booking->status;
                                            @endphp
                                            <span class="badge rounded-pill bg-{{ $badgeClass }} px-3">
                                                {{ strtoupper($statusText) }}
                                            </span>
                                        </td>
                                        <td>
                                            <p class="small mb-0 text-truncate" style="max-width: 150px;" title="{{ $booking->complaint }}">
                                                {{ $booking->complaint ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ backend_route('admin.booking.show', [$booking->id]) }}" class="btn btn-sm btn-outline-secondary mb-2 w-100">Detail</a>

                                            @php($adminUser = $backendAuthUser)
                                            @php($adminRole = $adminUser?->role)

                                            @if($adminRole === 'admin' && $booking->status == 'pending')
                                                {{-- Tombol Terima atau Tolak --}}
                                                <form action="{{ backend_route('admin.booking.updateStatus', [$booking->id, 'confirmed']) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success shadow-sm">Confirm</button>
                                                </form>
                                                <form action="{{ backend_route('admin.booking.updateStatus', [$booking->id, 'cancelled']) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">Cancel</button>
                                                </form>

                                            @elseif($adminRole === 'mekanik' && $booking->status == 'confirmed')
                                                {{-- Mekanik mulai kerjakan booking confirmed --}}
                                                <form action="{{ backend_route('admin.booking.updateStatus', [$booking->id, 'in_progress']) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-primary w-100 shadow-sm">Mulai Kerjakan</button>
                                                </form>

                                            @elseif($adminRole === 'mekanik' && $booking->status == 'in_progress' && (int) $booking->mekanik_id === (int) ($adminUser?->id ?? 0))
                                                {{-- Mekanik selesai kerja --}}
                                                <form action="{{ backend_route('admin.booking.updateStatus', [$booking->id, 'completed']) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-dark w-100 shadow-sm">Konfirmasi Selesai</button>
                                                </form>

                                            @elseif(in_array($adminRole, ['admin', 'kasir'], true) && $booking->status == 'completed')
                                                {{-- Tombol Langsung ke Transaksi --}}
                                                <a href="{{ $booking->transaction ? backend_route('admin.transactions.show', [$booking->transaction->id]) : backend_route('admin.transactions.create', ['booking_id' => $booking->id]) }}" class="btn btn-sm btn-success w-100">
                                                    <i class="mdi mdi-cash-multiple"></i> {{ $booking->transaction ? 'Lihat Payment' : 'Lanjut ke Payment' }}
                                                </a>

                                            @elseif($adminRole === 'admin' && $booking->status == 'confirmed')
                                                <span class="text-muted small">Menunggu mekanik mulai kerja</span>

                                            @elseif($adminRole === 'admin' && $booking->status == 'in_progress')
                                                <span class="text-muted small">Dikerjakan: {{ $booking->mechanic->name ?? 'Belum ada mekanik' }}</span>

                                            @elseif($adminRole === 'mekanik' && $booking->status == 'in_progress')
                                                <span class="text-muted small">Dikerjakan mekanik lain</span>

                                            @elseif($adminRole === 'mekanik' && $booking->status == 'completed')
                                                <span class="text-muted small">Menunggu pembayaran kasir/admin</span>

                                            @else
                                                <span class="text-muted small italic">No Action Needed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
