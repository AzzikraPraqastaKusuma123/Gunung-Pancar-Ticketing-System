function renderKeuangan() {
    const container = document.createElement('div');
    const data = Store.getData();
    
    // Calculate 
    let pendapatan = 0;
    let pengeluaran = 0;
    
    data.finance.forEach(t => {
        if(t.type === 'Pendapatan') pendapatan += t.amount;
        if(t.type === 'Pengeluaran') pengeluaran += t.amount;
    });
    
    const profit = pendapatan - pengeluaran;
    
    const formatRp = (num) => 'Rp ' + num.toLocaleString('id-ID');
    
    const financeRows = data.finance.sort((a,b) => b.id - a.id).map(t => `
        <tr>
            <td><i class="fa-regular fa-calendar" style="color:var(--text-muted); font-size:0.8rem; margin-right:4px;"></i> ${t.date}</td>
            <td style="font-weight: 500;">${t.category}</td>
            <td>${t.desc}</td>
            <td style="color: ${t.type === 'Pendapatan' ? 'var(--success)' : 'var(--danger)'}; font-weight:700;">
                ${t.type === 'Pendapatan' ? '+' : '-'} ${formatRp(t.amount)}
            </td>
            <td><span class="status ${t.type === 'Pendapatan' ? 'active' : 'inactive'}">${t.type}</span></td>
        </tr>
    `).join('');

    container.innerHTML = `
        <div class="section-header">
            <h2>Laporan Keuangan</h2>
            <button class="btn btn-primary" id="btn-toggle-form"><i class="fa-solid fa-file-invoice-dollar"></i> Catat Transaksi</button>
        </div>
        
        <div class="grid grid-cols-4" style="margin-bottom: 24px;">
            <div class="card stat-card bg-success-light">
                <div class="stat-icon" style="color:var(--success);"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Total Pendapatan</span>
                    <span class="stat-value">${formatRp(pendapatan)}</span>
                </div>
            </div>
            
            <div class="card stat-card bg-danger-light">
                <div class="stat-icon" style="color:var(--danger);"><i class="fa-solid fa-arrow-trend-down"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Total Pengeluaran</span>
                    <span class="stat-value">${formatRp(pengeluaran)}</span>
                </div>
            </div>
            
            <div class="card stat-card bg-primary-light">
                <div class="stat-icon" style="color:var(--primary);"><i class="fa-solid fa-sack-dollar"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Profit Bersih</span>
                    <span class="stat-value">${formatRp(profit)}</span>
                </div>
            </div>
            
            <div class="card stat-card bg-info-light">
                <div class="stat-icon" style="color:var(--info);"><i class="fa-solid fa-chart-pie"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Status Margin</span>
                    <span class="stat-value" style="font-size: 1.2rem;">${profit > 0 ? 'Positif (Untung)' : 'Negatif (Rugi)'}</span>
                </div>
            </div>
        </div>

        <div class="card mb-8">
            <div class="tabs">
                <div class="tab active">Daftar Transaksi</div>
            </div>
            
            <div class="table-container" style="margin-top:20px;">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                            <th>Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${financeRows.length > 0 ? financeRows : '<tr><td colspan="5" style="text-align:center;">Tidak ada transaksi</td></tr>'}
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Modal Transaksi -->
        <div class="global-modal-overlay" id="fin-modal">
            <div class="global-modal">
                <div class="modal-header-g">
                    <i class="fa-solid fa-file-invoice-dollar" style="font-size: 1.2rem;"></i>
                    <h3>Catat Transaksi Manual</h3>
                    <button class="modal-close-g" id="btn-close-fin-modal"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body-g">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Keterangan Transaksi</label>
                        <input type="text" id="t-desc" class="form-control" placeholder="Contoh: Beli Kopi">
                    </div>
                    <div class="grid grid-cols-2" style="gap: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Jenis Transaksi</label>
                            <select id="t-type" class="form-control">
                                <option value="Pendapatan">Pendapatan</option>
                                <option value="Pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Nominal (Rp)</label>
                            <input type="number" id="t-amount" class="form-control" placeholder="50000">
                        </div>
                    </div>
                    <button class="btn btn-primary" id="btn-add-trans" style="width: 100%; margin-top: 8px;">
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Modal Logic
    const finModal = container.querySelector('#fin-modal');
    const toggleBtn = container.querySelector('#btn-toggle-form');
    const closeBtn = container.querySelector('#btn-close-fin-modal');

    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            finModal.classList.add('show');
        });
    }
    if(closeBtn) {
        closeBtn.addEventListener('click', () => {
            finModal.classList.remove('show');
        });
    }

    // Add transaction logic
    const btnAdd = container.querySelector('#btn-add-trans');
    if(btnAdd) {
        btnAdd.addEventListener('click', () => {
            const desc = container.querySelector('#t-desc').value;
            const amount = parseInt(container.querySelector('#t-amount').value);
            const type = container.querySelector('#t-type').value;

            if(!desc || !amount) {
                alert('Lengkapi keterangan dan nominal!');
                return;
            }

            Store.addFinance({
                date: new Date().toISOString().split('T')[0],
                category: 'Manual Entry',
                desc: desc,
                amount: amount,
                type: type
            });
            
            finModal.classList.remove('show');
            if(window.navigate) window.navigate('keuangan');
        });
    }

    return container;
}
