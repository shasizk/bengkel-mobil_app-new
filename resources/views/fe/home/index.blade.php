@extends('fe.master')

@section('content')
  @php($filledStars = (int) round($averageRating))
  <style>
    .home-section-tight {
      padding: 54px 0;
    }

    .home-service-card {
      border: 1px solid #e8eef5;
      border-radius: 18px;
      background: #fff;
      box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
      transition: transform .2s ease, box-shadow .2s ease;
    }

    .home-service-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 22px 40px rgba(15, 23, 42, 0.12);
    }

    .home-rating-card {
      border: 1px solid #e8eef5;
      border-radius: 18px;
      background: linear-gradient(180deg, #ffffff, #f8fbff);
      box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
    }
  </style>
  <div class="ftco-blocks-cover-1">
    <div class="ftco-cover-1 overlay" style="background-image: url('{{ asset('fe/assets/images/hero_1.jpg') }}')">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-7 text-white">
            <span class="d-inline-block px-3 py-2 mb-3" style="background: rgba(255,255,255,.12); border-radius: 999px;">RAXY GARAGE</span>
            <h1 class="mb-3">Servis mobil premium, cepat, dan transparan.</h1>
            <p class="mb-4" style="font-size: 18px;">
              Pilih layanan bengkel terbaik, lihat rating customer, dan booking langsung lewat menu Booking.
              Tampilan dirancang seperti web app profesional agar lebih nyaman di desktop maupun mobile.
            </p>
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="p-3" style="background: rgba(255,255,255,.12); border-radius: 14px;">
                  <strong class="d-block mb-1">Layanan terstruktur</strong>
                  <span>Semua servis tersusun jelas dengan harga dan estimasi waktu.</span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="p-3" style="background: rgba(255,255,255,.12); border-radius: 14px;">
                  <strong class="d-block mb-1">Review real customer</strong>
                  <span>Lihat rating dan pengalaman client sebelum booking.</span>
                </div>
              </div>
            </div>
            @if($clientUser && $recentBookings->isNotEmpty())
              <div class="mt-4 p-3" style="background: rgba(255,255,255,.12); border-radius: 18px;">
                @php($latestBooking = $recentBookings->first())
                <strong class="d-block mb-2">Jadwal Booking Terbaru</strong>
                <span>{{ \Carbon\Carbon::parse($latestBooking->booking_date)->format('d M Y') }} {{ $latestBooking->booking_time }} - {{ $latestBooking->vehicle->license_plate ?? '-' }} - {{ strtoupper(str_replace('_', ' ', $latestBooking->status)) }}</span>
              </div>
            @endif
            <div class="mt-4 d-flex flex-wrap gap-2">
              <a href="{{ route('client.booking.index') }}" class="btn btn-light px-4 py-2" style="border-radius: 999px; font-weight: 700;">Masuk Menu Booking</a>
              <a href="{{ route('client.rating.index') }}" class="btn btn-outline-light px-4 py-2" style="border-radius: 999px; font-weight: 700;">Lihat Rating Bengkel</a>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="feature-car-rent-box-1">
              <h3 class="mb-4">Ringkasan Rating</h3>
              <ul class="list-unstyled">
                <li>
                  <span>Rata-rata rating</span>
                  <span class="spec">{{ number_format($averageRating ?: 0, 1) }}/5</span>
                </li>
                <li>
                  <span>Total ulasan</span>
                  <span class="spec">{{ $ratingCount }}</span>
                </li>
                <li>
                  <span>Total layanan</span>
                  <span class="spec">{{ $services->count() }}</span>
                </li>
                <li>
                  <span>Status booking</span>
                  <span class="spec">Online</span>
                </li>
                <li>
                  <span>Jam operasional</span>
                  <span class="spec">08.00 - 17.00</span>
                </li>
              </ul>
              <div class="d-flex align-items-center bg-light p-3" style="gap: 8px;">
                <span style="font-size: 24px; color: #f59e0b; letter-spacing: 2px;">
                  @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $filledStars ? '★' : '☆' }}
                  @endfor
                </span>
                <a href="{{ route('client.rating.index') }}" class="ml-auto btn btn-primary">Lihat Detail</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="site-section home-section-tight bg-light" id="layanan-section">
    <div class="container">
      <div class="row align-items-center mb-4">
        <div class="col-md-8">
          <h2 class="m-0">Layanan Bengkel</h2>
          <p class="mb-0 text-muted">Pilih layanan sesuai kebutuhan mobil kamu. Booking dilakukan di menu Booking.</p>
        </div>
        <div class="col-md-4 text-md-right">
          <a href="{{ route('client.booking.index') }}" class="btn btn-primary px-4 py-2">Menu Booking</a>
        </div>
      </div>
      <div class="row">
        @forelse($services as $service)
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="p-4 h-100 home-service-card">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <strong>{{ $service->service_name }}</strong>
                <span class="badge badge-primary">{{ $service->estimated_time }}m</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary font-weight-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                <a href="{{ route('client.booking.index') }}" class="btn btn-sm btn-outline-primary">Booking</a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-warning mb-0">Belum ada layanan aktif di database.</div>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="site-section home-section-tight bg-white" id="rating-highlight">
    <div class="container">
      <div class="row align-items-center mb-4">
        <div class="col-md-7">
          <h2 class="m-0">Rating Bengkel</h2>
          <p class="text-muted mb-0">Ulasan terbaru dari client RAXY GARAGE.</p>
        </div>
        <div class="col-md-5 text-md-right">
          <h4 class="mb-0" style="font-weight:700; color:#0f172a;">{{ number_format($averageRating ?: 0, 1) }}/5 • {{ $ratingCount }} ulasan</h4>
        </div>
      </div>
      <div class="row">
        @forelse($latestRatings as $rating)
          <div class="col-md-4 mb-4">
            <div class="h-100 p-4 home-rating-card">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>{{ $rating->user->name ?? 'Client' }}</strong>
                <span style="color:#f59e0b;">{{ str_repeat('★', (int) $rating->rating) }}{{ str_repeat('☆', 5 - (int) $rating->rating) }}</span>
              </div>
              <p class="text-muted mb-0">{{ $rating->comment }}</p>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-light border">Belum ada rating. Jadi yang pertama memberi ulasan.</div>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="site-section section-3" style="background-image: url('{{ asset('fe/assets/images/hero_2.jpg') }}');">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center mb-5">
          <h2 class="text-white">Alur Booking</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <div class="service-1">
            <span class="service-1-icon">
              <span class="flaticon-car-1"></span>
            </span>
            <div class="service-1-contents">
              <h3>Isi Data Customer</h3>
              <p>Nama, email, telepon, dan alamat akan tersimpan ke tabel users dengan role customer.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="service-1">
            <span class="service-1-icon">
              <span class="flaticon-traffic"></span>
            </span>
            <div class="service-1-contents">
              <h3>Input Kendaraan</h3>
              <p>Brand, model, tahun, warna, dan plat nomor akan disimpan ke tabel vehicles.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="service-1">
            <span class="service-1-icon">
              <span class="flaticon-valet"></span>
            </span>
            <div class="service-1-contents">
              <h3>Booking Masuk Admin</h3>
              <p>Jadwal, keluhan, dan layanan pilihan akan tercatat ke bookings serta booking_service.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
