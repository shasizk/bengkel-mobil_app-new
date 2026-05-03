@extends('be.master')
@section('Booking')
<div class="container">
    <div class="page-inner">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Detail Booking #{{ $booking->id }}</h4>
                <a href="{{ backend_route('admin.booking.index') }}" class="btn btn-light btn-sm">Kembali</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-4">
                        <h5 class="text-primary">Customer</h5>
                        <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr><td>Nama</td><td class="fw-bold">: {{ $booking->user->name ?? '-' }}</td></tr>
                            <tr><td>Email</td><td class="fw-bold">: {{ $booking->user->email ?? '-' }}</td></tr>
                            <tr><td>Telepon</td><td class="fw-bold">: {{ $booking->user->phone ?? '-' }}</td></tr>
                            <tr><td>Alamat</td><td class="fw-bold">: {{ $booking->user->address ?? '-' }}</td></tr>
                        </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                        <h5 class="text-primary">Kendaraan & Jadwal</h5>
                        <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr><td>Brand</td><td class="fw-bold">: {{ $booking->vehicle->brand ?? '-' }}</td></tr>
                            <tr><td>Model</td><td class="fw-bold">: {{ $booking->vehicle->model ?? '-' }}</td></tr>
                            <tr><td>Tahun</td><td class="fw-bold">: {{ $booking->vehicle->year ?? '-' }}</td></tr>
                            <tr><td>Plat Nomor</td><td class="fw-bold">: {{ $booking->vehicle->license_plate ?? '-' }}</td></tr>
                            <tr><td>Warna</td><td class="fw-bold">: {{ $booking->vehicle->color ?? '-' }}</td></tr>
                            <tr><td>Tanggal</td><td class="fw-bold">: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td></tr>
                            <tr><td>Jam</td><td class="fw-bold">: {{ $booking->booking_time }}</td></tr>
                            <tr><td>Status</td><td class="fw-bold">: <span class="badge bg-secondary text-uppercase">{{ str_replace('_', ' ', $booking->status) }}</span></td></tr>
                        </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                        <h5 class="text-primary">Keluhan</h5>
                        <div class="border rounded p-3 bg-light">
                            {{ $booking->complaint ?: '-' }}
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                        <h5 class="text-primary">Layanan Dipilih</h5>
                        <div class="border rounded p-3 bg-light">
                            @forelse($booking->services as $item)
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2 flex-wrap">
                                    <span>{{ $item->service->service_name ?? 'Layanan' }}</span>
                                    <strong>Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                                </div>
                            @empty
                                <div class="text-muted">Belum ada layanan dipilih.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
