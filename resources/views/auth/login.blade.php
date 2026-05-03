<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Admin - Bengkel Mobil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fe/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fe/assets/css/style.css') }}">

    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --sky: #0ea5e9;
            --mint: #34d399;
            --sand: #f8fafc;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(52, 211, 153, 0.25), transparent 30%),
                radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.22), transparent 26%),
                linear-gradient(135deg, #e2e8f0 0%, #f8fafc 45%, #ecfeff 100%);
            color: var(--ink);
        }
        .auth-wrap { min-height: 100vh; display: flex; align-items: center; padding: 40px 0; }
        .auth-shell {
            border-radius: 34px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.18);
            background: rgba(255,255,255,0.78);
            backdrop-filter: blur(16px);
        }
        .brand-panel {
            min-height: 100%;
            padding: 48px;
            color: #fff;
            background:
                linear-gradient(160deg, rgba(15,23,42,0.95) 0%, rgba(8,47,73,0.92) 55%, rgba(6,95,70,0.88) 100%);
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
            font-size: 46px;
            line-height: 1.05;
            margin: 24px 0 16px;
        }
        .brand-card {
            margin-top: 28px;
            padding: 24px;
            border-radius: 28px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .form-panel { padding: 48px 42px; background: rgba(255,255,255,0.92); }
        .auth-input {
            min-height: 54px;
            border-radius: 18px;
            border: 1px solid #dbe2ea;
            background: #f8fafc;
            padding: 0 18px;
        }
        .auth-input:focus {
            border-color: rgba(14, 165, 233, 0.6);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
            background: #fff;
        }
        .auth-btn {
            min-height: 54px;
            border-radius: 999px;
            font-weight: 700;
            border: none;
            background: linear-gradient(135deg, var(--sky), var(--mint));
            color: var(--ink);
        }
        .auth-btn:hover { color: var(--ink); transform: translateY(-1px); }
        .auth-link { color: var(--sky); font-weight: 700; }
        .muted-copy { color: var(--muted); }
        @media (max-width: 991.98px) {
            .brand-panel { padding: 36px 30px; }
            .form-panel { padding: 36px 30px; }
            .brand-title { font-size: 36px; }
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
                        <div class="col-lg-6 d-flex">
                            <div class="brand-panel w-100 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="brand-badge">Admin Access</span>
                                    <h1 class="brand-title">Servis mobil jadi lebih rapi, cepat, dan terasa premium.</h1>
                                    <p class="mb-0" style="color: rgba(255,255,255,.76); font-size: 16px;">
                                        Halaman ini khusus admin, owner, mekanik, dan kasir untuk masuk ke sistem operasional bengkel.
                                    </p>
                                </div>

                                <div class="brand-card">
                                    <h5 class="text-white">Kenapa halaman ini dirombak?</h5>
                                    <p class="mb-0" style="color: rgba(255,255,255,.76);">
                                        Supaya pengalaman login terasa lebih meyakinkan buat customer maupun admin: layout lebih kuat, visual lebih modern, dan tetap nyaman dipakai di mobile.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-panel">
                                <div class="mb-4">
                                    <p class="mb-2 text-uppercase font-weight-bold" style="letter-spacing: .28em; font-size: 12px; color: #0ea5e9;">Staff Login</p>
                                    <h2 class="mb-2" style="font-family: 'Space Grotesk', sans-serif; font-size: 38px;">Login Admin & Staff</h2>
                                    <p class="muted-copy mb-0">Masuk untuk mengelola dashboard admin, transaksi, booking, sparepart, Bookeeping, dan chat client. Sesi tiap role backend sekarang terpisah, jadi bisa aktif di tab berbeda.</p>
                                </div>

                                @if (session('status'))
                                    <div class="alert alert-success" style="border-radius: 18px;">{{ session('status') }}</div>
                                @endif

                                <form method="POST" action="{{ route('admin.login.store') }}">
                                    @csrf
                                    @if(request('ctx'))
                                        <input type="hidden" name="ctx" value="{{ request('ctx') }}">
                                    @endif

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

                                    <button type="submit" class="btn btn-block auth-btn">Login Sekarang</button>

                                    <p class="text-center mt-4 mb-0 muted-copy">
                                        Client/customer login di
                                        <a href="{{ route('client.login') }}" class="auth-link">halaman client</a>
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
