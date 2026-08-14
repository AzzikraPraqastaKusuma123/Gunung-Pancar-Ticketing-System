<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Invoice - Gunung Pancar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
        .font-mono { font-family: 'Space Mono', monospace; }
        
        .receipt-edge {
            background-image: radial-gradient(circle at 10px 10px, transparent 12px, #ffffff 13px);
            background-size: 20px 20px;
            background-position: -10px -10px;
            height: 10px;
            width: 100%;
        }
        
        .receipt-edge-bottom {
            background-image: radial-gradient(circle at 10px 0, transparent 12px, #ffffff 13px);
            background-size: 20px 20px;
            background-position: -10px 0;
            height: 10px;
            width: 100%;
        }

        .barcode-stripes {
            background: repeating-linear-gradient(
                90deg,
                #1e293b,
                #1e293b 2px,
                transparent 2px,
                transparent 4px,
                #1e293b 4px,
                #1e293b 5px,
                transparent 5px,
                transparent 8px,
                #1e293b 8px,
                #1e293b 12px,
                transparent 12px,
                transparent 14px
            );
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-2xl w-full">
        
        @if(session('success'))
            <div class="mb-6 bg-emerald-500 text-white px-4 py-3 rounded-xl text-center font-bold shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2 animate-bounce">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 transition font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>

        <!-- Receipt Container -->
        <div class="shadow-2xl shadow-slate-300/50 rounded-2xl overflow-hidden bg-transparent">
            
            <!-- Top Edge -->
            <div class="receipt-edge rotate-180"></div>
            
            <!-- Receipt Body -->
            <div class="bg-white px-8 pt-8 pb-4 relative">
                
                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path></svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">E-Ticket Confirmed</h1>
                    <p class="text-slate-500 font-medium">Taman Wisata Alam Gunung Pancar</p>
                </div>

                <!-- Booking Info -->
                <div class="bg-slate-50 rounded-xl p-5 mb-8 border border-slate-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Booking ID</p>
                            <p class="font-mono text-slate-800 font-bold text-sm">{{ strtoupper(substr($booking->uuid, 0, 8)) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Tgl Kunjungan</p>
                            <p class="text-emerald-600 font-bold text-sm">{{ \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') }}</p>
                        </div>
                        <div class="col-span-2 pt-3 border-t border-slate-200 mt-1">
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Pemesan</p>
                            <p class="text-slate-800 font-bold">{{ $booking->customer_name }}</p>
                            <div class="flex items-center gap-3 mt-1">
                                <p class="text-slate-500 text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $booking->customer_email }}
                                </p>
                                <p class="text-slate-500 text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $booking->customer_phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets Divider -->
                <div class="relative flex items-center justify-center mb-8">
                    <div class="border-t-2 border-dashed border-slate-200 w-full absolute"></div>
                    <div class="bg-white px-4 relative z-10 text-xs font-bold uppercase tracking-widest text-slate-400">
                        Daftar Tiket ({{ $booking->tickets->count() }})
                    </div>
                    <!-- Hole cutouts -->
                    <div class="absolute w-6 h-6 bg-slate-100 rounded-full -left-11 shadow-inner"></div>
                    <div class="absolute w-6 h-6 bg-slate-100 rounded-full -right-11 shadow-inner"></div>
                </div>

                <!-- Tickets List -->
                <div class="space-y-4 mb-8">
                    @foreach($booking->tickets as $ticket)
                    <div class="flex gap-4 items-center p-3 hover:bg-slate-50 rounded-xl transition border border-transparent hover:border-slate-100">
                        <div class="flex-1 min-w-0">
                            <div class="font-mono text-sm font-bold text-slate-800 truncate mb-0.5">{{ $ticket->ticket_number }}</div>
                            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-1">{{ $ticket->category }}</div>
                            
                            @if($ticket->status == 'booked')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
                                    {{ $ticket->status }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                    {{ $ticket->status }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Total & Action -->
                <div class="border-t-2 border-slate-900 pt-6">
                    <div class="flex justify-between items-end mb-8">
                        <div>
                            <p class="text-sm text-slate-500 font-bold uppercase tracking-wider mb-1">Total Pembayaran</p>
                            <p class="text-3xl font-extrabold text-slate-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-16 h-8 barcode-stripes opacity-20 hidden sm:block"></div>
                    </div>

                    @if($booking->status === 'pending')
                        <button id="pay-button" class="flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl transition duration-300 shadow-lg shadow-emerald-600/20 group gap-2">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Bayar Sekarang
                        </button>
                        <p class="text-center text-xs text-slate-400 mt-4 font-medium">Selesaikan pembayaran untuk mengaktifkan tiket Anda.</p>
                    @else

                        @if(Str::startsWith($booking->customer_email, 'walkin_'))
                            <a href="{{ route('booking.pos_print', $booking->uuid) }}" class="flex items-center justify-center w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl transition duration-300 shadow-lg shadow-slate-900/20 group gap-2">
                                <svg class="w-5 h-5 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak Struk Thermal
                            </a>
                            <p class="text-center text-xs text-slate-400 mt-4 font-medium">Ini adalah pesanan offline. Struk akan dicetak dengan format printer thermal.</p>
                        @else
                            <a href="{{ route('ticket.download', $booking->uuid) }}" class="flex items-center justify-center w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl transition duration-300 shadow-lg shadow-slate-900/20 group gap-2">
                                <svg class="w-5 h-5 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh PDF Tiket
                            </a>
                            <p class="text-center text-xs text-slate-400 mt-4 font-medium">Harap tunjukkan QR Code pada PDF/halaman ini kepada petugas di gerbang masuk.</p>
                        @endif
                    @endif
                </div>

            </div>
            
            <!-- Bottom Edge -->
            <div class="receipt-edge-bottom"></div>
        </div>
    </div>

    @if($booking->status === 'pending')
        @if($booking->snap_token)
            <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
            <script>
                var payButton = document.getElementById('pay-button');
                if (payButton) {
                    payButton.onclick = function () {
                        if (typeof snap === 'undefined') {
                            alert("Sistem pembayaran (Midtrans) gagal dimuat oleh browser Anda. Harap matikan AdBlock, periksa koneksi internet, atau pastikan kunci Midtrans sudah benar.");
                            return;
                        }

                        try {
                            snap.pay('{{ $booking->snap_token }}', {
                                onSuccess: function(result){
                                    window.location.reload();
                                },
                                onPending: function(result){
                                    alert("Menunggu pembayaran Anda!");
                                },
                                onError: function(result){
                                    alert("Pembayaran gagal!");
                                },
                                onClose: function(){
                                    console.log('Anda menutup popup tanpa menyelesaikan pembayaran');
                                }
                            });
                        } catch (e) {
                            alert("Terjadi kesalahan saat memproses pembayaran: " + e.message);
                        }
                    };
                }
            </script>
        @else
            <script>
                var payButton = document.getElementById('pay-button');
                if (payButton) {
                    payButton.onclick = function () {
                        alert("Gagal memuat token pembayaran dari sistem. Pastikan konfigurasi kunci Midtrans Anda benar atau hubungi admin.");
                    };
                }
            </script>
        @endif
    @endif
</body>
</html>
