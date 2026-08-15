function renderLaporan() {
    const container = document.createElement('div');
    container.className = 'page-container fade-in';
    container.innerHTML = `
        <h2 style="margin-bottom: 24px;">Laporan Sistem CCTV</h2>
        <div class="grid-5" style="margin-bottom: 24px; gap: 16px; display: grid; grid-template-columns: repeat(3, 1fr);">
            <div class="stat-card-modern" style="background: rgba(0,0,0,0.2); padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 8px;">Rata-rata Uptime</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--success);">99.8%</div>
            </div>
            <div class="stat-card-modern" style="background: rgba(0,0,0,0.2); padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 8px;">Penyimpanan Terpakai (Bulan Ini)</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--warning);">1.2 TB</div>
            </div>
            <div class="stat-card-modern" style="background: rgba(0,0,0,0.2); padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 8px;">Total Insiden Terdeteksi</div>
                <div style="font-size: 2rem; font-weight: 700; color: var(--danger);">14</div>
            </div>
        </div>
        <div class="card" style="margin-top: 24px;">
            <h3 style="margin-bottom: 16px; font-size: 1rem;">Penggunaan Penyimpanan DVR (7 Hari Terakhir)</h3>
            <div style="height: 250px; display: flex; align-items: flex-end; gap: 12px; padding: 20px 0; border-bottom: 1px solid var(--border-color); position: relative;">
                <!-- Dummy CSS Bar Chart -->
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 8px;">
                    <div style="width: 100%; max-width: 60px; height: 120px; background: var(--primary); border-radius: 4px 4px 0 0; transition: height 0.5s;"></div>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Senin</span>
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 8px;">
                    <div style="width: 100%; max-width: 60px; height: 150px; background: var(--primary); border-radius: 4px 4px 0 0;"></div>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Selasa</span>
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 8px;">
                    <div style="width: 100%; max-width: 60px; height: 90px; background: var(--primary); border-radius: 4px 4px 0 0;"></div>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Rabu</span>
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 8px;">
                    <div style="width: 100%; max-width: 60px; height: 180px; background: var(--warning); border-radius: 4px 4px 0 0;"></div>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Kamis</span>
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 8px;">
                    <div style="width: 100%; max-width: 60px; height: 210px; background: var(--danger); border-radius: 4px 4px 0 0;"></div>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Jumat</span>
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 8px;">
                    <div style="width: 100%; max-width: 60px; height: 140px; background: var(--primary); border-radius: 4px 4px 0 0;"></div>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Sabtu</span>
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 8px;">
                    <div style="width: 100%; max-width: 60px; height: 100px; background: var(--primary); border-radius: 4px 4px 0 0;"></div>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Minggu</span>
                </div>
            </div>
        </div>
    `;
    return container;
}
