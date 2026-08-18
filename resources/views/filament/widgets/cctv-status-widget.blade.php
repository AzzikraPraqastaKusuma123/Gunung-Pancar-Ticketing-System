<x-filament-widgets::widget>
    <style>
        .stat-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:0.625rem; }
        .stat-card {
            border-radius:0.75rem; padding:0.875rem 1rem;
            position:relative; overflow:hidden;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }
        .dark .stat-card { background: rgba(17,24,39,0.6); border-color: rgba(255,255,255,0.08); }

        .stat-card-green  { border-left: 3px solid #10b981; }
        .stat-card-blue   { border-left: 3px solid #3b82f6; }

        .stat-label {
            font-size:0.65rem; font-weight:600; color:#6b7280;
            text-transform:uppercase; letter-spacing:0.06em;
            margin:0 0 0.375rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .stat-value {
            font-size:1.25rem; font-weight:800; color:#111827; margin:0 0 0.375rem; line-height:1;
        }
        .dark .stat-value { color:#f9fafb; }

        .stat-desc {
            font-size:0.65rem; margin:0;
            display:flex; align-items:center; gap:0.3rem;
            white-space:nowrap; overflow:hidden;
        }
        .stat-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
        @keyframes stat-ping {
            0%,100% { box-shadow:0 0 0 0 currentColor; opacity:1; }
            50% { box-shadow:0 0 0 3px transparent; opacity:0.6; }
        }
        @media (max-width:380px) { .stat-value { font-size:1rem; } .stat-label { font-size:0.6rem; } }
    </style>

    <div class="stat-grid">
        <div class="stat-card stat-card-green">
            <p class="stat-label">Kamera Aktif</p>
            <p class="stat-value">24/24</p>
            <p class="stat-desc" style="color:#10b981;">
                <span class="stat-dot" style="background:#10b981;"></span>Termonitor
            </p>
        </div>
        <div class="stat-card stat-card-blue">
            <p class="stat-label">DVR</p>
            <p class="stat-value" style="color:#3b82f6;">78%</p>
            <p class="stat-desc" style="color:#6b7280;">4.2 / 20 TB</p>
            <div style="position:absolute;bottom:0;left:0;right:0;height:3px;background:rgba(59,130,246,0.15);">
                <div style="height:100%;width:78%;background:#3b82f6;"></div>
            </div>
        </div>
        <div class="stat-card stat-card-green">
            <p class="stat-label">Keamanan</p>
            <p class="stat-value">Normal</p>
            <p class="stat-desc" style="color:#10b981;">
                <span class="stat-dot" style="background:#10b981;"></span>Aman
            </p>
        </div>
    </div>
</x-filament-widgets::widget>
