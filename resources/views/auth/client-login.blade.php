<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Client - Bengkel Mobil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fe/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fe/assets/css/style.css') }}">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.22), transparent 28%),
                radial-gradient(circle at bottom right, rgba(59, 130, 246, 0.16), transparent 30%),
                linear-gradient(145deg, #fffaf0 0%, #f8fafc 45%, #eff6ff 100%);
            color: #0f172a;
        }
        .auth-wrap { min-height: 100vh; display: flex; align-items: center; padding: 40px 0; }
        .auth-shell { border-radius: 34px; overflow: hidden; box-shadow: 0 30px 80px rgba(15, 23, 42, 0.16); background: rgba(255,255,255,0.85); }
        .brand-panel { min-height: 100%; padding: 46px; color: #fff; background: linear-gradient(160deg, rgba(22,78,99,.95), rgba(30,64,175,.88)); }
        .brand-badge { display:inline-flex; padding:10px 16px; border-radius:999px; letter-spacing:.2em; font-size:11px; text-transform:uppercase; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.12);}
        .brand-title { font-family:'Space Grotesk', sans-serif; font-size:42px; line-height:1.08; margin:24px 0 16px; }
        .form-panel { padding: 46px 40px; background: rgba(255,255,255,0.95); }
        .auth-input { min-height: 52px; border-radius: 18px; border: 1px solid #dbe2ea; background: #f8fafc; padding: 0 18px; }
        .auth-input:focus { border-color: rgba(14, 165, 233, 0.6); box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12); background: #fff; }
        .auth-btn { min-height: 54px; border-radius: 999px; font-weight: 800; border: none; background: linear-gradient(135deg, #38bdf8, #facc15); color: #0f172a; }
        .auth-link { color: #0284c7; font-weight: 700; }
        .muted-copy { color: #64748b; }
    </style>
</head>
<body>
<section class="auth-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="auth-shell">
                    <div class="row no-gutters">
                        <div class="col-lg-5 d-flex">
                            <div class="brand-panel w-100 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="brand-badge">Client Login</span>
                                    <h1 class="brand-title">Masuk sebagai customer bengkel.</h1>
                                    <p style="color: rgba(255,255,255,.78);">Halaman ini khusus client untuk booking servis, cek riwayat, dan update profil pribadi.</p>
                                </div>
                                <div style="padding: 22px; border-radius: 28px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.08);">
                                    <h5 class="text-white mb-2">Akses client</h5>
                                    <p class="mb-0" style="color: rgba(255,255,255,.78);">Akun client tidak bisa masuk ke halaman admin, owner, mekanik, atau kasir.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-panel">
                                <div class="mb-4">
                                    <p class="mb-2 text-uppercase font-weight-bold" style="letter-spacing: .28em; font-size: 12px; color: #0284c7;">Customer Area</p>
                                    <h2 class="mb-2" style="font-family: 'Space Grotesk', sans-serif; font-size: 36px;">Login Client</h2>
                                    <p class="muted-copy mb-0">Masuk untuk booking servis, pantau histori kendaraan, dan akses My Profile client.</p>
                                </div>

                                @if (session('status'))
                                    <div class="alert alert-success" style="border-radius: 18px;">{{ session('status') }}</div>
                                @endif

                                <form method="POST" action="{{ route('client.login.store') }}">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label for="email" class="font-weight-bold">Email</label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                                               class="form-control auth-input @error('email') is-invalid @enderror"
                                               required autofocus autocomplete="username" placeholder="nama@email.com">
                                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password" class="font-weight-bold">Password</label>
                                        <input id="password" type="password" name="password"
                                               class="form-control auth-input @error('password') is-invalid @enderror"
                                               required autocomplete="current-password" placeholder="Masukkan password">
                                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                                        <div class="form-check mb-2 mb-sm-0">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                            <label class="form-check-label" for="remember_me">Ingat saya</label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="auth-link">Lupa password?</a>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-block auth-btn">Masuk ke Akun Client</button>

                                    <p class="text-center mt-4 mb-0 muted-copy">
                                        Belum punya akun?
                                        <a href="{{ route('client.register') }}" class="auth-link">Daftar client</a>
                                    </p>
                                    <p class="text-center mt-2 mb-0 muted-copy">
                                        Admin/staff masuk di
                                        <a href="{{ route('admin.login') }}" class="auth-link">login admin</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
