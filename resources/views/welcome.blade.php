<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taman Wisata Alam Gunung Pancar</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: { 50: '#f0fdf4', 200: '#bbf7d0', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 950: '#052e16' },
                        night: { base: '#020804', 700: '#1a4228', 800: '#112b18', 900: '#0a1f12', 950: '#050f08' },
                        gold: { 100: '#fef3c7', 400: '#fbbf24', 500: '#f59e0b', 700: '#b45309', 900: '#78350f' },
                        earth: { 50: '#faf9f8', 100: '#f5f5f4', 300: '#d6d3d1', 500: '#78716c', 700: '#44403c', 950: '#0c0a09' }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        cursive: ['Playfair Display', 'serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>

    <style>
        .hero-pattern {
            background-image: url('https://images.unsplash.com/photo-1517600813898-103328e85449?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .hero-overlay {
            background: linear-gradient(to bottom, rgba(5, 46, 22, 0.6) 0%, rgba(2, 8, 4, 0.95) 100%);
        }
        .glass-card {
            background: rgba(250, 249, 248, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(214, 211, 209, 0.5);
        }
        .text-glow {
            text-shadow: 0 0 30px rgba(34, 197, 94, 0.4);
        }
        .premium-shadow {
            box-shadow: 0 20px 40px -15px rgba(5, 46, 22, 0.3);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-earth-50 text-earth-950 antialiased selection:bg-forest-500 selection:text-white" 
      x-data="{ mobileMenuOpen: false, scrolled: false }" 
      @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navbar -->
    <nav :class="{'bg-night-base/90 backdrop-blur-md shadow-lg border-b border-forest-900/50': scrolled, 'bg-transparent': !scrolled}" class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-forest-700 to-forest-500 flex items-center justify-center shadow-[0_0_15px_rgba(34,197,94,0.3)]">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-white tracking-widest">GUNUNG PANCAR</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#tentang" class="text-earth-300 hover:text-white transition font-medium text-sm tracking-wide">Tentang</a>
                    <a href="#harga" class="text-earth-300 hover:text-white transition font-medium text-sm tracking-wide">Harga Tiket</a>
                    <a href="#fasilitas" class="text-earth-300 hover:text-white transition font-medium text-sm tracking-wide">Fasilitas</a>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-forest-700 to-forest-500 text-white font-bold hover:from-forest-600 hover:to-forest-400 transition transform hover:-translate-y-0.5 shadow-[0_4px_15px_rgba(34,197,94,0.3)] text-sm">Login Petugas</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-forest-400 focus:outline-none">
                        <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg class="h-6 w-6" x-show="mobileMenuOpen" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-night-base border-t border-forest-900" x-transition>
            <div class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
                <a href="#tentang" class="block px-3 py-2 text-base font-medium text-earth-300 hover:text-white hover:bg-night-900 rounded-md">Tentang</a>
                <a href="#harga" class="block px-3 py-2 text-base font-medium text-earth-300 hover:text-white hover:bg-night-900 rounded-md">Harga Tiket</a>
                <a href="#fasilitas" class="block px-3 py-2 text-base font-medium text-earth-300 hover:text-white hover:bg-night-900 rounded-md">Fasilitas</a>
                <div class="mt-4 pt-4 border-t border-night-800">
                    <a href="{{ route('login') }}" class="block w-full text-center px-5 py-3 rounded-xl bg-forest-600 text-white font-bold">Login Petugas</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 hero-pattern transform scale-105 animate-pulse-slow"></div>
        <div class="absolute inset-0 hero-overlay"></div>
        
        <!-- Animated Particles/Leaves effect (pure CSS) -->
        <div class="absolute inset-0 pointer-events-none opacity-20" style="background-image: radial-gradient(#22c55e 1px, transparent 1px); background-size: 50px 50px;"></div>
        
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto mt-16" 
             x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
            
            <div x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-forest-900/50 border border-forest-500/30 text-forest-400 font-semibold text-xs tracking-widest uppercase mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-forest-500 animate-ping"></span>
                    Destinasi Ekowisata Premium
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold text-white mb-6 leading-tight text-glow">
                    Kembali ke <span class="font-cursive italic font-medium text-forest-400">Alam</span>
                </h1>
                
                <p class="text-lg md:text-xl text-earth-300 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                    Temukan ketenangan di tengah rimbunnya hutan pinus. Pelarian sempurna untuk relaksasi, camping, dan petualangan.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                    <a href="{{ route('booking') }}" class="group relative px-8 py-4 bg-forest-600 text-white font-bold text-lg rounded-full overflow-hidden shadow-[0_0_30px_rgba(22,163,74,0.4)] transition-all hover:scale-105 hover:shadow-[0_0_50px_rgba(22,163,74,0.6)]">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-forest-700 to-forest-500 group-hover:scale-110 transition-transform duration-500"></div>
                        <span class="relative flex items-center gap-2">
                            Pesan Tiket Online
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </a>
                    <a href="#harga" class="px-8 py-4 bg-white/5 backdrop-blur-md text-white font-bold text-lg rounded-full border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all">
                        Lihat Paket & Harga
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#stats" class="text-white/50 hover:text-white transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </a>
        </div>
    </div>

    <!-- Animated Stats Section -->
    <div id="stats" class="bg-night-base border-t border-forest-900/50 py-16 relative z-20 -mt-1" 
         x-data="{ visible: false }" 
         x-intersect.half="visible = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-forest-900/50">
                <div class="py-4 md:py-0 transform transition duration-700" :class="visible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <div class="text-4xl md:text-5xl font-extrabold text-forest-400 mb-2 font-cursive italic">
                        <span x-data="{ count: 0 }" x-init="$watch('visible', value => { if(value) { let i = 0; let int = setInterval(() => { count = i++; if(i > 25) clearInterval(int); }, 50); } })" x-text="count">0</span>+
                    </div>
                    <div class="text-earth-300 text-sm font-medium tracking-widest uppercase">Spot Camping</div>
                </div>
                <div class="py-4 md:py-0 transform transition duration-700 delay-100" :class="visible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <div class="text-4xl md:text-5xl font-extrabold text-forest-400 mb-2 font-cursive italic">
                        <span x-data="{ count: 0 }" x-init="$watch('visible', value => { if(value) { let i = 0; let int = setInterval(() => { count += 5; if(count >= 150) { count = 150; clearInterval(int); } }, 40); } })" x-text="count">0</span>k+
                    </div>
                    <div class="text-earth-300 text-sm font-medium tracking-widest uppercase">Pengunjung</div>
                </div>
                <div class="py-4 md:py-0 transform transition duration-700 delay-200" :class="visible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <div class="text-4xl md:text-5xl font-extrabold text-forest-400 mb-2 font-cursive italic">
                        <span x-data="{ count: 0 }" x-init="$watch('visible', value => { if(value) { let i = 0; let int = setInterval(() => { count = i++; if(i > 15) clearInterval(int); }, 80); } })" x-text="count">0</span>+
                    </div>
                    <div class="text-earth-300 text-sm font-medium tracking-widest uppercase">Fasilitas</div>
                </div>
                <div class="py-4 md:py-0 transform transition duration-700 delay-300" :class="visible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                    <div class="text-4xl md:text-5xl font-extrabold text-forest-400 mb-2 font-cursive italic">24/7</div>
                    <div class="text-earth-300 text-sm font-medium tracking-widest uppercase">Keamanan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing / Packages Section -->
    <section id="harga" class="py-24 bg-earth-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-100 text-gold-700 font-bold text-xs tracking-widest uppercase mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    Pilihan Paket
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-earth-950 mb-6">Paket Wisata & Camping</h2>
                <p class="text-lg text-earth-500">Pilih paket sesuai gaya petualangan Anda. Tersedia dari tiket reguler hingga pengalaman glamping eksklusif.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                
                <!-- Package 1 -->
                <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300 premium-shadow">
                    <h3 class="text-xl font-bold text-earth-950 mb-2">Tiket Masuk</h3>
                    <p class="text-earth-500 text-sm mb-6">Cocok untuk kunjungan singkat, piknik, dan foto-foto.</p>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-forest-700">Rp 15k</span>
                        <span class="text-earth-500 font-medium">/orang</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-earth-700 text-sm">
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Akses area hutan pinus</li>
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Fasilitas umum & toilet</li>
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Akses trekking ringan</li>
                    </ul>
                    <a href="{{ route('booking') }}" class="block w-full py-3 px-4 bg-earth-100 hover:bg-earth-300 text-earth-950 font-bold text-center rounded-xl transition">Pilih Paket</a>
                </div>

                <!-- Package 2 (Popular) -->
                <div class="bg-night-base rounded-3xl p-8 transform md:-translate-y-4 shadow-2xl relative overflow-hidden border border-forest-900">
                    <div class="absolute top-0 right-0 bg-gold-500 text-white text-xs font-bold px-4 py-1 rounded-bl-lg uppercase tracking-wider">Terpopuler</div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-forest-600 rounded-full blur-[50px] opacity-20"></div>
                    
                    <h3 class="text-xl font-bold text-white mb-2 relative z-10">Camping Reguler</h3>
                    <p class="text-earth-300 text-sm mb-6 relative z-10">Bawa tenda sendiri atau sewa, nikmati malam di bawah bintang.</p>
                    <div class="mb-6 relative z-10">
                        <span class="text-4xl font-extrabold text-gold-400">Rp 85k</span>
                        <span class="text-earth-300 font-medium">/malam</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-earth-100 text-sm relative z-10">
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Kavling area camping</li>
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Akses listrik (titik tertentu)</li>
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Keamanan 24 jam</li>
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Toilet & Mushola</li>
                    </ul>
                    <a href="{{ route('booking') }}" class="block w-full py-3 px-4 bg-gradient-to-r from-forest-600 to-forest-500 hover:from-forest-500 hover:to-forest-400 text-white font-bold text-center rounded-xl transition shadow-[0_4px_15px_rgba(34,197,94,0.3)] relative z-10">Booking Area</a>
                </div>

                <!-- Package 3 -->
                <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300 premium-shadow">
                    <h3 class="text-xl font-bold text-earth-950 mb-2">Glamping VIP</h3>
                    <p class="text-earth-500 text-sm mb-6">Tinggal bawa badan. Pengalaman kemah mewah dengan kasur empuk.</p>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-forest-700">Rp 450k</span>
                        <span class="text-earth-500 font-medium">/tenda</span>
                    </div>
                    <ul class="space-y-4 mb-8 text-earth-700 text-sm">
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tenda Safari / Dome besar</li>
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Kasur, bantal, selimut</li>
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Sarapan pagi (2 orang)</li>
                        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Set alat panggang BBQ</li>
                    </ul>
                    <a href="{{ route('booking') }}" class="block w-full py-3 px-4 bg-earth-100 hover:bg-earth-300 text-earth-950 font-bold text-center rounded-xl transition">Lihat Detail</a>
                </div>

            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fasilitas" class="py-24 bg-white border-y border-earth-100 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-forest-600 font-bold tracking-widest text-sm uppercase mb-3">Pengalaman Tak Terlupakan</h3>
                <h2 class="text-3xl md:text-4xl font-extrabold text-earth-950 mb-6">Kenapa Memilih Gunung Pancar?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="group rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 bg-earth-50 border border-earth-100 transform hover:-translate-y-2">
                    <div class="h-64 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-night-base/80 to-transparent z-10"></div>
                        <img src="https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?q=80&w=2070&auto=format&fit=crop" alt="Camping Ground" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                        <h3 class="absolute bottom-6 left-6 text-2xl font-bold text-white z-20">Premium Camping</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-earth-500 mb-6 leading-relaxed">Area perkemahan luas dengan kontur datar di bawah rimbunnya pinus raksasa. Dilengkapi fasilitas air bersih dan colokan listrik.</p>
                        <a href="{{ route('booking') }}" class="inline-flex items-center text-forest-600 font-bold hover:text-forest-700 transition">
                            Pesan Kavling <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="group rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 bg-earth-50 border border-earth-100 transform hover:-translate-y-2 md:mt-8">
                    <div class="h-64 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-night-base/80 to-transparent z-10"></div>
                        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=2070&auto=format&fit=crop" alt="Trekking" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                        <h3 class="absolute bottom-6 left-6 text-2xl font-bold text-white z-20">Trekking Trails</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-earth-500 mb-6 leading-relaxed">Eksplorasi jalur pendakian melintasi hutan lebat, sungai kecil, dengan pemandangan pegunungan dan udara segar yang memukau.</p>
                        <a href="{{ route('booking') }}" class="inline-flex items-center text-gold-600 font-bold hover:text-gold-700 transition">
                            Mulai Petualangan <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="group rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 bg-earth-50 border border-earth-100 transform hover:-translate-y-2">
                    <div class="h-64 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-night-base/80 to-transparent z-10"></div>
                        <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=2120&auto=format&fit=crop" alt="Outbound" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                        <h3 class="absolute bottom-6 left-6 text-2xl font-bold text-white z-20">Team Outbound</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-earth-500 mb-6 leading-relaxed">Tersedia area khusus outbound dengan flying fox, jaring laba-laba, dan area luas untuk aktivitas company gathering.</p>
                        <a href="{{ route('booking') }}" class="inline-flex items-center text-info font-bold hover:text-sky-600 transition">
                            Pesan Paket Group <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-20 bg-earth-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-extrabold text-earth-950 mb-12">Kata Mereka Yang Pernah Kesini</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Testi 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-earth-100">
                    <div class="flex text-gold-400 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-earth-500 italic mb-6">"Fasilitas glampingnya luar biasa! Udara sejuk, toilet bersih, dan sangat aman karena ada CCTV dan security keliling. Anak-anak sangat senang."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-forest-200 flex items-center justify-center text-forest-700 font-bold">A</div>
                        <div>
                            <h4 class="font-bold text-earth-950 text-sm">Andi Setiawan</h4>
                            <p class="text-xs text-earth-500">Keluarga (2 Anak)</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testi 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-earth-100">
                    <div class="flex text-gold-400 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-earth-500 italic mb-6">"Booking tiket secara online sangat praktis. Begitu sampai tinggal scan QR Code. Area campingnya tertata dengan sangat rapi dan estetik."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gold-100 flex items-center justify-center text-gold-700 font-bold">R</div>
                        <div>
                            <h4 class="font-bold text-earth-950 text-sm">Rina Amelia</h4>
                            <p class="text-xs text-earth-500">Camper Campervan</p>
                        </div>
                    </div>
                </div>

                <!-- Testi 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-earth-100">
                    <div class="flex text-gold-400 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-earth-500 italic mb-6">"Trekking trailnya seru untuk pemula. Pemandangannya juara! Cuma saran mungkin bisa ditambah petunjuk arah di beberapa titik jalur."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">D</div>
                        <div>
                            <h4 class="font-bold text-earth-950 text-sm">Dimas Pratama</h4>
                            <p class="text-xs text-earth-500">Komunitas Hiking</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 relative overflow-hidden bg-night-base">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#22c55e 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Siap Untuk Healing Berkualitas?</h2>
            <p class="text-earth-300 text-lg mb-10 max-w-2xl mx-auto">Tinggalkan sejenak rutinitas harian Anda. Pesan tiket sekarang dan nikmati udara segar pegunungan esok hari.</p>
            <a href="{{ route('booking') }}" class="inline-block px-10 py-5 bg-gradient-to-r from-forest-600 to-forest-500 text-white font-bold text-lg rounded-full shadow-[0_0_30px_rgba(34,197,94,0.3)] transition-all hover:scale-105 hover:shadow-[0_0_50px_rgba(34,197,94,0.5)]">
                Pesan Tiket Online Sekarang
            </a>
        </div>
    </section>

    <!-- Footer with Map -->
    <footer class="bg-night-950 text-earth-500 pt-16 pb-8 border-t border-forest-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-forest-700 to-forest-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path></svg>
                        </div>
                        <span class="font-bold text-lg text-white tracking-widest">GUNUNG PANCAR</span>
                    </div>
                    <p class="text-sm leading-relaxed mb-6">Destinasi ekowisata premium di kawasan Sentul, Bogor. Menyajikan keindahan alam hutan pinus untuk berbagai aktivitas rekreasi.</p>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Tautan Cepat</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('booking') }}" class="hover:text-forest-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-forest-500"></span> Booking Tiket</a></li>
                        <li><a href="{{ url('/admin') }}" class="hover:text-forest-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-forest-500"></span> Login Petugas</a></li>
                        <li><a href="#" class="hover:text-forest-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-forest-500"></span> Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-forest-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-forest-500"></span> Kebijakan Privasi</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-forest-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Karang Tengah, Babakan Madang, Bogor, Jawa Barat 16810</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-forest-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>+62 812-3456-7890</span>
                        </li>
                    </ul>
                </div>

                <!-- Maps Embed -->
                <div class="md:col-span-1 rounded-xl overflow-hidden shadow-lg border border-forest-900/50 h-48 relative">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.0905470762413!2d106.88373657504068!3d-6.581534062463236!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c7f1a3b1a8f9%3A0xc02e75e1864196c0!2sTaman%20Wisata%20Alam%20Gunung%20Pancar!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0 grayscale contrast-125 opacity-70 hover:grayscale-0 hover:opacity-100 transition duration-500"></iframe>
                </div>
            </div>
            
            <div class="pt-8 border-t border-forest-900/50 text-sm text-center flex flex-col md:flex-row justify-between items-center gap-4">
                <p>&copy; {{ date('Y') }} Taman Wisata Alam Gunung Pancar. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-earth-500 hover:text-forest-400 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#" class="text-earth-500 hover:text-forest-400 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
