<x-filament-panels::page>

    {{-- PAGE HEADER --}}
    <div class="dvr-header">
        <div class="dvr-header-left">
            <div class="dvr-header-icon-wrap">
                <x-filament::icon icon="heroicon-s-film" class="dvr-header-icon" />
            </div>
            <div>
                <h1 class="dvr-header-title">Sistem Rekaman <span class="dvr-title-accent">DVR</span></h1>
                <p class="dvr-header-subtitle">Akses arsip video &amp; playback rekaman CCTV</p>
            </div>
        </div>
        <div class="dvr-header-actions">
            <button class="dvr-btn dvr-btn-ghost">
                <x-filament::icon icon="heroicon-m-calendar-days" class="dvr-btn-icon" />
                <span>Pilih Tanggal</span>
            </button>
            <button class="dvr-btn dvr-btn-primary">
                <x-filament::icon icon="heroicon-m-arrow-down-tray" class="dvr-btn-icon" />
                <span>Ekspor</span>
            </button>
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="dvr-layout">

        {{-- SIDEBAR --}}
        <aside class="dvr-sidebar">
            <div class="dvr-sidebar-head">
                <x-filament::icon icon="heroicon-m-archive-box" class="dvr-sec-icon" />
                <h2 class="dvr-sec-title">Arsip Rekaman</h2>
            </div>

            <div class="dvr-search-wrap">
                <x-filament::icon icon="heroicon-m-magnifying-glass" class="dvr-search-icon" />
                <input type="text" placeholder="Cari area, gate, waktu..." class="dvr-search-input" />
            </div>

            <div class="dvr-pills">
                <button class="dvr-pill dvr-pill--active">Hari Ini</button>
                <button class="dvr-pill">Kemarin</button>
                <button class="dvr-pill">Minggu Ini</button>
            </div>

            <div class="dvr-video-list custom-scrollbar">
                @php
                    $cameras = [
                        ['name' => 'Kamera Gate 1',      'area' => 'Gate Utama',       'dur' => '2j 15m', 'offset' => 0, 'motion' => 3],
                        ['name' => 'Kamera Gate 2',      'area' => 'Parkir Timur',     'dur' => '1j 58m', 'offset' => 1, 'motion' => 0],
                        ['name' => 'Kamera Loket A',     'area' => 'Loket Tiket',      'dur' => '3j 02m', 'offset' => 0, 'motion' => 7],
                        ['name' => 'Kamera Loket B',     'area' => 'Loket Tiket',      'dur' => '2j 45m', 'offset' => 1, 'motion' => 2],
                        ['name' => 'Kamera Jalur Masuk', 'area' => 'Jalur Masuk',      'dur' => '2j 30m', 'offset' => 2, 'motion' => 0],
                        ['name' => 'Kamera Area Wisata', 'area' => 'Taman Utama',      'dur' => '3j 10m', 'offset' => 0, 'motion' => 5],
                        ['name' => 'Kamera Parkir',      'area' => 'Parkir Barat',     'dur' => '1j 22m', 'offset' => 3, 'motion' => 1],
                        ['name' => 'Kamera Kantor',      'area' => 'Kantor Pengelola', 'dur' => '4j 00m', 'offset' => 0, 'motion' => 0],
                    ];
                    $cctvImages = [
                        asset('images/cctv/cctv_gerbang_1786524324305.jpg'),
                        asset('images/cctv/cctv_parking_lot.jpg'),
                        asset('images/cctv/cctv_resepsionis_1786524352663.jpg'),
                        asset('images/cctv/cctv_resepsionis_1786524352663.jpg'),
                        asset('images/cctv/cctv_gerbang_1786524324305.jpg'),
                        asset('images/cctv/cctv_glamping_1786524341566.jpg'),
                        asset('images/cctv/cctv_parking_lot.jpg'),
                        asset('images/cctv/cctv_camping_b.jpg'),
                    ];
                @endphp

                @foreach($cameras as $idx => $cam)
                    <button class="dvr-video-item {{ $idx === 0 ? 'dvr-video-item--active' : '' }}">
                        <div class="dvr-thumb">
                            <img src="{{ $cctvImages[$idx % count($cctvImages)] }}"
                                 alt="{{ $cam['name'] }}" class="dvr-thumb-img" />
                            <div class="dvr-thumb-overlay">
                                <x-filament::icon icon="heroicon-s-play-circle" class="dvr-thumb-play" />
                            </div>
                        </div>
                        <div class="dvr-item-meta">
                            <h4 class="dvr-item-name">{{ $cam['name'] }}</h4>
                            <div class="dvr-item-detail">
                                <x-filament::icon icon="heroicon-m-map-pin" class="dvr-meta-icon" />
                                <span>{{ $cam['area'] }}</span>
                            </div>
                            <div class="dvr-item-footer">
                                <div class="dvr-item-detail">
                                    <x-filament::icon icon="heroicon-m-clock" class="dvr-meta-icon" />
                                    <span>{{ now()->subDays($cam['offset'])->format('d/m/Y') }} · {{ $cam['dur'] }}</span>
                                </div>
                                @if($cam['motion'] > 0)
                                    <span class="dvr-motion-badge">
                                        <x-filament::icon icon="heroicon-m-bolt" class="dvr-badge-icon" />
                                        {{ $cam['motion'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </aside>

        {{-- MAIN AREA --}}
        <div class="dvr-main">

            {{-- Player Card --}}
            <div class="dvr-player-card">
                <div class="dvr-player-topbar">
                    <div class="dvr-player-info">
                        <div class="dvr-rec-dot"></div>
                        <span class="dvr-player-name">Rekaman Gate 1 – Shift Malam</span>
                    </div>
                    <div class="dvr-player-badges">
                        <span class="dvr-badge">1080p</span>
                        <span class="dvr-badge">H.265</span>
                        <span class="dvr-badge dvr-badge--green">ARSIP</span>
                    </div>
                </div>

                <div class="dvr-video-wrap" id="dvr-video-wrap">
                    <img src="{{ asset('images/cctv/cctv_gerbang_1786524324305.jpg') }}"
                         class="dvr-video-bg" alt="Preview rekaman" id="dvr-main-video" />
                    <div class="dvr-vignette"></div>
                    <div class="dvr-osd-tl">
                        <x-filament::icon icon="heroicon-m-camera" class="dvr-osd-icon" />
                        <span>Kamera Gate 1</span>
                    </div>
                    <div class="dvr-osd-tr">{{ now()->subDay()->format('d/m/Y H:i:s') }}</div>
                    <div class="dvr-play-wrap" id="dvr-big-play-wrap">
                        <button class="dvr-play-btn">
                            <div class="dvr-play-ripple"></div>
                            <x-filament::icon icon="heroicon-s-play" class="dvr-play-icon" />
                        </button>
                    </div>
                    <div class="dvr-controls" id="dvr-controls">
                        <div class="dvr-seek" id="dvr-seek-container">
                            <div class="dvr-seek-track">
                                <div class="dvr-seek-fill" id="dvr-seek-fill"></div>
                                <div class="dvr-seek-thumb" id="dvr-seek-thumb"></div>
                            </div>
                        </div>
                        <div class="dvr-ctrl-row">
                            <div class="dvr-ctrl-left">
                                <button class="dvr-ctrl-btn">
                                    <x-filament::icon icon="heroicon-s-backward" class="dvr-ctrl-icon" />
                                </button>
                                <button class="dvr-ctrl-btn" id="dvr-ctrl-play">
                                    <x-filament::icon icon="heroicon-s-pause" class="dvr-ctrl-icon dvr-ctrl-icon--lg" id="icon-pause" />
                                    <x-filament::icon icon="heroicon-s-play" class="dvr-ctrl-icon dvr-ctrl-icon--lg" id="icon-play" style="display: none;" />
                                </button>
                                <button class="dvr-ctrl-btn">
                                    <x-filament::icon icon="heroicon-s-forward" class="dvr-ctrl-icon" />
                                </button>
                                <code class="dvr-timecode">00:25:10 / 01:23:45</code>
                            </div>
                            <div class="dvr-ctrl-right">
                                <button class="dvr-ctrl-btn dvr-speed-btn" id="dvr-speed">1x</button>
                                <button class="dvr-ctrl-btn">
                                    <x-filament::icon icon="heroicon-s-speaker-wave" class="dvr-ctrl-icon" />
                                </button>
                                <button class="dvr-ctrl-btn">
                                    <x-filament::icon icon="heroicon-s-arrows-pointing-out" class="dvr-ctrl-icon" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dvr-player-footer">
                    <div class="dvr-info-grid">
                        <div class="dvr-info-item">
                            <span class="dvr-info-label">Tanggal &amp; Waktu</span>
                            <span class="dvr-info-val">{{ now()->subDay()->format('d M Y') }} · 18:00–06:00</span>
                        </div>
                        <div class="dvr-info-item dvr-info-item--border">
                            <span class="dvr-info-label">Sumber Perangkat</span>
                            <span class="dvr-info-val dvr-flex-center">
                                <x-filament::icon icon="heroicon-s-server" class="dvr-info-icon" />
                                DVR Induk 01
                            </span>
                        </div>
                        <div class="dvr-info-item dvr-info-item--border">
                            <span class="dvr-info-label">Deteksi Gerak</span>
                            <span class="dvr-info-val dvr-flex-center dvr-text-danger">
                                <x-filament::icon icon="heroicon-s-bolt" class="dvr-info-icon dvr-icon-danger" />
                                14 Kejadian
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline Card --}}
            <div class="dvr-timeline-card">
                <div class="dvr-tl-header">
                    <div class="dvr-tl-title-wrap">
                        <x-filament::icon icon="heroicon-m-chart-bar-square" class="dvr-sec-icon" />
                        <h3 class="dvr-tl-title">Smart Timeline Analysis</h3>
                    </div>
                    <div class="dvr-tl-legend">
                        <span class="dvr-legend-item"><i class="dvr-legend-dot dvr-legend-dot--green"></i>Normal</span>
                        <span class="dvr-legend-item"><i class="dvr-legend-dot dvr-legend-dot--red"></i>Gerak</span>
                    </div>
                </div>

                <div class="dvr-tl-bar">
                    <div class="dvr-tl-progress"></div>
                    @foreach([15, 22, 30, 47, 60, 75] as $pos)
                        <div class="dvr-tl-spike" style="left: {{ $pos }}%;"></div>
                    @endforeach
                    <div class="dvr-tl-playhead">
                        <div class="dvr-tl-playhead-cap"></div>
                        <div class="dvr-tl-playhead-time">18:25</div>
                    </div>
                    <div class="dvr-tl-labels">
                        @foreach(['18:00','20:00','22:00','00:00','02:00','04:00','06:00'] as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="dvr-motion-list">
                    <h4 class="dvr-motion-list-title">Kejadian Terdeteksi</h4>
                    <div class="dvr-motion-events">
                        @foreach([
                            ['time' => '19:30', 'desc' => 'Gerakan di area pintu masuk'],
                            ['time' => '20:45', 'desc' => 'Orang terdeteksi zona merah'],
                            ['time' => '22:15', 'desc' => 'Kendaraan melintas gate'],
                            ['time' => '23:50', 'desc' => 'Aktivitas di area loket'],
                        ] as $event)
                            <button class="dvr-event-item">
                                <div class="dvr-event-dot"></div>
                                <div class="dvr-event-body">
                                    <span class="dvr-event-time">{{ $event['time'] }}</span>
                                    <span class="dvr-event-desc">{{ $event['desc'] }}</span>
                                </div>
                                <x-filament::icon icon="heroicon-m-play" class="dvr-event-play" />
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        /* ── HEADER ── */
        .dvr-header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
        .dvr-header-left { display:flex; align-items:center; gap:14px; min-width:0; }
        .dvr-header-icon-wrap { flex-shrink:0; width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg,rgba(16,185,129,.2),rgba(16,185,129,.05)); border:1px solid rgba(16,185,129,.3); display:flex; align-items:center; justify-content:center; box-shadow:0 0 20px rgba(16,185,129,.15); }
        .dvr-header-icon { width:24px; height:24px; color:#10b981; }
        .dvr-header-title { font-size:1.35rem; font-weight:800; color:#f9fafb; margin:0; letter-spacing:-.02em; white-space:nowrap; }
        .dvr-title-accent { color:#10b981; }
        .dvr-header-subtitle { font-size:.8rem; color:#94a3b8; margin:4px 0 0; }
        .dvr-header-actions { display:flex; gap:10px; flex-shrink:0; width: 100%; }
        @media(min-width:640px) { .dvr-header-actions { width: auto; } }

        .dvr-btn { display:inline-flex; flex: 1; justify-content: center; align-items:center; gap:6px; padding:10px 16px; border-radius:12px; font-size:.85rem; font-weight:600; cursor:pointer; border:none; transition:all .2s; white-space:nowrap; }
        @media(min-width:640px) { .dvr-btn { flex: none; justify-content: flex-start; } }
        .dvr-btn-icon { width:16px; height:16px; }
        .dvr-btn-ghost { background:rgba(31,41,55,.6); color:#d1d5db; border:1px solid rgba(255,255,255,.08); }
        .dvr-btn-ghost:hover { background:rgba(55,65,81,.8); color:#fff; }
        .dvr-btn-primary { background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 4px 12px rgba(16,185,129,.3); }
        .dvr-btn-primary:hover { background:linear-gradient(135deg,#34d399,#10b981); box-shadow:0 6px 20px rgba(16,185,129,.4); transform:translateY(-1px); }

        /* ── MAIN LAYOUT ── */
        .dvr-layout { display: flex; flex-direction: column-reverse; gap:20px; }
        @media(min-width:1024px) { .dvr-layout { display: grid; grid-template-columns: 320px 1fr; gap:24px; } }

        /* ── SIDEBAR (ARSIP) ── */
        .dvr-sidebar { background:rgba(10,15,25,.75); border:1px solid rgba(255,255,255,.08); border-radius:16px; padding:20px; display:flex; flex-direction:column; gap:16px; backdrop-filter:blur(16px); box-shadow:0 10px 30px rgba(0,0,0,.5); }
        @media(min-width:1024px) { .dvr-sidebar { height:780px; } }
        .dvr-sidebar-head { display:flex; align-items:center; gap:10px; }
        .dvr-sec-icon { width:20px; height:20px; color:#10b981; flex-shrink:0; }
        .dvr-sec-title { font-size:1rem; font-weight:700; color:#f8fafc; margin:0; }

        .dvr-search-wrap { position:relative; }
        .dvr-search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#6b7280; pointer-events:none; }
        .dvr-search-input { width:100%; padding:10px 12px 10px 36px; background:rgba(31,41,55,.6); border:1px solid rgba(255,255,255,.1); border-radius:10px; color:#f3f4f6; font-size:.85rem; outline:none; transition:border-color .2s; }
        .dvr-search-input::placeholder { color:#6b7280; }
        .dvr-search-input:focus { border-color:rgba(16,185,129,.5); box-shadow: 0 0 0 2px rgba(16,185,129,.1); }

        .dvr-pills { display:flex; gap:8px; overflow-x:auto; padding-bottom: 4px; }
        .dvr-pills::-webkit-scrollbar { display:none; }
        .dvr-pill { padding:6px 14px; border-radius:999px; font-size:.75rem; font-weight:600; background:rgba(31,41,55,.8); color:#9ca3af; border:1px solid transparent; cursor:pointer; white-space:nowrap; transition:all .2s; }
        .dvr-pill:hover { color:#f3f4f6; border-color:rgba(255,255,255,.15); }
        .dvr-pill--active { background:rgba(16,185,129,.15); color:#34d399; border-color:rgba(16,185,129,.4); }

        /* Video List changed to vertical on mobile for much better UX */
        .dvr-video-list { display:grid; grid-template-columns: 1fr; gap:12px; max-height: 400px; overflow-y:auto; padding-right: 4px; }
        @media(min-width:640px) { .dvr-video-list { grid-template-columns: 1fr 1fr; } }
        @media(min-width:1024px) { .dvr-video-list { display:flex; flex-direction:column; max-height:none; flex:1; } }

        .dvr-video-item { width:100%; text-align:left; padding:12px; border-radius:14px; background:rgba(17,24,39,.6); border:1px solid transparent; display:flex; gap:12px; align-items:center; cursor:pointer; transition:all .2s; box-shadow: 0 4px 6px rgba(0,0,0,.2); }
        .dvr-video-item:hover { background:rgba(31,41,55,.9); border-color:rgba(16,185,129,.3); transform: translateY(-1px); }
        .dvr-video-item--active { background:rgba(16,185,129,.1); border-color:rgba(16,185,129,.5); box-shadow:0 0 20px rgba(16,185,129,.1); }

        .dvr-thumb { position:relative; width:80px; height:56px; border-radius:10px; overflow:hidden; flex-shrink:0; background:#000; border:1px solid rgba(255,255,255,.1); }
        .dvr-thumb-img { width:100%; height:100%; object-fit:cover; opacity:.6; transition:opacity .3s,transform .4s; }
        .dvr-video-item:hover .dvr-thumb-img { opacity:1; transform:scale(1.1); }
        .dvr-thumb-overlay { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; }
        .dvr-thumb-play { width:24px; height:24px; color:rgba(255,255,255,.8); transition:color .2s,transform .2s; filter:drop-shadow(0 2px 4px rgba(0,0,0,.8)); }
        .dvr-video-item:hover .dvr-thumb-play { color:#34d399; transform:scale(1.2); }
        .dvr-video-item--active .dvr-thumb-play { color:#10b981; }

        .dvr-item-meta { overflow:hidden; flex:1; min-width:0; }
        .dvr-item-name { font-size:.85rem; font-weight:700; color:#f3f4f6; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 4px; transition:color .2s; }
        .dvr-video-item:hover .dvr-item-name { color:#34d399; }
        .dvr-video-item--active .dvr-item-name { color:#10b981; }
        .dvr-item-detail { display:flex; align-items:center; gap:5px; font-size:.7rem; color:#94a3b8; }
        .dvr-meta-icon { width:12px; height:12px; flex-shrink:0; }
        .dvr-item-footer { display:flex; align-items:center; justify-content: space-between; margin-top:4px; }
        .dvr-motion-badge { display:inline-flex; align-items:center; gap:3px; padding:2px 8px; background:rgba(239,68,68,.15); border:1px solid rgba(239,68,68,.3); border-radius:999px; font-size:.65rem; font-weight:700; color:#fca5a5; }
        .dvr-badge-icon { width:10px; height:10px; }

        /* ── MAIN AREA ── */
        .dvr-main { display:flex; flex-direction:column; gap:24px; min-width:0; }

        /* ── PLAYER CARD ── */
        .dvr-player-card { background:rgba(10,15,25,.75); border:1px solid rgba(255,255,255,.08); border-radius:16px; overflow:hidden; backdrop-filter:blur(16px); box-shadow:0 10px 40px rgba(0,0,0,.6); }
        .dvr-player-topbar { padding:14px 20px; display:flex; align-items:center; justify-content:space-between; background:rgba(0,0,0,.4); border-bottom:1px solid rgba(255,255,255,.08); gap:12px; flex-wrap:wrap; }
        .dvr-player-info { display:flex; align-items:center; gap:12px; }
        .dvr-rec-dot { width:10px; height:10px; border-radius:50%; background:#10b981; box-shadow:0 0 10px rgba(16,185,129,1); flex-shrink:0; animation: pulse-rec 2s infinite; }
        @keyframes pulse-rec { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
        .dvr-player-name { font-size:1rem; font-weight:800; color:#f8fafc; }
        .dvr-player-badges { display:flex; gap:8px; flex-wrap:wrap; }
        .dvr-badge { padding:4px 10px; border-radius:6px; font-size:.7rem; font-weight:700; background:rgba(31,41,55,.9); color:#cbd5e1; border:1px solid rgba(255,255,255,.1); }
        .dvr-badge--green { background:rgba(16,185,129,.15); color:#34d399; border-color:rgba(16,185,129,.3); }

        .dvr-video-wrap { position:relative; width:100%; aspect-ratio:16/9; background:#030712; overflow:hidden; cursor:pointer; }
        .dvr-video-bg { width:100%; height:100%; object-fit:cover; opacity:.6; transition:opacity .3s; }
        .dvr-video-wrap:hover .dvr-video-bg { opacity:.8; }
        .dvr-vignette { position:absolute; inset:0; background:radial-gradient(circle, transparent 60%, rgba(0,0,0,0.8) 100%); pointer-events:none; z-index:1; }
        
        .dvr-osd-tl,.dvr-osd-tr { position:absolute; font-size:.7rem; font-family:monospace; font-weight:700; color:rgba(255,255,255,.8); background:rgba(0,0,0,.5); padding:4px 10px; border-radius:6px; backdrop-filter:blur(8px); display:flex; align-items:center; gap:6px; z-index: 2; border: 1px solid rgba(255,255,255,0.1); text-shadow: 0 1px 2px rgba(0,0,0,0.8); }
        .dvr-osd-tl { top:16px; left:16px; }
        .dvr-osd-tr { top:16px; right:16px; }
        .dvr-osd-icon { width:12px; height:12px; color:#10b981; }

        .dvr-play-wrap { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:3; transition: opacity 0.3s, visibility 0.3s; }
        .dvr-play-wrap.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        .dvr-play-btn { position:relative; width:80px; height:80px; border-radius:50%; background:rgba(16,185,129,.9); border:3px solid rgba(255,255,255,.3); color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .3s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow:0 0 50px rgba(16,185,129,.5),0 10px 30px rgba(0,0,0,.5); backdrop-filter:blur(8px); }
        .dvr-play-btn:hover { background:rgba(16,185,129,1); transform:scale(1.15); box-shadow:0 0 70px rgba(16,185,129,.7); border-color:rgba(255,255,255,.5); }
        .dvr-play-icon { width:38px; height:38px; margin-left:4px; z-index: 2; }
        .dvr-play-ripple { position:absolute; inset:-10px; border-radius:50%; border:2px solid #10b981; animation: play-ripple 2s linear infinite; opacity:0; z-index:1; pointer-events:none; }
        @keyframes play-ripple { 0% { transform:scale(0.8); opacity:1; } 100% { transform:scale(1.8); opacity:0; } }

        .dvr-controls { position:absolute; bottom:0; left:0; right:0; padding:16px 20px; background:linear-gradient(to top,rgba(0,0,0,.95) 0%,rgba(0,0,0,.7) 50%,transparent 100%); opacity:0; transition:opacity .3s; display:flex; flex-direction:column; gap:12px; z-index:4; }
        .dvr-video-wrap:hover .dvr-controls { opacity:1; }

        .dvr-seek { width:100%; cursor:pointer; padding:8px 0; touch-action: none; }
        .dvr-seek-track { position:relative; height:6px; background:rgba(255,255,255,.2); border-radius:999px; }
        .dvr-seek-fill { position:absolute; top:0; left:0; bottom:0; width:33.33%; background:linear-gradient(90deg,#10b981,#34d399); border-radius:999px; box-shadow:0 0 12px rgba(16,185,129,.8); transition: width 0.1s; }
        .dvr-seek-thumb { position:absolute; top:50%; left:33.33%; width:16px; height:16px; background:#fff; border-radius:50%; transform:translate(-50%,-50%); box-shadow:0 2px 8px rgba(0,0,0,.6); transition:transform .2s, left 0.1s; pointer-events:none; }
        .dvr-seek:hover .dvr-seek-thumb { transform:translate(-50%,-50%) scale(1.4); }

        .dvr-ctrl-row { display:flex; justify-content:space-between; align-items:center; flex-wrap: wrap; gap: 10px; }
        .dvr-ctrl-left,.dvr-ctrl-right { display:flex; align-items:center; gap:14px; }
        .dvr-ctrl-btn { background:transparent; border:none; color:rgba(255,255,255,.8); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; padding:6px; border-radius:8px; }
        .dvr-ctrl-btn:hover { color:#34d399; background:rgba(255,255,255,.1); }
        .dvr-ctrl-icon { width:20px; height:20px; }
        .dvr-ctrl-icon--lg { width:26px; height:26px; color: #fff; }
        .dvr-speed-btn { font-size:.8rem; font-weight:700; font-family:monospace; padding:4px 10px; border-radius:8px; background:rgba(31,41,55,.8); border:1px solid rgba(255,255,255,.15); color:#e5e7eb; }
        .dvr-timecode { font-size:.75rem; font-weight:600; font-family:monospace; color:rgba(255,255,255,.9); background:rgba(0,0,0,.5); padding:4px 10px; border-radius:6px; border:1px solid rgba(255,255,255,.1); }

        .dvr-player-footer { padding:16px 20px; background:rgba(0,0,0,.25); border-top:1px solid rgba(255,255,255,.08); }
        .dvr-info-grid { display:grid; grid-template-columns:1fr; gap:16px; }
        @media(min-width:640px) { .dvr-info-grid { grid-template-columns:repeat(3,1fr); } }
        .dvr-info-item { display:flex; flex-direction:column; gap:4px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        @media(min-width:640px) { 
            .dvr-info-item { padding-bottom: 0; border-bottom: none; }
            .dvr-info-item--border { padding-left:16px; border-left:1px solid rgba(255,255,255,.08); }
        }
        .dvr-info-label { font-size:.65rem; text-transform:uppercase; font-weight:800; color:#6b7280; letter-spacing:.08em; }
        .dvr-info-val { font-size:.85rem; font-weight:700; color:#f3f4f6; }
        .dvr-flex-center { display:flex; align-items:center; gap:6px; }
        .dvr-text-danger { color:#fca5a5; }
        .dvr-info-icon { width:16px; height:16px; color:#10b981; flex-shrink:0; }
        .dvr-icon-danger { color:#ef4444; }

        /* ── TIMELINE CARD ── */
        .dvr-timeline-card { background:rgba(10,15,25,.75); border:1px solid rgba(255,255,255,.08); border-radius:16px; padding:20px; backdrop-filter:blur(16px); box-shadow:0 10px 40px rgba(0,0,0,.4); }
        .dvr-tl-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; gap:10px; flex-wrap:wrap; }
        .dvr-tl-title-wrap { display:flex; align-items:center; gap:10px; }
        .dvr-tl-title { font-size:.9rem; font-weight:800; color:#f8fafc; text-transform:uppercase; letter-spacing:.06em; margin:0; }
        .dvr-tl-legend { display:flex; gap:16px; }
        .dvr-legend-item { display:flex; align-items:center; gap:6px; font-size:.7rem; font-weight:600; color:#9ca3af; text-transform: uppercase; }
        .dvr-legend-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
        .dvr-legend-dot--green { background:#10b981; box-shadow:0 0 8px rgba(16,185,129,.6); }
        .dvr-legend-dot--red   { background:#ef4444; box-shadow:0 0 8px rgba(239,68,68,.6); }

        .dvr-tl-bar { height:72px; background:rgba(0,0,0,.5); border-radius:12px; position:relative; display:flex; align-items:flex-end; border:1px solid rgba(255,255,255,.08); overflow:hidden; cursor:pointer; }
        .dvr-tl-progress { position:absolute; inset:0; width:33.33%; background:rgba(16,185,129,.2); border-right:2px solid #10b981; box-shadow:2px 0 20px rgba(16,185,129,.3); }
        .dvr-tl-spike { position:absolute; bottom:24px; width:4px; height:45%; background:#ef4444; border-radius:999px; box-shadow:0 0 8px rgba(239,68,68,.8); cursor:pointer; transition:height .2s,box-shadow .2s; }
        .dvr-tl-spike:hover { height:75%; box-shadow:0 0 16px rgba(239,68,68,1); background: #fca5a5; }
        .dvr-tl-playhead { position:absolute; top:0; bottom:0; left:33.33%; width:2px; background:#fff; box-shadow:0 0 12px rgba(255,255,255,1); z-index:10; }
        .dvr-tl-playhead-cap { position:absolute; top:-6px; left:50%; transform:translateX(-50%) rotate(45deg); width:12px; height:12px; background:#fff; border-radius:2px;}
        .dvr-tl-playhead-time { position:absolute; top:-26px; left:50%; transform:translateX(-50%); font-size:.65rem; font-family:monospace; font-weight:800; color:#0f172a; background:#fff; padding:2px 6px; border-radius:4px; white-space:nowrap; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .dvr-tl-labels { position:absolute; bottom:0; left:0; right:0; display:flex; justify-content:space-between; padding:0 12px 6px; font-size:.6rem; font-weight: 600; font-family:monospace; color:#6b7280; pointer-events:none; }

        .dvr-motion-list { margin-top:20px; }
        .dvr-motion-list-title { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#6b7280; margin:0 0 12px; }
        .dvr-motion-events { display:grid; grid-template-columns:1fr; gap:10px; }
        @media(min-width:640px) { .dvr-motion-events { grid-template-columns:repeat(2,1fr); } }
        @media(min-width:1024px) { .dvr-motion-events { grid-template-columns:repeat(4,1fr); } }
        
        .dvr-event-item { display:flex; align-items:center; gap:10px; padding:12px 14px; border-radius:12px; background:rgba(17,24,39,.6); border:1px solid rgba(255,255,255,.06); cursor:pointer; transition:all .2s; text-align:left; box-shadow: 0 4px 6px rgba(0,0,0,.15); }
        .dvr-event-item:hover { background:rgba(239,68,68,.1); border-color:rgba(239,68,68,.3); transform:translateY(-1px); box-shadow: 0 6px 12px rgba(239,68,68,.15); }
        .dvr-event-dot { width:8px; height:8px; border-radius:50%; background:#ef4444; box-shadow:0 0 8px rgba(239,68,68,.8); flex-shrink:0; }
        .dvr-event-body { display:flex; flex-direction:column; gap:2px; overflow:hidden; flex:1; }
        .dvr-event-time { font-size:.8rem; font-weight:800; font-family:monospace; color:#f3f4f6; }
        .dvr-event-desc { font-size:.65rem; font-weight:500; color:#9ca3af; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .dvr-event-play { width:14px; height:14px; color:#4b5563; flex-shrink:0; transition:color .2s; }
        .dvr-event-item:hover .dvr-event-play { color:#fca5a5; }

        .custom-scrollbar::-webkit-scrollbar { width:4px; height:4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background:transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background:rgba(75,85,99,.6); border-radius:999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background:rgba(107,114,128,.8); }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const speeds = ['0.5x','1x','1.5x','2x'];
            let si = 1;
            const speedBtn = document.getElementById('dvr-speed');
            if (speedBtn) {
                speedBtn.addEventListener('click', () => {
                    si = (si + 1) % speeds.length;
                    speedBtn.textContent = speeds[si];
                });
            }
            const mainVideo = document.getElementById('dvr-main-video');
            document.querySelectorAll('.dvr-video-item').forEach(item => {
                item.addEventListener('click', function () {
                    document.querySelectorAll('.dvr-video-item').forEach(i => i.classList.remove('dvr-video-item--active'));
                    this.classList.add('dvr-video-item--active');
                    // Ganti gambar player utama saat diklik
                    const thumbSrc = this.querySelector('.dvr-thumb-img').src;
                    if (mainVideo) mainVideo.src = thumbSrc;
                    
                    // Update judul video utama
                    const title = this.querySelector('.dvr-item-name').textContent;
                    document.querySelector('.dvr-player-name').textContent = title + ' - Arsip Rekaman';
                    document.querySelector('.dvr-osd-tl span').textContent = title;
                    
                    // Reset play state
                    if (isPlaying) togglePlay();
                });
            });
            document.querySelectorAll('.dvr-pill').forEach(pill => {
                pill.addEventListener('click', function () {
                    document.querySelectorAll('.dvr-pill').forEach(p => p.classList.remove('dvr-pill--active'));
                    this.classList.add('dvr-pill--active');
                });
            });

            // INTERACTIVE SEEK BAR LOGIC
            const seekContainer = document.getElementById('dvr-seek-container');
            const seekFill = document.getElementById('dvr-seek-fill');
            const seekThumb = document.getElementById('dvr-seek-thumb');
            let isDragging = false;

            function updateSeekProgress(e) {
                const rect = seekContainer.getBoundingClientRect();
                let clientX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
                let percent = (clientX - rect.left) / rect.width;
                percent = Math.max(0, Math.min(1, percent));
                const percentStr = (percent * 100) + '%';
                
                seekFill.style.width = percentStr;
                seekThumb.style.left = percentStr;
                
                // Matikan transisi saat di-drag agar instan (tidak ada delay animasi)
                seekFill.style.transition = isDragging ? 'none' : '';
                seekThumb.style.transition = isDragging ? 'none' : '';
            }

            if (seekContainer) {
                // Click
                seekContainer.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    updateSeekProgress(e);
                });
                seekContainer.addEventListener('touchstart', (e) => {
                    isDragging = true;
                    updateSeekProgress(e);
                });

                // Drag
                window.addEventListener('mousemove', (e) => {
                    if (isDragging) updateSeekProgress(e);
                });
                window.addEventListener('touchmove', (e) => {
                    if (isDragging) updateSeekProgress(e);
                });

                // Stop Drag
                window.addEventListener('mouseup', () => { isDragging = false; });
                window.addEventListener('touchend', () => { isDragging = false; });
            }

            // PLAY / PAUSE LOGIC
            let isPlaying = false;
            const bigPlayWrap = document.getElementById('dvr-big-play-wrap');
            const ctrlPlayBtn = document.getElementById('dvr-ctrl-play');
            const iconPlay = document.getElementById('icon-play');
            const iconPause = document.getElementById('icon-pause');

            function togglePlay() {
                isPlaying = !isPlaying;
                if (isPlaying) {
                    bigPlayWrap.classList.add('hidden');
                    iconPlay.style.display = 'none';
                    iconPause.style.display = 'block';
                } else {
                    bigPlayWrap.classList.remove('hidden');
                    iconPlay.style.display = 'block';
                    iconPause.style.display = 'none';
                }
            }

            if (bigPlayWrap) {
                bigPlayWrap.querySelector('.dvr-play-btn').addEventListener('click', togglePlay);
            }
            if (ctrlPlayBtn) {
                ctrlPlayBtn.addEventListener('click', togglePlay);
            }
        });
    </script>

</x-filament-panels::page>