function renderPetaMonitoring() {
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
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 20px;
        }

        .bottom-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr) minmax(0, 2fr);
            gap: 20px;
        }

        @media (max-width: 1200px) {
            /* No change needed for map-dashboard-grid as it is flex column */
        }

        @media (max-width: 1024px) {
            .grid-5 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .bottom-dashboard-grid { grid-template-columns: 1fr 1fr; }
            .live-cctv-grid { grid-template-columns: 1fr 1fr !important; }
        }

        @media (max-width: 768px) {
            .grid-5 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .bottom-dashboard-grid { grid-template-columns: 1fr; }
        }

        /* Improved Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        .map-view-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg, 16px);
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
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
            background-color: #0d1612;
            border-radius: var(--radius-md);
            position: relative;
            background-image: 
                radial-gradient(circle at 50% 50%, rgba(20, 40, 25, 0.4) 0%, transparent 70%),
                url('/images/camping_map_topdown.jpg');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.5); transform: translate(-50%, -100%) rotate(-45deg); cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 10;
            border: 2px solid rgba(255,255,255,0.2);
        }
        
        .node:hover, .node.selected {
            transform: translate(-50%, -100%) rotate(-45deg) scale(1.15); z-index: 20;
            box-shadow: 0 0 0 4px rgba(255,255,255, 0.2);
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
            position: absolute; border: 2px dashed rgba(255, 255, 255, 0.25); border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: rgba(255, 255, 255, 0.7); font-size: 1.5rem; letter-spacing: 2px;
            pointer-events: none; transition: background 0.3s;
            text-shadow: 0 2px 8px rgba(0,0,0,0.8);
        }
        
        .live-cctv-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 12px;
            width: 100%;
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
            bottom: 12px; left: 12px;
            font-size: 0.8rem;
            color: white;
            font-weight: 600;
            z-index: 2;
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

        .cctv-sidebar {
            background: var(--bg-card); border-radius: var(--radius-lg, 16px);
            border: 1px solid var(--border-color);
            padding: 24px; display: flex; flex-direction: column; gap: 16px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .cctv-header {
            display: flex; justify-content: space-between; align-items: center;
        }

        .cctv-header h3 {
            font-size: 1.1rem; font-weight: 700; color: white; letter-spacing: 1px;
            display: flex; align-items: center; gap: 8px; margin: 0;
        }

        .live-cctv-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
        }
        
        .cctv-card {
            position: relative; background: #000; border-radius: 12px;
            aspect-ratio: 16/10; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s; cursor: pointer;
        }

        .cctv-card:hover { transform: translateY(-2px); border-color: var(--primary); box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2); }
        .cctv-card img { width: 100%; height: 100%; object-fit: cover; opacity: 0.8; transition: opacity 0.3s; }
        .cctv-card:hover img { opacity: 1; }

        .cctv-card::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 50%;
            background: linear-gradient(transparent, rgba(0,0,0,0.9)); pointer-events: none;
        }

        .cctv-card .c-label {
            position: absolute; bottom: 8px; left: 10px; right: 10px;
            font-size: 0.7rem; color: white; font-weight: 600; z-index: 2;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .cctv-card .c-badge {
            position: absolute; top: 8px; right: 8px;
            font-size: 0.55rem; background: rgba(239, 68, 68, 0.8);
            padding: 2px 6px; border-radius: 4px; font-weight: 700; letter-spacing: 1px;
            animation: pulse-badge 2s infinite; color: white;
        }

        @keyframes pulse-badge { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }

        /* Stats Cards inside Bottom Dashboard */
        .b-card {
            background: var(--bg-card); border-radius: var(--radius-lg, 16px);
            border: 1px solid var(--border-color);
            padding: 24px; box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .b-card-title {
            font-size: 0.9rem; font-weight: 700; color: white; letter-spacing: 1px;
            text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.05);
            padding-bottom: 12px; display: flex; align-items: center; gap: 8px;
        }

        .activity-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; }
        .activity-list li {
            display: flex; align-items: flex-start; gap: 12px;
            font-size: 0.85rem; padding-bottom: 12px; border-bottom: 1px dashed rgba(255,255,255,0.05);
        }
        .activity-list li:last-child { border-bottom: none; padding-bottom: 0; }
        .act-time { color: #a1a1aa; font-family: monospace; font-size: 0.75rem; margin-top: 2px; }
        .act-content { flex: 1; }
        .act-content strong { color: white; display: block; margin-bottom: 2px; }
        .act-content span { color: #a1a1aa; }
        
        .sys-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .sys-item { background: rgba(0,0,0,0.3); padding: 12px 8px; border-radius: 12px; text-align: center; border: 1px solid rgba(255,255,255,0.05); }
        .sys-val { font-size: 1.25rem; font-weight: 800; color: white; margin-bottom: 4px; white-space: nowrap; }
        .sys-label { font-size: 0.65rem; color: #a1a1aa; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2; word-wrap: break-word; }

        .circular-chart { display: flex; flex-direction: column; align-items: center; gap: 8px; }
        .circle {
            width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            border: 4px solid var(--primary); font-weight: 700; font-size: 1.1rem;
        }

        /* Modal Styles */
        .global-modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999; opacity: 0; pointer-events: none; transition: opacity 0.3s;
        }
        .global-modal-overlay.show {
            opacity: 1; pointer-events: auto;
        }
        .global-modal {
            background: var(--bg-card); border-radius: var(--radius-lg);
            width: 100%; border: 1px solid var(--border-color);
            transform: translateY(20px); transition: transform 0.3s;
            box-shadow: 0 20px 40px rgba(0,0,0,0.8);
        }
        .global-modal-overlay.show .global-modal {
            transform: translateY(0);
        }
        .modal-header-g {
            padding: 16px 24px; border-bottom: 1px solid var(--border-color);
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-body-g { padding: 24px; }
        .modal-close-g {
            background: transparent; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.2rem;
        }
        .modal-close-g:hover { color: var(--text-main); }
    `;
    container.appendChild(style);

    // Fetch Data from Store
    let networkNodes = Store.getData().network_nodes || [];

    container.innerHTML += `

        <div class="map-dashboard-grid">
            <!-- Map Area -->
            <div class="map-view-card" style="padding: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h3 style="font-size: 1.1rem; color: var(--text-main); font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-map-location-dot" style="color: var(--primary);"></i> PETA MONITORING AREA
                    </h3>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button id="btn-add-device" class="btn" style="background: var(--primary); color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-plus"></i> Tambah CCTV / Perangkat</button>
                        <button id="btn-toggle-3d" class="btn" style="background: transparent; color: var(--text-main); border: 1px solid var(--border-color); padding: 6px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-cube"></i> 3D Mode</button>
                    </div>
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
                    <div class="map-zone" style="top: 15%; left: 10%; width: 25%; height: 30%; background: radial-gradient(circle, rgba(46,160,91,0.2) 0%, transparent 70%); border-color: rgba(46,160,91,0.5);">Area Glamping A</div>
                    <div class="map-zone" style="top: 15%; left: 65%; width: 25%; height: 30%; background: radial-gradient(circle, rgba(46,160,91,0.2) 0%, transparent 70%); border-color: rgba(46,160,91,0.5);">Area Camping B</div>
                    <div class="map-zone" style="top: 60%; left: 40%; width: 40%; height: 25%; background: radial-gradient(circle, rgba(46,160,91,0.2) 0%, transparent 70%); border-color: rgba(46,160,91,0.5);">Area Parkir</div>
                    <div class="map-zone" style="top: 75%; left: 5%; width: 25%; height: 20%; background: radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 70%); border-color: rgba(245,158,11,0.5);">Entrance</div>
                </div>
                
                <div style="display: flex; gap: 28px; margin-top: 20px; font-size: 0.9rem; color: var(--text-muted); justify-content: center; font-weight: 500; background: rgba(0,0,0,0.2); padding: 12px; border-radius: 8px;">
                    <div style="display:flex; align-items:center; gap: 8px;"><div style="width:12px; height:12px; border-radius:50%; background:var(--success); box-shadow: 0 0 8px var(--success);"></div> Online</div>
                    <div style="display:flex; align-items:center; gap: 8px;"><div style="width:12px; height:12px; border-radius:50%; background:var(--warning); box-shadow: 0 0 8px var(--warning);"></div> Warning</div>
                    <div style="display:flex; align-items:center; gap: 8px;"><div style="width:12px; height:12px; border-radius:50%; background:var(--danger); box-shadow: 0 0 8px var(--danger);"></div> Offline</div>
                    <div style="display:flex; align-items:center; gap: 8px; margin-left: 24px; color: var(--text-main);"><i class="fa-solid fa-video" style="color: rgba(255,255,255,0.7);"></i> Kamera CCTV (Analog)</div>
                    <div style="display:flex; align-items:center; gap: 8px; color: var(--text-main);"><i class="fa-solid fa-server" style="color: rgba(255,255,255,0.7);"></i> Mesin DVR Utama</div>
                </div>
            </div>
            
            <!-- Sidebar: Live CCTV Grid -->
            <div class="cctv-sidebar">
                <div class="cctv-header">
                    <h3><i class="fa-solid fa-border-all" style="color: var(--primary);"></i> LIVE FEED</h3>
                    <a href="#" style="color: var(--primary); font-size: 0.8rem; text-decoration: none; font-weight: 600;">Full Matrix ></a>
                </div>
                <div class="live-cctv-grid" id="sidebar-live-cctv">
                    <!-- Injected dynamically -->
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; background: rgba(16,185,129,0.1); padding: 12px 24px; border-radius: 10px; border: 1px solid rgba(16,185,129,0.2);">
                    <span style="color: var(--primary); font-size: 0.9rem; font-weight: 600;"><i class="fa-solid fa-shield-halved"></i> Sistem Keamanan Aktif</span>
                    <span style="color: var(--text-muted); font-size: 0.8rem;">Monitoring 24/7 Gunung Pancar</span>
                </div>
            </div>
        </div>

        <div class="bottom-dashboard-grid">
            <!-- Aktivitas Terakhir -->
            <div class="b-card">
                <h3 class="b-card-title"><i class="fa-solid fa-clock-rotate-left" style="color: var(--primary);"></i> Log Aktivitas</h3>
                <ul class="activity-list">
                    <li>
                        <div style="color:var(--primary); font-size: 1.2rem;"><i class="fa-solid fa-person-walking"></i></div>
                        <div class="act-content"><strong>Sektor Camping B</strong><span>Pergerakan terdeteksi (AI Motion)</span></div>
                        <div class="act-time">10:42</div>
                    </li>
                    <li>
                        <div style="color:#3b82f6; font-size: 1.2rem;"><i class="fa-solid fa-car"></i></div>
                        <div class="act-content"><strong>Gerbang Utama</strong><span>Kendaraan B 1234 XYZ masuk</span></div>
                        <div class="act-time">10:38</div>
                    </li>
                    <li>
                        <div style="color:var(--danger); font-size: 1.2rem;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="act-content"><strong>Area Parkir Sentral</strong><span>Kamera CAM-02 kehilangan sinyal</span></div>
                        <div class="act-time">10:35</div>
                    </li>
                    <li>
                        <div style="color:var(--warning); font-size: 1.2rem;"><i class="fa-solid fa-hard-drive"></i></div>
                        <div class="act-content"><strong>Server DVR 01</strong><span>Kapasitas penyimpanan 90%</span></div>
                        <div class="act-time">10:32</div>
                    </li>
                </ul>
            </div>
            
            <!-- Status Sistem -->
            <div class="b-card">
                <h3 class="b-card-title"><i class="fa-solid fa-server" style="color: #3b82f6;"></i> Kesehatan Server</h3>
                <div class="sys-grid">
                    <div class="sys-item">
                        <div class="sys-val" style="color: var(--primary);">98%</div>
                        <div class="sys-label">Uptime</div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-val" style="color: var(--warning);">42°C</div>
                        <div class="sys-label">Suhu CPU</div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-val" style="color: var(--primary);">2.4<span style="font-size: 0.8rem;">Gbps</span></div>
                        <div class="sys-label">Bandwidth</div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-val" style="color: #3b82f6;">24<span style="font-size: 0.8rem;">ms</span></div>
                        <div class="sys-label">Latensi Map</div>
                    </div>
                </div>
            </div>

            <!-- Penyimpanan Rekaman -->
            <div class="b-card">
                <h3 class="b-card-title" style="justify-content: space-between;">
                    <span><i class="fa-solid fa-database" style="color: var(--warning);"></i> Manajemen Data</span>
                    <span style="font-size: 0.7rem; background: rgba(255,255,255,0.1); padding: 4px 8px; border-radius: 6px;">RAID 5</span>
                </h3>
                
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px; margin-top: 20px;">
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 800; color: white; line-height: 1;">8.4 <span style="font-size: 1rem; color: #a1a1aa;">TB</span></div>
                        <div style="font-size: 0.8rem; color: #a1a1aa; margin-top: 4px;">Terpakai dari 16 TB</div>
                    </div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #3b82f6;">52%</div>
                </div>
                
                <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; margin-bottom: 16px;">
                    <div style="width: 52%; height: 100%; background: linear-gradient(90deg, #3b82f6, var(--primary)); border-radius: 4px;"></div>
                </div>
                
                <div style="display: flex; gap: 16px;">
                    <div style="flex: 1; background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                        <div style="font-size: 0.7rem; color: #a1a1aa; margin-bottom: 4px;">Arsip Tersedia</div>
                        <div style="font-size: 1.1rem; color: white; font-weight: 700;">180 Hari</div>
                    </div>
                    <div style="flex: 1; background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                        <div style="font-size: 0.7rem; color: #a1a1aa; margin-bottom: 4px;">Backup Cloud</div>
                        <div style="font-size: 1.1rem; color: var(--primary); font-weight: 700;">Sinkron</div>
                    </div>
                </div>
            </div>
        </div>




    `;

    setTimeout(() => {
        const mapArea = container.querySelector('#network-map');
        const svg = container.querySelector('#topology-svg');
        
        function renderTopologyLines() {
            svg.innerHTML = '';
            networkNodes.forEach(node => {
                if(node.parent) {
                    const parentNode = networkNodes.find(n => n.id === node.parent);
                    if(parentNode) {
                        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                        line.setAttribute('x1', `${parentNode.x}%`);
                        line.setAttribute('y1', `${parentNode.y}%`);
                        line.setAttribute('x2', `${node.x}%`);
                        line.setAttribute('y2', `${node.y}%`);
                        line.setAttribute('class', `topology-line ${node.status === 'offline' || parentNode.status === 'offline' ? 'offline' : ''}`);
                        svg.appendChild(line);
                    }
                }
            });
        }

        function renderNodes() {
            const oldNodes = mapArea.querySelectorAll('.node');
            oldNodes.forEach(n => n.remove());

            const sidebarLive = container.querySelector('#sidebar-live-cctv');
            if (sidebarLive) sidebarLive.innerHTML = '';
            let cctvCount = 0;

            networkNodes.forEach(node => {

                let iconClass = 'fa-video';
                if(node.type === 'dvr') iconClass = 'fa-server';

                const nodeEl = document.createElement('div');
                nodeEl.className = `node type-${node.type} ${node.status}`;
                nodeEl.style.left = `${node.x}%`;
                nodeEl.style.top = `${node.y}%`;
                nodeEl.innerHTML = `
                    <i class="fa-solid ${iconClass} node-icon"></i>
                    <div class="node-label">${node.name}</div>
                `;
                
                nodeEl.addEventListener('click', () => {
                    window.dispatchEvent(new CustomEvent('filament-open-detail-cctv', { detail: { id: node.id } }));
                });

                mapArea.appendChild(nodeEl);
                
                if (node.type === 'cctv' && sidebarLive && cctvCount < 4) {
                    const fallbackImages = [
                        '/images/cctv/cctv_glamping_1786524341566.jpg',
                        '/images/cctv/cctv_camping_b.jpg',
                        '/images/cctv/cctv_parking_lot.jpg',
                        '/images/cctv/cctv_gerbang_1786524324305.jpg',
                        '/images/cctv/cctv_resepsionis_1786524352663.jpg'
                    ];
                    const fallbackImg = fallbackImages[cctvCount % fallbackImages.length];
                    const imgSrc = (node.image && node.image.length > 5 && !node.image.includes('fallback')) ? node.image : fallbackImg;
                    const isLive = node.status === 'active';
                    sidebarLive.innerHTML += `
                        <div class="cctv-card" onclick="window.dispatchEvent(new CustomEvent('filament-open-detail-cctv', { detail: { id: ${node.id} } }));">
                            <img src="${imgSrc}" onerror="this.src='${fallbackImg}'" alt="">
                            ${isLive ? '<div class="c-badge">REC</div>' : '<div class="c-badge" style="background:#4b5563; animation:none;">OFF</div>'}
                            <div class="c-label" title="${node.name}">${node.name}</div>
                        </div>
                    `;
                    cctvCount++;
                }
            });
            
            renderTopologyLines();
        }

        container.querySelector('#btn-add-device').addEventListener('click', () => {
            // Langsung panggil event untuk trigger Filament Action Modal
            window.dispatchEvent(new CustomEvent('filament-open-tambah-cctv'));
        });

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
