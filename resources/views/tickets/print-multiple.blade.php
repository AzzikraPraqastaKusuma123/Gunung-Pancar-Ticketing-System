<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Booking - {{ $booking->uuid }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background-color: white !important;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                min-height: auto !important;
                display: block !important;
                height: 100% !important;
                overflow: hidden !important;
            }
            .no-print {
                display: none !important;
            }
            .ticket-container {
                page-break-inside: avoid;
                box-shadow: none !important;
                border: none !important;
                margin: 0 auto !important;
                padding-top: 10mm !important;
                padding-bottom: 0 !important;
                transform: scale(0.95);
                transform-origin: top center;
            }
            .ticket-container:not(:last-child) {
                page-break-after: always;
            }
            .cutout-top, .cutout-bottom {
                background-color: white !important;
            }
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc;
        }
        .text-gold {
            color: #E2C792;
        }
        .border-gold {
            border-color: #E2C792;
        }
        .bg-gold {
            background-color: #E2C792;
        }
        .ticket-bg {
            background-color: #064e3b;
            position: relative;
            overflow: hidden;
        }
        .font-cursive {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen py-10">

    <div class="no-print mb-10 w-[1000px] flex items-center justify-between bg-white/80 backdrop-blur-md p-5 rounded-2xl shadow-sm border border-slate-200/60">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Semua Tiket Anda</h2>
            <p class="text-slate-500 text-sm font-medium">Terdapat {{ $booking->tickets->count() }} tiket dalam pesanan ini.</p>
            <!-- Action Buttons -->
            <div class="flex gap-4 mt-4">
                <button onclick="window.print()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition-colors shadow-[0_0_15px_rgba(5,150,105,0.4)] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Simpan PDF / Cetak
                </button>
                <button onclick="history.back()" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition-colors flex items-center gap-2">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @foreach($booking->tickets as $ticket)
    <!-- TICKET -->
    <div class="ticket-container relative w-[1000px] min-h-[520px] flex shadow-[0_20px_50px_rgba(15,44,35,0.15)] rounded-[2rem] overflow-hidden bg-white mb-10 border border-slate-100">
        
        <!-- LEFT SIDE: Image and Info -->
        <div class="w-[70%] h-full ticket-bg relative text-white rounded-l-[2rem] flex flex-col">
            <!-- Background Image -->
            <img src="{{ asset('images/mountain_ticket_bg.png') }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Gunung Pancar">
            <!-- Dark Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#064e3b]/80 via-[#047857]/50 to-[#022c22]/90"></div>

            <div class="relative z-10 h-full p-8 flex flex-col justify-between grow">
                <!-- Top: Logo -->
                <div class="flex flex-col items-start relative">
                    <svg class="w-12 h-12 text-gold mb-2 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path>
                    </svg>
                    <h1 class="text-4xl font-extrabold tracking-[0.25em] leading-tight drop-shadow-lg">GUNUNG</h1>
                    <h1 class="text-4xl font-extrabold tracking-[0.25em] text-white leading-tight drop-shadow-lg">PANCAR</h1>
                    <p class="text-gold text-[10px] font-semibold tracking-[0.4em] mt-2 opacity-90">NATURE &bull; ADVENTURE &bull; HARMONY</p>
                </div>

                <!-- Middle: Ticket Title -->
                <div class="mt-4 relative">
                    <div class="absolute -left-6 top-0 w-2 h-full bg-gold rounded-r-md shadow-[0_0_15px_rgba(226,199,146,0.3)]"></div>
                    <h2 class="text-gold text-[26px] font-black tracking-widest uppercase">TIKET MASUK</h2>
                    <p class="text-[12px] font-semibold tracking-[0.3em] text-emerald-100/70 uppercase">ADMISSION TICKET</p>
                </div>

                <!-- Middle-Bottom: Info grid -->
                <div class="grid grid-cols-4 gap-4 mt-4 w-[95%]">
                    <div class="bg-black/20 backdrop-blur-md p-4 rounded-2xl border border-white/10 shadow-xl">
                        <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-gold mb-2 shadow-inner">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[9px] font-bold tracking-widest text-emerald-200/60 uppercase">Tanggal</p>
                        <p class="text-sm font-extrabold mt-0.5 uppercase text-white truncate">{{ \Carbon\Carbon::parse($ticket->booking->booking_date)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="bg-black/20 backdrop-blur-md p-4 rounded-2xl border border-white/10 shadow-xl">
                        <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-gold mb-2 shadow-inner">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[9px] font-bold tracking-widest text-emerald-200/60 uppercase">Jam Masuk</p>
                        <p class="text-sm font-extrabold mt-0.5 uppercase text-white truncate">08:00 - END</p>
                    </div>
                    <div class="bg-black/20 backdrop-blur-md p-4 rounded-2xl border border-white/10 shadow-xl">
                        <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-gold mb-2 shadow-inner">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <p class="text-[9px] font-bold tracking-widest text-emerald-200/60 uppercase">Kategori</p>
                        <p class="text-sm font-extrabold mt-0.5 uppercase text-white truncate">{{ Str::limit($ticket->category, 12) }}</p>
                    </div>
                    <div class="bg-black/20 backdrop-blur-md p-4 rounded-2xl border border-[#E2C792]/30 shadow-xl">
                        <div class="w-9 h-9 rounded-full bg-[#E2C792]/20 flex items-center justify-center text-gold mb-2 shadow-inner">
                            <span class="font-black text-xs">Rp</span>
                        </div>
                        <p class="text-[9px] font-bold tracking-widest text-emerald-200/60 uppercase">Harga</p>
                        <p class="text-sm font-extrabold mt-0.5 text-gold truncate">Rp {{ number_format($booking->total_price / max(1, $booking->tickets->count()), 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Bottom: Footer text -->
                <div class="mt-8 flex justify-between items-end relative z-20">
                    <div class="font-cursive text-emerald-100/20 text-[3.5rem] leading-none absolute -bottom-2 -left-2 font-bold select-none pointer-events-none drop-shadow-xl">
                        Escape.
                    </div>
                    <div class="w-full text-[10px] font-bold text-white/60 text-right flex justify-end gap-5 items-center tracking-wider relative z-10 mb-1 drop-shadow-md">
                        <span>IG: @gunungpancar</span>
                        <span>WWW.GUNUNGPANCAR.ID</span>
                    </div>
                </div>
            </div>
            
            <!-- Side Text -->
            <div class="absolute right-4 top-1/2 -translate-y-1/2 rotate-90 text-[10px] font-bold tracking-[0.5em] text-gold/30 whitespace-nowrap">
                JELAJAH • RASAKAN • JAGA ALAM
            </div>
        </div>

        <!-- Perforated Line Divider -->
        <div class="w-[2px] h-full border-r-[3px] border-dashed border-slate-200 relative z-20 bg-slate-50">
            <div class="cutout-top absolute -top-5 -left-5 w-10 h-10 bg-[#f8fafc] rounded-full shadow-inner border border-slate-200/60"></div>
            <div class="cutout-bottom absolute -bottom-5 -left-5 w-10 h-10 bg-[#f8fafc] rounded-full shadow-inner border border-slate-200/60"></div>
        </div>

        <!-- RIGHT SIDE: Stub -->
        <div class="w-[30%] h-full bg-slate-50 px-8 py-10 flex flex-col items-center justify-between text-slate-800 relative overflow-hidden">
            <!-- Decorative background shape -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100 rounded-bl-full opacity-50 -z-10"></div>
            
            <div class="text-center z-10">
                <svg class="w-10 h-10 mx-auto text-emerald-900 mb-2 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path>
                </svg>
                <h3 class="text-[17px] font-black tracking-widest text-emerald-950 leading-none">GUNUNG PANCAR</h3>
                <p class="text-emerald-700 text-[10px] font-bold tracking-[0.3em] mt-2 uppercase">TIKET MASUK</p>
            </div>

            <div class="flex flex-col items-center w-full mt-5 z-10">
                <!-- QR Code Container -->
                <div class="p-3 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-slate-100 relative group mt-2">
                    <img src="{{ asset('storage/' . $ticket->qr_code_path) }}" alt="QR Code" class="w-32 h-32 object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/api/tickets/validate/' . $ticket->ticket_number)) }}'">
                    
                    <!-- Ticket Number floating over QR bottom -->
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-emerald-900 text-white text-[8px] font-bold py-1.5 px-3 rounded-xl shadow-lg tracking-widest whitespace-nowrap border-2 border-white min-w-[85%] text-center">
                        {{ strtoupper($ticket->ticket_number) }}
                    </div>
                </div>
            </div>

            <!-- Details Table -->
            <div class="w-full text-[10px] space-y-3 mt-8 font-bold tracking-wide text-slate-600 z-10 px-2">
                <div class="flex justify-between items-end border-b border-slate-200 pb-1.5">
                    <span class="text-slate-400 text-[9px] uppercase tracking-widest">NAMA</span>
                    <span class="text-right text-slate-800 uppercase">{{ Str::limit($ticket->participant_name ?? $ticket->booking->customer_name, 18) }}</span>
                </div>
                <div class="flex justify-between items-end border-b border-slate-200 pb-1.5">
                    <span class="text-slate-400 text-[9px] uppercase tracking-widest">JUMLAH</span>
                    <span class="text-right text-slate-800">{{ $ticket->participant_count ?? 1 }} ORANG</span>
                </div>
                <div class="flex justify-between items-end border-b border-slate-200 pb-1.5">
                    <span class="text-slate-400 text-[9px] uppercase tracking-widest">KATEGORI</span>
                    <span class="text-right text-slate-800 uppercase">{{ $ticket->category }}</span>
                </div>
                <div class="flex justify-between items-end border-b border-slate-200 pb-1.5">
                    <span class="text-slate-400 text-[9px] uppercase tracking-widest">TANGGAL</span>
                    <span class="text-right text-slate-800">{{ \Carbon\Carbon::parse($ticket->booking->booking_date)->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <div class="mt-7 text-center w-full z-10">
                <p class="text-[9px] font-bold text-slate-400 tracking-[0.25em] uppercase">TERIMA KASIH TELAH MENJAGA ALAM</p>
            </div>
        </div>
        
    </div>
    @endforeach

</body>
</html>
