<x-filament-panels::page>
    @php
        function getRealisticCctvImage($name) {
            $name = strtolower($name);
            if (str_contains($name, 'gerbang') || str_contains($name, 'masuk')) {
                return asset('images/cctv/cctv_gerbang_1786524324305.jpg');
            } elseif (str_contains($name, 'parkir')) {
                return asset('images/cctv/cctv_parking_lot.jpg');
            } elseif (str_contains($name, 'glamping')) {
                return asset('images/cctv/cctv_glamping_1786524341566.jpg');
            } elseif (str_contains($name, 'camping')) {
                return asset('images/cctv/cctv_camping_b.jpg');
            } elseif (str_contains($name, 'resepsionis') || str_contains($name, 'loket')) {
                return asset('images/cctv/cctv_resepsionis_1786524352663.jpg');
            }
            
            // Fallbacks
            $fallbacks = [
                asset('images/cctv/cctv_glamping_1786524341566.jpg'),
                asset('images/cctv/cctv_camping_b.jpg'),
                asset('images/cctv/cctv_parking_lot.jpg'),
                asset('images/cctv/cctv_gerbang_1786524324305.jpg'),
                asset('images/cctv/cctv_resepsionis_1786524352663.jpg')
            ];
            return $fallbacks[crc32($name) % count($fallbacks)];
        }
    @endphp

    <div class="cctv-page-header">
        <div>
            <h2>
                <x-filament::icon icon="heroicon-s-video-camera" class="cctv-header-icon" />
                Live CCTV Monitoring
            </h2>
            <p>Pantau seluruh area Outbound & Camping secara real-time dari Command Center.</p>
        </div>
        <div class="cctv-header-actions">
            <div class="layout-switcher">
                <button class="active"><x-filament::icon icon="heroicon-m-squares-2x2" /></button>
                <button><x-filament::icon icon="heroicon-m-queue-list" /></button>
                <button><x-filament::icon icon="heroicon-m-rectangle-group" /></button>
            </div>
            <x-filament::button color="success" icon="heroicon-m-funnel">
                Filter Area
            </x-filament::button>
        </div>
    </div>

    <!-- Status Summary Bar -->
    <div class="cctv-summary-bar">
        <div class="summary-item">
            <div class="summary-icon bg-success-soft text-success"><x-filament::icon icon="heroicon-s-video-camera" /></div>
            <div class="summary-text">
                <span class="value">{{ count($this->getActiveCctvs()) }}</span>
                <span class="label">Kamera Aktif</span>
            </div>
        </div>
        <div class="summary-item">
            <div class="summary-icon bg-danger-soft text-danger"><x-filament::icon icon="heroicon-s-video-camera-slash" /></div>
            <div class="summary-text">
                <span class="value">2</span>
                <span class="label">Offline</span>
            </div>
        </div>
        <div class="summary-item">
            <div class="summary-icon bg-warning-soft text-warning"><x-filament::icon icon="heroicon-s-exclamation-triangle" /></div>
            <div class="summary-text">
                <span class="value">1</span>
                <span class="label">Peringatan</span>
            </div>
        </div>
        <div class="summary-item" style="margin-left: auto;">
            <div class="summary-status">
                <span class="ping-dot"></span> System Normal
            </div>
        </div>
    </div>

    <div class="cctv-grid">
        @forelse($this->getActiveCctvs() as $cctv)
            <div class="cctv-card">
                <div class="cctv-video-container">
                    @php
                        $imgSrc = getRealisticCctvImage($cctv->name);
                    @endphp
                    <img src="{{ $imgSrc }}" alt="{{ $cctv->name }}" class="cctv-img" />
                    
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
            text-shadow: 0 0 15px rgba(34,197,94,0.3);
            margin: 0;
        }

        .cctv-header-icon {
            width: 32px;
            height: 32px;
            margin-right: 8px;
            color: #22c55e;
        }

        .cctv-page-header p {
            font-size: 0.875rem;
            color: #9ca3af;
            margin-top: 4px;
        }

        .cctv-header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .layout-switcher {
            display: flex;
            background: rgba(10, 31, 18, 0.7);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 8px;
            padding: 4px;
        }
        
        .layout-switcher button {
            background: transparent;
            border: none;
            color: #9ca3af;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .layout-switcher button svg {
            width: 20px;
            height: 20px;
        }
        
        .layout-switcher button.active, .layout-switcher button:hover {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }
        
        /* Summary Bar */
        .cctv-summary-bar {
            display: flex;
            gap: 20px;
            background: rgba(10, 31, 18, 0.6);
            border: 1px solid rgba(34, 197, 94, 0.15);
            border-radius: 12px;
            padding: 16px 24px;
            margin-bottom: 24px;
            align-items: center;
            backdrop-filter: blur(12px);
        }
        
        .summary-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .summary-icon svg {
            width: 20px;
            height: 20px;
        }
        
        .bg-success-soft { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .bg-danger-soft { background: rgba(220, 38, 38, 0.15); color: #f87171; }
        .bg-warning-soft { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        
        .summary-text {
            display: flex;
            flex-direction: column;
        }
        
        .summary-text .value {
            font-size: 1.25rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }
        
        .summary-text .label {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        
        .summary-status {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4ade80;
            font-weight: 600;
            font-size: 0.875rem;
            background: rgba(34, 197, 94, 0.1);
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .ping-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 8px #22c55e;
            animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        
        @keyframes ping {
            75%, 100% { transform: scale(2); opacity: 0; }
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
            background: rgba(10, 31, 18, 0.7);
            border-radius: 12px;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(34, 197, 94, 0.15);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            backdrop-filter: blur(12px);
        }

        .cctv-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(34, 197, 94, 0.3);
            border-color: rgba(34, 197, 94, 0.5);
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
            color: #22c55e;
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
            background: rgba(5, 15, 8, 0.8);
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
            color: #4ade80;
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
            color: #22c55e;
            background: rgba(34, 197, 94, 0.15);
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
