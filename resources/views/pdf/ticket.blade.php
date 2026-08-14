<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket - {{ $booking->uuid }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 14px; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; width: 100%; display: table; }
        .header-left { display: table-cell; width: 50%; }
        .header-right { display: table-cell; width: 50%; text-align: right; }
        .header h1 { margin: 0; color: #1e293b; font-size: 24px; }
        .ticket-box { border: 1px dashed #ccc; padding: 15px; margin-bottom: 15px; background: #f8fafc; border-radius: 8px; display: table; width: 100%; box-sizing: border-box; page-break-inside: avoid; }
        .qr-code { display: table-cell; width: 100px; vertical-align: top; }
        .qr-code img { width: 90px; height: 90px; border-radius: 5px; }
        .ticket-details { display: table-cell; vertical-align: top; padding-left: 15px; }
        .ticket-number { font-size: 18px; font-weight: bold; color: #0f172a; margin-top: 5px; }
        .ticket-category { color: #64748b; font-size: 13px; text-transform: uppercase; margin-top: 5px; }
        .ticket-status { display: inline-block; padding: 3px 8px; font-size: 11px; background: #d1fae5; color: #065f46; border-radius: 20px; margin-top: 10px; }
        .footer { margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px; text-align: center; color: #64748b; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <h1>E-TICKET GUNUNG PANCAR</h1>
            <p style="margin: 5px 0 0; color:#64748b;">ID Booking: {{ $booking->uuid }}</p>
        </div>
        <div class="header-right">
            <div style="font-weight: bold; color: #0f172a;">{{ $booking->customer_name }}</div>
            <div style="color: #64748b;">{{ $booking->customer_email }}</div>
            <div style="color: #64748b; margin-top: 5px;">Tgl Kunjungan: {{ \Carbon\Carbon::parse($booking->visit_date)->format('d F Y') }}</div>
        </div>
    </div>

    <h3 style="margin-top: 0;">Daftar Tiket Anda:</h3>

    @foreach($booking->tickets as $ticket)
    <div class="ticket-box">
        <div class="qr-code">
            <!-- Gunakan public_path() untuk dompdf jika local image -->
            @php
                // Jika pakai URL
                // $imageSrc = asset('storage/' . $ticket->qr_code_path);
                // Jika pakai path fisik (disarankan untuk dompdf)
                $imagePath = storage_path('app/public/' . $ticket->qr_code_path);
                $imageData = base64_encode(@file_get_contents($imagePath));
                $imageSrc = 'data:image/png;base64,'.$imageData;
            @endphp
            <img src="{{ $imageSrc }}" alt="QR Code">
        </div>
        <div class="ticket-details">
            <div class="ticket-number">{{ $ticket->ticket_number }}</div>
            <div class="ticket-category">Kategori: {{ $ticket->category }} &bull; Untuk: {{ $ticket->participant_count }} Orang</div>
            <div class="ticket-status">{{ strtoupper($ticket->status) }}</div>
        </div>
    </div>
    @endforeach

    <div class="footer">
        Harap tunjukkan E-Ticket (QR Code) ini kepada petugas di gerbang masuk.<br>
        Taman Wisata Alam Gunung Pancar
    </div>

</body>
</html>
