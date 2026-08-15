<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tiket Fisik - {{ $ticket->ticket_number }}</title>
    <style>
        :root {
            --bg-body: #f1f5f9;
            --bg-receipt: #ffffff;
            --text-main: #0f172a;
            --text-muted: #475569;
            --border-dash: #cbd5e1;
        }
        
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: var(--bg-body);
            margin: 0;
            padding: 20px 10px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            color: var(--text-main);
            box-sizing: border-box;
        }

        .receipt-container {
            width: 100%;
            max-width: 80mm;
            background: var(--bg-receipt);
            padding: 8mm 6mm;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            margin: 0 auto;
            position: relative;
        }

        /* Jagged bottom edge effect for digital preview */
        .receipt-container::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 8px;
            background-size: 16px 16px;
            background-image: radial-gradient(circle at 8px 0, transparent 8px, var(--bg-receipt) 9px);
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed var(--border-dash);
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0 0 4px 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
        }

        .content {
            margin-bottom: 15px;
        }

        .content .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
            font-size: 12px;
            line-height: 1.4;
        }

        .content .row span:first-child {
            color: var(--text-muted);
            min-width: 80px;
        }

        .content .row span:last-child {
            text-align: right;
            font-weight: 600;
        }

        .content .row.bold {
            font-size: 13px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dotted var(--border-dash);
        }

        .content .row.bold span:first-child {
            color: var(--text-main);
        }

        .ticket-number-wrapper {
            text-align: center;
            margin: 20px 0;
            padding: 12px 0;
            border-top: 2px dashed var(--border-dash);
            border-bottom: 2px dashed var(--border-dash);
            background-color: #f8fafc;
            border-radius: 4px;
        }

        .ticket-number-wrapper .label {
            font-size: 10px;
            margin-bottom: 4px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .ticket-number {
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 2px;
            color: #000;
        }

        .qr-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .qr-container img {
            width: 140px;
            height: 140px;
            object-fit: contain;
            border-radius: 4px;
            padding: 4px;
            border: 1px solid #e2e8f0;
            background: #fff;
        }

        .footer {
            text-align: center;
            border-top: 2px dashed var(--border-dash);
            padding-top: 15px;
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .print-btn-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
        }

        .btn-print {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 9999px;
            font-family: inherit;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                display: block;
                min-height: auto;
            }
            .receipt-container {
                width: 80mm;
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }
            .receipt-container::after {
                display: none;
            }
            .ticket-number-wrapper {
                background-color: transparent;
            }
            .qr-container img {
                border: none;
                padding: 0;
            }
            .print-btn-container {
                display: none !important;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="setTimeout(() => window.print(), 500)">

<div class="receipt-container">
    <div class="header">
        <h2>TWA GUNUNG PANCAR</h2>
        <p>Tiket Masuk Resmi</p>
    </div>

    <div class="content">
        <div class="row">
            <span>Pelanggan:</span>
            <span>{{ Str::limit($ticket->participant_name ?? $ticket->booking->customer_name, 18) }}</span>
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
        <strong>Terima kasih atas kunjungan Anda!</strong>
    </div>
</div>

<div class="print-btn-container">
    <button class="btn-print" onclick="window.print()">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Tiket
    </button>
</div>

</body>
</html>

