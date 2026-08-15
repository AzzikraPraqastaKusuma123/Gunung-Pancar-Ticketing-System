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
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .bottom-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr) minmax(0, 2fr);
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .grid-5 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .bottom-dashboard-grid { grid-template-columns: 1fr 1fr; }
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
                url('https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?q=80&w=1000&auto=format&fit=crop');
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

        .stat-card-modern {
            background: var(--bg-card);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 0;
            overflow: hidden;
        }

        .stat-card-modern .info h4 {
            font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px; font-weight: 500;
        }

        .stat-card-modern .info .val {
            font-size: 1.6rem; font-weight: 700; color: var(--text-main);
        }

        .stat-card-modern .info .sub {
            font-size: 0.7rem; color: var(--text-muted);
        }

        .stat-card-modern .icon-circle {
            width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
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
        
        .progress-bar-container { margin-bottom: 12px; }
        .progress-bar-container .top { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px; }
        .progress-bar-container .bar-bg { width: 100%; height: 6px; background: var(--bg-main); border-radius: 3px; overflow: hidden; }
        .progress-bar-container .bar-fill { height: 100%; background: var(--primary); border-radius: 3px; }

        .circular-chart { display: flex; flex-direction: column; align-items: center; gap: 8px; }
        .circle {
            width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            border: 4px solid var(--primary); font-weight: 700; font-size: 1.1rem;
        }
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
        </div>



        <!-- Modals -->
        <div id="add-modal" class="global-modal-overlay">
            <div class="global-modal" style="max-width: 500px;">
                <div class="modal-header-g">
                    <h3>Tambah Perangkat / CCTV Baru</h3>
                    <button class="modal-close-g" id="close-add-modal"><i class="fa-solid fa-times"></i></button>
                </div>
                <div class="modal-body-g">
                    <div class="form-group">
                        <label>Nama Perangkat</label>
                        <input type="text" id="add-name" class="form-control" placeholder="Contoh: CCTV Area Timur">
                    </div>
                    <div class="form-group">
                        <label>Alamat IP / DVR Channel (Analog CCTV)</label>
                        <input type="text" id="add-ip" class="form-control" placeholder="Contoh: 192.168.1.1 atau CH 01">
                    </div>
                    <div class="form-group">
                        <label>Tipe Perangkat</label>
                        <select id="add-type" class="form-control">
                            <option value="cctv">CCTV Camera (Analog)</option>
                            <option value="dvr">Mesin DVR Utama</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 16px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Koordinat X (%)</label>
                            <input type="number" id="add-x" class="form-control" value="50" min="0" max="100">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Koordinat Y (%)</label>
                            <input type="number" id="add-y" class="form-control" value="50" min="0" max="100">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Hubungkan Ke (Parent)</label>
                        <select id="add-parent" class="form-control">
                            <!-- Injected dynamically -->
                        </select>
                    </div>
                </div>
                <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px; background: rgba(0,0,0,0.2);">
                    <button class="btn btn-outline" id="cancel-add">Batal</button>
                    <button class="btn btn-primary" id="submit-add">Simpan Perangkat</button>
                </div>
            </div>
        </div>

        <div id="detail-modal" class="global-modal-overlay">
            <div class="global-modal" style="max-width: 400px;">
                <div class="modal-header-g">
                    <h3 id="detail-title">Detail Perangkat</h3>
                    <button class="modal-close-g" id="close-detail-modal"><i class="fa-solid fa-times"></i></button>
                </div>
                <div class="modal-body-g">
                    <div style="text-align: center; margin-bottom: 16px;">
                        <i id="detail-icon" class="fa-solid fa-video" style="font-size: 3rem; color: var(--primary);"></i>
                        <h3 id="detail-name" style="margin-top: 12px; color: var(--text-main);">Nama Perangkat</h3>
                        <span id="detail-status" class="badge" style="background: var(--success); margin-top: 8px; display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 700; color: white; position: static;">Online</span>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 8px 0; color: var(--text-muted);" id="detail-ip-label">IP / Channel</td>
                            <td style="padding: 8px 0; text-align: right; font-weight: 500;" id="detail-ip">192.168.x.x</td>
                        </tr>
                        <tr id="mac-address-row" style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 8px 0; color: var(--text-muted);">MAC Address</td>
                            <td style="padding: 8px 0; text-align: right; font-weight: 500;" id="detail-mac">00:00:00:00:00</td>
                        </tr>
                    </table>
                    <div id="detail-feed-container" style="margin-top: 16px; display: none;">
                        <div class="cctv-mini" style="width: 100%;"><img id="detail-feed" src="" alt="Live Feed"><div class="live-badge">LIVE</div></div>
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
                
            });
            
            renderTopologyLines();
        }

        function openDetailModal(node) {
            const modal = container.querySelector('#detail-modal');
            modal.querySelector('#detail-title').textContent = 'Detail ' + (node.type === 'cctv' ? 'CCTV Analog' : 'DVR Utama');
            modal.querySelector('#detail-name').textContent = node.name;
            modal.querySelector('#detail-ip').textContent = node.ip;
            modal.querySelector('#detail-mac').textContent = node.mac || 'N/A';
            
            if (node.type === 'cctv') {
                modal.querySelector('#detail-ip-label').textContent = 'DVR Channel';
                modal.querySelector('#mac-address-row').style.display = 'none';
            } else {
                modal.querySelector('#detail-ip-label').textContent = 'IP Address';
                modal.querySelector('#mac-address-row').style.display = 'table-row';
            }
            
            const icon = modal.querySelector('#detail-icon');
            icon.className = node.type === 'cctv' ? 'fa-solid fa-video' : 'fa-solid fa-server';
            icon.style.color = node.status === 'offline' ? 'var(--danger)' : node.status === 'warning' ? 'var(--warning)' : 'var(--success)';
            
            const statusBadge = modal.querySelector('#detail-status');
            statusBadge.textContent = node.status.toUpperCase();
            statusBadge.style.background = node.status === 'offline' ? 'var(--danger)' : node.status === 'warning' ? 'var(--warning)' : 'var(--success)';

            const feedContainer = modal.querySelector('#detail-feed-container');
            const feedImg = modal.querySelector('#detail-feed');
            if (node.type === 'cctv' && node.image) {
                feedContainer.style.display = 'block';
                feedImg.src = node.image;
            } else {
                feedContainer.style.display = 'none';
            }
            modal.classList.add('show');
        }

        const addModal = container.querySelector('#add-modal');
        const detailModal = container.querySelector('#detail-modal');
        
        container.querySelector('#btn-add-device').addEventListener('click', () => {
            // Populate parent select dynamically
            const parentSelect = container.querySelector('#add-parent');
            parentSelect.innerHTML = '<option value="">-- Tanpa Parent (DVR Induk) --</option>';
            networkNodes.forEach(n => {
                if(n.type === 'dvr') {
                    parentSelect.innerHTML += `<option value="${n.id}">${n.name}</option>`;
                }
            });
            addModal.classList.add('show');
        });
        
        container.querySelector('#close-add-modal').addEventListener('click', () => addModal.classList.remove('show'));
        container.querySelector('#cancel-add').addEventListener('click', () => addModal.classList.remove('show'));
        container.querySelector('#close-detail-modal').addEventListener('click', () => detailModal.classList.remove('show'));

        container.querySelector('#submit-add').addEventListener('click', () => {
            const name = container.querySelector('#add-name').value;
            const ip = container.querySelector('#add-ip').value;
            const type = container.querySelector('#add-type').value;
            const x = parseInt(container.querySelector('#add-x').value);
            const y = parseInt(container.querySelector('#add-y').value);
            const parent = parseInt(container.querySelector('#add-parent').value);

            if (!name || !ip) {
                alert('Nama dan IP Address harus diisi!');
                return;
            }

            const newNode = {
                name: name,
                type: type,
                status: 'active',
                x: x,
                y: y,
                ip: ip,
                mac: type === 'cctv' ? null : 'XX:XX:XX:XX:XX:XX',
                parent: parent,
                image: type === 'cctv' ? 'images/cctv_gerbang_1786524324305.jpg' : null
            };

            const addedNode = Store.addNetworkNode(newNode);
            networkNodes.push(addedNode);

            addModal.classList.remove('show');
            container.querySelector('#add-name').value = '';
            container.querySelector('#add-ip').value = '';

            renderNodes();
            alert('Perangkat berhasil ditambahkan!');
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
