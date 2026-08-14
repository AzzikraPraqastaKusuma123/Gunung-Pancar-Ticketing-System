<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Booking Tiket - Taman Wisata Alam Gunung Pancar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            bg: '#F5F3EB',
                            card: '#FFFFFF',
                            text: '#3A4933',
                            green: '#455A42',
                            greenHover: '#324330',
                            badgeBg: '#E5E9DF',
                            badgeText: '#455A42',
                            border: '#E8E4D9',
                            muted: '#7A8075'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F5F3EB; color: #3A4933; }
        [x-cloak] { display: none !important; }
        
        /* Floating Labels */
        .input-floating { position: relative; }
        .input-floating input, .input-floating input[type="date"] {
            padding: 1.5rem 1rem 0.5rem;
            border: 1px solid #E8E4D9;
            background-color: #FFFFFF;
        }
        .input-floating label {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #7A8075;
            transition: all 0.2s ease-out;
            pointer-events: none;
        }
        .input-floating input:focus + label,
        .input-floating input:not(:placeholder-shown) + label,
        .input-floating input[type="date"] + label {
            top: 0.5rem;
            transform: translateY(0);
            font-size: 0.75rem;
            color: #455A42;
            font-weight: 700;
        }
        
        .pb-mobile-sticky { padding-bottom: 140px; }
        @media (min-width: 768px) {
            .pb-mobile-sticky { padding-bottom: 3rem; }
        }

        .custom-card {
            background-color: #FFFFFF;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
            border: 1px solid rgba(232, 228, 217, 0.8);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            isolation: isolate;
            -webkit-mask-image: -webkit-radial-gradient(white, black);
        }
        .custom-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 50px -12px rgba(69, 90, 66, 0.25);
            border-color: rgba(69, 90, 66, 0.4);
        }

        /* GoFood style qty button */
        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.2s;
        }
        .qty-btn-minus {
            background-color: #F5F3EB;
            color: #455A42;
            border: 1px solid #E8E4D9;
        }
        .qty-btn-plus {
            background-color: #455A42;
            color: #FFFFFF;
        }
        .qty-btn-minus:hover { background-color: #E5E9DF; }
        .qty-btn-plus:hover { background-color: #324330; }
    </style>
</head>
<body class="antialiased pb-mobile-sticky">
    
    <!-- Navbar -->
    <nav class="w-full bg-brand-bg/80 py-4 md:py-6 border-b border-brand-border/50 relative z-20">
        <div class="max-w-5xl mx-auto px-4 md:px-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-brand-green hover:text-brand-greenHover transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-bold text-sm tracking-widest uppercase">Kembali</span>
            </a>
            <div class="flex items-center">
                <img src="{{ asset('images/logo-gp.png') }}" alt="Gunung Pancar" class="h-10 md:h-12 object-contain">
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 md:px-6 mt-8 relative z-20">
        <div class="mb-10">
            <h1 class="text-3xl md:text-5xl font-extrabold mb-3 tracking-tight">Pilih Paket Wisata</h1>
            <p class="text-brand-muted text-lg md:text-xl font-medium">Jelajahi keindahan alam Gunung Pancar dengan berbagai pilihan paket yang tersedia.</p>
        </div>

        @if(session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <div class="font-semibold">
                    Terjadi kesalahan:
                    <ul class="list-disc pl-5 mt-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('booking.store') }}" method="POST" x-data="bookingForm()" class="space-y-10">
            @csrf
            <input type="hidden" name="qty_dewasa" x-model="qty_dewasa">
            <input type="hidden" name="qty_anak" x-model="qty_anak">
            <input type="hidden" name="qty_group" x-model="qty_group">
            <input type="hidden" name="qty_pancar_trek" x-model="qty_pancar_trek">
            <input type="hidden" name="qty_pancar_school" x-model="qty_pancar_school">
            <input type="hidden" name="qty_prewedding" x-model="qty_prewedding">
            <input type="hidden" name="qty_foto_produk" x-model="qty_foto_produk">
            <input type="hidden" name="qty_shooting" x-model="qty_shooting">
            
            <!-- Data Pemesan (Penanggung Jawab) -->
            <div class="custom-card p-6 md:p-8">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold flex items-center gap-3">
                        <span class="bg-brand-badgeBg text-brand-badgeText rounded-full w-8 h-8 flex items-center justify-center text-sm">1</span>
                        Data Penanggung Jawab
                    </h3>
                    <p class="text-brand-muted mt-1 ml-11 text-sm font-medium">Informasi ini akan digunakan untuk pengiriman e-ticket.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 ml-0 md:ml-11">
                    <div class="input-floating">
                        <input type="text" name="customer_name" id="customer_name" required placeholder=" "
                            class="block w-full rounded-xl focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none transition duration-200 font-semibold text-brand-text">
                        <label for="customer_name">Nama Lengkap Sesuai KTP</label>
                    </div>
                    
                    <div class="input-floating">
                        <input type="email" name="customer_email" id="customer_email" required placeholder=" "
                            class="block w-full rounded-xl focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none transition duration-200 font-semibold text-brand-text">
                        <label for="customer_email">Alamat Email Aktif</label>
                    </div>
                    
                    <div class="input-floating">
                        <input type="tel" name="customer_phone" id="customer_phone" required placeholder=" "
                            class="block w-full rounded-xl focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none transition duration-200 font-semibold text-brand-text">
                        <label for="customer_phone">Nomor WhatsApp Aktif</label>
                    </div>
                    
                    <div class="input-floating">
                        <input type="date" name="visit_date" id="visit_date" required min="{{ date('Y-m-d') }}" placeholder=" "
                            class="block w-full rounded-xl focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none transition duration-200 font-semibold text-brand-text">
                        <label for="visit_date">Tanggal Kunjungan</label>
                    </div>
                </div>
            </div>

            <!-- Tiket Reguler -->
            <div>
                <h3 class="text-2xl font-bold flex items-center gap-3 mb-6">
                    <span class="bg-brand-badgeBg text-brand-badgeText rounded-full w-8 h-8 flex items-center justify-center text-sm">2</span>
                    Pilihan Paket
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 ml-0 md:ml-11">

                    <!-- Template Component Paket (Alpine x-data is isolated inside form, we use x-data properties) -->
                    @php
                        $packages = [
                            [
                                'model' => 'qty_group',
                                'badge' => 'LEBIH HEMAT',
                                'title' => 'Paket Group',
                                'subtitle' => 'Berlibur bersama rombongan lebih seru dan hemat.',
                                'price' => 200000,
                                'price_label' => '/ 5 orang',
                                'features' => ['Tiket untuk 5 Orang', 'Akses Area Pinus', 'Lebih Praktis'],
                                'input_name' => 'participants_group',
                                'input_placeholder' => 'Nama Ketua Grup',
                                'input_helper' => '1 tiket berlaku untuk 5 orang.',
                                'unit_label' => 'grup',
                                'image' => 'https://gunungpancar.co.id/wp-content/uploads/2026/07/32.jpg'
                            ],
                            [
                                'model' => 'qty_pancar_trek',
                                'badge' => 'SETENGAH HARI',
                                'title' => 'Pancar Trek',
                                'subtitle' => 'Jelajahi Alam dengan Persiapan Lengkap',
                                'desc' => 'Nikmati perjalanan setengah hari dengan fasilitas lengkap yang mendukung eksplorasi alam Gunung Pancar dari awal hingga akhir!',
                                'price' => 165000,
                                'price_label' => '/ pax',
                                'features' => ['Tiket Masuk', 'Guide', 'Rute Pilihan', 'Jemputan Mobil Pickup', 'Air Mineral per Peserta', 'Tiket Masuk Curug'],
                                'input_name' => 'participants_pancar_trek',
                                'input_placeholder' => 'Nama Peserta / Ketua Rombongan',
                                'box_msg' => 'Satu harga, semua fasilitas sudah termasuk.',
                                'unit_label' => 'pax',
                                'image' => 'https://gunungpancar.co.id/wp-content/uploads/2026/06/2-7.jpg'
                            ],
                            [
                                'model' => 'qty_pancar_school',
                                'badge' => 'BAWA TENDA SENDIRI',
                                'title' => 'Pancar School',
                                'subtitle' => 'Paket kegiatan sekolah di alam terbuka',
                                'desc' => 'Kegiatan jadi lebih tertata dengan fasilitas utama yang siap mendukung aktivitas dari awal hingga akhir.',
                                'price' => 125000,
                                'price_label' => '/ pax',
                                'features' => ['Tiket Masuk', 'Toilet', 'Penerangan Area', 'Parkir & Security 24 Jam', 'Rangers', 'Area Camping'],
                                'input_name' => 'participants_pancar_school',
                                'input_placeholder' => 'Nama Sekolah / Koordinator',
                                'box_msg' => 'Satu harga, semua fasilitas sudah termasuk.',
                                'unit_label' => 'pax',
                                'image' => 'https://gunungpancar.co.id/wp-content/uploads/2026/07/33.jpg'
                            ],
                            [
                                'model' => 'qty_prewedding',
                                'badge' => 'WEDDING PHOTOGRAPHY',
                                'title' => 'Prewedding / Wedding Photo',
                                'subtitle' => 'Prewedding & Wedding',
                                'desc' => 'Hadirkan nuansa romantis di setiap frame dengan backdrop hutan pinus yang alami dan elegan.',
                                'price' => 750000,
                                'price_label' => '/ 8 jam',
                                'features_text' => 'Sesi setengah hari (8 jam) dengan spot foto pilihan. Overtime Rp150.000 / jam.',
                                'input_name' => 'participants_prewedding',
                                'input_placeholder' => 'Nama Pasangan',
                                'box_msg' => 'Abadikan momen spesial dengan latar pinus yang natural dan elegan.',
                                'unit_label' => 'sesi',
                                'image' => 'https://gunungpancar.co.id/wp-content/uploads/2026/06/3-4.jpg'
                            ],
                            [
                                'model' => 'qty_foto_produk',
                                'badge' => 'PRODUCT PHOTOGRAPHY',
                                'title' => 'Foto Produk',
                                'subtitle' => 'E-commerce, Katalog',
                                'desc' => 'Foto produk dengan sentuhan alam yang estetik untuk hasil visual yang lebih menarik, profesional, dan siap digunakan di berbagai media promosi.',
                                'price' => 7500000,
                                'price_label' => '/ 8 jam',
                                'features_text' => 'Satu hari produksi (8 jam). Overtime Rp500.000 / jam.',
                                'input_name' => 'participants_foto_produk',
                                'input_placeholder' => 'Nama Brand / Perwakilan',
                                'box_msg' => 'Siapkan kebutuhan produksi visualmu di lokasi yang lebih berkarakter.',
                                'unit_label' => 'sesi',
                                'image' => 'https://gunungpancar.co.id/wp-content/uploads/2026/06/4-2.jpg'
                            ],
                            [
                                'model' => 'qty_shooting',
                                'badge' => 'COMMERCIAL PRODUCTION',
                                'title' => 'Shooting Komersial',
                                'subtitle' => 'Film, Iklan, MV',
                                'desc' => 'Lokasi outdoor sinematik dengan karakter hutan pinus yang kuat, cocok untuk produksi visual dengan berbagai mood dan konsep.',
                                'price' => 20000000,
                                'price_label' => '/ hari',
                                'features_text' => 'Durasi 8 jam. Belum termasuk peak season & overtime Rp2.500.000 / jam.',
                                'input_name' => 'participants_shooting',
                                'input_placeholder' => 'Nama Production House',
                                'box_msg' => 'Wujudkan konsep visualmu dengan nuansa hutan pinus Gunung Pancar.',
                                'unit_label' => 'sesi',
                                'image' => 'https://gunungpancar.co.id/wp-content/uploads/2026/07/38.jpg'
                            ]
                        ];
                    @endphp

                    @foreach($packages as $pkg)
                        <div class="custom-card flex flex-col h-full cursor-pointer" :class="{'ring-2 ring-brand-green bg-brand-green/5': {{ $pkg['model'] }} > 0}" @click="if({{ $pkg['model'] }} === 0) {{ $pkg['model'] }} = 1">
                            
                            <!-- Image Top -->
                            <div class="w-full aspect-[16/10] relative overflow-hidden bg-gray-100 flex-shrink-0 border-b border-brand-border/60">
                                <img src="{{ $pkg['image'] }}" alt="{{ $pkg['title'] }}" class="w-full h-full object-cover transform hover:scale-105 transition duration-700">
                                <div class="absolute top-4 left-4 inline-flex items-center px-3 py-1.5 rounded-full bg-white/95 backdrop-blur-md text-brand-badgeText text-[10px] font-extrabold tracking-widest uppercase shadow-sm border border-white/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-green mr-2 shadow-sm shadow-brand-green/50"></span>
                                    {{ $pkg['badge'] }}
                                </div>
                            </div>
                            
                            <!-- Text Info -->
                            <div class="p-4 md:p-6 flex-1 flex flex-col">
                                <h4 class="text-xl md:text-2xl font-extrabold mb-1 tracking-tight text-brand-text">{{ $pkg['title'] }}</h4>
                                <p class="text-brand-text font-bold text-sm md:text-base mb-3 opacity-90">{{ $pkg['subtitle'] }}</p>
                                
                                @if(isset($pkg['desc']))
                                    <p class="text-brand-muted text-xs md:text-sm leading-relaxed mb-5 flex-1">{{ $pkg['desc'] }}</p>
                                @endif

                                @if(isset($pkg['features']))
                                    <div class="flex flex-wrap gap-1.5 mt-auto mb-2">
                                        @foreach($pkg['features'] as $feature)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-brand-green/5 text-[10.5px] font-bold text-brand-green border border-brand-green/10">
                                                <svg class="w-3 h-3 text-brand-green/80 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if(isset($pkg['features_text']))
                                    <div class="flex items-start gap-2 mt-4 text-xs font-bold text-brand-green bg-brand-green/5 p-3 rounded-xl border border-brand-green/10 mt-auto">
                                        <svg class="w-4 h-4 flex-shrink-0 text-brand-green/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $pkg['features_text'] }}
                                    </div>
                                @endif
                            </div>

                            <!-- Bottom: Price & Action -->
                            <div class="w-full flex flex-col bg-gray-50/50 p-4 md:p-6 border-t border-brand-border/60">
                                <div>
                                    <div class="flex items-baseline justify-between mb-2">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-sm font-bold">Rp</span>
                                            <span class="text-2xl font-extrabold tracking-tighter">{{ number_format($pkg['price'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-xs font-medium text-brand-muted">{{ $pkg['price_label'] }}</div>
                                    </div>
                                    
                                    @if(isset($pkg['box_msg']))
                                        <p class="text-xs font-semibold text-brand-muted mb-4 pb-4 border-b border-brand-border border-dashed">{{ $pkg['box_msg'] }}</p>
                                    @endif
                                </div>

                                <div>
                                    <!-- Add button when qty == 0 -->
                                    <button type="button" x-show="{{ $pkg['model'] }} === 0" @click="{{ $pkg['model'] }} = 1"
                                        class="w-full bg-brand-green hover:bg-brand-greenHover text-white font-bold py-3 px-4 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Pesan Sekarang
                                    </button>

                                    <!-- Qty Control when qty > 0 -->
                                    <div x-show="{{ $pkg['model'] }} > 0" x-cloak class="flex items-center justify-between bg-white border-2 border-brand-green rounded-xl p-1.5 shadow-sm">
                                        <button type="button" @click="if({{ $pkg['model'] }} > 0) {{ $pkg['model'] }}--" class="qty-btn qty-btn-minus w-8 h-8">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                        </button>
                                        <div class="font-extrabold text-base text-brand-text px-4"><span x-text="{{ $pkg['model'] }}"></span></div>
                                        <button type="button" @click="{{ $pkg['model'] }}++" class="qty-btn qty-btn-plus w-8 h-8">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                    <div x-show="{{ $pkg['model'] }} > 0" x-cloak class="text-center text-[10px] font-bold text-brand-green mt-2 uppercase tracking-wide">
                                        <span x-text="{{ $pkg['model'] }}"></span> {{ $pkg['unit_label'] }} terpilih
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            
            <div class="h-10"></div> <!-- Spacer for scrolling -->

            <!-- Bottom Checkout Bar (Fixed at bottom) -->
            <div class="fixed bottom-0 left-0 w-full bg-white/95 backdrop-blur-lg border-t border-brand-border/80 px-5 py-4 md:px-8 md:py-6 z-50 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] transition-all duration-300" x-show="getTotalTickets() > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="transform translate-y-full opacity-0" x-transition:enter-end="transform translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform translate-y-0 opacity-100" x-transition:leave-end="transform translate-y-full opacity-0">
                <div class="max-w-5xl mx-auto flex flex-row items-center justify-between">
                    <div>
                        <div class="text-brand-muted text-[10px] md:text-xs font-bold tracking-widest uppercase mb-0.5">
                            Total <span x-text="getTotalTickets()" class="text-brand-green mx-1"></span> Pilihan
                        </div>
                        <div class="text-xl md:text-3xl font-extrabold text-brand-text tracking-tight" x-text="formatRupiah(calculateTotal())">Rp 0</div>
                    </div>
                    <button type="submit" 
                        class="bg-brand-green hover:bg-brand-greenHover text-white font-extrabold py-3 px-6 md:py-3.5 md:px-12 rounded-xl md:rounded-2xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2">
                        <span>Lanjut Pembayaran</span>
                        <svg class="w-5 h-5 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bookingForm', () => ({
                qty_dewasa: 0,
                qty_anak: 0,
                qty_group: 0,
                qty_pancar_trek: 0,
                qty_pancar_school: 0,
                qty_prewedding: 0,
                qty_foto_produk: 0,
                qty_shooting: 0,
                
                calculateTotal() {
                    const hargaGroup = 200000;
                    const hargaPancarTrek = 165000;
                    const hargaPancarSchool = 125000;
                    const hargaPrewedding = 750000;
                    const hargaFotoProduk = 7500000;
                    const hargaShooting = 20000000;
                    
                    return (this.qty_group * hargaGroup) +
                           (this.qty_pancar_trek * hargaPancarTrek) +
                           (this.qty_pancar_school * hargaPancarSchool) +
                           (this.qty_prewedding * hargaPrewedding) +
                           (this.qty_foto_produk * hargaFotoProduk) +
                           (this.qty_shooting * hargaShooting);
                },

                getTotalTickets() {
                    return parseInt(this.qty_group || 0) +
                           parseInt(this.qty_pancar_trek || 0) + parseInt(this.qty_pancar_school || 0) +
                           parseInt(this.qty_prewedding || 0) + parseInt(this.qty_foto_produk || 0) + parseInt(this.qty_shooting || 0);
                },
                
                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(angka);
                }
            }))
        })
    </script>
</body>
</html>
