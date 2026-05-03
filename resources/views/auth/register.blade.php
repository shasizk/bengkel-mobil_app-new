<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Bengkel Mobil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fe/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fe/assets/css/style.css') }}">

    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --orange: #fb923c;
            --peach: #fdba74;
            --teal: #14b8a6;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(251, 146, 60, 0.20), transparent 28%),
                radial-gradient(circle at bottom left, rgba(20, 184, 166, 0.18), transparent 30%),
                linear-gradient(145deg, #fff7ed 0%, #f8fafc 45%, #ecfeff 100%);
            color: var(--ink);
        }
        .auth-wrap { min-height: 100vh; display: flex; align-items: center; padding: 40px 0; }
        .auth-shell {
            border-radius: 34px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.15);
            background: rgba(255,255,255,0.82);
            backdrop-filter: blur(14px);
        }
        .brand-panel {
            min-height: 100%;
            padding: 46px;
            color: #fff;
            background:
                linear-gradient(165deg, rgba(124,45,18,.94) 0%, rgba(194,65,12,.92) 45%, rgba(19,78,74,.88) 100%);
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            border-radius: 999px;
            letter-spacing: .2em;
            font-size: 11px;
            text-transform: uppercase;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .brand-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 42px;
            line-height: 1.08;
            margin: 24px 0 16px;
        }
        .mini-note {
            margin-top: 28px;
            padding: 22px;
            border-radius: 28px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .form-panel { padding: 46px 40px; background: rgba(255,255,255,0.94); }
        .auth-input {
            min-height: 52px;
            border-radius: 18px;
            border: 1px solid #dbe2ea;
            background: #f8fafc;
            padding: 0 18px;
        }
        textarea.auth-input { min-height: 110px; padding: 14px 18px; }
        .auth-input:focus {
            border-color: rgba(251, 146, 60, 0.6);
            box-shadow: 0 0 0 4px rgba(251, 146, 60, 0.12);
            background: #fff;
        }
        .auth-btn {
            min-height: 54px;
            border-radius: 999px;
            font-weight: 800;
            border: none;
            background: linear-gradient(135deg, var(--orange), var(--peach));
            color: var(--ink);
        }
        .auth-btn:hover { color: var(--ink); transform: translateY(-1px); }
        .auth-link { color: #ea580c; font-weight: 700; }
        .muted-copy { color: var(--muted); }
        @media (max-width: 991.98px) {
            .brand-panel { padding: 36px 30px; }
            .form-panel { padding: 36px 30px; }
            .brand-title { font-size: 34px; }
        }
    </style>
</head>
<body>
<section class="auth-wrap py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="auth-shell">
                    <div class="row no-gutters">
                        <div class="col-lg-5 d-flex">
                            <div class="brand-panel w-100 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="brand-badge">New Customer</span>
                                    <h1 class="brand-title">Bikin akun yang terasa hangat, jelas, dan siap booking.</h1>
                                    <p class="mb-0" style="color: rgba(255,255,255,.78); font-size: 16px;">
                                        Daftar sekali, lalu simpan histori servis, kendaraan, foto profil, dan transaksi Anda dalam satu akun pelanggan.
                                    </p>
                                </div>

                                <div class="mini-note">
                                    <h5 class="text-white mb-2">Yang langsung Anda dapat</h5>
                                    <p class="mb-0" style="color: rgba(255,255,255,.78);">
                                        Profil pelanggan yang bisa diedit, pengalaman booking lebih rapi, dan halaman akun yang tampil lebih modern dari template default.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-panel">
                                <div class="mb-4">
                                    <p class="mb-2 text-uppercase font-weight-bold" style="letter-spacing: .28em; font-size: 12px; color: #ea580c;">Create Account</p>
                                    <h2 class="mb-2" style="font-family: 'Space Grotesk', sans-serif; font-size: 36px;">Daftar akun pelanggan</h2>
                                    <p class="muted-copy mb-0">Isi data utama di bawah ini, lalu Anda bisa langsung booking layanan bengkel.</p>
                                </div>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="form-row">
                                        <div class="form-group col-md-6 mb-3">
                                            <label for="name" class="font-weight-bold">Nama Lengkap</label>
                                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                                   class="form-control auth-input @error('name') is-invalid @enderror"
                                                   required autofocus autocomplete="name" placeholder="Nama Anda">
                                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="form-group col-md-6 mb-3">
                                            <label for="email" class="font-weight-bold">Email</label>
                                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                                   class="form-control auth-input @error('email') is-invalid @enderror"
                                                   required autocomplete="username" placeholder="nama@email.com">
                                            @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6 mb-3">
                                            <label for="phone" class="font-weight-bold">No. HP</label>
                                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                                   class="form-control auth-input @error('phone') is-invalid @enderror"
                                                   autocomplete="tel" placeholder="08xxxxxxxxxx">
                                            @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="form-group col-md-6 mb-3">
                                            <label for="password" class="font-weight-bold">Password</label>
                                            <input id="password" type="password" name="password"
                                                   class="form-control auth-input @error('password') is-invalid @enderror"
                                                   required autocomplete="new-password" placeholder="Minimal password aman">
                                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="address" class="font-weight-bold">Alamat</label>
                                        <textarea id="address" name="address" rows="3"
                                                  class="form-control auth-input @error('address') is-invalid @enderror"
                                                  autocomplete="street-address" placeholder="Alamat lengkap Anda">{{ old('address') }}</textarea>
                                        @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="password_confirmation" class="font-weight-bold">Konfirmasi Password</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                               class="form-control auth-input @error('password_confirmation') is-invalid @enderror"
                                               required autocomplete="new-password" placeholder="Ulangi password">
                                        @error('password_confirmation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <button type="submit" class="btn btn-block auth-btn">Daftar Sekarang</button>

                                    <p class="text-center mt-4 mb-0 muted-copy">
                                        Sudah punya akun client?
                                        <a href="{{ route('client.login') }}" class="auth-link">Login di sini</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center mt-3 mb-0 muted-copy">
                    <a href="{{ route('home') }}" class="auth-link">Kembali ke beranda</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('fe/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('fe/assets/js/bootstrap.min.js') }}"></script>
</body>
</html>
