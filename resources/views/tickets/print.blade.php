<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $ticket->ticket_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0;
                padding: 0;
                background-color: white !important;
                display: block !important;
            }
            .ticket-container {
                page-break-inside: avoid;
                box-shadow: none !important;
                border: none !important;
                margin: 20mm auto !important;
                transform: scale(0.95);
                transform-origin: top center;
            }
            .no-print {
                display: none !important;
            }
            /* Menghilangkan background abu-abu saat diprint */
            .cutout-top, .cutout-bottom {
                background-color: white !important;
            }
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f1f5f9;
        }
        .text-gold {
            color: #d4af37;
        }
        .border-gold {
            border-color: #d4af37;
        }
        .bg-gold {
            background-color: #d4af37;
        }
        
        .ticket-bg {
            background-image: url('{{ asset('images/mountain_ticket_bg.png') }}');
            background-size: cover;
            background-position: right center;
        }

        .dark-overlay {
            /* Create the angled dark green overlay */
            background: linear-gradient(105deg, rgba(11, 31, 26, 1) 0%, rgba(11, 31, 26, 1) 48%, rgba(11, 31, 26, 0.9) 58%, rgba(11, 31, 26, 0) 80%);
        }
        
        /* Font for cursive text */
        .font-cursive {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen py-10">

    <div class="no-print mb-8 space-x-4">
        <button onclick="window.print()" class="px-6 py-2 bg-emerald-700 text-white font-bold rounded-lg shadow-lg hover:bg-emerald-800 transition">
            🖨️ Cetak ke PDF
        </button>
        <button onclick="window.close()" class="px-6 py-2 bg-slate-200 text-slate-800 font-bold rounded-lg shadow hover:bg-slate-300 transition">
            Tutup
        </button>
    </div>

    <!-- TICKET -->
    <div class="ticket-container relative w-[1000px] h-[450px] flex shadow-2xl rounded-2xl overflow-hidden bg-white">
        
        <!-- LEFT SIDE: Image and Info -->
        <div class="w-[70%] h-full ticket-bg relative text-white">
            <div class="absolute inset-0 dark-overlay"></div>
            
            <div class="relative z-10 h-full p-10 flex flex-col justify-between">
                <!-- Top: Logo -->
                <div class="flex flex-col items-start">
                    <!-- Fake mountain logo vector using SVG -->
                    <svg class="w-16 h-12 text-gold mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path>
                    </svg>
                    <h1 class="text-3xl font-bold tracking-widest leading-none">GUNUNG</h1>
                    <h1 class="text-[2.5rem] font-extrabold tracking-widest text-white mt-1 leading-none">PANCAR</h1>
                    <p class="text-gold text-[10px] tracking-[0.3em] mt-2">NATURE &bull; ADVENTURE &bull; HARMONY</p>
                </div>

                <!-- Middle: Ticket Title -->
                <div class="mt-6">
                    <h2 class="text-gold text-3xl font-bold tracking-wider">TIKET MASUK</h2>
                    <p class="text-sm tracking-widest text-slate-300 mt-1">ADMISSION TICKET</p>
                    <div class="w-12 h-[3px] bg-gold mt-4"></div>
                </div>

                <!-- Middle-Bottom: Info grid -->
                <div class="grid grid-cols-4 gap-4 mt-8">
                    <div>
                        <div class="w-11 h-11 rounded-full border border-gold flex items-center justify-center text-gold mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[10px] tracking-widest text-slate-300">TANGGAL</p>
                        <p class="text-xs font-bold mt-1 uppercase">{{ \Carbon\Carbon::parse($ticket->booking->booking_date)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div>
                        <div class="w-11 h-11 rounded-full border border-gold flex items-center justify-center text-gold mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[10px] tracking-widest text-slate-300">JAM MASUK</p>
                        <p class="text-xs font-bold mt-1 uppercase">08:00 - SELESAI</p>
                    </div>
                    <div>
                        <div class="w-11 h-11 rounded-full border border-gold flex items-center justify-center text-gold mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <p class="text-[10px] tracking-widest text-slate-300">KATEGORI</p>
                        <p class="text-xs font-bold mt-1 uppercase">{{ $ticket->category }}</p>
                    </div>
                    <div>
                        <div class="w-11 h-11 rounded-full border border-gold flex items-center justify-center text-gold mb-3">
                            <span class="font-bold text-sm">Rp</span>
                        </div>
                        <p class="text-[10px] tracking-widest text-slate-300">HARGA</p>
                        <p class="text-xs font-bold mt-1">Rp {{ number_format($ticket->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Bottom: Footer text -->
                <div class="mt-8 flex justify-between items-end">
                    <div class="font-cursive text-gold text-4xl leading-tight opacity-90">
                        The Mountain <br> Is Calling
                    </div>
                    <div class="text-[10px] text-slate-400 text-right flex gap-3 items-center">
                        <span>IG: @gunungpancar</span>
                        <span>www.gunungpancar.id</span>
                    </div>
                </div>
            </div>
            
            <!-- Side text (rotated) -->
            <div class="absolute left-3 top-1/2 -translate-y-1/2 -rotate-90 text-[8px] tracking-[0.4em] text-gold whitespace-nowrap opacity-60">
                JELAJAH • RASAKAN • JAGA ALAM
            </div>
            <!-- Side golden bar -->
            <div class="absolute left-0 top-12 bottom-12 w-8 bg-[#bd952a] rounded-r-xl mix-blend-overlay opacity-30"></div>
        </div>

        <!-- Perforated Line Divider -->
        <div class="w-[0px] h-full border-r-[2px] border-dashed border-[#d1d5db] relative z-20 bg-[#f8f5ee]">
            <div class="cutout-top absolute -top-4 -left-4 w-8 h-8 bg-[#f1f5f9] rounded-full shadow-inner"></div>
            <div class="cutout-bottom absolute -bottom-4 -left-4 w-8 h-8 bg-[#f1f5f9] rounded-full shadow-inner"></div>
        </div>

        <!-- RIGHT SIDE: Stub -->
        <div class="w-[30%] h-full bg-[#f8f5ee] px-8 py-10 flex flex-col items-center justify-between text-[#2c3e35]">
            <!-- Stub Header -->
            <div class="text-center">
                <svg class="w-12 h-10 mx-auto text-[#1a2f26] mb-1 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path>
                </svg>
                <h3 class="text-lg font-bold tracking-widest text-[#1a2f26] leading-none mt-1">GUNUNG PANCAR</h3>
                <p class="text-[#bd952a] text-[10px] font-bold tracking-widest mt-2">TIKET MASUK</p>
            </div>

            <!-- QR Code Box -->
            <div class="flex flex-col items-center w-full mt-4">
                <div class="bg-[#2c3e35] text-white text-[11px] py-1.5 px-4 rounded-full mb-4 tracking-widest w-[80%] text-center shadow-md">
                    {{ $ticket->ticket_number }}
                </div>
                <!-- Generate QR Code using SimpleSoftwareIO or Fallback to Google -->
                <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-200">
                    <img src="{{ asset('storage/' . $ticket->qr_code_path) }}" alt="QR Code" class="w-32 h-32" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/api/tickets/validate/' . $ticket->ticket_number)) }}'">
                </div>
            </div>

            <!-- Stub Details -->
            <div class="w-full text-[10px] space-y-2.5 mt-6 font-semibold tracking-wide text-[#1a2f26]">
                <div class="flex justify-between border-b border-[#e5e0d8] pb-1">
                    <span class="text-slate-500">NAMA</span>
                    <span class="text-right uppercase">{{ Str::limit($ticket->participant_name ?? $ticket->booking->customer_name, 15) }}</span>
                </div>
                <div class="flex justify-between border-b border-[#e5e0d8] pb-1">
                    <span class="text-slate-500">JUMLAH</span>
                    <span class="text-right">{{ $ticket->participant_count ?? 1 }} ORANG</span>
                </div>
                <div class="flex justify-between border-b border-[#e5e0d8] pb-1">
                    <span class="text-slate-500">KATEGORI</span>
                    <span class="text-right uppercase">{{ $ticket->category }}</span>
                </div>
                <div class="flex justify-between border-b border-[#e5e0d8] pb-1">
                    <span class="text-slate-500">TANGGAL</span>
                    <span class="text-right">{{ \Carbon\Carbon::parse($ticket->booking->booking_date)->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <!-- Footer Badge -->
            <div class="mt-6 text-center w-full">
                <div class="bg-[#1a2f26] text-white text-[9px] py-2 px-4 rounded-full inline-flex items-center gap-1.5 tracking-widest shadow-md">
                    <svg class="w-3.5 h-3.5 text-[#bd952a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    VALID FOR ONE ENTRY ONLY
                </div>
                <p class="text-[8px] text-slate-500 mt-3 tracking-widest">TERIMA KASIH TELAH MENJAGA ALAM</p>
            </div>
        </div>
        
    </div>

</body>
</html>
