function renderNotifikasi() {
    const container = document.createElement('div');
    container.className = 'page-container fade-in';
    container.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="margin-bottom: 4px;">Log Peringatan & Notifikasi</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Daftar semua aktivitas anomali dan peringatan sistem</p>
            </div>
            <button class="btn btn-outline">Tandai Semua Dibaca</button>
        </div>
        <table class="device-table" style="width: 100%; border-collapse: collapse; margin-top: 16px;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); text-align: left; color: var(--text-muted);">
                    <th style="padding: 12px;">Waktu</th>
                    <th style="padding: 12px;">Keparahan</th>
                    <th style="padding: 12px;">Lokasi / Perangkat</th>
                    <th style="padding: 12px;">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--border-color); background: rgba(239, 68, 68, 0.1);">
                    <td style="padding: 12px;">Hari ini, 14:32</td>
                    <td style="padding: 12px;"><span class="badge" style="background: var(--danger); color: white;">KRITIS</span></td>
                    <td style="padding: 12px;">CCTV Parkir</td>
                    <td style="padding: 12px;">Koneksi terputus secara tiba-tiba. Video loss.</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 12px;">Hari ini, 10:42</td>
                    <td style="padding: 12px;"><span class="badge" style="background: var(--info); color: white;">INFO</span></td>
                    <td style="padding: 12px;">Area Camping B</td>
                    <td style="padding: 12px;">Pergerakan terdeteksi di luar jam operasional.</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--border-color); background: rgba(234, 179, 8, 0.1);">
                    <td style="padding: 12px;">Hari ini, 09:15</td>
                    <td style="padding: 12px;"><span class="badge" style="background: var(--warning); color: white;">WARNING</span></td>
                    <td style="padding: 12px;">CCTV Camping B</td>
                    <td style="padding: 12px;">Gangguan sinyal analog (noise interference) pada kabel koaksial.</td>
                </tr>
            </tbody>
        </table>
    `;
    return container;
}
