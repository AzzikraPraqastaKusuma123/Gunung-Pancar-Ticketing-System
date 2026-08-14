<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk POS - {{ $booking->uuid }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        
        body {
            font-family: 'Courier Prime', 'Courier New', Courier, monospace; /* Thermal printers use monospace */
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 20px;
            margin: 0;
            color: #000;
        }

        .receipt-container {
            width: 300px; /* ~58mm thermal paper width */
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Print styles */
        @media print {
            @page {
                margin: 0;
                size: 58mm auto; /* Standard thermal roll width */
            }
            body {
                background-color: #fff;
                padding: 0;
                display: block;
            }
            .receipt-container {
                width: 100%;
                max-width: 58mm;
                box-shadow: none;
                padding: 5mm;
                margin: 0 auto;
            }
            .no-print {
                display: none !important;
            }
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-sm { font-size: 12px; }
        .text-xs { font-size: 10px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        
        .divider {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }
        
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        
        .ticket-item {
            margin-bottom: 8px;
        }

        .qr-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .qr-container img {
            width: 120px;
            height: 120px;
        }

        /* Action buttons for preview screen */
        .preview-actions {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-family: sans-serif;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
        }
        .btn-secondary {
            background-color: #64748b;
        }
    </style>
</head>
<body>

    <div class="no-print preview-actions">
        <button onclick="window.print()" class="btn">🖨️ Cetak Struk</button>
        <button onclick="history.back()" class="btn btn-secondary">Kembali</button>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>

    <div class="receipt-container">
        
        <!-- Header -->
        <div class="text-center mb-2">
            <h2 style="margin: 0; font-size: 18px;">GUNUNG PANCAR</h2>
            <div class="text-xs mt-2">
                Taman Wisata Alam Gunung Pancar<br>
                Jl. Desa Karang Tengah, Babakan Madang<br>
                Bogor, Jawa Barat
            </div>
        </div>

        <div class="divider"></div>

        <!-- Meta Info -->
        <div class="text-xs mb-2">
            <div class="flex justify-between">
                <span>WAKTU:</span>
                <span>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>ORDER:</span>
                <span>{{ substr($booking->uuid, 0, 8) }}</span>
            </div>
            <div class="flex justify-between">
                <span>KASIR:</span>
                <span>{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
            <div class="flex justify-between">
                <span>NAMA:</span>
                <span>{{ Str::limit($booking->customer_name, 15) }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <div class="text-sm font-bold mb-1">TIKET MASUK</div>
        
        @foreach($booking->items as $item)
            @php 
                $qty = $item->quantity;
                $pricePerItem = $item->price_per_item;
                $subtotal = $qty * $pricePerItem;
                $itemName = $item->tourPackage->name ?? str_replace('_', ' ', $item->category ?? 'Tiket');
            @endphp
            <div class="ticket-item text-xs">
                <div class="font-bold">{{ strtoupper($itemName) }}</div>
                <div class="flex justify-between">
                    <span>{{ $qty }}x @ {{ number_format($pricePerItem, 0, ',', '.') }}</span>
                    <span>{{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach

        <div class="divider"></div>

        <!-- Total -->
        <div class="flex justify-between font-bold text-sm">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-xs mt-2">
            <span>METODE BAYAR:</span>
            <span>{{ strtoupper($booking->payment_method ?? 'CASH') }}</span>
        </div>
        <div class="flex justify-between text-xs">
            <span>STATUS:</span>
            <span>{{ strtoupper($booking->status) }}</span>
        </div>

        <div class="divider"></div>

        <!-- QR Codes for each ticket -->
        <div class="text-center text-xs font-bold mt-4 mb-2">SCAN QR DI GERBANG</div>
        
        @foreach($booking->tickets as $ticket)
            <div class="qr-container">
                <div class="text-xs mb-1 font-bold">{{ $ticket->ticket_number }}</div>
                <div class="text-xs mb-1">{{ strtoupper($ticket->category) }}</div>
                <img src="{{ asset('storage/' . $ticket->qr_code_path) }}" alt="QR Code" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/api/tickets/validate/' . $ticket->ticket_number)) }}'">
            </div>
            @if(!$loop->last)
                <div style="border-bottom: 1px dotted #ccc; margin: 10px 0;"></div>
            @endif
        @endforeach

        <div class="divider"></div>

        <!-- Footer -->
        <div class="text-center text-xs mt-4">
            <p style="margin: 0 0 5px 0;">Terima kasih atas kunjungan Anda!</p>
            <p style="margin: 0 0 5px 0;">Patuhi protokol kesehatan &</p>
            <p style="margin: 0;">jaga kebersihan lingkungan.</p>
            <br>
            <p style="margin: 0; font-weight: bold;">IG: @gunungpancar</p>
        </div>

    </div>

</body>
</html>
