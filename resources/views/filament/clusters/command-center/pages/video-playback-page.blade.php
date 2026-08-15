<x-filament-panels::page>
    <div class="dvr-page-header">
        <div>
            <h2>
                <x-filament::icon icon="heroicon-s-film" class="dvr-header-icon" />
                Sistem Rekaman (DVR)
            </h2>
            <p>Akses arsip video dan playback rekaman CCTV.</p>
        </div>
        <div class="dvr-header-actions">
            <x-filament::button color="gray" icon="heroicon-m-calendar">
                Pilih Tanggal
            </x-filament::button>
            <x-filament::button icon="heroicon-m-arrow-down-tray" color="primary">
                Ekspor Video
            </x-filament::button>
        </div>
    </div>

    <div class="dvr-layout">
        
        <!-- Sidebar: Video List / Archive -->
        <div class="dvr-sidebar">
            <h3 class="dvr-sidebar-title">
                <x-filament::icon icon="heroicon-m-archive-box" class="dvr-title-icon" />
                Arsip Rekaman
            </h3>
            
            <div class="dvr-sidebar-content">
                <div class="dvr-search">
                    <x-filament::input.wrapper icon="heroicon-m-magnifying-glass">
                        <x-filament::input type="text" placeholder="Cari area, gate, waktu..." class="dvr-search-input" />
                    </x-filament::input.wrapper>
                </div>

                <div class="dvr-filter-pills">
                    <span class="dvr-pill active">Hari Ini</span>
                    <span class="dvr-pill">Kemarin</span>
                    <span class="dvr-pill">Minggu Ini</span>
                </div>

                <div class="dvr-video-list custom-scrollbar">
                    @for($i = 1; $i <= 8; $i++)
                        <button class="dvr-video-item">
                            <div class="dvr-thumb-container">
                                <img src="https://images.unsplash.com/photo-1557053964-937650ddbfce?q=80&w=150&auto=format&fit=crop" class="dvr-thumb-img" />
                                <div class="dvr-thumb-overlay">
                                    <x-filament::icon icon="heroicon-s-play-circle" class="dvr-thumb-icon" />
                                </div>
                            </div>
                            <div class="dvr-item-info">
                                <h4>Kamera Gate {{ $i }}</h4>
                                <div class="dvr-item-meta">
                                    <x-filament::icon icon="heroicon-m-clock" class="dvr-meta-icon" />
                                    <span>{{ now()->subDays($i)->format('d/m/Y') }} • 2j 15m</span>
                                </div>
                            </div>
                        </button>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Main Video Player Area -->
        <div class="dvr-main">
            <div class="dvr-player-card">
                
                <!-- Player Header -->
                <div class="dvr-player-header">
                    <div class="dvr-player-title">
                        <div class="dvr-status-dot"></div>
                        <h2>Rekaman Gate 1 - Shift Malam</h2>
                    </div>
                    <div class="dvr-player-badges">
                        <span class="dvr-badge">1080p</span>
                        <span class="dvr-badge">H.265</span>
                    </div>
                </div>

                <div class="dvr-video-wrapper">
                    <!-- Placeholder for Video Player -->
                    <img src="https://images.unsplash.com/photo-1557053964-937650ddbfce?q=80&w=1200&auto=format&fit=crop" class="dvr-video-bg" />
                    
                    <div class="dvr-play-center">
                        <button class="dvr-play-btn">
                            <x-filament::icon icon="heroicon-s-play" class="dvr-play-icon" />
                        </button>
                    </div>
                    
                    <div class="dvr-controls-overlay">
                        <!-- Seek Bar -->
                        <div class="dvr-seek-container">
                            <div class="dvr-seek-bg"></div>
                            <div class="dvr-seek-progress"></div>
                            <div class="dvr-seek-handle"></div>
                        </div>

                        <!-- Controls -->
                        <div class="dvr-controls-row">
                            <div class="dvr-controls-left">
                                <div class="dvr-media-btns">
                                    <button><x-filament::icon icon="heroicon-s-backward" /></button>
                                    <button><x-filament::icon icon="heroicon-s-pause" style="width:24px;height:24px;"/></button>
                                    <button><x-filament::icon icon="heroicon-s-forward" /></button>
                                </div>
                                <div class="dvr-timecode">
                                    00:25:10 / 01:23:45
                                </div>
                            </div>
                            <div class="dvr-controls-right">
                                <button title="Kecepatan"><x-filament::icon icon="heroicon-s-clock" /></button>
                                <button title="Volume"><x-filament::icon icon="heroicon-s-speaker-wave" /></button>
                                <button title="Layar Penuh" style="margin-left:8px;"><x-filament::icon icon="heroicon-s-arrows-pointing-out" /></button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="dvr-player-footer">
                    <div class="dvr-info-grid">
                        <div class="dvr-info-col">
                            <span class="dvr-info-label">Tanggal & Waktu</span>
                            <span class="dvr-info-val">{{ now()->subDays(1)->format('d M Y') }} • 18:00 - 06:00</span>
                        </div>
                        <div class="dvr-info-col bordered">
                            <span class="dvr-info-label">Sumber Perangkat</span>
                            <span class="dvr-info-val flex-center"><x-filament::icon icon="heroicon-s-server" class="dvr-info-icon" /> DVR Induk 01</span>
                        </div>
                        <div class="dvr-info-col bordered">
                            <span class="dvr-info-label">Deteksi Gerak</span>
                            <span class="dvr-info-val flex-center text-danger"><x-filament::icon icon="heroicon-s-bolt" class="dvr-info-icon" /> 14 Kejadian</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Controls / Timeline / Motion events -->
            <div class="dvr-timeline-card">
                <div class="dvr-timeline-header">
                    <h3>
                        <x-filament::icon icon="heroicon-m-chart-bar-square" class="dvr-title-icon" />
                        Smart Timeline Analysis
                    </h3>
                    <div class="dvr-timeline-legend">
                        <span><div class="dvr-legend-dot primary"></div> Rekaman Normal</span>
                        <span><div class="dvr-legend-dot danger"></div> Deteksi Gerak</span>
                    </div>
                </div>
                
                <div class="dvr-timeline-bar">
                    <!-- Background Progress -->
                    <div class="dvr-timeline-progress"></div>
                    
                    <!-- Motion Events -->
                    <div class="dvr-motion-event" style="left: 15%; height: 50%;" title="Gerakan terdeteksi (19:30)"></div>
                    <div class="dvr-motion-event" style="left: 22%; height: 60%;" title="Gerakan terdeteksi (20:45)"></div>
                    <div class="dvr-motion-event" style="left: 30%; height: 40%;" title="Gerakan terdeteksi (22:15)"></div>
                    
                    <!-- Playhead -->
                    <div class="dvr-timeline-playhead">
                        <div class="dvr-playhead-cap"></div>
                    </div>

                    <div class="dvr-timeline-labels">
                        <span>18:00</span><span>20:00</span><span>22:00</span><span>00:00</span><span>02:00</span><span>04:00</span><span>06:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dvr-page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 24px;
        }

        .dvr-page-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            text-shadow: 0 0 15px rgba(255,255,255,0.1);
            margin: 0;
        }

        .dvr-header-icon {
            width: 32px;
            height: 32px;
            margin-right: 8px;
            color: #10b981;
        }

        .dvr-page-header p {
            font-size: 0.875rem;
            color: #9ca3af;
            margin-top: 4px;
        }

        .dvr-header-actions {
            display: flex;
            gap: 12px;
        }

        .dvr-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        @media (min-width: 1024px) {
            .dvr-layout { grid-template-columns: 300px 1fr; }
        }

        /* Sidebar Styles */
        .dvr-sidebar {
            background: rgba(17, 24, 39, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 700px;
            backdrop-filter: blur(12px);
        }

        .dvr-sidebar-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 16px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dvr-title-icon { width: 20px; height: 20px; color: #10b981; }

        .dvr-sidebar-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            gap: 16px;
        }

        .dvr-search-input {
            background: rgba(31, 41, 55, 0.5) !important;
            border: none !important;
        }

        .dvr-filter-pills {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .dvr-filter-pills::-webkit-scrollbar { display: none; }

        .dvr-pill {
            padding: 4px 12px;
            background: rgba(31, 41, 55, 1);
            color: #9ca3af;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dvr-pill:hover { background: rgba(55, 65, 81, 1); }
        .dvr-pill.active {
            background: rgba(16, 185, 129, 0.1);
            color: #34d399;
        }

        .dvr-video-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
            overflow-y: auto;
            padding-right: 8px;
        }

        .dvr-video-item {
            width: 100%;
            text-align: left;
            padding: 12px;
            border-radius: 12px;
            background: rgba(31, 41, 55, 0.4);
            border: 1px solid transparent;
            display: flex;
            gap: 12px;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dvr-video-item:hover {
            background: rgba(31, 41, 55, 0.8);
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .dvr-thumb-container {
            position: relative;
            width: 80px;
            height: 56px;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .dvr-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
            transition: all 0.5s;
        }

        .dvr-video-item:hover .dvr-thumb-img {
            opacity: 1;
            transform: scale(1.1);
        }

        .dvr-thumb-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dvr-thumb-icon {
            width: 24px;
            height: 24px;
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
        }

        .dvr-video-item:hover .dvr-thumb-icon {
            color: #34d399;
            transform: scale(1.1);
        }

        .dvr-item-info {
            overflow: hidden;
            flex: 1;
        }

        .dvr-item-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #e5e7eb;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0;
            transition: color 0.2s;
        }

        .dvr-video-item:hover .dvr-item-info h4 { color: #10b981; }

        .dvr-item-meta {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .dvr-meta-icon { width: 12px; height: 12px; }

        /* Main Area Styles */
        .dvr-main {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .dvr-player-card {
            background: rgba(17, 24, 39, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            backdrop-filter: blur(12px);
        }

        .dvr-player-header {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dvr-player-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dvr-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.8);
        }

        .dvr-player-title h2 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .dvr-player-badges { display: flex; gap: 8px; }

        .dvr-badge {
            background: rgba(31, 41, 55, 1);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #d1d5db;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .dvr-video-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #050505;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .dvr-video-wrapper:hover .dvr-play-center button { opacity: 1; }
        .dvr-video-wrapper:hover .dvr-controls-overlay { opacity: 1; }

        .dvr-video-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.5;
        }

        .dvr-play-center {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dvr-play-btn {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.8);
            border: none;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
            cursor: pointer;
            transition: all 0.3s;
            opacity: 0.9;
        }

        .dvr-play-btn:hover {
            background: rgba(16, 185, 129, 1);
            transform: scale(1.1);
        }

        .dvr-play-icon { width: 40px; height: 40px; margin-left: 4px; }

        .dvr-controls-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: linear-gradient(to top, rgba(0,0,0,1), rgba(0,0,0,0.7), transparent);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .dvr-seek-container {
            width: 100%;
            height: 6px;
            position: relative;
            cursor: pointer;
        }

        .dvr-seek-container:hover .dvr-seek-handle { opacity: 1; }

        .dvr-seek-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(31, 41, 55, 1);
            border-radius: 9999px;
            overflow: hidden;
        }

        .dvr-seek-progress {
            position: absolute;
            top: 0; left: 0; bottom: 0;
            width: 33.33%;
            background: #10b981;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.8);
            border-radius: 9999px;
        }

        .dvr-seek-handle {
            position: absolute;
            top: 50%; left: 33.33%;
            width: 12px; height: 12px;
            background: #fff;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.5);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .dvr-controls-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }

        .dvr-controls-left, .dvr-controls-right {
            display: flex;
            align-items: center;
            gap: 24px;
            color: #fff;
        }

        .dvr-controls-right { gap: 16px; }

        .dvr-media-btns {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .dvr-media-btns button, .dvr-controls-right button {
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dvr-media-btns button svg, .dvr-controls-right button svg {
            width: 20px;
            height: 20px;
        }

        .dvr-media-btns button:hover, .dvr-controls-right button:hover {
            color: #34d399;
            transform: scale(1.1);
        }

        .dvr-timecode {
            font-size: 0.75rem;
            font-family: monospace;
            background: rgba(0,0,0,0.5);
            padding: 4px 8px;
            border-radius: 4px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .dvr-player-footer {
            padding: 16px 24px;
            background: rgba(0, 0, 0, 0.2);
        }

        .dvr-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .dvr-info-col {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .dvr-info-col.bordered {
            padding-left: 16px;
            border-left: 1px solid rgba(255,255,255,0.05);
        }

        .dvr-info-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #6b7280;
            letter-spacing: 0.05em;
        }

        .dvr-info-val {
            font-size: 0.875rem;
            font-weight: 600;
            color: #e5e7eb;
        }

        .flex-center { display: flex; align-items: center; gap: 6px; }
        .text-danger { color: #ef4444; }
        .dvr-info-icon { width: 16px; height: 16px; color: #10b981; }
        .text-danger .dvr-info-icon { color: #ef4444; }

        /* Timeline Card */
        .dvr-timeline-card {
            background: rgba(17, 24, 39, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
            backdrop-filter: blur(12px);
        }

        .dvr-timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .dvr-timeline-header h3 {
            font-size: 0.875rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        .dvr-timeline-legend {
            display: flex;
            gap: 16px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #d1d5db;
        }

        .dvr-timeline-legend span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dvr-legend-dot {
            width: 8px; height: 8px; border-radius: 4px;
        }

        .dvr-legend-dot.primary { background: #10b981; }
        .dvr-legend-dot.danger { background: #ef4444; }

        .dvr-timeline-bar {
            height: 80px;
            background: rgba(3, 7, 18, 1);
            border-radius: 12px;
            position: relative;
            display: flex;
            align-items: flex-end;
            border: 1px solid rgba(31, 41, 55, 1);
            overflow: hidden;
        }

        .dvr-timeline-progress {
            position: absolute;
            inset: 0 0 0 0;
            width: 33.33%;
            background: rgba(16, 185, 129, 0.2);
            border-right: 1px solid #10b981;
            box-shadow: 2px 0 10px rgba(16, 185, 129, 0.2);
        }

        .dvr-motion-event {
            position: absolute;
            bottom: 24px;
            width: 4px;
            background: #ef4444;
            border-radius: 9999px;
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.8);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .dvr-motion-event:hover { transform: scaleX(2); }

        .dvr-timeline-playhead {
            position: absolute;
            top: 0; bottom: 0; left: 33.33%;
            width: 2px;
            background: #fff;
            box-shadow: 0 0 5px rgba(255,255,255,0.8);
            z-index: 10;
        }

        .dvr-playhead-cap {
            position: absolute;
            top: -4px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 12px; height: 12px;
            background: #fff;
        }

        .dvr-timeline-labels {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 12px 6px 12px;
            font-size: 10px;
            font-family: monospace;
            color: #6b7280;
            user-select: none;
            z-index: 0;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(75, 85, 99, 0.5);
            border-radius: 20px;
        }
    </style>
</x-filament-panels::page>
