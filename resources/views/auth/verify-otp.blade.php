<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verifikasi OTP - Bengkel Mobil</title>
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
            text-align: center;
            font-size: 24px;
            letter-spacing: 4px;
            font-weight: 700;
        }
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
        .otp-input {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: 2px solid #dbe2ea;
            background: #f8fafc;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
        }
        .otp-input:focus {
            border-color: rgba(251, 146, 60, 0.6);
            box-shadow: 0 0 0 4px rgba(251, 146, 60, 0.12);
            background: #fff;
        }
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
                                    <span class="brand-badge">Verifikasi Email</span>
                                    <h1 class="brand-title">Verifikasi email kamu untuk melanjutkan.</h1>
                                    <p class="mb-0" style="color: rgba(255,255,255,.78); font-size: 16px;">
                                        Kami telah mengirimkan kode OTP ke email terdaftar. Masukkan kode tersebut untuk memverifikasi akun Anda.
                                    </p>
                                </div>

                                <div class="mini-note">
                                    <h5 class="text-white mb-2">Tidak menerima email?</h5>
                                    <p class="mb-0" style="color: rgba(255,255,255,.78);">
                                        Periksa folder spam atau gunakan tombol "Kirim Ulang Kode" di halaman verifikasi.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-panel">
                                <div class="mb-4">
                                    <p class="mb-2 text-uppercase font-weight-bold" style="letter-spacing: .28em; font-size: 12px; color: #ea580c;">Verifikasi Email</p>
                                    <h2 class="mb-2" style="font-family: 'Space Grotesk', sans-serif; font-size: 36px;">Masukkan Kode OTP</h2>
                                    <p class="muted-copy mb-0">Kami telah mengirimkan kode 6 digit ke email <strong>{{ $email }}</strong></p>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        @foreach($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('otp.verify') }}">
                                    @csrf

                                    <div class="form-group mb-4">
                                        <label for="otp" class="font-weight-bold">Kode OTP (6 digit)</label>
                                        <input id="otp" type="text" name="otp" 
                                               class="form-control auth-input @error('otp') is-invalid @enderror"
                                               required autofocus maxlength="6" inputmode="numeric"
                                               placeholder="000000">
                                        @error('otp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>

                                    <button type="submit" class="btn btn-block auth-btn">Verifikasi Sekarang</button>

                                    <p class="text-center mt-4 mb-0 muted-copy">
                                        Belum menerima kode?
                                        <form method="POST" action="{{ route('otp.resend') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 auth-link" style="text-decoration: none;">Kirim Ulang Kode</button>
                                        </form>
                                    </p>
                                </form>

                                <div style="margin-top: 20px; padding: 16px; background: #f0fdf4; border-radius: 14px; border: 1px solid #c9efd9;">
                                    <p class="text-success mb-0" style="font-size: 13px;">
                                        <strong>ℹ️ Informasi:</strong> Kode OTP berlaku selama 15 menit. Jangan bagikan kode ini kepada siapa pun.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center mt-3 mb-0 muted-copy"></p>
            </div>
        </div>
    </div>
</section>
</body>
</html>
