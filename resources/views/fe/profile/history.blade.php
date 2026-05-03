@extends('fe.master')

@section('content')
<div class="site-section" style="background: radial-gradient(circle at top left, rgba(13, 148, 136, 0.12), transparent 20%), radial-gradient(circle at bottom right, rgba(14, 116, 144, 0.1), transparent 24%), linear-gradient(180deg, #edf7fa 0%, #f8fbff 42%, #eef5f8 100%);">
  <div class="container">
    <div class="p-4 p-lg-5 text-white shadow-sm mb-4" style="border-radius: 26px; background: linear-gradient(135deg, #0f172a, #155e75);">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <p class="mb-2 text-uppercase" style="letter-spacing: .2em; color: rgba(255,255,255,.68); font-size: 12px;">Customer Area</p>
          <h2 class="mb-2">Riwayat Booking</h2>
          <p class="mb-0" style="color: rgba(255,255,255,.82);">Semua riwayat booking dan status pembayaran akun Anda ada di sini.</p>
        </div>
        <a href="{{ route('client.profile') }}" class="btn btn-light" style="border-radius: 999px;">Kembali ke My Profile</a>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <div class="p-4 h-100 shadow-sm" style="border-radius: 20px; background: rgba(255,255,255,.78); backdrop-filter: blur(10px);">
          <small class="text-muted d-block mb-1">Total Booking</small>
          <h3 class="mb-0">{{ $stats['total_bookings'] }}</h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="p-4 h-100 shadow-sm" style="border-radius: 20px; background: rgba(255,255,255,.78); backdrop-filter: blur(10px);">
          <small class="text-muted d-block mb-1">Booking Aktif</small>
          <h3 class="mb-0">{{ $stats['active_bookings'] }}</h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="p-4 h-100 shadow-sm" style="border-radius: 20px; background: rgba(255,255,255,.78); backdrop-filter: blur(10px);">
          <small class="text-muted d-block mb-1">Service Selesai</small>
          <h3 class="mb-0">{{ $stats['completed_bookings'] }}</h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="p-4 h-100 shadow-sm" style="border-radius: 20px; background: rgba(255,255,255,.78); backdrop-filter: blur(10px);">
          <small class="text-muted d-block mb-1">Lunas</small>
          <h3 class="mb-0">{{ $stats['paid_bookings'] }}</h3>
        </div>
      </div>
    </div>

    <div class="p-4 p-lg-5 shadow-sm" style="border-radius: 26px; background: rgba(255,255,255,.82); backdrop-filter: blur(12px);">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <h4 class="text-primary mb-0">Riwayat</h4>
        <a href="{{ route('client.booking.index') }}" class="btn btn-outline-primary" style="border-radius: 999px;">Booking Lagi</a>
      </div>

      @forelse($bookings as $booking)
        <div class="border bg-light shadow-sm mb-3" style="border-radius: 18px; overflow: hidden;">
          <div class="p-3 p-lg-4 bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div>
                <h5 class="mb-1">Booking #{{ $booking->id }}</h5>
                <small class="text-muted">{{ $booking->vehicle->brand ?? '-' }} {{ $booking->vehicle->model ?? '' }} • {{ $booking->vehicle->license_plate ?? '-' }}</small>
              </div>
              <div class="mt-2 mt-md-0 text-md-right">
                <span class="badge px-3 py-2 text-uppercase" style="background: #e2e8f0; color: #0f172a;">{{ str_replace('_', ' ', $booking->status) }}</span>
                <div class="small text-muted mt-2">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }} • {{ $booking->booking_time }}</div>
              </div>
            </div>
          </div>
          <div class="p-3 p-lg-4">
            <div class="row">
              <div class="col-12 mb-2">
                <small class="text-uppercase d-block text-muted mb-1">Layanan</small>
                <strong>{{ $booking->services->pluck('service.service_name')->filter()->implode(', ') ?: '-' }}</strong>
              </div>
              <div class="col-md-4 mb-2">
                <small class="text-uppercase d-block text-muted mb-1">Preferensi Bayar</small>
                <strong class="text-capitalize">{{ str_replace('_', ' ', $booking->payment_preference ?? 'cash') }}</strong>
              </div>
              <div class="col-md-4 mb-2">
                <small class="text-uppercase d-block text-muted mb-1">Status Bayar</small>
                <strong class="text-capitalize">{{ $booking->transaction?->payment?->payment_status ?? 'belum dibuat' }}</strong>
              </div>
              <div class="col-md-4 mb-2">
                <small class="text-uppercase d-block text-muted mb-1">Total</small>
                <strong>{{ $booking->transaction ? 'Rp ' . number_format($booking->transaction->grand_total ?? 0, 0, ',', '.') : '-' }}</strong>
              </div>
              <div class="col-12 mt-2">
                <small class="text-uppercase d-block text-muted mb-1">Keluhan</small>
                <span>{{ $booking->complaint ?: '-' }}</span>
              </div>
              <div class="col-12 mt-3">
                @if($booking->transaction?->payment?->payment_url && ($booking->transaction?->payment?->payment_status !== 'paid'))
                  <a href="{{ $booking->transaction->payment->payment_url }}" target="_blank" class="btn btn-sm btn-outline-primary" style="border-radius: 999px;">Buka Pembayaran</a>
                @elseif($booking->transaction?->payment?->payment_status === 'paid')
                  <span class="badge bg-success px-3 py-2">Pembayaran selesai - invoice sudah lunas</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="alert alert-light border" style="border-radius: 16px;">Belum ada riwayat booking untuk akun ini.</div>
      @endforelse
    </div>
  </div>
</div>

@php
  $hasPendingGatewayPayment = $bookings->contains(function ($booking) {
      $payment = $booking->transaction?->payment;

      if (! $payment) {
          return false;
      }

      $method = strtolower((string) ($payment->payment_method ?? ''));
      return in_array($method, ['qris', 'transfer', 'bank_transfer', 'credit_card', 'gopay', 'shopeepay'], true)
          && ($payment->payment_status ?? 'unpaid') !== 'paid';
  });
@endphp

@if($hasPendingGatewayPayment)
  <script>
    setInterval(function () {
      if (!document.hidden) {
        window.location.reload();
      }
    }, 20000);
  </script>
@endif
@endsection
