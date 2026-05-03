@extends('fe.master')

@section('content')
  @php($profilePhoto = $user->profile_photo_url)
  @php($initials = $user->initials())
  @php($heroBackground = $profilePhoto ?: asset('fe/assets/images/hero_1.jpg'))
  <style>
    .client-profile-shell {
      background:
        radial-gradient(circle at top left, rgba(13, 148, 136, 0.12), transparent 22%),
        radial-gradient(circle at bottom right, rgba(14, 116, 144, 0.10), transparent 24%),
        linear-gradient(180deg, #eef8fb 0%, #f8fbff 42%, #edf5f8 100%);
      padding: 18px 0 40px;
    }
    .client-profile-banner {
      position: relative;
      overflow: hidden;
      border-radius: 28px;
      background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(8, 145, 178, 0.84));
      min-height: 230px;
    }
    .client-profile-banner::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(90deg, rgba(15, 23, 42, 0.94) 0%, rgba(15, 23, 42, 0.78) 42%, rgba(15, 23, 42, 0.3) 100%),
        url('{{ $heroBackground }}');
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      opacity: 0.42;
    }
    .client-profile-banner-content {
      position: relative;
      z-index: 1;
      padding: 42px 40px;
    }
    .client-profile-card {
      border: 1px solid rgba(15, 23, 42, 0.06);
      background: rgba(255, 255, 255, 0.78) !important;
      backdrop-filter: blur(14px);
      box-shadow: 0 18px 38px rgba(15, 23, 42, 0.08);
    }
    .client-profile-card--dark {
      background: linear-gradient(140deg, #0f172a 0%, #164e63 100%) !important;
      border: 0;
    }
    @media (max-width: 991.98px) {
      .client-profile-banner {
        min-height: auto;
      }
      .client-profile-banner-content {
        padding: 32px 24px;
      }
    }
  </style>
  <div class="site-section pb-0 client-profile-shell">
    <div class="container">
      <div class="client-profile-banner shadow-sm">
        <div class="client-profile-banner-content">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <p class="mb-2 text-uppercase" style="letter-spacing: .25em; color: rgba(255,255,255,.7); font-size: 12px;">Account Center</p>
              <h1 class="text-white mb-3">My Profile</h1>
              <p class="text-white mb-0" style="max-width: 680px; color: rgba(255,255,255,.78);">Kelola foto profil, data akun, dan keamanan akun Anda dari satu halaman yang rapi.</p>
            </div>
            <div class="col-lg-4 text-lg-right mt-4 mt-lg-0">
              <a href="{{ route('home') }}" class="btn btn-light px-4 py-2" style="border-radius: 999px;">Kembali ke Home</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="site-section client-profile-shell pt-0">
    <div class="container">
      @if (session('status') === 'profile-updated')
        <div class="alert alert-success shadow-sm mb-4" style="border-radius: 18px;">
          Profil berhasil diperbarui.
        </div>
      @endif

      <div class="row mb-4">
        <div class="col-lg-5 mb-4">
          <div class="client-profile-card client-profile-card--dark p-4 p-lg-5 text-white h-100 shadow" style="border-radius: 28px;">
            <div class="text-center text-lg-start">
              @if ($profilePhoto)
                <img src="{{ $profilePhoto }}" alt="{{ $user->name }}" id="clientProfilePreview" class="mb-4" style="width: 110px; height: 110px; object-fit: cover; border-radius: 28px; border: 4px solid rgba(255,255,255,.18);">
              @else
                <div id="clientProfileFallback" class="d-inline-flex align-items-center justify-content-center mb-4" style="width: 110px; height: 110px; border-radius: 28px; font-size: 32px; font-weight: 700; color: #0f172a; background: linear-gradient(135deg, #bae6fd, #a7f3d0);">
                  {{ $initials }}
                </div>
                <img src="" alt="{{ $user->name }}" id="clientProfilePreview" class="mb-4 d-none" style="width: 110px; height: 110px; object-fit: cover; border-radius: 28px; border: 4px solid rgba(255,255,255,.18);">
              @endif

              <div class="mb-4">
                <span class="badge badge-pill px-3 py-2" style="background: rgba(255,255,255,.12); color: #dbeafe;">Customer Account</span>
                <h3 class="text-white mt-3 mb-1">{{ $user->name }}</h3>
                <p class="mb-0" style="color: rgba(255,255,255,.78);">{{ $user->email }}</p>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-6 mb-3">
                <div class="p-3 h-100" style="border-radius: 20px; background: rgba(255,255,255,.08);">
                  <small class="d-block text-uppercase" style="letter-spacing: .15em; color: rgba(255,255,255,.6);">No. HP</small>
                  <strong>{{ $user->phone ?: '-' }}</strong>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="p-3 h-100" style="border-radius: 20px; background: rgba(255,255,255,.08);">
                  <small class="d-block text-uppercase" style="letter-spacing: .15em; color: rgba(255,255,255,.6);">Kendaraan</small>
                  <strong>{{ $stats['total_vehicles'] }}</strong>
                </div>
              </div>
            </div>

            <div class="mt-2">
              <small class="d-block text-uppercase mb-2" style="letter-spacing: .15em; color: rgba(255,255,255,.6);">Alamat</small>
              <p class="mb-0" style="color: rgba(255,255,255,.78);">{{ $user->address ?: 'Alamat belum diisi.' }}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-7 mb-4">
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="client-profile-card p-3 h-100" style="border-radius: 20px;">
                <small class="text-muted d-block mb-1">Total Booking</small>
                <h4 class="mb-0">{{ $stats['total_bookings'] }}</h4>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="client-profile-card p-3 h-100" style="border-radius: 20px;">
                <small class="text-muted d-block mb-1">Booking Selesai</small>
                <h4 class="mb-0">{{ $stats['completed_bookings'] }}</h4>
              </div>
            </div>
          </div>

          <div class="client-profile-card p-4 p-lg-5 shadow-sm mt-3" style="border-radius: 28px;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
              <div>
                <h4 class="text-primary mb-1">Update Profil</h4>
                <p class="text-muted mb-0">Email akun di bawah ini adalah email yang dipakai untuk menerima notifikasi booking.</p>
              </div>
            </div>

            <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data">
              @csrf
              @method('PATCH')

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Nama Lengkap</label>
                  <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" style="border-radius: 14px; min-height: 48px;" required>
                  @error('name') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                  <label>Email</label>
                  <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" style="border-radius: 14px; min-height: 48px;" required>
                  @error('email') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>No. HP</label>
                  <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" style="border-radius: 14px; min-height: 48px;">
                  @error('phone') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-6">
                  <label>Foto Profil</label>
                  <input type="file" name="profile_photo" id="client_profile_photo" accept=".jpg,.jpeg,.png,.webp" class="form-control" style="border-radius: 14px; min-height: 48px;">
                  @error('profile_photo') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                </div>
              </div>

              <div class="form-group">
                <label>Alamat</label>
                <textarea name="address" rows="4" class="form-control" style="border-radius: 18px;">{{ old('address', $user->address) }}</textarea>
                @error('address') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
              </div>

              <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 999px;">Simpan Profil</button>
            </form>
          </div>

          <div class="client-profile-card p-4 p-lg-5 shadow-sm mt-3" style="border-radius: 28px;">
            <h4 class="text-primary mb-3">Keamanan Akun</h4>
            <p class="text-muted">Ganti password kapan saja untuk menjaga akun tetap aman.</p>

            <form method="POST" action="{{ route('client.password.update') }}">
              @csrf
              @method('PUT')

              <div class="form-group">
                <label>Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control" style="border-radius: 14px; min-height: 48px;">
                @if ($errors->updatePassword->has('current_password'))
                  <small class="text-danger d-block mt-2">{{ $errors->updatePassword->first('current_password') }}</small>
                @endif
              </div>

              <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" class="form-control" style="border-radius: 14px; min-height: 48px;">
                @if ($errors->updatePassword->has('password'))
                  <small class="text-danger d-block mt-2">{{ $errors->updatePassword->first('password') }}</small>
                @endif
              </div>

              <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" style="border-radius: 14px; min-height: 48px;">
                @if ($errors->updatePassword->has('password_confirmation'))
                  <small class="text-danger d-block mt-2">{{ $errors->updatePassword->first('password_confirmation') }}</small>
                @endif
              </div>

              <button type="submit" class="btn btn-dark btn-block" style="border-radius: 999px;">Update Password</button>
            </form>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const input = document.getElementById('client_profile_photo');
      const preview = document.getElementById('clientProfilePreview');
      const fallback = document.getElementById('clientProfileFallback');

      if (!input || !preview) {
        return;
      }

      input.addEventListener('change', function (event) {
        const file = event.target.files && event.target.files[0];

        if (!file) {
          return;
        }

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');

        if (fallback) {
          fallback.classList.add('d-none');
        }
      });
    });
  </script>
@endsection
