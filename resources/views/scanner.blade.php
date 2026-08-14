@php
    $isWidget = request()->query('widget') == 'true';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Tiket - Camping Ground</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Sinkronisasi dengan Dark Mode Filament
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Minimal override for html5-qrcode */
        #reader img {
            max-width: 100%;
            border-radius: 0.75rem;
        }
        #reader video {
            border-radius: 0.75rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            width: 100% !important;
        }
        @if($isWidget)
        body, html {
            background: transparent !important;
        }
        #reader, #reader > div, #reader__dashboard_section_csr {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }
        #reader span, #reader div, #reader label {
            color: #9ca3af !important;
        }
        #reader select {
            background-color: #18181b !important;
            color: #f8fafc !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem;
            outline: none;
        }
        @else
        .dark #reader span, .dark #reader div, .dark #reader label {
            color: #d1d5db !important; /* gray-300 */
        }
        .dark #reader select {
            background-color: #27272a !important; /* zinc-800 */
            color: #f8fafc !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem;
            outline: none;
        }
        html:not(.dark) #reader select {
            background-color: #f3f4f6 !important;
            color: #1f2937 !important;
            border: 1px solid #d1d5db !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem;
            outline: none;
        }
        @endif
        
        /* ── PREMIUM BUTTONS ── */
        #reader button, #flip-camera-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
            padding: 0.6rem 1.25rem !important;
            border-radius: 0.75rem !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 10px -2px rgba(16, 185, 129, 0.4), inset 0 1px 0 rgba(255,255,255,0.2) !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer;
            margin: 0.5rem 0.25rem !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.8rem !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        #reader button:hover, #flip-camera-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px -2px rgba(16, 185, 129, 0.5), inset 0 1px 0 rgba(255,255,255,0.3) !important;
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%) !important;
        }
        #reader button:active, #flip-camera-btn:active {
            transform: translateY(0);
        }
        #reader a {
            color: #10b981 !important;
            text-decoration: underline;
            font-weight: 600;
        }
    </style>
