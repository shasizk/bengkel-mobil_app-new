@extends('fe.master')

@section('content')
  <style>
    .about-page-shell {
      background:
        radial-gradient(circle at top left, rgba(14, 165, 233, 0.08), transparent 26%),
        radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.10), transparent 24%),
        linear-gradient(180deg, #f8fbff 0%, #eef6f8 100%);
    }
    .about-story-card {
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }
    .about-story-card::after {
      content: "";
      position: absolute;
      right: -80px;
      bottom: -90px;
      width: 220px;
      height: 220px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(14, 165, 233, 0.14), transparent 68%);
    }
    .about-info-card {
      position: relative;
      overflow: hidden;
      background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }
    .about-info-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, #0ea5e9, #14b8a6);
    }
    .about-metric-card {
      border-radius: 20px;
      background: rgba(255,255,255,.84);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(15, 23, 42, 0.06);
      box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }
    .metric-bar-track {
      width: 100%;
      height: 10px;
      background: #e2e8f0;
      border-radius: 999px;
      overflow: hidden;
    }
    .metric-bar-fill {
      height: 100%;
      border-radius: 999px;
      background: linear-gradient(90deg, #0ea5e9, #14b8a6);
    }
  </style>
  <div class="site-section pb-0">
    <div class="container">
      <div class="p-4 p-lg-5 text-white shadow-sm" style="border-radius: 30px; background: linear-gradient(135deg, #0f172a 0%, #12324a 54%, #0f766e 100%);">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <p class="mb-2 text-uppercase" style="letter-spacing: .25em; color: rgba(255,255,255,.7); font-size: 12px;">About Bengkel</p>
            <h1 class="mb-3 text-white">Tentang Bengkel Mobil</h1>
            <p class="mb-0" style="max-width: 720px; color: rgba(255,255,255,.78);">
              Bengkel kami berfokus pada servis berkala, perbaikan umum, pemeriksaan kendaraan, dan penanganan keluhan pelanggan dengan proses yang rapi, cepat, dan transparan.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="site-section about-page-shell">
    <div class="container">
      <div class="row mb-4">
        <div class="col-lg-4 mb-4">
          <div class="about-info-card shadow-sm h-100 p-4" style="border-radius: 24px;">
            <h4 class="text-primary mb-3">Lokasi Bengkel</h4>
            <p class="mb-2"><strong>Alamat:</strong></p>
            <p class="text-muted mb-3">{{ $workshopAddress }}</p>
            <p class="mb-2"><strong>Kontak:</strong></p>
            <p class="text-muted mb-0">{{ $workshopPhone }}<br>{{ $workshopEmail }}</p>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="about-info-card shadow-sm h-100 p-4" style="border-radius: 24px;">
            <h4 class="text-primary mb-3">Jam Operasional</h4>
            <div class="d-flex justify-content-between py-2 border-bottom"><span>Senin - Jumat</span><strong>08:00 - 17:00</strong></div>
            <div class="d-flex justify-content-between py-2 border-bottom"><span>Sabtu</span><strong>08:00 - 15:00</strong></div>
            <div class="d-flex justify-content-between py-2"><span>Minggu</span><strong>Tutup</strong></div>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="about-info-card shadow-sm h-100 p-4" style="border-radius: 24px;">
            <h4 class="text-primary mb-3">Keunggulan Kami</h4>
            <div class="mb-2 text-muted">Pengerjaan terjadwal dan tercatat</div>
            <div class="mb-2 text-muted">Estimasi biaya lebih jelas</div>
            <div class="mb-2 text-muted">Riwayat servis pelanggan tersimpan</div>
            <div class="text-muted">Dukungan booking online untuk client</div>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="about-metric-card p-4 h-100">
            <small class="text-muted d-block mb-1">Total Layanan</small>
            <h3 class="mb-0">{{ $aboutStats['service_count'] }}</h3>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="about-metric-card p-4 h-100">
            <small class="text-muted d-block mb-1">Total Booking</small>
            <h3 class="mb-0">{{ $aboutStats['booking_count'] }}</h3>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="about-metric-card p-4 h-100">
            <small class="text-muted d-block mb-1">Booking Selesai</small>
            <h3 class="mb-0">{{ $aboutStats['completed_count'] }}</h3>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="about-metric-card p-4 h-100">
            <small class="text-muted d-block mb-1">Mekanik Aktif</small>
            <h3 class="mb-0">{{ $aboutStats['mechanic_count'] }}</h3>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-7 mb-4">
          <div class="about-story-card shadow-sm p-4 p-lg-5 h-100" style="border-radius: 28px;">
            <p class="text-uppercase mb-2" style="letter-spacing: .2em; font-size: 12px; color: #0ea5e9;">Cerita Bengkel</p>
            <h3 class="mb-3">Bengkel yang dibangun untuk servis yang lebih tertata</h3>
            <p class="text-muted">
              Kami menghadirkan proses servis kendaraan yang lebih mudah dipahami pelanggan, mulai dari booking, pemeriksaan awal, pengerjaan, sampai transaksi akhir.
            </p>
            <p class="text-muted mb-0">
              Halaman ini dibuat sebagai ruang perkenalan bengkel untuk client: mengenal lokasi, layanan, jam buka, dan cara kerja bengkel sebelum melakukan booking.
            </p>
          </div>
        </div>
        <div class="col-lg-5 mb-4">
          <div class="about-info-card shadow-sm p-4 p-lg-5 h-100" style="border-radius: 28px;">
            <p class="text-uppercase mb-2" style="letter-spacing: .2em; font-size: 12px; color: #0ea5e9;">Visual Performa</p>
            <h3 class="mb-3">Progress Operasional Bengkel</h3>
            <div class="mb-3">
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Penyelesaian Booking</span>
                <strong>{{ $aboutStats['completion_rate'] }}%</strong>
              </div>
              <div class="metric-bar-track">
                <div class="metric-bar-fill" style="width: {{ $aboutStats['completion_rate'] }}%;"></div>
              </div>
            </div>
            <div class="mb-3">
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Kesiapan Layanan</span>
                <strong>{{ min(100, $aboutStats['service_count'] * 5) }}%</strong>
              </div>
              <div class="metric-bar-track">
                <div class="metric-bar-fill" style="width: {{ min(100, $aboutStats['service_count'] * 5) }}%;"></div>
              </div>
            </div>
            <div>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Ketersediaan Mekanik</span>
                <strong>{{ min(100, $aboutStats['mechanic_count'] * 20) }}%</strong>
              </div>
              <div class="metric-bar-track">
                <div class="metric-bar-fill" style="width: {{ min(100, $aboutStats['mechanic_count'] * 20) }}%;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
