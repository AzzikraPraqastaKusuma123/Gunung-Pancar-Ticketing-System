<x-filament-widgets::widget>
    @php
        $user = auth()->user();
        $name = $user?->name ?? 'Admin';
        $firstName = explode(' ', $name)[0];
        
        $hour = now()->format('H');
        if ($hour < 11) {
            $greeting = 'Selamat pagi';
        } elseif ($hour < 15) {
            $greeting = 'Selamat siang';
        } elseif ($hour < 18) {
            $greeting = 'Selamat sore';
        } else {
            $greeting = 'Selamat malam';
        }
    @endphp

    <div class="custom-wb-container">
        <!-- Subtle mountain silhouette background -->
        <div class="custom-wb-bg"></div>

        <div class="custom-wb-content">
            {{-- Heading --}}
            <h1 class="custom-wb-heading">
                {{ $greeting }}, {{ $firstName }} 👋
            </h1>
            
            {{-- Subtitle --}}
            <p class="custom-wb-subtitle">
                Monitor reservasi, kasir, penyewaan, inventaris, SDM, jaringan, dan CCTV dari satu dashboard terpadu.
            </p>
        </div>
        
        {{-- Illustration --}}
        <div class="custom-wb-image-container">
            <img src="{{ asset('images/logo_gunung_pancar.png') }}" alt="Gunung Pancar" class="custom-wb-image" />
        </div>
    </div>

    <style>
        .custom-wb-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #0a2218 0%, #061510 60%, #04100a 100%);
            border-radius: 1.25rem;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
            min-height: 180px;
            box-shadow: 0 8px 32px -8px rgba(0, 0, 0, 0.6);
            gap: 1.5rem;
        }

        .custom-wb-bg {
            position: absolute;
            bottom: 0; 
            right: 0; 
            width: 60%; 
            height: 100%;
            background: radial-gradient(ellipse at 80% 50%, rgba(16, 185, 129, 0.08) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
        }

        .custom-wb-content {
            flex: 1;
            position: relative;
            z-index: 10;
        }

        .custom-wb-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            margin-bottom: 1.25rem;
        }

        .custom-wb-dot {
            width: 8px;
            height: 8px;
            background-color: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 8px #22c55e;
            animation: wbPulse 2s infinite;
        }

        @keyframes wbPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.2); }
        }

        .custom-wb-heading {
            font-size: 2.25rem;
            font-weight: 800;
            color: #f0fdf4;
            line-height: 1.1;
            margin: 0 0 0.75rem 0;
            letter-spacing: -0.02em;
        }

        .custom-wb-subtitle {
            font-size: 0.8rem;
            color: rgba(110, 231, 183, 0.8);
            line-height: 1.5;
            margin: 0;
            max-width: 500px;
        }

        .custom-wb-image-container {
            flex-shrink: 0;
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .custom-wb-image {
            max-height: 130px;
            width: auto;
            object-fit: contain;
            opacity: 0.9;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4));
        }

        @media (max-width: 768px) {
            .custom-wb-container {
                flex-direction: column-reverse;
                text-align: center;
                padding: 1.5rem;
            }
            .custom-wb-image-container {
                justify-content: center;
                margin-bottom: 1rem;
            }
            .custom-wb-image {
                max-height: 90px;
            }
            .custom-wb-heading {
                font-size: 1.75rem;
            }
            .custom-wb-subtitle {
                margin: 0 auto;
            }
        }
    </style>
</x-filament-widgets::widget>