</head>
<body class="{{ $isWidget ? 'bg-transparent' : 'bg-gray-50 dark:bg-[#09090b]' }} flex flex-col items-center justify-center w-full min-h-screen font-sans m-0 p-0 text-gray-800 dark:text-gray-200 relative transition-colors duration-300">
    
    @if(!$isWidget)
    <!-- Header -->
    <div class="absolute top-0 left-0 w-full p-4 md:p-6 z-50 flex justify-between items-center bg-white dark:bg-[#18181b] shadow-sm border-b border-gray-100 dark:border-white/5 transition-colors duration-300">
        <div class="flex items-center gap-3">
            <a href="{{ url('/dasbord') }}" class="p-2 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-full transition text-gray-600 dark:text-gray-300">

                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white tracking-wide">Scanner Gate</h1>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 font-bold tracking-widest uppercase">Tim Tiket Gunung Pancar</p>
            </div>
        </div>
    </div>
    @endif

    <div class="w-full max-w-lg text-center px-4 {{ $isWidget ? 'mt-4' : 'mt-20 md:mt-24' }} z-10">
        <!-- Scanner Container -->
        <div class="relative {{ $isWidget ? '' : 'p-[2px] rounded-[1.4rem] bg-gradient-to-tr from-emerald-400 via-emerald-500 to-green-500 shadow-[0_10px_40px_-10px_rgba(16,185,129,0.3)]' }}">
            <div id="reader" class="w-full {{ $isWidget ? 'bg-transparent' : 'bg-white dark:bg-[#18181b]' }} p-4 sm:p-6 rounded-2xl mx-auto overflow-hidden"></div>
        </div>
        
        <div id="result" class="hidden mt-4 p-4 rounded-xl border">
            <!-- Hasil akan dirender di sini -->
        </div>
    </div>

    <script>
        let html5QrcodeScanner;
        let isProcessing = false;
        let lastScanned = "";
        
        // Buat satu AudioContext global (dihindari membuat baru setiap kali beep)
        let audioCtx = null;

        // Konfigurasi Notifikasi Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#ffffff',
            color: '#1f2937',
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-gray-100'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        function playBeep(type) {
            try {
                // Inisialisasi AudioContext hanya sekali
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                
                // Jika context tersuspensi (karena browser policy), resume
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }

                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                
                if (type === 'success') {
                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(1200, audioCtx.currentTime); // Nada tinggi
                    gainNode.gain.setValueAtTime(0.5, audioCtx.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.2);
                    oscillator.start(audioCtx.currentTime);
                    oscillator.stop(audioCtx.currentTime + 0.2);
                } else {
                    oscillator.type = 'square';
                    oscillator.frequency.setValueAtTime(250, audioCtx.currentTime); // Nada rendah/error
                    gainNode.gain.setValueAtTime(0.5, audioCtx.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.4);
                    oscillator.start(audioCtx.currentTime);
                    oscillator.stop(audioCtx.currentTime + 0.4);
                }
            } catch (e) {
                console.log("Audio not supported or interaction required");
            }
        }

        function validateTicket(ticketNumber) {
            fetch(`/api/tickets/validate/${ticketNumber}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('result').classList.add('hidden');

                    if(data.success) {
                        playBeep('success');
                        document.body.classList.add('bg-emerald-100');
                        setTimeout(() => document.body.classList.remove('bg-emerald-100'), 400);
                        
                        Toast.fire({
                            icon: 'success',
                            title: 'Tiket Valid!',
                            text: `${data.data.customer} - ${data.data.category} (${data.data.pax} Pax)`
                        });
                    } else {
                        playBeep('error');
                        document.body.classList.add('bg-red-100');
                        setTimeout(() => document.body.classList.remove('bg-red-100'), 400);
                        
                        Toast.fire({
                            icon: 'error',
                            title: 'Tiket Ditolak!',
                            text: data.message
                        });
                    }
                    
                    // Selesai memproses, siap scan lagi
                    setTimeout(() => {
                        isProcessing = false;
                    }, 1000);
                })
                .catch(err => {
                    console.error('Error:', err);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Koneksi ke server gagal.'
                    });
                    isProcessing = false;
                });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Cegah scan ganda saat memproses atau jika scan kode yang sama berulang kali terlalu cepat
            if (isProcessing || decodedText === lastScanned) {
                return;
            }
            
            isProcessing = true;
            lastScanned = decodedText;
            
            // Reset last scanned setelah 3 detik
            setTimeout(() => { lastScanned = ""; }, 3000);
            
            // Ekstrak ticketNumber
            let ticketNumber = decodedText;
            try {
                let url = new URL(decodedText);
                if (url.searchParams.has('ticket')) {
                    ticketNumber = url.searchParams.get('ticket');
                } else {
                    ticketNumber = url.pathname.split('/').pop();
                }
            } catch (e) {
                if (decodedText.includes('/')) {
                    ticketNumber = decodedText.split('/').pop();
                }
            }

            validateTicket(ticketNumber);
        }

        function onScanFailure(error) {
            // abaikan
        }

        function startScanner() {
            document.getElementById('result').classList.add('hidden');
            
            // Inisialisasi audio setelah interaksi user
            if (!audioCtx) {
                try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e){}
            }

            // Jika tombol ditekan dan scanner sudah jalan, abaikan
            if (html5QrcodeScanner && html5QrcodeScanner.getState() === 2) {
                return;
            }
            
            // Optimasi performa: Kurangi resolusi video agar tidak ngelag
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { 
                    fps: 5, 
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                        // Jadikan kotak scanner 85% dari lebar/tinggi area kamera agar terlihat besar maksimal
                        let qrboxSize = Math.floor(minEdgeSize * 0.85);
                        return { width: qrboxSize, height: qrboxSize };
                    },
                    videoConstraints: {
                        facingMode: "environment",
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                },
                /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
        
        // Auto start / Check URL params
        window.onload = () => {
            const urlParams = new URLSearchParams(window.location.search);
            const ticketFromUrl = urlParams.get('ticket');
            
            if (ticketFromUrl) {
                // Langsung validasi jika URL mengandung param ?ticket=...
                validateTicket(ticketFromUrl);
            } else {
                startScanner();
            }
            
            // Injeksi Tombol Flip Kamera (Tukar Depan/Belakang)
            setInterval(() => {
                let cameraSelect = document.querySelector('#reader select');
                // Pastikan select sudah ada dan tombol flip belum pernah ditambahkan
                if (cameraSelect && !document.getElementById('flip-camera-btn')) {
                    let flipBtn = document.createElement('button');
                    flipBtn.id = 'flip-camera-btn';
                    // Hanya menggunakan SVG ikon bulat saja, ukuran diperbesar dikit
                    flipBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3"/></svg>';
                    flipBtn.title = "Tukar Kamera (Depan/Belakang)";
                    
                    // Modifikasi padding khusus buat ikon doang biar bentuknya pas
                    flipBtn.style.padding = '0.6rem 0.6rem';
                    
                    flipBtn.onclick = function(e) {
                        e.preventDefault();
                        if (cameraSelect.options.length > 1) {
                            // Pindah ke opsi kamera berikutnya (putar jika sudah di akhir)
                            let nextIndex = (cameraSelect.selectedIndex + 1) % cameraSelect.options.length;
                            cameraSelect.selectedIndex = nextIndex;
                            cameraSelect.dispatchEvent(new Event('change'));
                        } else {
                            Toast.fire({ icon: 'info', title: 'Info', text: 'Hanya 1 kamera yang terdeteksi di perangkat ini.' });
                        }
                    };
                    cameraSelect.parentNode.insertBefore(flipBtn, cameraSelect.nextSibling);
                }
            }, 1000);
        };
    </script>
</body>
</html>
