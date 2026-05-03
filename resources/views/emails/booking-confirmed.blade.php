<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
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
            background: #0f766e;
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
                <h1 style="margin: 10px 0 0; font-size: 26px;">Booking Confirmed</h1>
                <p style="margin: 8px 0 0; color: rgba(255,255,255,.85);">Jadwal servis kendaraan Anda sudah kami konfirmasi.</p>
            </div>
            <div class="content">
                <p>Halo {{ $booking->user->name ?? 'Customer' }},</p>
                <p>
                    Booking Anda sudah <strong>dikonfirmasi</strong>. Silakan datang sesuai jadwal berikut:
                </p>

                <table class="detail">
                    <tr>
                        <td>No. Booking</td>
                        <td>#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Servis</td>
                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Jam Servis</td>
                        <td>{{ $booking->booking_time }}</td>
                    </tr>
                    <tr>
                        <td>Kendaraan</td>
                        <td>{{ $booking->vehicle->brand ?? '-' }} {{ $booking->vehicle->model ?? '' }} - {{ $booking->vehicle->license_plate ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><span class="pill">CONFIRMED</span></td>
                    </tr>
                </table>

                <p>
                    Booking confirmed, silahkan datang tanggal <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}</strong> jam <strong>{{ $booking->booking_time }}</strong>.
                </p>

                <p class="foot">
                    Terima kasih telah mempercayakan kendaraan Anda kepada Raxy Garage.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
