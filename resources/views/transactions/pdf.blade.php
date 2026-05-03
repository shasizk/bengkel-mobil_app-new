<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            color: #111827;
            font-size: 12px;
            background: #fff;
        }

        .invoice-paper {
            border: 1px solid #d1d5db;
            padding: 14px;
        }

        .invoice-paper .line-title {
            font-size: 11px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .invoice-paper table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-paper table th,
        .invoice-paper table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            vertical-align: top;
        }

        .invoice-paper .table-title {
            background: #f3f4f6;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    @include('transactions.partials.invoice-document', ['transaction' => $transaction])
</body>
</html>
