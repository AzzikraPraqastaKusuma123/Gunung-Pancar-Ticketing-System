<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taman Wisata Alam Gunung Pancar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-cursive { font-family: 'Playfair Display', serif; }
        .hero-pattern {
            background-image: url('https://images.unsplash.com/photo-1517600813898-103328e85449?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .hero-overlay {
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.4) 0%, rgba(15, 23, 42, 0.8) 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .text-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-emerald-500 selection:text-white" x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navbar -->
    <nav :class="{'bg-slate-900/90 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled}" class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path></svg>
                    <span class="font-bold text-xl text-white tracking-widest">GUNUNG PANCAR</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#tentang" class="text-slate-300 hover:text-white transition font-medium text-sm tracking-wide">Tentang</a>
                    <a href="#fasilitas" class="text-slate-300 hover:text-white transition font-medium text-sm tracking-wide">Fasilitas</a>
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full bg-emerald-600 text-white font-bold hover:bg-emerald-500 transition transform hover:-translate-y-0.5 shadow-lg shadow-emerald-500/30 text-sm">Login Multi Level</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-emerald-400 focus:outline-none">
                        <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg class="h-6 w-6" x-show="mobileMenuOpen" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-slate-900 border-t border-slate-800" x-transition>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#tentang" class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800 rounded-md">Tentang</a>
                <a href="#fasilitas" class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800 rounded-md">Fasilitas</a>
                <div class="mt-4 pt-4 border-t border-slate-800">
                    <a href="{{ route('login') }}" class="block w-full text-center px-5 py-3 rounded-xl bg-emerald-600 text-white font-bold">Login Multi Level</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 hero-pattern transform scale-105 transition-transform duration-10000 hover:scale-100"></div>
        <div class="absolute inset-0 hero-overlay"></div>
        
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto mt-16">
            <h2 class="text-emerald-400 font-bold tracking-[0.2em] uppercase text-sm md:text-base mb-4 drop-shadow-md">Taman Wisata Alam</h2>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold text-white mb-6 leading-tight text-glow">
                Kembali ke <span class="font-cursive italic font-medium text-emerald-300">Alam</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-200 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                Temukan ketenangan di tengah hutan pinus yang asri. Cocok untuk camping, relaksasi, dan pelarian sejenak dari hiruk pikuk kota.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('booking') }}" class="group relative px-8 py-4 bg-emerald-500 text-white font-bold text-lg rounded-full overflow-hidden shadow-[0_0_40px_rgba(16,185,129,0.4)] transition-all hover:scale-105 hover:shadow-[0_0_60px_rgba(16,185,129,0.6)]">
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-400 to-emerald-600 group-hover:scale-110 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-2">
                        Pesan Tiket Sekarang
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </a>
                <a href="#fasilitas" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white font-bold text-lg rounded-full border border-white/20 hover:bg-white/20 transition-all">
                    Lihat Fasilitas
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-slate-900 border-t border-slate-800 py-12 relative z-20 -mt-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-800">
                <div class="py-4 md:py-0">
                    <div class="text-3xl md:text-4xl font-extrabold text-emerald-400 mb-1">20+</div>
                    <div class="text-slate-400 text-sm font-medium tracking-wide uppercase">Area Camping</div>
                </div>
                <div class="py-4 md:py-0">
                    <div class="text-3xl md:text-4xl font-extrabold text-emerald-400 mb-1">100k+</div>
                    <div class="text-slate-400 text-sm font-medium tracking-wide uppercase">Pengunjung</div>
                </div>
                <div class="py-4 md:py-0">
                    <div class="text-3xl md:text-4xl font-extrabold text-emerald-400 mb-1">15+</div>
                    <div class="text-slate-400 text-sm font-medium tracking-wide uppercase">Fasilitas</div>
                </div>
                <div class="py-4 md:py-0">
                    <div class="text-3xl md:text-4xl font-extrabold text-emerald-400 mb-1">24/7</div>
                    <div class="text-slate-400 text-sm font-medium tracking-wide uppercase">Keamanan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="fasilitas" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-emerald-600 font-bold tracking-widest text-sm uppercase mb-3">Pengalaman Tak Terlupakan</h3>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">Kenapa Memilih Gunung Pancar?</h2>
                <p class="text-lg text-slate-600">Beragam aktivitas alam yang menanti Anda, mulai dari camping santai bersama keluarga hingga trekking menantang adrenalin.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Card 1 -->
                <div class="group rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-500/20 transition-all duration-300 bg-slate-50 border border-slate-100 transform hover:-translate-y-2">
                    <div class="h-64 overflow-hidden relative">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition duration-300 z-10"></div>
                        <img src="https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?q=80&w=2070&auto=format&fit=crop" alt="Camping Ground" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                    </div>
                    <div class="p-8">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Premium Camping Ground</h3>
                        <p class="text-slate-600 mb-6 leading-relaxed">Area perkemahan yang luas di bawah naungan pohon pinus raksasa. Tersedia area campervan dan glamping.</p>
                        <a href="{{ route('booking') }}" class="inline-flex items-center text-emerald-600 font-bold hover:text-emerald-700 transition">
                            Pesan Kavling <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="group rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-500/20 transition-all duration-300 bg-slate-50 border border-slate-100 transform hover:-translate-y-2 md:mt-8">
                    <div class="h-64 overflow-hidden relative">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition duration-300 z-10"></div>
                        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=2070&auto=format&fit=crop" alt="Trekking" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                    </div>
                    <div class="p-8">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Trekking & Hiking Trails</h3>
                        <p class="text-slate-600 mb-6 leading-relaxed">Jalur pendakian ringan hingga menengah melintasi hutan lebat dengan pemandangan pegunungan yang memukau.</p>
                        <a href="{{ route('booking') }}" class="inline-flex items-center text-orange-600 font-bold hover:text-orange-700 transition">
                            Mulai Petualangan <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="group rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-500/20 transition-all duration-300 bg-slate-50 border border-slate-100 transform hover:-translate-y-2">
                    <div class="h-64 overflow-hidden relative">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition duration-300 z-10"></div>
                        <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=2120&auto=format&fit=crop" alt="Outbound" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                    </div>
                    <div class="p-8">
                        <div class="w-12 h-12 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Group Outbound</h3>
                        <p class="text-slate-600 mb-6 leading-relaxed">Fasilitas team building lengkap untuk sekolah, perusahaan, maupun komunitas dengan instruktur profesional.</p>
                        <a href="{{ route('booking') }}" class="inline-flex items-center text-sky-600 font-bold hover:text-sky-700 transition">
                            Pesan Paket Group <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 relative overflow-hidden bg-slate-900">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#10b981 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Siap Untuk Healing Berkualitas?</h2>
            <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto">Tinggalkan sejenak rutinitas harian Anda. Pesan tiket sekarang dan nikmati udara segar pegunungan esok hari.</p>
            <a href="{{ route('booking') }}" class="inline-block px-10 py-5 bg-emerald-500 text-slate-900 font-bold text-lg rounded-full overflow-hidden shadow-[0_0_30px_rgba(16,185,129,0.3)] transition-all hover:scale-105 hover:bg-emerald-400">
                Pesan Tiket Online Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l-8 12h16L12 4zm0 0l4 6-4 6-4-6 4-6z"></path></svg>
                    <span class="font-bold text-lg text-white tracking-widest">GUNUNG PANCAR</span>
                </div>
                <p class="text-sm leading-relaxed">Destinasi ekowisata premium di kawasan Sentul, Bogor. Menyajikan keindahan alam hutan pinus untuk berbagai aktivitas rekreasi.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('booking') }}" class="hover:text-emerald-400 transition">Booking Tiket</a></li>
                    <li><a href="{{ url('/admin') }}" class="hover:text-emerald-400 transition">Login Petugas</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Hubungi Kami</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Karang Tengah, Babakan Madang, Bogor
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        +62 812-3456-7890
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        info@gunungpancar.id
                    </li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-slate-800 text-sm text-center">
            &copy; {{ date('Y') }} Taman Wisata Alam Gunung Pancar. All rights reserved.
        </div>
    </footer>

</body>
</html>
