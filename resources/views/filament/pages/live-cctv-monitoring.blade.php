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
        
        .cctv-card-large::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 40%;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            pointer-events: none;
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
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            font-weight: 600;
        }
        
        .cctv-card-large .label-bottom p {
            font-size: 0.8rem;
            color: #22c55e;
            font-family: monospace;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .live-badge-large {
            position: absolute;
            top: 16px; right: 16px;
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
    
    <div class="live-grid-container">
        @foreach($cameras as $cctv)
            <div class="cctv-card-large">
                <img src="{{ $cctv->thumbnail_url ?? 'https://images.unsplash.com/photo-1557053964-937650ddbfce?q=80&w=600&auto=format&fit=crop' }}" alt="{{ $cctv->name }}" style="opacity: {{ $cctv->status === 'offline' ? '0.3' : '0.9' }}; filter: {{ $cctv->status === 'offline' ? 'grayscale(100%)' : 'none' }};">
                
                @if($cctv->status === 'active')
                    <div class="live-badge-large">
                        <x-heroicon-m-signal class="w-4 h-4" /> LIVE
                    </div>
                @endif
                
                <div class="label-bottom">
                    <h3>{{ $cctv->name }}</h3>
                    <p style="color: {{ $cctv->status === 'active' ? '#22c55e' : '#ef4444' }};">
                        <x-heroicon-m-video-camera class="w-4 h-4" /> 
                        {{ $cctv->ip_address ?? '0.0.0.0' }} | {{ $cctv->status === 'active' ? 'REC - 1080p 60fps' : 'CONNECTION LOST' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
