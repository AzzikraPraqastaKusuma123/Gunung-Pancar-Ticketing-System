<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Tiket Fisik - {{ $ticket->ticket_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #e2e8f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            color: #000;
        }
        .receipt-container {
            width: 80mm;
            background: #fff;
            padding: 10mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }
        .content {
            margin-bottom: 10px;
        }
        .content .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .content .row.bold {
            font-weight: bold;
            font-size: 14px;
        }
        .ticket-number-wrapper {
            text-align: center;
            margin: 20px 0;
            padding: 10px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .ticket-number-wrapper .label {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .ticket-number {
            font-weight: bold;
            font-size: 18px;
            letter-spacing: 2px;
        }
        .footer {
            text-align: center;
            border-top: 2px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }
        .qr-container {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }
        .qr-container img {
            width: 120px;
            height: 120px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
                display: block;
            }
            .receipt-container {
                width: 80mm; /* Keep it 80mm in print */
                max-width: 100%;
                margin: 0 auto;
                padding: 0;
                box-shadow: none;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="receipt-container">
    <div class="header">
        <h2>TWA GUNUNG PANCAR</h2>
        <p>Tiket Masuk Resmi</p>
    </div>

    <div class="content">
        <div class="row">
            <span>Pelanggan:</span>
            <span>{{ Str::limit($ticket->participant_name ?? $ticket->booking->customer_name, 15) }}</span>
        </div>
        <div class="row">
            <span>Tanggal:</span>
            <span>{{ \Carbon\Carbon::parse($ticket->booking->visit_date)->format('d/m/Y') }}</span>
        </div>
        <div class="row">
            <span>Pembayaran:</span>
            <span>{{ $ticket->booking->payment_method ?? 'CASH' }}</span>
        </div>
        <div class="row bold">
            <span>Kategori:</span>
            <span>{{ strtoupper($ticket->category) }}</span>
        </div>
        <div class="row bold">
            <span>Jumlah:</span>
            <span>{{ $ticket->participant_count }} PAX</span>
        </div>
    </div>

    <div class="ticket-number-wrapper">
        <div class="label">NOMOR TIKET:</div>
        <div class="ticket-number">{{ $ticket->ticket_number }}</div>
    </div>

    <div class="qr-container">
        <img src="{{ asset('storage/' . $ticket->qr_code_path) }}" alt="QR Code" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/api/tickets/validate/' . $ticket->ticket_number)) }}'">
    </div>

    <div class="footer">
        Simpan tiket ini dengan baik.<br>
        Tunjukkan tiket fisik ini ke petugas Gate.<br>
        Terima kasih atas kunjungan Anda!
    </div>
</div>

</body>
</html>
