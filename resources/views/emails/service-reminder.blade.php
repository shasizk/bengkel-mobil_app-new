<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Service</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f1f5f9;
            font-family: Arial, Helvetica, sans-serif;
            color: #0f172a;
        }
        .wrapper {
            max-width: 640px;
            margin: 0 auto;
            padding: 24px;
        }
        .card {
            background: #ffffff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.08);
        }
        .header {
            padding: 24px;
            background: linear-gradient(135deg, #0f172a 0%, #0f766e 100%);
            color: #fff;
        }
        .content {
            padding: 24px;
        }
        .detail {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }
        .detail td {
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .detail td:first-child {
            width: 38%;
            color: #64748b;
            font-weight: 700;
        }
        .button {
            display: inline-block;
            margin-top: 22px;
            padding: 12px 18px;
            border-radius: 999px;
            background: #0f766e;
            color: #fff !important;
            text-decoration: none;
            font-weight: 700;
        }
        .muted {
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div style="letter-spacing: .18em; font-size: 12px; text-transform: uppercase; opacity: .8;">Raxy Garage</div>
                <h1 style="margin: 10px 0 0; font-size: 26px;">Pengingat Jadwal Service Hari Ini</h1>
            </div>
            <div class="content">
                <p>Halo {{ $booking->user->name ?? 'Customer' }},</p>
                <p>Ini adalah pengingat bahwa kendaraan Anda memiliki jadwal service hari ini. Silakan datang sesuai waktu yang sudah Anda pilih.</p>

                <table class="detail">
                    <tr>
                        <td>Booking</td>
                        <td>#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <td>Kendaraan</td>
                        <td>{{ $booking->vehicle->brand ?? '-' }} {{ $booking->vehicle->model ?? '' }} - {{ $booking->vehicle->license_plate ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jadwal</td>
                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }} jam {{ $booking->booking_time }}</td>
                    </tr>
                    <tr>
                        <td>Preferensi Bayar</td>
                        <td>{{ strtoupper($booking->payment_preference ?? 'cash') }}</td>
                    </tr>
                    <tr>
                        <td>Keluhan</td>
                        <td>{{ $booking->complaint ?: '-' }}</td>
                    </tr>
                </table>

                @if($booking->transaction?->payment?->payment_url)
                    <a href="{{ $booking->transaction->payment->payment_url }}" target="_blank" class="button">Buka Pembayaran</a>
                @endif

                <p class="muted" style="margin-top: 22px;">Jika Anda memilih QRIS atau debit/transfer, ikuti tautan pembayaran yang sudah disiapkan admin. Jika memilih cash, Anda bisa membayar langsung di kasir saat datang ke bengkel.</p>
            </div>
        </div>
    </div>
</body>
</html>
