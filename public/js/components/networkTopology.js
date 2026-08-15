function renderNetworkTopology() {
    const container = document.createElement('div');
    container.className = 'network-topology-container';
    
    // Custom Styles for Network Topology
    const style = document.createElement('style');
    style.textContent = `
        :root {
            --topo-primary: #0ea5e9;
            --topo-danger: #ef4444;
            --topo-warning: #f59e0b;
            --topo-bg: #020617;
            --topo-card-bg: rgba(15, 23, 42, 0.85);
            --topo-border: rgba(14, 165, 233, 0.2);
        }

        .topo-dashboard-grid {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* The main topology area */
        .topo-wrapper {
            position: relative;
            background-color: var(--topo-bg);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--topo-border);
            min-height: 700px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 40px rgba(0,0,0,0.6);
        }
        
        /* Topology Background with Grid Overlay */
        .topo-area {
            flex: 1;
            position: relative;
            overflow: hidden;
            background: radial-gradient(circle at center, #0f172a 0%, #020617 100%);
        }

        /* Tech Grid Overlay */
        .topo-area::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                linear-gradient(rgba(14, 165, 233, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(14, 165, 233, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 1;
        }

        /* Glowing center effect */
        .topo-area::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 400px; height: 400px;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }

        /* SVG Topology Layers */
        .topo-svg-layer {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 2;
        }
        
        .topo-line {
            stroke: rgba(14, 165, 233, 0.5);
            stroke-width: 2;
            stroke-dasharray: 6, 6;
            animation: topo-dash 15s linear infinite;
        }

        .topo-line.offline {
            stroke: rgba(239, 68, 68, 0.3);
            animation: none;
            stroke-dasharray: none;
        }

        @keyframes topo-dash {
            to { stroke-dashoffset: -1000; }
        }
        
        /* Nodes */
        .topo-node {
            position: absolute;
            background: var(--topo-card-bg);
            border-radius: 12px;
            border: 1px solid var(--topo-border);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            transform: translate(-50%, -50%);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
            backdrop-filter: blur(12px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            min-width: 180px;
        }
        
        .topo-node:hover {
            transform: translate(-50%, -50%) scale(1.05);
            border-color: #38bdf8;
            box-shadow: 0 0 35px rgba(14, 165, 233, 0.4);
            z-index: 20;
        }

        .topo-node.dvr {
            border-width: 2px;
            border-color: rgba(139, 92, 246, 0.6);
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.2);
            min-width: 220px;
            background: rgba(15, 23, 42, 0.95);
        }
        
        .topo-node.dvr:hover {
            border-color: #a78bfa;
            box-shadow: 0 0 40px rgba(139, 92, 246, 0.5);
        }

        .topo-node.offline {
            border-color: rgba(239, 68, 68, 0.4);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.1);
            opacity: 0.85;
        }

        .node-icon-wrapper {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: rgba(14, 165, 233, 0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; color: var(--topo-primary);
        }

        .topo-node.dvr .node-icon-wrapper {
            background: rgba(139, 92, 246, 0.15);
            color: #a78bfa;
            font-size: 1.5rem;
        }

        .topo-node.offline .node-icon-wrapper {
            background: rgba(239, 68, 68, 0.1);
            color: var(--topo-danger);
        }

        .node-info { display: flex; flex-direction: column; }
        .node-name { font-weight: 700; color: #f8fafc; font-size: 0.95rem; margin-bottom: 2px; }
        .node-ip { font-family: monospace; font-size: 0.75rem; color: #94a3b8; }
        .node-status { font-size: 0.65rem; padding: 2px 8px; border-radius: 6px; display: inline-block; margin-top: 6px; width: fit-content; font-weight: 700; letter-spacing: 0.5px;}
        .status-active { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .status-offline { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

        /* Header Info */
        .topo-header {
            position: absolute; top: 24px; left: 30px; z-index: 10;
        }
        .topo-title { font-size: 1.6rem; font-weight: 800; color: white; margin: 0; letter-spacing: 1px; display: flex; align-items: center; gap: 12px;}
        .topo-subtitle { font-size: 0.8rem; color: #94a3b8; margin-top: 6px; font-family: monospace; letter-spacing: 2px;}
        
        .pulse-dot {
            width: 14px; height: 14px; border-radius: 50%; background: #22c55e;
            box-shadow: 0 0 12px #22c55e; animation: pulse 2s infinite;
        }
        @keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.6; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
    `;

    // Fetch Data from Store
    let networkNodes = Store.getData().network_nodes || [];
    
    container.appendChild(style);

    const contentDiv = document.createElement('div');
    contentDiv.innerHTML = `
        <div class="topo-dashboard-grid">
            <div class="topo-wrapper">
                <div class="topo-header">
                    <h2 class="topo-title"><div class="pulse-dot"></div> Live Network Topology</h2>
                    <div class="topo-subtitle">DATA CENTER MAPPING & ROUTING</div>
                </div>
                
                <div class="topo-area" id="topo-area">
                    <svg id="topo-lines-svg" class="topo-svg-layer"></svg>
                    <!-- Nodes injected dynamically -->
                </div>
            </div>
        </div>
    `;
    container.appendChild(contentDiv);

    setTimeout(() => {
        const topoArea = container.querySelector('#topo-area');
        const svg = container.querySelector('#topo-lines-svg');
        
        // Ensure SVG matches container size dynamically
        const updateSVGSize = () => {
            svg.setAttribute('viewBox', \`0 0 \${topoArea.offsetWidth} \${topoArea.offsetHeight}\`);
        };
        updateSVGSize();

        // Let's create a radial layout.
        const dvrs = networkNodes.filter(n => n.type === 'dvr');
        const cctvs = networkNodes.filter(n => n.type === 'cctv');
        
        // Calculate Center
        const centerX = topoArea.offsetWidth / 2;
        const centerY = topoArea.offsetHeight / 2;

        let centerNodesData = [];
        
        // Place DVRs in center (if multiple, offset them slightly)
        if(dvrs.length > 0) {
            dvrs.forEach((dvr, idx) => {
                const offset = dvrs.length > 1 ? (idx === 0 ? -120 : 120) : 0;
                dvr.renderX = centerX + offset;
                dvr.renderY = centerY;
                centerNodesData.push(dvr);
            });
        } else {
             // Mock a core switch if no DVR
             centerNodesData.push({id: 'core', renderX: centerX, renderY: centerY});
        }

        // Place CCTVs in a circle
        const radius = Math.min(centerX, centerY) - 100; // Responsive radius
        const angleStep = (Math.PI * 2) / Math.max(1, cctvs.length);

        cctvs.forEach((cctv, idx) => {
            const angle = idx * angleStep;
            cctv.renderX = centerX + Math.cos(angle) * radius;
            cctv.renderY = centerY + Math.sin(angle) * radius;
        });

        // Render Lines
        cctvs.forEach((cctv) => {
            // connect to the first DVR or core
            const parent = centerNodesData[0];
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', parent.renderX);
            line.setAttribute('y1', parent.renderY);
            line.setAttribute('x2', cctv.renderX);
            line.setAttribute('y2', cctv.renderY);
            line.setAttribute('class', \`topo-line \${cctv.status === 'offline' ? 'offline' : ''}\`);
            svg.appendChild(line);
        });

        // Render Nodes HTML
        const renderNodeHTML = (node) => {
            const isDvr = node.type === 'dvr';
            const isOffline = node.status === 'offline';
            const icon = isDvr ? 'fa-server' : 'fa-video';
            const statusText = isOffline ? 'OFFLINE' : 'ONLINE';
            const statusClass = isOffline ? 'status-offline' : 'status-active';
            
            const el = document.createElement('div');
            el.className = \`topo-node \${isDvr ? 'dvr' : ''} \${isOffline ? 'offline' : ''}\`;
            el.style.left = \`\${node.renderX}px\`;
            el.style.top = \`\${node.renderY}px\`;
            el.innerHTML = \`
                <div class="node-icon-wrapper">
                    <i class="fa-solid \${icon}"></i>
                </div>
                <div class="node-info">
                    <div class="node-name">\${node.name}</div>
                    <div class="node-ip">\${node.ip_address || '192.168.1.x'}</div>
                    <div class="node-status \${statusClass}">\${statusText}</div>
                </div>
            \`;
            topoArea.appendChild(el);
        };

        dvrs.forEach(renderNodeHTML);
        cctvs.forEach(renderNodeHTML);

    }, 200);

    return container;
}
