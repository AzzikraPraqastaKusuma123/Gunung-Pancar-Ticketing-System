<x-filament-widgets::widget>
    @php
        function getDashboardCctvImage($name) {
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
            $fallbacks = [
                asset('images/cctv/cctv_glamping_1786524341566.jpg'),
                asset('images/cctv/cctv_camping_b.jpg'),
                asset('images/cctv/cctv_parking_lot.jpg'),
                asset('images/cctv/cctv_gerbang_1786524324305.jpg'),
            ];
            return $fallbacks[crc32($name) % count($fallbacks)];
        }
        $devices = $this->getDevices();
    @endphp

    <style>
        .lf-mini-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.625rem;
        }
        @media (min-width: 1280px) {
            .lf-mini-grid { grid-template-columns: repeat(4, 1fr); }
        }
        .lf-mini-card {
            position: relative;
            background: #000;
            border-radius: 0.625rem;
            overflow: hidden;
            aspect-ratio: 16/9;
            cursor: pointer;
            border: 1px solid rgba(255,255,255,0.06);
            transition: border-color 0.2s, transform 0.15s;
        }
        .lf-mini-card:hover { border-color: rgba(255,255,255,0.2); transform: translateY(-1px); }
        .lf-mini-card img { width:100%; height:100%; object-fit:cover; transition:transform 0.4s; pointer-events:none; display:block; }
        .lf-mini-card:hover img { transform: scale(1.04); }
        .lf-top-bar {
            position:absolute; top:0; left:0; right:0;
            background: linear-gradient(180deg, rgba(0,0,0,0.65) 0%, transparent 100%);
            padding: 0.5rem 0.625rem;
            display:flex; align-items:center; justify-content:space-between;
        }
        .lf-bot-bar {
            position:absolute; bottom:0; left:0; right:0;
            background: linear-gradient(0deg, rgba(0,0,0,0.65) 0%, transparent 100%);
            padding: 0.5rem 0.625rem;
            display:flex; align-items:center; justify-content:space-between;
        }
        .lf-expand-icon {
            position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
            width:2.25rem; height:2.25rem;
            background:rgba(0,0,0,0.55); border:1px solid rgba(255,255,255,0.15); border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            opacity:0; transition:opacity 0.2s; pointer-events:none;
        }
        .lf-mini-card:hover .lf-expand-icon { opacity:1; }
        .lf-rec-pill { display:flex; align-items:center; gap:0.3rem; background:rgba(0,0,0,0.55); border-radius:9999px; padding:0.2rem 0.45rem; }
        .lf-rec-dot { width:5px; height:5px; background:#ef4444; border-radius:50%; animation:lf-blink 1.8s infinite; }
        .lf-rec-txt { font-size:0.6rem; font-weight:700; color:#ef4444; letter-spacing:0.04em; }
        @keyframes lf-blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

        /* Fullscreen overlay */
        .lf-fs-overlay {
            position: fixed; inset: 0; z-index: 99999;
            background: #000;
            display: flex; flex-direction: column;
        }
        .lf-fs-bar {
            flex-shrink: 0;
            display:flex; align-items:center; justify-content:space-between;
            padding: 0.625rem 1.25rem;
            background: rgba(10,10,10,0.95);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .lf-fs-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 2px;
            overflow: hidden;
        }
        .lf-fs-cell { position:relative; overflow:hidden; background:#080808; }
        .lf-fs-cell img { width:100%; height:100%; object-fit:cover; display:block; }
        .lf-fs-cell-top {
            position:absolute; top:0; left:0; right:0;
            background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, transparent 100%);
            padding: 0.875rem 1rem;
            display:flex; align-items:center; justify-content:space-between;
        }
        .lf-fs-cell-bot {
            position:absolute; bottom:0; left:0; right:0;
            background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, transparent 100%);
            padding: 0.875rem 1rem;
            display:flex; align-items:center; justify-content:space-between;
        }
    </style>

    {{-- Use Alpine.js x-data for click handling (works properly with Livewire) --}}
    <div x-data="{ open: false }" x-on:keydown.escape.window="open = false">

        {{-- FULLSCREEN COLLAGE OVERLAY --}}
        <div x-show="open" x-cloak class="lf-fs-overlay" wire:ignore>
            <div class="lf-fs-bar">
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <span class="lf-rec-dot" style="width:7px; height:7px;"></span>
                        <span style="font-size:0.875rem; font-weight:700; color:#f9fafb;">CCTV Live Wall — Gunung Pancar</span>
                    </div>
                    <span style="font-size:0.75rem; color:#4b5563;" x-text="new Date().toLocaleString('id-ID')"></span>
                </div>
                <button x-on:click="open = false"
                    style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1); border-radius:0.5rem; padding:0.4rem 1rem; color:#d1d5db; font-size:0.8rem; font-weight:500; cursor:pointer;">
                    ✕ Tutup
                </button>
            </div>

            <div class="lf-fs-grid">
                @foreach($devices as $cctv)
                    <div class="lf-fs-cell">
                        <img src="{{ getDashboardCctvImage($cctv->name) }}" alt="{{ $cctv->name }}" />
                        <div class="lf-fs-cell-top">
                            <span style="font-size:0.95rem; font-weight:600; color:#fff; text-shadow:0 1px 4px rgba(0,0,0,0.9);">{{ $cctv->name }}</span>
                            <div class="lf-rec-pill">
                                <span class="lf-rec-dot"></span>
                                <span class="lf-rec-txt">REC</span>
                            </div>
                        </div>
                        <div class="lf-fs-cell-bot">
                            <span style="font-size:0.8rem; color:rgba(255,255,255,0.5);">CAM-{{ str_pad($cctv->id, 3, '0', STR_PAD_LEFT) }}</span>
                            <span style="font-size:0.8rem; color:rgba(255,255,255,0.5); font-family:monospace;" x-text="new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false})"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- MINI DASHBOARD GRID --}}
        <x-filament::section>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                <div>
                    <p style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em; margin:0;">LIVE FEEDS</p>
                    <h2 class="text-gray-900 dark:text-gray-100" style="font-size:1.125rem; font-weight:700; margin:0.25rem 0 0;">Tampilan Kamera</h2>
                </div>
                <button x-on:click="open = true" class="text-gray-600 border-gray-300 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:border-white/10 dark:bg-white/5 dark:hover:bg-white/10"
                    style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; border-width:1px; border-style:solid; border-radius:0.5rem; cursor:pointer; transition:all 0.2s;" title="Buka Full Matrix">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                </button>
            </div>

            <div class="lf-mini-grid">
                @foreach($devices as $cctv)
                    <div x-on:click="open = true" class="lf-mini-card">
                        <img src="{{ getDashboardCctvImage($cctv->name) }}" alt="{{ $cctv->name }}" />
                        <div class="lf-top-bar">
                            <span style="font-size:0.7rem; font-weight:600; color:#fff; text-shadow:0 1px 3px rgba(0,0,0,0.9);">{{ $cctv->name }}</span>
                            <div class="lf-rec-pill"><span class="lf-rec-dot"></span><span class="lf-rec-txt">REC</span></div>
                        </div>
                        <div class="lf-bot-bar">
                            <span style="font-size:0.65rem; color:rgba(255,255,255,0.45);">CAM-{{ str_pad($cctv->id, 3, '0', STR_PAD_LEFT) }}</span>
                            <span style="font-size:0.65rem; color:rgba(255,255,255,0.45); font-family:monospace;" x-text="new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false})"></span>
                        </div>
                        <div class="lf-expand-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>
</x-filament-widgets::widget>
