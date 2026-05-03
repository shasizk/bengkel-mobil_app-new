@extends('fe.master')

@section('content')
  <div class="ftco-blocks-cover-1">
    <div class="ftco-cover-1 overlay" style="background-image: url('{{ asset('fe/assets/images/hero_2.jpg') }}')">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-8 text-white">
            <span class="d-inline-block px-3 py-2 mb-3" style="background: rgba(255,255,255,.12); border-radius: 999px;">Menu Booking</span>
            <h1 class="mb-3">Form Booking Servis Customer</h1>
            <p class="mb-0" style="font-size: 18px; max-width: 760px;">
              Isi data customer, kendaraan, jadwal, keluhan, dan layanan di halaman ini.
              Setelah submit, booking langsung masuk ke dashboard admin.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="site-section pt-0 pb-0 bg-light" id="booking-form">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <form action="{{ route('client.booking.store') }}" method="POST" class="trip-form">
            @csrf

            <div class="row align-items-center mb-4">
              <div class="col-md-8">
                <h3 class="m-0">Form Booking Bengkel</h3>
                <p class="mb-0 text-muted">Silakan isi sesuai data customer dan kendaraan.</p>
              </div>
              <div class="col-md-4 text-md-right">
                <span class="text-primary">{{ $services->count() }}</span> <span>layanan tersedia</span>
              </div>
            </div>

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger">
                <strong>Data belum lengkap.</strong>
                <div class="mt-2">
                  @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                  @endforeach
                </div>
              </div>
            @endif

            <div class="row">
              <div class="col-12 mb-3">
                <h4 class="text-primary">Data Customer</h4>
              </div>
              <div class="form-group col-md-4">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $clientUser->name ?? '') }}" class="form-control" placeholder="Nama customer" required>
              </div>
              <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $clientUser->email ?? '') }}" class="form-control" placeholder="email@contoh.com" required>
              </div>
              <div class="form-group col-md-4">
                <label for="phone">No. HP</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $clientUser->phone ?? '') }}" class="form-control" placeholder="08xxxxxxxxxx" required>
              </div>
              <div class="form-group col-md-12">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" class="form-control" rows="3" placeholder="Alamat customer">{{ old('address', $clientUser->address ?? '') }}</textarea>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12 mb-3">
                <h4 class="text-primary">Data Kendaraan</h4>
              </div>
              @if($clientUser && $vehicles->isNotEmpty())
              <div class="form-group col-md-12">
                <label for="vehicle_id">Pilih Kendaraan Tersimpan</label>
                <select id="vehicle_id" name="vehicle_id" class="form-control">
                  <option value="">Gunakan input manual / kendaraan baru</option>
                  @foreach($vehicles as $vehicle)
                    <option
                      value="{{ $vehicle->id }}"
                      data-brand="{{ $vehicle->brand }}"
                      data-model="{{ $vehicle->model }}"
                      data-year="{{ $vehicle->year }}"
                      data-license="{{ $vehicle->license_plate }}"
                      data-color="{{ $vehicle->color }}"
                      {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}
                    >
                      {{ $vehicle->license_plate }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->color }})
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Pilih kendaraan dari database kalau sudah pernah terdaftar.</small>
              </div>
              @endif
              <div class="form-group col-md-3">
                <label for="brand">Brand</label>
                <input type="text" id="brand" name="brand" value="{{ old('brand') }}" class="form-control" placeholder="Toyota" required>
              </div>
              <div class="form-group col-md-3">
                <label for="model">Model</label>
                <input type="text" id="model" name="model" value="{{ old('model') }}" class="form-control" placeholder="Avanza" required>
              </div>
              <div class="form-group col-md-3">
                <label for="year">Tahun</label>
                <input type="number" id="year" name="year" value="{{ old('year') }}" min="1900" max="{{ now()->year }}" class="form-control" placeholder="2022" required>
              </div>
              <div class="form-group col-md-3">
                <label for="color">Warna</label>
                <input type="text" id="color" name="color" value="{{ old('color') }}" class="form-control" placeholder="Hitam" required>
              </div>
              <div class="form-group col-md-6">
                <label for="license_plate">Plat Nomor</label>
                <input type="text" id="license_plate" name="license_plate" value="{{ old('license_plate') }}" class="form-control text-uppercase" placeholder="B 1234 XYZ" required>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12 mb-3">
                <h4 class="text-primary">Jadwal dan Keluhan</h4>
              </div>
              <div class="form-group col-md-4">
                <label for="booking_date">Tanggal Booking</label>
                <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" min="{{ now()->toDateString() }}" class="form-control" required>
              </div>
              <div class="form-group col-md-4">
                <label for="booking_time">Jam Booking</label>
                <input type="time" id="booking_time" name="booking_time" value="{{ old('booking_time') }}" class="form-control" required>
              </div>
              <div class="form-group col-md-12">
                <label for="complaint">Keluhan</label>
                <textarea id="complaint" name="complaint" rows="4" class="form-control" placeholder="Contoh: mesin bergetar, rem kurang pakem, oli ingin diganti" required>{{ old('complaint') }}</textarea>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12 mb-3">
                <h4 class="text-primary">Preferensi Pembayaran</h4>
              </div>
              <div class="form-group col-md-6">
                <label for="payment_preference">Metode yang Diinginkan</label>
                <select id="payment_preference" name="payment_preference" class="form-control" required>
                  <option value="cash" {{ old('payment_preference', 'cash') === 'cash' ? 'selected' : '' }}>Cash di Kasir</option>
                  <option value="transfer" {{ old('payment_preference') === 'transfer' ? 'selected' : '' }}>Debit / Transfer</option>
                  <option value="qris" {{ old('payment_preference') === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
              </div>
              <div class="form-group col-md-6 d-flex align-items-end">
                <div class="alert alert-info w-100 mb-0">
                  Jika memilih debit atau QRIS, admin akan menyiapkan pembayaran digital sesuai total yang ditentukan saat transaksi dibuat.
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12 mb-3">
                <h4 class="text-primary">Pilih Layanan</h4>
              </div>
              @forelse($services as $service)
                <div class="col-lg-4 col-md-6 mb-4">
                  <label class="d-block h-100 p-4 bg-white" style="border: 1px solid #e9ecef; border-radius: 14px; cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <strong>{{ $service->service_name }}</strong>
                      <input
                        type="checkbox"
                        name="service_ids[]"
                        value="{{ $service->id }}"
                        {{ in_array($service->id, old('service_ids', [])) ? 'checked' : '' }}
                      >
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="text-primary font-weight-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                      <span>{{ $service->estimated_time }} menit</span>
                    </div>
                  </label>
                </div>
              @empty
                <div class="col-12">
                  <div class="alert alert-warning mb-0">Belum ada layanan aktif di database.</div>
                </div>
              @endforelse
            </div>

            <div class="row mt-2">
              <div class="col-lg-6">
                <input type="submit" value="Kirim Booking" class="btn btn-primary px-4 py-3">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    (() => {
      const vehicleSelect = document.getElementById('vehicle_id');
      if (!vehicleSelect) return;

      const map = {
        brand: document.getElementById('brand'),
        model: document.getElementById('model'),
        year: document.getElementById('year'),
        license: document.getElementById('license_plate'),
        color: document.getElementById('color'),
      };

      function syncVehicle() {
        const option = vehicleSelect.options[vehicleSelect.selectedIndex];
        const useSavedVehicle = !!vehicleSelect.value;

        Object.entries(map).forEach(([key, input]) => {
          if (!input) return;
          if (useSavedVehicle) {
            input.value = option.getAttribute(`data-${key}`) || '';
          }
          input.readOnly = useSavedVehicle;
          input.style.background = useSavedVehicle ? '#f8fafc' : '';
        });
      }

      vehicleSelect.addEventListener('change', syncVehicle);
      syncVehicle();
    })();
  </script>
@endsection
