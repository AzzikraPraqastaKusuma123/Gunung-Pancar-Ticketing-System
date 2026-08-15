<x-filament-panels::page>
    <div class="cctv-page-header">
        <div>
            <h2>
                <x-filament::icon icon="heroicon-s-video-camera" class="cctv-header-icon" />
                Live CCTV Monitoring
            </h2>
            <p>Pantau seluruh area secara real-time dari Command Center.</p>
        </div>
        <div class="cctv-header-actions">
            <x-filament::button color="gray" icon="heroicon-m-funnel">
                Filter Area
            </x-filament::button>
            <x-filament::button icon="heroicon-m-arrows-pointing-out">
                Grid Matrix 4x4
            </x-filament::button>
        </div>
    </div>

    <div class="cctv-grid">
        @forelse($this->getActiveCctvs() as $cctv)
            <div class="cctv-card">
                <div class="cctv-video-container">
                    @php
                        $fallbackImages = [
                            'https://images.unsplash.com/photo-1448375240586-882707db888b?q=85&w=900&auto=format&fit=crop', // Gerbang/Jalan masuk
                            'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?q=85&w=900&auto=format&fit=crop', // Parkir/Outdoor mobil
                            'https://images.unsplash.com/photo-1478827387698-1527781a4887?q=85&w=900&auto=format&fit=crop', // Glamping/Tenda
                            'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?q=85&w=900&auto=format&fit=crop', // Camping
                            'https://images.unsplash.com/photo-1519331379826-f10be5486c6f?q=85&w=900&auto=format&fit=crop', // Taman
                        ];
                        $imgSrc = $cctv->thumbnail_url ?: $fallbackImages[$loop->index % count($fallbackImages)];
                        $fallbackSrc = $fallbackImages[$loop->index % count($fallbackImages)];
                    @endphp
                    <img src="{{ $imgSrc }}" onerror="this.src='{{ $fallbackSrc }}'" alt="{{ $cctv->name }}" class="cctv-img" />
                    
                    <div class="cctv-badge-cam">
                        {{ $cctv->type == 'cctv' ? 'CAM' : 'DEV' }}-{{ str_pad($cctv->id, 3, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="cctv-badge-rec">
                        <span class="cctv-dot-rec"></span>
                        REC
                    </div>
                    
                    <div class="cctv-video-overlay">
                        <h3>{{ $cctv->name }}</h3>
                        <div class="cctv-video-meta">
                            <p>
                                <x-filament::icon icon="heroicon-m-map-pin" class="cctv-meta-icon" />
                                {{ $cctv->location ?: 'Lokasi Tidak Diketahui' }}
                            </p>
                            <span>{{ date('H:i:s') }}</span>
                        </div>
                    </div>

                    <div class="cctv-scanner"></div>
                </div>
                
                <div class="cctv-card-footer">
                    <div class="cctv-footer-info">
                        <x-filament::icon icon="heroicon-m-wifi" class="cctv-footer-icon" />
                        <span>1080p • 60fps</span>
                    </div>
                    <div class="cctv-footer-actions">
                        <button><x-filament::icon icon="heroicon-m-camera" /></button>
                        <button><x-filament::icon icon="heroicon-m-arrows-pointing-out" /></button>
                    </div>
                </div>
            </div>
        @empty
            <div class="cctv-empty-state">
                <div class="cctv-empty-icon">
                    <x-filament::icon icon="heroicon-o-video-camera-slash" />
                </div>
                <h3>Tidak Ada CCTV Aktif</h3>
                <p>Sistem tidak mendeteksi adanya perangkat CCTV yang berstatus aktif saat ini. Silakan cek manajemen perangkat.</p>
                <x-filament::button icon="heroicon-m-arrow-path" color="gray" outlined>
                    Muat Ulang
                </x-filament::button>
            </div>
        @endforelse
    </div>

    <style>
        .cctv-page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 24px;
        }

        .cctv-page-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            text-shadow: 0 0 15px rgba(255,255,255,0.1);
            margin: 0;
        }

        .cctv-header-icon {
            width: 32px;
            height: 32px;
            margin-right: 8px;
            color: #10b981;
        }

        .cctv-page-header p {
            font-size: 0.875rem;
            color: #9ca3af;
            margin-top: 4px;
        }

        .cctv-header-actions {
            display: flex;
            gap: 12px;
        }

        .cctv-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 24px;
        }

        @media (min-width: 768px) { .cctv-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (min-width: 1024px) { .cctv-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (min-width: 1280px) { .cctv-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }

        .cctv-card {
            position: relative;
            background: rgba(17, 24, 39, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            backdrop-filter: blur(12px);
        }

        .cctv-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
            border-color: rgba(16, 185, 129, 0.5);
        }

        .cctv-video-container {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .cctv-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.7;
            transform: scale(1.05);
            transition: all 0.5s ease;
            mix-blend-mode: screen;
        }

        .cctv-card:hover .cctv-img {
            opacity: 1;
            transform: scale(1);
        }

        .cctv-badge-cam {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            z-index: 10;
        }

        .cctv-badge-rec {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(239, 68, 68, 0.2);
            backdrop-filter: blur(4px);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 10;
        }

        .cctv-dot-rec {
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(239, 68, 68, 0.8);
            animation: pulse-rec 2s infinite;
        }

        @keyframes pulse-rec {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .cctv-video-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to top, rgba(0,0,0,1), rgba(0,0,0,0.8), transparent);
            padding: 16px;
            padding-top: 48px;
            transform: translateY(8px);
            transition: transform 0.3s ease;
            z-index: 10;
        }

        .cctv-card:hover .cctv-video-overlay {
            transform: translateY(0);
        }

        .cctv-video-overlay h3 {
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: 0.025em;
            margin: 0 0 4px 0;
        }

        .cctv-video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cctv-video-meta p {
            color: #9ca3af;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 4px;
            margin: 0;
        }

        .cctv-meta-icon {
            width: 12px;
            height: 12px;
            color: #34d399;
        }

        .cctv-video-meta span {
            color: #9ca3af;
            font-size: 10px;
            font-family: monospace;
        }

        .cctv-scanner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(16, 185, 129, 0.5);
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.8);
            animation: scan 3s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes scan {
            0% { top: -10%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 110%; opacity: 0; }
        }

        .cctv-card-footer {
            padding: 12px 16px;
            background: rgba(17, 24, 39, 0.5);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .cctv-footer-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #34d399;
        }

        .cctv-footer-icon {
            width: 16px;
            height: 16px;
        }

        .cctv-footer-actions {
            display: flex;
            gap: 8px;
        }

        .cctv-footer-actions button {
            color: #9ca3af;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s;
            background: transparent;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cctv-footer-actions button svg {
            width: 20px;
            height: 20px;
        }

        .cctv-footer-actions button:hover {
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }

        .cctv-empty-state {
            grid-column: 1 / -1;
            background: rgba(17, 24, 39, 0.8);
            border-radius: 16px;
            border: 2px dashed rgba(255, 255, 255, 0.1);
            padding: 64px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .cctv-empty-icon {
            width: 80px;
            height: 80px;
            background: rgba(31, 41, 55, 1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.6);
        }

        .cctv-empty-icon svg {
            width: 40px;
            height: 40px;
            color: #9ca3af;
        }

        .cctv-empty-state h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 8px 0;
        }

        .cctv-empty-state p {
            font-size: 0.875rem;
            color: #9ca3af;
            max-width: 400px;
            margin: 0 0 24px 0;
            line-height: 1.5;
        }
    </style>
</x-filament-panels::page>
