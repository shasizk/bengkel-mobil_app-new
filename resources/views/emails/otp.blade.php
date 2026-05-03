<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #eef2f7;
            font-family: Arial, Helvetica, sans-serif;
            color: #0f172a;
        }
        .wrapper {
            max-width: 680px;
            margin: 0 auto;
            padding: 28px 16px;
        }
        .card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.12);
        }
        .hero {
            padding: 28px;
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            color: #ffffff;
            text-align: center;
        }
        .hero small {
            display: block;
            letter-spacing: .18em;
            text-transform: uppercase;
            opacity: .72;
            font-size: 11px;
        }
        .hero h1 {
            margin: 12px 0 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 24px 28px 30px;
            line-height: 1.7;
            font-size: 14px;
            text-align: center;
        }
        .otp-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
            border: 2px solid #f59e0b;
            border-radius: 14px;
            padding: 24px;
            margin: 20px 0;
            text-align: center;
        }
        .otp-code {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #ea580c;
            font-family: 'Courier New', monospace;
        }
        .note {
            background: #f0fdf4;
            border: 1px solid #c9efd9;
            border-radius: 10px;
            padding: 14px;
            margin: 16px 0;
            font-size: 13px;
            color: #166534;
        }
        .footer {
            padding: 20px 28px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 12px 28px;
            background: linear-gradient(135deg, #fb923c, #f97316);
            color: #ffffff;
            text-decoration: none;
            border-radius: 999px;
            font-weight: bold;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="hero">
                <small>Verifikasi Email</small>
                <h1>Bengkel Mobil</h1>
            </div>
            <div class="content">
                <p>Halo <strong>{{ $user->name }}</strong>,</p>
                <p>Terima kasih telah mendaftar di Bengkel Mobil. Gunakan kode OTP di bawah ini untuk memverifikasi email Anda.</p>
                
                <div class="otp-box">
                    <div class="otp-code">{{ $otp }}</div>
                </div>
                
                <div class="note">
                    <strong>⏱️ Catatan Penting:</strong><br>
                    Kode OTP ini berlaku selama <strong>15 menit</strong>. Jangan bagikan kode ini kepada siapa pun.
                </div>
                
                <p>Jika Anda tidak mendaftar untuk layanan ini, abaikan email ini.</p>
            </div>
            <div class="footer">
                <p>© {{ now()->year }} Bengkel Mobil. Semua hak dilindungi.</p>
                <p>Email ini dikirim karena ada permintaan pendaftaran akun baru dengan email ini.</p>
            </div>
        </div>
    </div>
</body>
</html>
