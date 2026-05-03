@extends('be.master')

@section('User')
@php($profilePhoto = $user->profile_photo_url)
@php($initials = $user->initials())
<div class="container-fluid" style="margin-top: 30px;">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-white" style="border-radius: 24px; background: linear-gradient(135deg, #0f172a, #155e75);">
                <div class="card-body p-4 p-lg-5 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <p class="mb-2 text-uppercase" style="letter-spacing: .25em; color: rgba(255,255,255,.7); font-size: 12px;">Account Center</p>
                        <h2 class="fw-bold mb-2">My Profile</h2>
                        <p class="mb-0" style="color: rgba(255,255,255,.75);">Kelola data akun, foto profil, password, dan keamanan dari dashboard admin.</p>
                    </div>
                    <a href="{{ backend_route('admin.dashboard.index') }}" class="btn btn-light mt-3 mt-md-0" style="border-radius: 999px;">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                <div class="card-body p-4 p-lg-5">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            @if ($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="{{ $user->name }}" id="profilePhotoPreview" style="width: 120px; height: 120px; object-fit: cover; border-radius: 28px;">
                                <div id="profilePhotoFallback" class="d-none"></div>
                            @else
                                <div id="profilePhotoFallback" class="d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; border-radius: 28px; background: linear-gradient(135deg, #38bdf8, #86efac); color: #0f172a; font-size: 32px; font-weight: 700;">
                                    {{ $initials }}
                                </div>
                                <img src="" alt="{{ $user->name }}" id="profilePhotoPreview" class="d-none" style="width: 120px; height: 120px; object-fit: cover; border-radius: 28px;">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                            <p class="text-muted mb-2">{{ $user->email }}</p>
                            <span class="badge bg-primary text-uppercase">{{ $user->role }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ backend_route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" style="min-height: 48px; border-radius: 16px;" required>
                                @error('name') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" style="min-height: 48px; border-radius: 16px;" required>
                                @error('email') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">No. HP</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" style="min-height: 48px; border-radius: 16px;">
                                @error('phone') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Foto Profil</label>
                                <input type="file" name="profile_photo" id="profile_photo" accept=".jpg,.jpeg,.png,.webp" class="form-control" style="min-height: 48px; border-radius: 16px;">
                                @error('profile_photo') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <textarea name="address" rows="4" class="form-control" style="border-radius: 18px;">{{ old('address', $user->address) }}</textarea>
                                @error('address') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 999px;">Simpan Profil</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 24px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Update Password</h4>
                    <form method="POST" action="{{ backend_route('admin.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" style="min-height: 48px; border-radius: 16px;">
                            @if ($errors->updatePassword->has('current_password'))
                                <small class="text-danger d-block mt-2">{{ $errors->updatePassword->first('current_password') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Baru</label>
                            <input type="password" name="password" class="form-control" style="min-height: 48px; border-radius: 16px;">
                            @if ($errors->updatePassword->has('password'))
                                <small class="text-danger d-block mt-2">{{ $errors->updatePassword->first('password') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" style="min-height: 48px; border-radius: 16px;">
                            @if ($errors->updatePassword->has('password_confirmation'))
                                <small class="text-danger d-block mt-2">{{ $errors->updatePassword->first('password_confirmation') }}</small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-dark w-100" style="border-radius: 999px;">Update Password</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-danger mb-3">Hapus Akun</h4>
                    <p class="text-muted">Aksi ini permanen. Masukkan password untuk menghapus akun.</p>
                    <form method="POST" action="{{ backend_route('admin.profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password" class="form-control" style="min-height: 48px; border-radius: 16px;">
                            @if ($errors->userDeletion->has('password'))
                                <small class="text-danger d-block mt-2">{{ $errors->userDeletion->first('password') }}</small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-danger w-100" style="border-radius: 999px;">Hapus Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('profile_photo');
        const preview = document.getElementById('profilePhotoPreview');
        const fallback = document.getElementById('profilePhotoFallback');

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
