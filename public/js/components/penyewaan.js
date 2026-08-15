function renderPenyewaan() {
    const container = document.createElement('div');
    const data = Store.getData();
    let currentTab = 'Sedang Disewa';
    let showForm = false;
    
    function render() {
        const rentals = data.rentals.filter(r => currentTab === 'Sedang Disewa' ? r.status === 'Dipinjam' : r.status !== 'Dipinjam');
        
        // Generate dropdown for available assets
        const availableAssets = data.inventory.filter(i => i.category === 'Asset (Sewa)' && i.stock > 0);
        const assetOptions = availableAssets.map(a => `<option value="${a.name}">${a.name} (Stok: ${a.stock})</option>`).join('');

        const rows = rentals.map(r => `
            <tr>
                <td>${r.id}</td>
                <td>${r.name}</td>
                <td>${r.items}</td>
                <td>${r.out}</td>
                <td>${r.in}</td>
                <td><span class="status ${r.status === 'Dipinjam' ? 'pending' : 'active'}">${r.status}</span></td>
                <td>
                    ${r.status === 'Dipinjam' ? `<button class="btn btn-outline btn-return" data-id="${r.id}" style="padding: 4px 8px; font-size: 0.8rem;">Kembalikan</button>` : '-'}
                </td>
            </tr>
        `).join('');

        container.innerHTML = `
            <div class="card mb-8">
                <div class="section-header">
                    <h2>Penyewaan Alat Camping</h2>
                    <button class="btn btn-primary" id="btn-toggle-form"><i class="fa-solid fa-plus"></i> Buat Penyewaan Baru</button>
                </div>
                
                ${showForm ? `
                <div style="background:var(--bg-main); padding:16px; border-radius:8px; margin-bottom:20px;">
                    <div class="grid grid-cols-2" style="gap:16px;">
                        <div>
                            <div class="form-group">
                                <label>Nama Penyewa</label>
                                <input type="text" id="rn-name" class="form-control" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group">
                                <label>Pilih Barang (Asset)</label>
                                <select id="rn-item" class="form-control">
                                    <option value="">-- Pilih Barang --</option>
                                    ${assetOptions}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Pinjam</label>
                                <input type="number" id="rn-qty" class="form-control" value="1" min="1">
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label>Tanggal Keluar</label>
                                <input type="date" id="rn-out" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Kembali</label>
                                <input type="date" id="rn-in" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Biaya Sewa (Total Keseluruhan)</label>
                                <input type="number" id="rn-price" class="form-control" placeholder="Misal: 50000">
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:flex-end; margin-top:10px;">
                        <button class="btn btn-primary" id="btn-save-rn">Simpan & Proses Pembayaran</button>
                    </div>
                </div>
                ` : ''}
                
                <div class="tabs">
                    <div class="tab ${currentTab === 'Sedang Disewa' ? 'active' : ''}" data-type="Sedang Disewa">Sedang Disewa</div>
                    <div class="tab ${currentTab === 'Riwayat' ? 'active' : ''}" data-type="Riwayat">Riwayat Penyewaan</div>
                </div>
                
                <div class="table-container" style="margin-top:20px;">
                    <table>
                        <thead>
                            <tr>
                                <th>No Referensi</th>
                                <th>Nama Penyewa</th>
                                <th>Alat Disewa</th>
                                <th>Waktu Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows.length > 0 ? rows : '<tr><td colspan="7" style="text-align:center;">Tidak ada data</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>
        `;

        // Tab events
        container.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                currentTab = e.target.dataset.type;
                showForm = false;
                render();
            });
        });

        // Toggle form
        const toggleBtn = container.querySelector('#btn-toggle-form');
        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                showForm = !showForm;
                render();
            });
        }

        // Save Rental
        const saveBtn = container.querySelector('#btn-save-rn');
        if(saveBtn) {
            saveBtn.addEventListener('click', () => {
                const name = container.querySelector('#rn-name').value;
                const item = container.querySelector('#rn-item').value;
                const qty = container.querySelector('#rn-qty').value;
                const outDate = container.querySelector('#rn-out').value;
                const inDate = container.querySelector('#rn-in').value;
                const totalAmount = container.querySelector('#rn-price').value || 0;
                
                if(!name || !item || !outDate || !inDate) {
                    alert('Lengkapi Nama, Barang, dan Tanggal!');
                    return;
                }
                
                Store.addRental({
                    name, 
                    items: qty + 'x ' + item, 
                    out: outDate, 
                    in: inDate,
                    totalAmount: totalAmount
                });
                
                alert('Penyewaan berhasil disimpan! Pemasukan Rp ' + parseInt(totalAmount).toLocaleString('id-ID') + ' tercatat di Keuangan.');
                
                if(window.navigate) window.navigate('penyewaan');
            });
        }

        // Return Item
        container.querySelectorAll('.btn-return').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const storeData = Store.getData();
                const rental = storeData.rentals.find(r => r.id === id);
                if(rental) rental.status = 'Dikembalikan';
                Store.saveData(storeData);
                if(window.navigate) window.navigate('penyewaan');
            });
        });
    }

    render();
    return container;
}
