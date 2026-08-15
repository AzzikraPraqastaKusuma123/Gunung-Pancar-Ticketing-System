function renderPemetaanJaringan() {
    const container = document.createElement('div');
    container.className = 'pemetaan-jaringan-container';
    
    // Custom Styles for enhanced UI
    const style = document.createElement('style');
    style.textContent = `
        .grid-5 {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 16px;
        }

        .map-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 2.5fr) minmax(0, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }

        .bottom-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr) minmax(0, 2fr);
            gap: 16px;
        }

        @media (max-width: 1024px) {
            .grid-5 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .map-dashboard-grid { grid-template-columns: 1fr; }
            .bottom-dashboard-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .grid-5 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .bottom-dashboard-grid { grid-template-columns: 1fr; }
        }

        .map-view-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            padding: 24px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 500px;
            perspective: 1200px; /* Added for 3D effect */
        }
        
        .map-area {
            flex: 1;
            background-color: var(--bg-main);
            border-radius: var(--radius-md);
            position: relative;
            background-image: url('https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?q=80&w=1000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            overflow: hidden;
            border: 1px solid var(--border-color);
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.2);
            min-height: 400px;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: bottom center;
        }

        .map-road {
            fill: none;
            stroke: rgba(255, 255, 255, 0.3);
            stroke-width: 8;
            stroke-linecap: round;
            stroke-linejoin: round;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
        }

        .map-road-border {
            fill: none;
            stroke: rgba(0, 0, 0, 0.5);
            stroke-width: 12;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .map-area.is-3d {
            transform: rotateX(45deg) rotateZ(0deg) scale(0.9);
            box-shadow: 0 20px 40px rgba(0,0,0,0.8);
            border-radius: 12px;
        }

        #topology-svg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1;
        }

        .topology-line {
            stroke: rgba(46, 160, 91, 0.3);
            stroke-width: 2;
            stroke-dasharray: 5, 5;
            animation: dash 20s linear infinite;
        }

        .topology-line.offline {
            stroke: rgba(239, 68, 68, 0.3);
            animation: none;
        }

        @keyframes dash {
            to { stroke-dashoffset: -1000; }
        }
        
        .node {
            position: absolute; width: 32px; height: 32px; background: var(--bg-card);
            border-radius: 50% 50% 50% 0; display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-sm); transform: translate(-50%, -100%) rotate(-45deg); cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 10;
            border: 2px solid white;
        }
        
        .node:hover, .node.selected {
            transform: translate(-50%, -100%) rotate(-45deg) scale(1.15); z-index: 20;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        }
        
        .node.active { background-color: var(--success); }
        .node.warning { background-color: var(--warning); }
        .node.offline { background-color: var(--danger); }
        
        .node-icon { font-size: 0.9rem; color: white; transform: rotate(45deg); }
        
        .node-label {
            position: absolute; top: -20px; left: 24px; background: rgba(0,0,0,0.7); color: white;
            padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; white-space: nowrap;
            pointer-events: none; opacity: 1; transform: rotate(45deg); font-weight: 500;
            border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(2.2); opacity: 0; }
        }

        .map-zone {
            position: absolute; border: 2px dashed rgba(255, 255, 255, 0.6); border-radius: var(--radius-lg);
            background: rgba(0, 0, 0, 0.3); display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #ffffff; font-size: 1.5rem; letter-spacing: 2px;
            pointer-events: none; transition: background 0.3s;
            text-shadow: 0 2px 8px rgba(0,0,0,0.9);
        }
        
        .live-cctv-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 12px;
            width: 100%;
            align-content: start;
        }

        @media (max-width: 768px) {
            .live-cctv-grid {
                grid-template-columns: 1fr;
            }
        }

        .cctv-mini {
            position: relative;
            background: #000;
            border-radius: var(--radius-md);
            overflow: hidden;
            aspect-ratio: 16/9;
            border: 1px solid var(--border-color);
            width: 100%;
        }

        .cctv-mini img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }

        .cctv-mini::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 50%;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            pointer-events: none;
        }

        .cctv-mini .label {
            position: absolute;
            bottom: 8px; left: 8px; right: 8px;
            font-size: 0.7rem;
            color: white;
            font-weight: 600;
            z-index: 2;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            text-shadow: 0 1px 4px rgba(0,0,0,0.8);
        }

        .cctv-mini .live-badge {
            position: absolute;
            top: 8px; right: 8px;
            font-size: 0.6rem;
            background: rgba(46, 160, 91, 0.8);
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            animation: blink 2s infinite;
        }

        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

        .stat-card-modern {
            background: var(--bg-card);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 0;
            overflow: hidden;
            gap: 12px;
            box-shadow: var(--shadow-sm);
        }

        .stat-card-modern .info { flex: 1; min-width: 0; }

        .stat-card-modern .info h4 {
            font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px; font-weight: 500;
        }

        .stat-card-modern .info .val {
            font-size: 1.8rem; font-weight: 700; color: var(--text-main); line-height: 1.1; margin-bottom: 4px;
        }

        .list-activity {
            list-style: none; padding: 0; margin: 0;
        }
        .list-activity li {
            display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border-color); font-size: 0.85rem;
        }
        .list-activity li:last-child { border-bottom: none; }
        .list-activity .time { color: var(--text-muted); font-family: monospace; width: 40px; }
        .list-activity .area { font-weight: 600; width: 120px; }
        .list-activity .desc { color: var(--text-muted); flex: 1; }
        
        .progress-bar-container { margin-bottom: 16px; }
        .progress-bar-container .top { display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 6px; font-weight: 500; }
        .progress-bar-container .bar-bg { width: 100%; height: 6px; background: var(--bg-main, rgba(255,255,255,0.1)); border-radius: 3px; overflow: hidden; }
        .progress-bar-container .bar-fill { height: 100%; background: var(--primary); border-radius: 3px; }

        /* Modals */
        .global-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.7); z-index: 9999;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
            backdrop-filter: blur(4px);
        }
        .global-modal-overlay.show { opacity: 1; pointer-events: auto; }
        .global-modal {
            background: var(--bg-card); border: 1px solid var(--border-color);
            border-radius: var(--radius-lg); width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            transform: translateY(20px); transition: transform 0.3s ease;
            overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;
        }
        .global-modal-overlay.show .global-modal { transform: translateY(0); }
        .modal-header-g {
            padding: 16px 24px; border-bottom: 1px solid var(--border-color);
            display: flex; justify-content: space-between; align-items: center;
            background: rgba(0,0,0,0.2);
        }
        .modal-header-g h3 { margin: 0; font-size: 1.1rem; color: var(--text-main); font-weight: 600; }
        .modal-close-g {
            background: none; border: none; color: var(--text-muted); font-size: 1.2rem;
            cursor: pointer; transition: color 0.2s;
        }
        .modal-close-g:hover { color: var(--danger); }
        .modal-body-g { padding: 24px; overflow-y: auto; flex: 1; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 0.85rem; color: var(--text-muted); font-weight: 500; }
        .form-control {
            width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.2);
            border: 1px solid var(--border-color); border-radius: 6px;
            color: var(--text-main); font-size: 0.9rem; transition: border-color 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); }
        .btn { padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s; font-size: 0.9rem; border: none; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-outline { background: transparent; border: 1px solid var(--border-color); color: var(--text-main); }
        .btn-outline:hover { background: rgba(255,255,255,0.05); }
    `;
    container.appendChild(style);

    // Fetch Data from Store
    let networkNodes = Store.getData().network_nodes || [];
    const stats = {
        total: networkNodes.length,
        online: networkNodes.filter(n => n.status === 'active').length,
        warning: networkNodes.filter(n => n.status === 'warning').length,
        offline: networkNodes.filter(n => n.status === 'offline').length
    };

    container.innerHTML = `
        <div class="map-controls">
            <button class="btn btn-primary" id="btn-toggle-3d" style="box-shadow: 0 4px 12px rgba(16,185,129,0.2);"><i class="fa-solid fa-cube"></i> Toggle 3D Mode</button>
            <button class="btn btn-outline" id="btn-zoom-in" title="Perbesar"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
            <button class="btn btn-outline" id="btn-zoom-out" title="Perkecil"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
        </div>
    <div style="font-family: inherit;">
        
        </div>

        <div class="map-dashboard-grid">
            <!-- Map Area -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding: 16px;">
                <div style="display: none;">
                    <button id="btn-add-device"></button>
                    <button id="btn-toggle-3d"></button>
                </div>
                
                <div class="map-area" id="network-map">
                    <!-- Base SVG Layout (Roads and Environment) -->
                    <svg viewBox="0 0 1000 600" preserveAspectRatio="none" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;">
                        <!-- Road Borders -->
                        <path class="map-road-border" d="M -50 550 Q 200 450, 500 300 T 950 100 M 500 300 Q 750 250, 850 550 M 200 150 Q 300 300, 500 300" />
                        <!-- Road Inner -->
                        <path class="map-road" d="M -50 550 Q 200 450, 500 300 T 950 100 M 500 300 Q 750 250, 850 550 M 200 150 Q 300 300, 500 300" />
                        
                        <!-- Tent Icons (Glamping Area A) -->
                        <g transform="translate(150, 150) scale(1.5)" fill="rgba(255,255,255,0.4)">
                            <path d="M15,0 L30,20 L0,20 Z" /><path d="M45,10 L60,30 L30,30 Z" /><path d="M25,40 L40,60 L10,60 Z" />
                        </g>
                        <!-- Tent Icons (Camping Area B) -->
                        <g transform="translate(750, 150) scale(1.5)" fill="rgba(255,255,255,0.4)">
                            <path d="M15,0 L30,20 L0,20 Z" /><path d="M45,20 L60,40 L30,40 Z" /><path d="M-10,30 L5,50 L-25,50 Z" />
                        </g>
                    </svg>
                    
                    <!-- Dynamic Topology SVG (Lines) -->
                    <svg id="topology-svg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1;"></svg>
                    
                    <!-- Zones -->
                    <div class="map-zone" style="top: 15%; left: 10%; width: 25%; height: 30%; border-color: rgba(16,185,129,0.5);">Area Glamping A</div>
                    <div class="map-zone" style="top: 15%; left: 65%; width: 25%; height: 30%; border-color: rgba(16,185,129,0.5);">Area Camping B</div>
                    <div class="map-zone" style="top: 60%; left: 40%; width: 40%; height: 25%; border-color: rgba(16,185,129,0.5);">Area Parkir</div>
                    <div class="map-zone" style="top: 75%; left: 5%; width: 25%; height: 20%; border-color: rgba(245,158,11,0.5);">Entrance</div>
                </div>
                
                <div style="display: flex; gap: 28px; margin-top: 20px; font-size: 0.9rem; color: #71717a; justify-content: center; font-weight: 500; border: 1px solid #e4e4e7; padding: 12px; border-radius: 8px;">
                    <div style="display:flex; align-items:center; gap: 8px;"><div style="width:12px; height:12px; border-radius:50%; background:#10b981; box-shadow: 0 0 8px #10b981;"></div> Online</div>
                    <div style="display:flex; align-items:center; gap: 8px;"><div style="width:12px; height:12px; border-radius:50%; background:#f59e0b; box-shadow: 0 0 8px #f59e0b;"></div> Warning</div>
                    <div style="display:flex; align-items:center; gap: 8px;"><div style="width:12px; height:12px; border-radius:50%; background:#ef4444; box-shadow: 0 0 8px #ef4444;"></div> Offline</div>
                    <div style="display:flex; align-items:center; gap: 8px; margin-left: 24px; color: #000;"><i class="fa-solid fa-video" style="color: #3b82f6;"></i> Kamera CCTV (Analog)</div>
                    <div style="display:flex; align-items:center; gap: 8px; color: #000;"><i class="fa-solid fa-server" style="color: #3b82f6;"></i> Mesin DVR Utama</div>
                </div>
            </div>
            
            <!-- Live CCTV Wall -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding: 16px; display: flex; flex-direction: column;">
                <div class="fi-section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white" style="text-transform: uppercase; letter-spacing: 1px;">
                        LIVE CCTV
                    </h3>
                    <a href="#" style="color: #3b82f6; font-size: 0.85rem; text-decoration: none; font-weight: 600;">Lihat Semua ></a>
                </div>
                <div class="live-cctv-grid" id="sidebar-live-cctv" style="flex: 1;">
                    <!-- Injected dynamically -->
                </div>
                <div style="display:flex; justify-content:center; gap: 6px; margin-top: 12px;">
                    <div style="width:24px; height:6px; background:#3b82f6; border-radius:3px;"></div>
                    <div style="width:6px; height:6px; background:#71717a; border-radius:50%;"></div>
                    <div style="width:6px; height:6px; background:#71717a; border-radius:50%;"></div>
                </div>
            </div>
        </div>

        <div class="bottom-dashboard-grid" style="margin-top: 24px;">
            <!-- Aktivitas Terbaru -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding: 20px;">
                <div class="fi-section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white" style="text-transform: uppercase; letter-spacing: 1px;">
                        AKTIVITAS TERAKHIR
                    </h3>
                    <a href="#" style="color: #3b82f6; font-size: 0.85rem; text-decoration: none; font-weight: 600;">Lihat Semua ></a>
                </div>
                
                <ul class="list-activity">
                    <li>
                        <div style="color:#10b981;"><i class="fa-solid fa-person-walking"></i></div>
                        <div class="time">10:42</div><div class="area">Area Camping B</div><div class="desc">Pergerakan terdeteksi</div>
                    </li>
                    <li>
                        <div style="color:#3b82f6;"><i class="fa-solid fa-car"></i></div>
                        <div class="time">10:38</div><div class="area">Area Parkir</div><div class="desc">Kendaraan masuk</div>
                    </li>
                    <li>
                        <div style="color:#10b981;"><i class="fa-solid fa-wifi"></i></div>
                        <div class="time">10:35</div><div class="area">Area Glamping</div><div class="desc">Perangkat kembali online</div>
                    </li>
                    <li>
                        <div style="color:#ef4444;"><i class="fa-solid fa-lock"></i></div>
                        <div class="time">10:32</div><div class="area">Area Entrance</div><div class="desc">Akses ditolak</div>
                    </li>
                </ul>
            </div>
            
            <!-- Status Sistem -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding: 20px; flex: 1;">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white" style="text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;">
                        STATUS SISTEM
                    </h3>
                    <div style="display: flex; justify-content: space-between; text-align: center;">
                        <div>
                            <svg viewBox="0 0 36 36" style="width: 50px; height: 50px; margin: 0 auto 8px;">
                                <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e4e4e7" stroke-width="3"/>
                                <path class="circle" stroke-dasharray="79, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round"/>
                                <text x="18" y="20.35" style="font-size: 8px; font-weight: 700; fill: currentColor;" class="text-gray-950 dark:text-white" text-anchor="middle">79%</text>
                            </svg>
                            <div style="font-size: 0.75rem; color: #71717a;" class="dark:text-gray-400">Bandwidth</div>
                            <div style="font-size: 0.7rem; color: #10b981; font-weight: 600;">Baik</div>
                        </div>
                        <div>
                            <svg viewBox="0 0 36 36" style="width: 50px; height: 50px; margin: 0 auto 8px;">
                                <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e4e4e7" stroke-width="3"/>
                                <path class="circle" stroke-dasharray="85, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
                                <text x="18" y="20.35" style="font-size: 8px; font-weight: 700; fill: currentColor;" class="text-gray-950 dark:text-white" text-anchor="middle">85%</text>
                            </svg>
                            <div style="font-size: 0.75rem; color: #71717a;" class="dark:text-gray-400">Storage</div>
                            <div style="font-size: 0.7rem; color: #f59e0b; font-weight: 600;">Cukup</div>
                        </div>
                        <div>
                            <svg viewBox="0 0 36 36" style="width: 50px; height: 50px; margin: 0 auto 8px;">
                                <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e4e4e7" stroke-width="3"/>
                                <path class="circle" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round"/>
                                <text x="18" y="20.35" style="font-size: 8px; font-weight: 700; fill: currentColor;" class="text-gray-950 dark:text-white" text-anchor="middle">100%</text>
                            </svg>
                            <div style="font-size: 0.75rem; color: #71717a;" class="dark:text-gray-400">NVR Status</div>
                            <div style="font-size: 0.7rem; color: #10b981; font-weight: 600;">Optimal</div>
                        </div>
                        <div>
                            <svg viewBox="0 0 36 36" style="width: 50px; height: 50px; margin: 0 auto 8px;">
                                <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e4e4e7" stroke-width="3"/>
                                <path class="circle" stroke-dasharray="97, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round"/>
                                <text x="18" y="20.35" style="font-size: 8px; font-weight: 700; fill: currentColor;" class="text-gray-950 dark:text-white" text-anchor="middle">97%</text>
                            </svg>
                            <div style="font-size: 0.75rem; color: #71717a;" class="dark:text-gray-400">Kesehatan</div>
                            <div style="font-size: 0.7rem; color: #10b981; font-weight: 600;">Optimal</div>
                        </div>
                    </div>
                </div>

                <!-- Rekaman Storage info -->
                <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white" style="text-transform: uppercase; letter-spacing: 1px; margin: 0;">PENYIMPANAN REKAMAN</h3>
                        <a href="#" style="font-size: 0.75rem; color: #71717a; text-decoration: none;" class="dark:text-gray-400">Lihat Detail ></a>
                    </div>
                    <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 8px;">
                        <span class="text-gray-950 dark:text-white" style="font-size: 1.5rem; font-weight: 800;">4.2 TB</span>
                        <span class="text-gray-500 dark:text-gray-400" style="font-size: 0.75rem;">/ 8 TB</span>
                        <span class="text-gray-950 dark:text-white" style="font-size: 1rem; font-weight: 700; margin-left: auto;">52%</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: rgba(59, 130, 246, 0.2); border-radius: 4px; overflow: hidden; margin-bottom: 8px;">
                        <div style="width: 52%; height: 100%; background: #3b82f6; border-radius: 4px;"></div>
                    </div>
                    <div class="text-gray-500 dark:text-gray-400" style="font-size: 0.7rem;">204 Hari Tersedia</div>
                </div>
            </div>
        </div>

        <!-- Modals -->

        function openDetailModal(node) {
            if(window.Livewire) {
                window.Livewire.dispatch('open-detail-cctv', { deviceId: node.id });
            } else {
                console.warn('Livewire is not initialized yet.');
            }
        }

        // 3D Mode Toggle
        const btnToggle3d = container.querySelector('#btn-toggle-3d');
        const mapAreaElement = container.querySelector('#network-map');
        if(btnToggle3d && mapAreaElement) {
            let is3d = false;
            btnToggle3d.addEventListener('click', () => {
                is3d = !is3d;
                if(is3d) {
                    mapAreaElement.classList.add('is-3d');
                    btnToggle3d.classList.add('btn-primary');
                    btnToggle3d.classList.remove('btn-outline');
                    btnToggle3d.innerHTML = '<i class="fa-solid fa-map"></i> 2D Mode';
                } else {
                    mapAreaElement.classList.remove('is-3d');
                    btnToggle3d.classList.add('btn-outline');
                    btnToggle3d.classList.remove('btn-primary');
                    btnToggle3d.innerHTML = '<i class="fa-solid fa-cube"></i> 3D Mode';
                }
            });
        }

        renderNodes();
    }, 100);

    return container;
}
