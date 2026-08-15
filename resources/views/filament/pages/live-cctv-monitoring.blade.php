<x-filament-panels::page>
    <style>
        .live-grid-container {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 16px;
            margin-top: 24px;
        }
        @media (min-width: 768px) {
            .live-grid-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .live-grid-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .cctv-card-large {
            position: relative;
            background: #000;
            border-radius: 0.75rem;
            overflow: hidden;
            aspect-ratio: 16/9;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        
        .cctv-card-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }
        
        /* Scanline effect for realism */
        .cctv-scanlines {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0),
                rgba(255,255,255,0) 50%,
                rgba(0,0,0,0.1) 50%,
                rgba(0,0,0,0.1)
            );
            background-size: 100% 4px;
            pointer-events: none;
            z-index: 1;
        }

        .cctv-card-large::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 50%;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            pointer-events: none;
            z-index: 1;
        }
        
        .cctv-card-large .label-bottom {
            position: absolute;
            bottom: 16px; left: 16px;
            z-index: 2;
        }
        
        .cctv-card-large .label-bottom h3 {
            font-size: 1.1rem;
            color: white;
            margin-bottom: 4px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .cctv-card-large .label-bottom p {
            font-size: 0.8rem;
            color: #22c55e;
            font-family: monospace;
            display: flex;
            align-items: center;
            gap: 6px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.8);
        }
        
        /* Top Right overlay: Timestamp */
        .cctv-timestamp {
            position: absolute;
            top: 16px; right: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-family: monospace;
            font-size: 0.85rem;
            z-index: 2;
            text-shadow: 0 1px 3px rgba(0,0,0,0.8);
            text-align: right;
        }

        /* Top Left overlay: REC badge */
        .live-badge-large {
            position: absolute;
            top: 16px; left: 16px;
            background: rgba(220, 38, 38, 0.9);
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            animation: blink-live 2s infinite;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 2;
        }
        
        @keyframes blink-live {
            0% { opacity: 1; }
            50% { opacity: 0.3; }
            100% { opacity: 1; }
        }
        
        .cctv-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            border-radius: 0.75rem;
        }
    </style>

    <div class="cctv-toolbar bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Live CCTV Wall</h2>
        <div class="flex gap-3">
            <x-filament::button color="gray" icon="heroicon-m-arrows-pointing-out" onclick="document.documentElement.requestFullscreen()">
                Fullscreen
            </x-filament::button>
        </div>
    </div>
    
    @php
        $campingImages = [
            asset('images/cctv/cctv_glamping_1786524341566.jpg'),
            asset('images/cctv/cctv_camping_b.jpg'),
            asset('images/cctv/cctv_parking_lot.jpg'),
            asset('images/cctv/cctv_gerbang_1786524324305.jpg'),
            asset('images/cctv/cctv_resepsionis_1786524352663.jpg')
        ];
    @endphp

    <div class="live-grid-container">
        @foreach($cameras as $index => $cctv)
            <div class="cctv-card-large">
                <div class="cctv-scanlines"></div>
                <img src="{{ $cctv->thumbnail_url ?? $campingImages[$index % count($campingImages)] }}" alt="{{ $cctv->name }}" style="opacity: {{ $cctv->status === 'offline' ? '0.3' : '1.0' }}; filter: {{ $cctv->status === 'offline' ? 'grayscale(100%)' : 'contrast(1.1) saturate(1.1)' }};">
                
                @if($cctv->status === 'active')
                    <div class="live-badge-large">
                        <x-heroicon-m-signal class="w-4 h-4" /> REC
                    </div>
                @endif
                
                <div class="cctv-timestamp">
                    {{ now()->format('Y-m-d') }}<br>
                    <span id="time-{{ $cctv->id }}">{{ now()->format('H:i:s') }}</span>
                </div>
                
                <div class="label-bottom">
                    <h3>{{ $cctv->name }}</h3>
                    <p style="color: {{ $cctv->status === 'active' ? '#22c55e' : '#ef4444' }};">
                        <x-heroicon-m-video-camera class="w-4 h-4" /> 
                        {{ $cctv->ip_address ?? '192.168.1.' . ($index + 10) }} | {{ $cctv->status === 'active' ? 'CH' . str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ' - 1080p 60fps' : 'CONNECTION LOST' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // Simple script to update the timestamps in real-time
        setInterval(() => {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-GB', { hour12: false });
            @foreach($cameras as $cctv)
                const timeEl_{{ $cctv->id }} = document.getElementById('time-{{ $cctv->id }}');
                if (timeEl_{{ $cctv->id }}) timeEl_{{ $cctv->id }}.textContent = timeString;
            @endforeach
        }, 1000);
    </script>
</x-filament-panels::page>
