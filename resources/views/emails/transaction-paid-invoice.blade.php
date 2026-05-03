<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran Lunas</title>
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
            background: linear-gradient(135deg, #0f172a 0%, #155e75 100%);
            color: #ffffff;
        }
        .hero small {
            display: block;
            letter-spacing: .18em;
            text-transform: uppercase;
            opacity: .72;
            font-size: 11px;
        }
        .content {
            padding: 24px 28px 30px;
            line-height: 1.7;
            font-size: 14px;
        }
        .detail {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0 22px;
        }
        .detail td {
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 0;
            vertical-align: top;
        }
        .detail td:first-child {
            width: 38%;
            color: #64748b;
            font-weight: 700;
        }
        .pill {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
            background: #15803d;
        }
        .foot {
            margin-top: 22px;
            color: #64748b;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="hero">
                <small>Raxy Garage</small>
                <h1 style="margin: 10px 0 0; font-size: 26px;">Pembayaran Berhasil</h1>
                <p style="margin: 8px 0 0; color: rgba(255,255,255,.85);">Invoice Anda sudah lunas dan terlampir pada email ini.</p>
            </div>
            <div class="content">
                <p>Halo {{ $transaction->booking->user->name ?? 'Customer' }},</p>
                <p>
                    Terima kasih, pembayaran Anda telah kami terima. Berikut ringkasan invoice:
                </p>

                <table class="detail">
                    <tr>
                        <td>No. Invoice</td>
                        <td>#{{ $transaction->id }}</td>
                    </tr>
                    <tr>
                        <td>Kode Bayar</td>
                        <td>{{ $transaction->payment->payment_code ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Metode</td>
                        <td>{{ strtoupper($transaction->payment->payment_method ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>Rp {{ number_format((float) $transaction->grand_total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><span class="pill">PAID</span></td>
                    </tr>
                </table>

                <p>
                    Tim kami sudah menyiapkan dokumen invoice final. Anda juga dapat melihat status terbaru melalui menu riwayat booking akun Anda.
                </p>

                <p class="foot">
                    Terima kasih telah mempercayakan kendaraan Anda kepada Raxy Garage.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
