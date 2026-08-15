function renderRekaman() {
    const container = document.createElement('div');
    container.className = 'page-container fade-in';
    
    const style = document.createElement('style');
    style.textContent = `
        .rekaman-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 24px;
            margin-top: 16px;
        }
        
        .video-player {
            background: #000;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            overflow: hidden;
            aspect-ratio: 16/9;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .timeline {
            height: 40px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            margin-top: 16px;
            position: relative;
            overflow: hidden;
        }
        
        .timeline-track {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.05);
            display: flex;
        }
        
        .timeline-chunk {
            height: 100%;
            background: rgba(46, 160, 91, 0.5); /* Green for recorded */
            border-right: 1px solid rgba(0,0,0,0.5);
        }
        
        .timeline-cursor {
            position: absolute;
            top: 0; bottom: 0;
            width: 2px;
            background: var(--accent);
            left: 30%;
            z-index: 2;
        }
        
        .record-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .record-list li {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .record-list li:hover {
            background: var(--bg-hover);
        }
        
        .record-list li img {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
    `;
    container.appendChild(style);

    container.innerHTML += `
        <div class="card" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin-bottom: 4px;">Arsip Rekaman (Playback)</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Putar ulang rekaman dari CCTV yang terhubung ke NVR</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <input type="date" class="form-control" style="width: auto;" value="2026-08-13">
                    <select class="form-control" style="width: auto;">
                        <option>Semua Kamera</option>
                        <option>CCTV Gerbang</option>
                        <option>CCTV Glamping</option>
                        <option>CCTV Parkir</option>
                    </select>
                    <button class="btn btn-primary"><i class="fa-solid fa-search"></i> Cari</button>
                </div>
            </div>
        </div>
        
        <div class="rekaman-layout">
            <div class="player-section">
                <div class="video-player">
                    <img src="images/cctv_gerbang_1786524324305.jpg" alt="Video Placeholder" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.7;">
                    <i class="fa-solid fa-play" style="position: absolute; font-size: 4rem; color: rgba(255,255,255,0.8); cursor: pointer;"></i>
                    <div style="position: absolute; top: 16px; left: 16px; background: rgba(0,0,0,0.6); padding: 4px 12px; border-radius: 4px; font-family: monospace;">2026-08-13 14:32:01</div>
                </div>
                
                <div style="display: flex; justify-content: center; gap: 16px; margin-top: 16px;">
                    <button class="btn-icon"><i class="fa-solid fa-backward-step"></i></button>
                    <button class="btn-icon"><i class="fa-solid fa-play"></i></button>
                    <button class="btn-icon"><i class="fa-solid fa-forward-step"></i></button>
                    <button class="btn-icon" style="margin-left: 24px;"><i class="fa-solid fa-download"></i></button>
                </div>
                
                <div class="timeline">
                    <div class="timeline-track">
                        <div class="timeline-chunk" style="width: 25%;"></div>
                        <div class="timeline-chunk" style="width: 5%; background: rgba(239, 68, 68, 0.5);"></div> <!-- Motion detected -->
                        <div class="timeline-chunk" style="width: 40%;"></div>
                        <div class="timeline-chunk" style="width: 10%; background: transparent;"></div> <!-- Offline -->
                        <div class="timeline-chunk" style="width: 20%;"></div>
                    </div>
                    <div class="timeline-cursor"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.7rem; color: var(--text-muted); margin-top: 4px;">
                    <span>00:00</span><span>06:00</span><span>12:00</span><span>18:00</span><span>24:00</span>
                </div>
            </div>
            
            <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                <div style="padding: 16px; border-bottom: 1px solid var(--border-color); background: rgba(0,0,0,0.2);">
                    <h3 style="font-size: 1rem;">Klip Tersimpan</h3>
                </div>
                <ul class="record-list" style="overflow-y: auto; flex: 1; max-height: 500px;">
                    <li>
                        <img src="images/cctv_gerbang_1786524324305.jpg">
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">Pergerakan Terdeteksi</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">CCTV Gerbang | 14:32:01</div>
                        </div>
                    </li>
                    <li>
                        <img src="images/cctv_resepsionis_1786524352663.jpg">
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">Kendaraan Masuk</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">CCTV Parkir | 10:38:15</div>
                        </div>
                    </li>
                    <li>
                        <img src="images/cctv_glamping_1786524341566.jpg">
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">Pergerakan Terdeteksi</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">CCTV Glamping | 08:12:44</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    `;

    return container;
}
