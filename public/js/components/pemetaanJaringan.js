function renderPemetaanJaringan() {
    const container = document.createElement('div');
    container.className = 'pemetaan-jaringan-container';
    
    // Custom Styles for enhanced UI
    const style = document.createElement('style');
    style.textContent = `
        :root {
            --map-primary: #10b981;
            --map-danger: #ef4444;
            --map-warning: #f59e0b;
            --map-bg-card: rgba(10, 10, 10, 0.6);
            --map-border: rgba(255, 255, 255, 0.1);
        }

        .map-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 3fr) minmax(0, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .bottom-dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        @media (max-width: 1200px) {
            .map-dashboard-grid { grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); }
        }

        @media (max-width: 1024px) {
            .map-dashboard-grid { grid-template-columns: 1fr; }
            .bottom-dashboard-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .bottom-dashboard-grid { grid-template-columns: 1fr; }
        }

        /* The main map area */
        .map-wrapper {
            position: relative;
            background-color: #050505;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--map-border);
            min-height: 550px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        
        /* Map Background with Grid Overlay */
        .map-area {
            flex: 1;
            position: relative;
            background-color: #050f08;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center center;
        }

        /* Google Maps iframe fills the canvas */
        .map-iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: none;
            z-index: 0;
            filter: brightness(0.85) saturate(1.1);
            pointer-events: auto;
        }

        /* Dark overlay + Tech Grid Overlay so zones/nodes are readable */
        .map-area::before {
            content: '';
            position: absolute;
            inset: 0;
            background-color: rgba(3, 10, 5, 0.35);
            background-image: 
                linear-gradient(rgba(16, 185, 129, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16, 185, 129, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 1;
        }


        /* Animated Radar Sweep */
        .map-area::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 800px; height: 800px;
            margin-top: -400px; margin-left: -400px;
            background: conic-gradient(from 0deg, transparent 70%, rgba(16, 185, 129, 0.1) 80%, rgba(16, 185, 129, 0.4) 100%);
            border-radius: 50%;
            animation: radar-sweep 8s linear infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes radar-sweep {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* 3D Mode */
        .map-wrapper.is-3d .map-area {
            transform: perspective(1000px) rotateX(45deg) scale(1.1) translateY(-50px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
        }

        /* SVG Topology Layers */
        .map-svg-layer {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 2;
        }

        .map-road {
            fill: none;
            stroke: rgba(255, 255, 255, 0.1);
            stroke-width: 6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        
        .topology-line {
            stroke: rgba(16, 185, 129, 0.4);
            stroke-width: 2;
            stroke-dasharray: 6, 6;
            animation: dash 15s linear infinite;
        }

        .topology-line.offline {
            stroke: rgba(239, 68, 68, 0.4);
            animation: none;
        }

        @keyframes dash {
            to { stroke-dashoffset: -1000; }
        }
        
        /* Map Markers (Nodes) */
        .node {
            position: absolute; width: 28px; height: 28px; background: rgba(0,0,0,0.8);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            transform: translate(-50%, -50%); cursor: pointer;
            transition: all 0.3s ease; z-index: 10;
            border: 2px solid var(--map-primary);
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
        }
        
        .node::before {
            content: ''; position: absolute; top: -4px; left: -4px; right: -4px; bottom: -4px;
            border-radius: 50%; border: 1px solid var(--map-primary); opacity: 0.5;
            animation: pulse-ring 2s infinite cubic-bezier(0.215, 0.61, 0.355, 1);
        }

        .node:hover {
            transform: translate(-50%, -50%) scale(1.2); z-index: 20;
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.8);
            background: var(--map-primary);
        }
        
        .node.active { border-color: var(--map-primary); }
        .node.warning { border-color: var(--map-warning); box-shadow: 0 0 15px rgba(245, 158, 11, 0.5); }
        .node.warning::before { border-color: var(--map-warning); }
        
        .node.offline { border-color: var(--map-danger); box-shadow: 0 0 15px rgba(239, 68, 68, 0.5); }
        .node.offline::before { border-color: var(--map-danger); }
        
        .node-icon { font-size: 0.8rem; color: white; }
        .node:hover .node-icon { color: #000; }
        
        .node-label {
            position: absolute; top: -35px; left: 50%; transform: translateX(-50%);
            background: rgba(0,0,0,0.8); color: white; padding: 4px 10px; border-radius: 6px;
            font-size: 0.75rem; white-space: nowrap; pointer-events: none;
            border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px);
            opacity: 0; transition: all 0.3s ease; font-weight: 600;
        }

        .node:hover .node-label { opacity: 1; top: -45px; }
        
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(2); opacity: 0; }
        }

        /* Map Zones (Regions) */
        .map-zone {
            position: absolute; 
            border: 1px solid rgba(16, 185, 129, 0.3); 
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), transparent);
            display: flex; align-items: flex-start; justify-content: flex-start;
            pointer-events: none; transition: background 0.3s;
            padding: 12px; z-index: 2;
            box-shadow: inset 0 0 20px rgba(16, 185, 129, 0.05);
        }

        .map-zone .zone-title {
            color: rgba(255, 255, 255, 0.8); font-size: 0.8rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            border-left: 2px solid var(--map-primary);
            padding-left: 8px;
        }
        
        /* Map Footer (Legend) */
        .map-footer {
            background: var(--map-bg-card);
            backdrop-filter: blur(10px);
            padding: 16px 24px;
            border-top: 1px solid var(--map-border);
            display: flex; gap: 24px; font-size: 0.85rem; color: #a1a1aa;
            justify-content: center; font-weight: 600; align-items: center; z-index: 10;
        }
        
        .legend-item { display:flex; align-items:center; gap: 8px; }
        .legend-dot { width:10px; height:10px; border-radius:50%; }

        /* Controls */
        .map-tools {
            position: absolute; top: 20px; right: 20px; z-index: 10;
            display: flex; flex-direction: column; gap: 8px;
        }

        .map-btn {
            width: 40px; height: 40px; border-radius: 10px;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.1);
            color: white; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
        }

        .map-btn:hover { background: rgba(16, 185, 129, 0.8); border-color: rgba(16, 185, 129, 1); }

        /* Live CCTV Grid Sidebar */
        .cctv-sidebar {
            background: var(--map-bg-card); border-radius: 20px;
            border: 1px solid var(--map-border); backdrop-filter: blur(10px);
            padding: 24px; display: flex; flex-direction: column; gap: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .cctv-header {
            display: flex; justify-content: space-between; align-items: center;
        }

        .cctv-header h3 {
            font-size: 1.1rem; font-weight: 700; color: white; letter-spacing: 1px;
            display: flex; align-items: center; gap: 8px; margin: 0;
        }

        .live-cctv-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
        }

        .cctv-card {
            position: relative; background: #000; border-radius: 12px;
            aspect-ratio: 16/10; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s; cursor: pointer;
        }

        .cctv-card:hover { transform: translateY(-2px); border-color: var(--map-primary); box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2); }
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
            background: var(--map-bg-card); border-radius: 20px;
            border: 1px solid var(--map-border); backdrop-filter: blur(10px);
            padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
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

    `;

    // Fetch Data from Store
    let networkNodes = Store.getData().network_nodes || [];
    
    container.appendChild(style);

    const contentDiv = document.createElement('div');
    contentDiv.innerHTML = `
        <div class="map-dashboard-grid">
            
            <!-- Map Wrapper -->
            <div class="map-wrapper" id="map-wrapper-element">
                <div class="map-tools">
                    <button class="map-btn" id="btn-toggle-3d" title="3D View"><i class="fa-solid fa-cube"></i></button>
                    <button class="map-btn" title="Zoom In"><i class="fa-solid fa-plus"></i></button>
                    <button class="map-btn" title="Zoom Out"><i class="fa-solid fa-minus"></i></button>
                </div>
                
                <div class="map-area" id="network-map">
                    <!-- Google Maps iframe — Gunung Pancar Satellite View -->
                    <iframe
                        class="map-iframe"
                        src="https://maps.google.com/maps?q=-6.5917058,106.9116798&z=18&t=k&output=embed"
                        title="Peta Gunung Pancar"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                    
                    <!-- Base SVG Layout (Roads and Environment) -->
                    <svg class="map-svg-layer" viewBox="0 0 1000 600" preserveAspectRatio="none">
                        <!-- Curved Roads/Paths -->
                        <path class="map-road" d="M -50 500 Q 250 450, 500 350 T 1050 150" />
                        <path class="map-road" d="M 400 650 Q 500 350, 800 50" />
                    </svg>
                    
                    <!-- Dynamic Topology SVG (Lines) -->
                    <svg id="topology-svg" class="map-svg-layer"></svg>
                    
                    <!-- High-Tech Zones -->
                    <div class="map-zone" style="top: 10%; left: 10%; width: 30%; height: 35%; border-color: rgba(16,185,129,0.4);">
                        <div class="zone-title">Sektor Glamping A</div>
                    </div>
                    <div class="map-zone" style="top: 10%; left: 60%; width: 30%; height: 40%; border-color: rgba(59,130,246,0.4);">
                        <div class="zone-title" style="border-left-color: #3b82f6;">Sektor Camping B</div>
                    </div>
                    <div class="map-zone" style="top: 60%; left: 35%; width: 45%; height: 30%; border-color: rgba(245,158,11,0.4);">
                        <div class="zone-title" style="border-left-color: #f59e0b;">Area Parkir Sentral</div>
                    </div>
                    <div class="map-zone" style="top: 65%; left: 5%; width: 25%; height: 25%; border-color: rgba(239,68,68,0.4);">
                        <div class="zone-title" style="border-left-color: #ef4444;">Gerbang Utama</div>
                    </div>
                </div>
                
                <div class="map-footer">
                    <div class="legend-item"><div class="legend-dot" style="background:var(--map-primary); box-shadow: 0 0 10px var(--map-primary);"></div> Normal / Aktif</div>
                    <div class="legend-item"><div class="legend-dot" style="background:var(--map-warning); box-shadow: 0 0 10px var(--map-warning);"></div> Peringatan</div>
                    <div class="legend-item"><div class="legend-dot" style="background:var(--map-danger); box-shadow: 0 0 10px var(--map-danger);"></div> Offline / Mati</div>
                    <div class="legend-item" style="margin-left: 20px;"><i class="fa-solid fa-video" style="color: #fff;"></i> Kamera Analog / IP</div>
                    <div class="legend-item"><i class="fa-solid fa-server" style="color: #fff;"></i> Server DVR / NVR</div>
                </div>
            </div>
            
            <!-- Sidebar: Live CCTV Grid -->
            <div class="cctv-sidebar">
                <div class="cctv-header">
                    <h3><i class="fa-solid fa-border-all" style="color: var(--map-primary);"></i> LIVE FEED</h3>
                    <a href="#" style="color: var(--map-primary); font-size: 0.8rem; text-decoration: none; font-weight: 600;">Full Matrix ></a>
                </div>
                <div class="live-cctv-grid" id="sidebar-live-cctv">
                    <!-- Injected dynamically -->
                </div>
                <div style="background: rgba(16,185,129,0.1); padding: 12px; border-radius: 10px; border: 1px solid rgba(16,185,129,0.2); text-align: center; margin-top: auto;">
                    <span style="color: var(--map-primary); font-size: 0.8rem; font-weight: 600;"><i class="fa-solid fa-shield-halved"></i> Sistem Keamanan Aktif</span>
                </div>
            </div>
        </div>

        <div class="bottom-dashboard-grid">
            
            <!-- Aktivitas Terakhir -->
            <div class="b-card">
                <h3 class="b-card-title"><i class="fa-solid fa-clock-rotate-left" style="color: var(--map-primary);"></i> Log Aktivitas</h3>
                <ul class="activity-list">
                    <li>
                        <div style="color:var(--map-primary); font-size: 1.2rem;"><i class="fa-solid fa-person-walking"></i></div>
                        <div class="act-content"><strong>Sektor Camping B</strong><span>Pergerakan terdeteksi (AI Motion)</span></div>
                        <div class="act-time">10:42</div>
                    </li>
                    <li>
                        <div style="color:#3b82f6; font-size: 1.2rem;"><i class="fa-solid fa-car"></i></div>
                        <div class="act-content"><strong>Gerbang Utama</strong><span>Kendaraan B 1234 XYZ masuk</span></div>
                        <div class="act-time">10:38</div>
                    </li>
                    <li>
                        <div style="color:var(--map-danger); font-size: 1.2rem;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="act-content"><strong>Area Parkir Sentral</strong><span>Kamera CAM-02 kehilangan sinyal</span></div>
                        <div class="act-time">10:35</div>
                    </li>
                    <li>
                        <div style="color:var(--map-warning); font-size: 1.2rem;"><i class="fa-solid fa-hard-drive"></i></div>
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
                        <div class="sys-val" style="color: var(--map-primary);">98%</div>
                        <div class="sys-label">Uptime</div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-val" style="color: var(--map-warning);">42°C</div>
                        <div class="sys-label">Suhu CPU</div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-val" style="color: var(--map-primary);">2.4<span style="font-size: 0.8rem;">Gbps</span></div>
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
                    <span><i class="fa-solid fa-database" style="color: var(--map-warning);"></i> Manajemen Data</span>
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
                    <div style="width: 52%; height: 100%; background: linear-gradient(90deg, #3b82f6, var(--map-primary)); border-radius: 4px;"></div>
                </div>
                
                <div style="display: flex; gap: 16px;">
                    <div style="flex: 1; background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                        <div style="font-size: 0.7rem; color: #a1a1aa; margin-bottom: 4px;">Arsip Tersedia</div>
                        <div style="font-size: 1.1rem; color: white; font-weight: 700;">180 Hari</div>
                    </div>
                    <div style="flex: 1; background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                        <div style="font-size: 0.7rem; color: #a1a1aa; margin-bottom: 4px;">Backup Cloud</div>
                        <div style="font-size: 1.1rem; color: var(--map-primary); font-weight: 700;">Sinkron</div>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(contentDiv);

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
                
                nodeEl.addEventListener('click', () => openDetailModal(node));

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
                        <div class="cctv-card">
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

        function openDetailModal(node) {
            if(window.Livewire) {
                window.Livewire.dispatch('open-detail-cctv', { deviceId: node.id });
            } else {
                console.warn('Livewire is not initialized yet.');
            }
        }

        // 3D Mode Toggle
        const btnToggle3d = container.querySelector('#btn-toggle-3d');
        const mapWrapperElement = container.querySelector('#map-wrapper-element');
        if(btnToggle3d && mapWrapperElement) {
            let is3d = false;
            btnToggle3d.addEventListener('click', () => {
                is3d = !is3d;
                if(is3d) {
                    mapWrapperElement.classList.add('is-3d');
                    btnToggle3d.innerHTML = '<i class="fa-solid fa-map"></i>';
                    btnToggle3d.style.background = 'rgba(16, 185, 129, 0.8)';
                } else {
                    mapWrapperElement.classList.remove('is-3d');
                    btnToggle3d.innerHTML = '<i class="fa-solid fa-cube"></i>';
                    btnToggle3d.style.background = 'rgba(0,0,0,0.6)';
                }
            });
        }

        renderNodes();
    }, 100);

    return container;
}
