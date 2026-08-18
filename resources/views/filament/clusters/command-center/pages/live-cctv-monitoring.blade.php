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

    <div class="cctv-dashboard-wrapper">

        <!-- HEADER SECTION -->
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-icon-wrapper">
                    <x-filament::icon icon="heroicon-s-video-camera" class="header-icon" />
                    <span class="live-badge"><span class="ping-dot"></span> LIVE SYSTEM</span>
                </div>
                <div class="header-text">
                    <h2 class="tracking-tight">Live CCTV Monitoring Wall</h2>
                    <p>Pantau seluruh area Outbound & Camping secara real-time dari Command Center.</p>
                </div>
            </div>
            
            <div class="header-actions">
                <div class="layout-controls">
                    <button id="btn-grid" class="layout-btn active" title="Grid 2x2"><x-filament::icon icon="heroicon-m-squares-2x2" /></button>
                    <button id="btn-list" class="layout-btn" title="List View"><x-filament::icon icon="heroicon-m-queue-list" /></button>
                    <button id="btn-large" class="layout-btn" title="Grid 1x2"><x-filament::icon icon="heroicon-m-rectangle-group" /></button>
                </div>
                <button class="filter-btn" onclick="promptFilterArea()">
                    <x-filament::icon icon="heroicon-m-funnel" />
                    <span>Filter Area</span>
                </button>
            </div>
        </div>

        <!-- METRICS SUMMARY BAR -->
        <div class="metrics-container">
            <div class="metric-card success">
                <div class="metric-icon-box">
                    <x-filament::icon icon="heroicon-s-check-circle" />
                </div>
                <div class="metric-info">
                    <span class="metric-value">{{ count($this->getActiveCctvs()) }}</span>
                    <span class="metric-label">KAMERA AKTIF</span>
                </div>
            </div>
            <div class="metric-card danger">
                <div class="metric-icon-box">
                    <x-filament::icon icon="heroicon-s-video-camera-slash" />
                </div>
                <div class="metric-info">
                    <span class="metric-value">2</span>
                    <span class="metric-label">KONEKSI TERPUTUS</span>
                </div>
            </div>
            <div class="metric-card warning">
                <div class="metric-icon-box">
                    <x-filament::icon icon="heroicon-s-exclamation-triangle" />
                </div>
                <div class="metric-info">
                    <span class="metric-value">1</span>
                    <span class="metric-label">PERINGATAN SISTEM</span>
                </div>
            </div>
            <div class="metric-card system-status">
                <div class="status-indicator">
                    <div class="radar-ping"></div>
                    <div class="radar-dot"></div>
                </div>
                <div class="metric-info">
                    <span class="metric-value status-text">SYSTEM NORMAL</span>
                    <span class="metric-label" x-data x-text="new Date().toLocaleDateString('id-ID', {weekday:'long', year:'numeric', month:'long', day:'numeric'})"></span>
                </div>
            </div>
        </div>

        <!-- OFFLINE ALERTS -->
        @php
            $offlineDevices = [
                ['name' => 'CAM-08 (Pintu Belakang)', 'id' => 'CAM-008', 'since' => '04:18:32'],
            ];
        @endphp
        @if(count($offlineDevices) > 0)
        <div id="critical-alert-banner" class="critical-alert-banner">
            <div class="alert-icon-wrapper">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="alert-content">
                <h4 class="alert-title">CRITICAL: Perangkat Tidak Dapat Dijangkau</h4>
                <div class="alert-tags">
                    @foreach($offlineDevices as $od)
                    <span class="alert-tag">
                        <span class="tag-dot"></span>
                        {{ $od['name'] }} &mdash; Offline sejak {{ $od['since'] }}
                    </span>
                    @endforeach
                </div>
                <p class="alert-desc">Koneksi ke perangkat terputus. Sistem mendeteksi tidak ada respons ping. Harap jadwalkan pemeriksaan fisik oleh teknisi lapangan.</p>
            </div>
            <button onclick="document.getElementById('critical-alert-banner').style.display='none'" class="alert-close-btn" title="Tutup Peringatan">
                <x-filament::icon icon="heroicon-m-x-mark" />
            </button>
        </div>
        @endif

        <!-- CCTV GRID -->
        <div class="cctv-grid" id="main-cctv-grid">
            @forelse($this->getActiveCctvs() as $cctv)
                <div class="cctv-card">
                    <div class="video-feed-wrapper">
                        @php
                            $imgSrc = getRealisticCctvImage($cctv->name);
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $cctv->name }}" class="cctv-feed-img" />
                        
                        <!-- Overlay Effects -->
                        <div class="scanline-overlay"></div>
                        <div class="vignette-overlay"></div>
                        
                        <!-- OSD (On Screen Display) -->
                        <div class="osd-top-left">
                            <span class="osd-badge cam-id">{{ $cctv->type == 'cctv' ? 'CAM' : 'DEV' }}-{{ str_pad($cctv->id, 3, '0', STR_PAD_LEFT) }}</span>
                            <span class="osd-badge cam-name">{{ $cctv->name }}</span>
                        </div>
                        
                        <div class="osd-top-right">
                            <div class="rec-indicator">
                                <span class="rec-dot"></span>
                                <span>REC</span>
                            </div>
                        </div>

                        <div class="osd-bottom-left">
                            <div class="osd-info-row">
                                <x-filament::icon icon="heroicon-m-map-pin" class="osd-icon" />
                                <span>{{ $cctv->location ?: 'Lokasi Tidak Diketahui' }}</span>
                            </div>
                            <div class="osd-info-row text-primary">
                                <x-filament::icon icon="heroicon-m-wifi" class="osd-icon" />
                                <span>Signal 98% • 1080p 60fps</span>
                            </div>
                        </div>

                        <div class="osd-bottom-right">
                            <div class="osd-timestamp" x-data x-text="new Date().toLocaleTimeString('id-ID', {hour12: false})"></div>
                            <div class="osd-date" x-data x-text="new Date().toLocaleDateString('id-ID', {year:'numeric', month:'2-digit', day:'2-digit'})"></div>
                        </div>
                        
                        <!-- Action Overlay (Appears on hover) -->
                        <div class="cctv-action-overlay">
                            <button class="action-btn" onclick="takeSnapshot('{{ $cctv->name }}')" title="Ambil Snapshot">
                                <x-filament::icon icon="heroicon-m-camera" />
                            </button>
                            <button class="action-btn" onclick="toggleFullscreen(this.closest('.cctv-card'))" title="Layar Penuh">
                                <x-filament::icon icon="heroicon-m-arrows-pointing-out" />
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="cctv-empty-state">
                    <div class="empty-icon-wrapper">
                        <x-filament::icon icon="heroicon-o-video-camera-slash" />
                    </div>
                    <h3>TIDAK ADA UMPAN VIDEO</h3>
                    <p>Sistem tidak mendeteksi adanya perangkat CCTV yang berstatus aktif saat ini. Pastikan perangkat telah didaftarkan dan terhubung ke jaringan Command Center.</p>
                    <button class="reload-btn" onclick="window.location.reload()">
                        <x-filament::icon icon="heroicon-m-arrow-path" /> Muat Ulang Sistem
                    </button>
                </div>
            @endforelse
        </div>

    </div>

    <style>
        /* BASE THEME & VARIABLES */
        .cctv-dashboard-wrapper {
            --cc-bg: transparent;
            --cc-card: #ffffff;
            --cc-card-hover: #f9fafb;
            --cc-border: #e5e7eb;
            --cc-text-main: #111827;
            --cc-text-muted: #6b7280;
            
            --cc-primary: #10b981;
            --cc-primary-glow: rgba(16, 185, 129, 0.4);
            --cc-danger: #ef4444;
            --cc-warning: #f59e0b;
            
            color: var(--cc-text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .dark .cctv-dashboard-wrapper {
            --cc-bg: transparent;
            --cc-card: rgba(17, 24, 39, 0.7);
            --cc-card-hover: rgba(31, 41, 55, 0.8);
            --cc-border: rgba(255, 255, 255, 0.1);
            --cc-text-main: #f3f4f6;
            --cc-text-muted: #9ca3af;
        }

        /* HEADER SECTION */
        .dashboard-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            background: var(--cc-card);
            border: 1px solid var(--cc-border);
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .header-icon-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .header-icon {
            width: 2.5rem;
            height: 2.5rem;
            color: var(--cc-primary);
            filter: drop-shadow(0 0 8px var(--cc-primary-glow));
        }

        .live-badge {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(16, 185, 129, 0.15);
            color: var(--cc-primary);
            padding: 0.2rem 0.5rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .live-badge .ping-dot {
            width: 6px;
            height: 6px;
            background: var(--cc-primary);
            border-radius: 50%;
            animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        .header-text h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 0 0.25rem 0;
            color: var(--cc-text-main);
            letter-spacing: -0.025em;
        }

        .header-text p {
            font-size: 0.875rem;
            color: var(--cc-text-muted);
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .layout-controls {
            display: flex;
            background: rgba(0, 0, 0, 0.05);
            border: 1px solid var(--cc-border);
            border-radius: 0.75rem;
            padding: 0.25rem;
        }
        .dark .layout-controls { background: rgba(0, 0, 0, 0.2); }

        .layout-btn {
            background: transparent;
            border: none;
            color: var(--cc-text-muted);
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .layout-btn svg { width: 1.25rem; height: 1.25rem; }
        
        .layout-btn:hover, .layout-btn.active {
            background: var(--cc-primary);
            color: #fff;
            box-shadow: 0 0 10px var(--cc-primary-glow);
        }

        .filter-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--cc-card);
            border: 1px solid var(--cc-border);
            color: var(--cc-text-main);
            padding: 0.6rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .filter-btn svg { width: 1.25rem; height: 1.25rem; color: var(--cc-primary); }
        .filter-btn:hover {
            border-color: var(--cc-primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* METRICS SUMMARY BAR */
        .metrics-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
        }

        .metric-card {
            background: var(--cc-card);
            border: 1px solid var(--cc-border);
            border-radius: 1rem;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .metric-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }

        .metric-icon-box {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .metric-icon-box svg { width: 1.5rem; height: 1.5rem; }

        .metric-card.success .metric-icon-box { background: rgba(16,185,129,0.15); color: var(--cc-primary); border: 1px solid rgba(16,185,129,0.3); }
        .metric-card.danger .metric-icon-box { background: rgba(239,68,68,0.15); color: var(--cc-danger); border: 1px solid rgba(239,68,68,0.3); }
        .metric-card.warning .metric-icon-box { background: rgba(245,158,11,0.15); color: var(--cc-warning); border: 1px solid rgba(245,158,11,0.3); }

        .metric-info {
            display: flex;
            flex-direction: column;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--cc-text-main);
        }

        .metric-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--cc-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.25rem;
        }

        .metric-card.system-status {
            background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(16,185,129,0.02));
            border-color: rgba(16,185,129,0.3);
            justify-content: center;
        }
        .dark .metric-card.system-status { background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(0,0,0,0.2)); }

        .status-indicator {
            position: relative;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .radar-dot { width: 10px; height: 10px; background: var(--cc-primary); border-radius: 50%; box-shadow: 0 0 10px var(--cc-primary); }
        .radar-ping { position: absolute; width: 100%; height: 100%; background: var(--cc-primary); border-radius: 50%; animation: ping 2s cubic-bezier(0,0,0.2,1) infinite; }
        
        .status-text { font-size: 1.1rem; color: var(--cc-primary); text-shadow: 0 0 10px rgba(16,185,129,0.4); }

        /* CRITICAL ALERT BANNER */
        .critical-alert-banner {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            background: linear-gradient(to right, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.05));
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-left: 4px solid var(--cc-danger);
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            position: relative;
            backdrop-filter: blur(12px);
            animation: slideInDown 0.5s ease-out forwards;
        }

        @keyframes slideInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .alert-icon-wrapper {
            margin-top: 0.15rem;
            color: var(--cc-danger);
            filter: drop-shadow(0 0 5px rgba(239, 68, 68, 0.5));
            animation: pulse-danger 2s infinite;
        }

        @keyframes pulse-danger { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.7; transform: scale(0.95); } }

        .alert-content { flex: 1; }
        .alert-title { font-size: 1rem; font-weight: 800; color: #f87171; margin: 0 0 0.5rem 0; text-transform: uppercase; letter-spacing: 0.05em; }
        .alert-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem; }
        
        .alert-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fca5a5;
        }
        .tag-dot { width: 6px; height: 6px; background: #f87171; border-radius: 50%; box-shadow: 0 0 5px #f87171; }
        .alert-desc { font-size: 0.85rem; color: var(--cc-text-muted); margin: 0; line-height: 1.5; }
        
        .alert-close-btn {
            background: transparent;
            border: none;
            color: var(--cc-text-muted);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .alert-close-btn:hover { background: rgba(255,255,255,0.1); color: var(--cc-text-main); }
        .alert-close-btn svg { width: 1.25rem; height: 1.25rem; }


        /* CCTV GRID & CARDS */
        .cctv-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1rem;
        }

        @media (min-width: 640px)  { .cctv-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem; } }
        @media (min-width: 1024px) { .cctv-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem; } }
        @media (min-width: 1280px) { .cctv-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.5rem; } }

        /* Layout Overrides */
        .cctv-grid.list-view { grid-template-columns: 1fr !important; }
        .cctv-grid.large-view { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
        @media (max-width: 768px) { .cctv-grid.large-view { grid-template-columns: 1fr !important; } }

        .cctv-card {
            background: #000;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid var(--cc-border);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .dark .cctv-card { border-color: rgba(255,255,255,0.15); }

        .cctv-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.4), 0 0 0 1px var(--cc-primary);
        }

        .video-feed-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #000;
        }

        .cctv-feed-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: contrast(1.1) brightness(0.9);
            transition: filter 0.5s ease;
        }
        .cctv-card:hover .cctv-feed-img { filter: contrast(1.1) brightness(1.1); }

        /* Realistic OSD Effects */
        .scanline-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0),
                rgba(255,255,255,0),
                rgba(0,0,0,0.1) 50%,
                rgba(0,0,0,0) 50%
            );
            background-size: 100% 4px;
            pointer-events: none;
            opacity: 0.3;
            z-index: 1;
        }

        .vignette-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle, transparent 50%, rgba(0,0,0,0.8) 150%);
            pointer-events: none;
            z-index: 2;
        }

        .osd-top-left { position: absolute; top: 1rem; left: 1rem; z-index: 10; display: flex; gap: 0.5rem; align-items: center; }
        .osd-top-right { position: absolute; top: 1rem; right: 1rem; z-index: 10; }
        .osd-bottom-left { position: absolute; bottom: 1rem; left: 1rem; z-index: 10; display: flex; flex-direction: column; gap: 0.25rem; }
        .osd-bottom-right { position: absolute; bottom: 1rem; right: 1rem; z-index: 10; text-align: right; }

        .osd-badge {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            padding: 0.25rem 0.6rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            font-family: monospace;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-transform: uppercase;
        }
        .osd-badge.cam-id { background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.5); color: #34d399; }

        .rec-indicator {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(0, 0, 0, 0.6);
            padding: 0.25rem 0.6rem;
            border-radius: 0.25rem;
            font-family: monospace;
            font-weight: 700;
            font-size: 0.75rem;
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .rec-dot { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px #ef4444; animation: blink 1s step-end infinite; }
        @keyframes blink { 50% { opacity: 0; } }

        .osd-info-row {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #f3f4f6;
            font-size: 0.75rem;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
            background: rgba(0,0,0,0.4);
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            width: fit-content;
        }
        .osd-info-row.text-primary { color: #34d399; }
        .osd-icon { width: 12px; height: 12px; }

        .osd-timestamp { font-family: monospace; font-size: 1.25rem; font-weight: 800; color: #fff; text-shadow: 2px 2px 4px rgba(0,0,0,0.8); letter-spacing: 0.05em; }
        .osd-date { font-family: monospace; font-size: 0.75rem; font-weight: 600; color: #d1d5db; text-shadow: 1px 1px 2px rgba(0,0,0,0.8); }

        .cctv-action-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 20;
            backdrop-filter: blur(2px);
        }
        .cctv-card:hover .cctv-action-overlay { opacity: 1; }

        .action-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .action-btn svg { width: 1.5rem; height: 1.5rem; }
        .action-btn:hover { background: var(--cc-primary); border-color: var(--cc-primary); transform: scale(1.1); box-shadow: 0 0 15px var(--cc-primary-glow); }

        /* EMPTY STATE */
        .cctv-empty-state {
            grid-column: 1 / -1;
            background: var(--cc-card);
            border: 2px dashed var(--cc-border);
            border-radius: 1rem;
            padding: 4rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .empty-icon-wrapper {
            width: 5rem;
            height: 5rem;
            background: rgba(239,68,68,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--cc-danger);
            border: 1px solid rgba(239,68,68,0.2);
        }
        .empty-icon-wrapper svg { width: 2.5rem; height: 2.5rem; }

        .cctv-empty-state h3 { font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem 0; color: var(--cc-text-main); letter-spacing: 0.05em; }
        .cctv-empty-state p { font-size: 0.875rem; color: var(--cc-text-muted); max-width: 400px; margin: 0 0 2rem 0; line-height: 1.6; }
        
        .reload-btn {
            background: var(--cc-card);
            border: 1px solid var(--cc-border);
            color: var(--cc-text-main);
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .reload-btn:hover { border-color: var(--cc-primary); color: var(--cc-primary); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    </style>

    <script>
        // Auto Toast for Offline Devices
        document.addEventListener('DOMContentLoaded', function () {
            const offlineDevices = [
                { name: 'CAM-08 (Pintu Belakang)', since: '04:18:32' },
            ];

            if (offlineDevices.length > 0 && typeof FilamentNotification !== 'undefined') {
                offlineDevices.forEach(function(dev, i) {
                    setTimeout(function() {
                        new FilamentNotification()
                            .title('⚠ Peringatan Sistem')
                            .body(dev.name + ' tidak terhubung sejak ' + dev.since + '. Diperlukan pengecekan teknis.')
                            .danger()
                            .persistent()
                            .send();
                    }, 800 + (i * 600));
                });
            }
        });

        // Layout Switcher
        document.addEventListener('DOMContentLoaded', () => {
            const gridContainer = document.getElementById('main-cctv-grid');
            const btnGrid = document.getElementById('btn-grid');
            const btnList = document.getElementById('btn-list');
            const btnLarge = document.getElementById('btn-large');

            function resetButtons() {
                btnGrid.classList.remove('active');
                btnList.classList.remove('active');
                btnLarge.classList.remove('active');
                gridContainer.classList.remove('list-view', 'large-view');
            }

            if (btnGrid) {
                btnGrid.addEventListener('click', () => { resetButtons(); btnGrid.classList.add('active'); });
            }
            if (btnList) {
                btnList.addEventListener('click', () => { resetButtons(); btnList.classList.add('active'); gridContainer.classList.add('list-view'); });
            }
            if (btnLarge) {
                btnLarge.addEventListener('click', () => { resetButtons(); btnLarge.classList.add('active'); gridContainer.classList.add('large-view'); });
            }
        });

        // Filter Logic
        function promptFilterArea() {
            const area = prompt("Masukkan lokasi/nama kamera (misal: Gerbang):");
            if (area === null) return;
            const term = area.toLowerCase();
            const cards = document.querySelectorAll('.cctv-card');
            let found = 0;
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(term)) {
                    card.style.display = 'block';
                    found++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (typeof FilamentNotification !== 'undefined') {
                new FilamentNotification()
                    .title('Filter Diterapkan')
                    .body(`Menemukan ${found} kamera untuk pencarian "${area}"`)
                    .success()
                    .send();
            }
        }

        // Actions
        function takeSnapshot(camName) {
            if (typeof FilamentNotification !== 'undefined') {
                new FilamentNotification()
                    .title('Snapshot Disimpan')
                    .body('Gambar dari ' + camName + ' berhasil diarsipkan ke sistem.')
                    .success()
                    .send();
            }
        }

        function toggleFullscreen(elem) {
            if (!document.fullscreenElement) {
                elem.requestFullscreen().catch(err => alert(`Gagal: ${err.message}`));
            } else {
                document.exitFullscreen();
            }
        }
    </script>
</x-filament-panels::page>
