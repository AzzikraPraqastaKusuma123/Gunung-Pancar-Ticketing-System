function renderLiveCCTV() {
    const container = document.createElement('div');
    container.className = 'page-container fade-in';
    
    // Internal Style
    const style = document.createElement('style');
    style.textContent = `
        .live-grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 24px;
        }
        
        .cctv-card-large {
            position: relative;
            background: #000;
            border-radius: var(--radius-lg);
            overflow: hidden;
            aspect-ratio: 16/9;
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        
        .cctv-card-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }
        
        .cctv-card-large::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 40%;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            pointer-events: none;
        }
        
        .cctv-card-large .label-bottom {
            position: absolute;
            bottom: 16px; left: 16px;
            z-index: 2;
        }
        
        .cctv-card-large .label-bottom h3 {
            font-size: 1.1rem;
            color: white;
            margin-bottom: 4px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        
        .cctv-card-large .label-bottom p {
            font-size: 0.8rem;
            color: var(--success);
            font-family: monospace;
        }
        
        .live-badge-large {
            position: absolute;
            top: 16px; right: 16px;
            background: rgba(220, 38, 38, 0.9); /* Red for live */
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            animation: blink-live 2s infinite;
        }
        
        @keyframes blink-live {
            0% { opacity: 1; }
            50% { opacity: 0.3; }
            100% { opacity: 1; }
        }
        
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-card);
            padding: 16px 24px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
        }
    `;
    container.appendChild(style);

    const nodes = Store.getData().network_nodes || [];
    const cctvs = nodes.filter(n => n.type === 'cctv');

    let cctvHtml = '';
    cctvs.forEach(cctv => {
        const imgSrc = cctv.image || 'https://images.unsplash.com/photo-1557053964-937650ddbfce?q=80&w=600&auto=format&fit=crop';
        const statusText = cctv.status === 'active' ? 'REC - 1080p 60fps' : (cctv.status === 'offline' ? 'CONNECTION LOST' : 'WARNING - SIGNAL LOW');
        const statusColor = cctv.status === 'active' ? 'var(--success)' : 'var(--danger)';
        const opacity = cctv.status === 'offline' ? '0.3' : '0.9';
        const liveBadge = cctv.status === 'active' ? '<div class="live-badge-large"><i class="fa-solid fa-circle" style="font-size: 0.5rem; margin-right: 4px; vertical-align: middle;"></i>LIVE</div>' : '';
        
        cctvHtml += `
            <div class="cctv-card-large">
                <img src="${imgSrc}" alt="${cctv.name}" style="opacity: ${opacity}; filter: ${cctv.status === 'offline' ? 'grayscale(100%)' : 'none'};">
                ${liveBadge}
                <div class="label-bottom">
                    <h3>${cctv.name}</h3>
                    <p style="color: ${statusColor};"><i class="fa-solid fa-video"></i> ${cctv.ip} | ${statusText}</p>
                </div>
            </div>
        `;
    });

    container.innerHTML += `
        <div class="toolbar">
            <h2 style="font-size: 1.2rem;">Live CCTV Wall</h2>
            <div style="display: flex; gap: 12px;">
                <select class="form-control" style="width: auto;">
                    <option>Semua Area</option>
                    <option>Area Camping</option>
                    <option>Area Glamping</option>
                </select>
                <button class="btn btn-outline"><i class="fa-solid fa-border-all"></i> Grid 3x3</button>
                <button class="btn btn-outline" onclick="document.documentElement.requestFullscreen()"><i class="fa-solid fa-expand"></i> Fullscreen</button>
            </div>
        </div>
        
        <div class="live-grid-container">
            ${cctvHtml}
        </div>
    `;

    return container;
}
