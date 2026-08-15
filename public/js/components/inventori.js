function renderInventori() {
    const container = document.createElement('div');
    const data = Store.getData();
    let currentTab = 'Consumable';
    let showForm = false;
    
    function render() {
        const filtered = data.inventory.filter(i => i.category === currentTab);
        
        const rows = filtered.map(i => `
            <tr>
                <td><i class="fa-solid fa-qrcode" style="color:var(--primary); font-size:1.2rem;"></i> ${i.id}</td>
                <td>${i.name}</td>
                <td>${i.category}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <button class="btn-icon btn-stock-minus" data-id="${i.id}" style="font-size:0.8rem; padding:4px;"><i class="fa-solid fa-minus"></i></button>
                        <span style="font-weight:bold; min-width:30px; text-align:center;">${i.stock}</span>
                        <button class="btn-icon btn-stock-plus" data-id="${i.id}" style="font-size:0.8rem; padding:4px;"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </td>
                <td><span class="status ${i.status === 'Baik' ? 'active' : 'pending'}">${i.status}</span></td>
                <td>
                    <button class="btn btn-outline btn-delete" data-id="${i.id}" style="padding: 4px 8px; font-size: 0.8rem; color:var(--danger); border-color:var(--danger);"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('');

        container.innerHTML = `
            <div class="card mb-8">
                <div class="section-header">
                    <h2>Manajemen Inventori & Aset</h2>
                    <div>
                        <button class="btn btn-primary" id="btn-toggle-form"><i class="fa-solid fa-plus"></i> Tambah Barang Baru</button>
                    </div>
                </div>
                
                ${showForm ? `
                <div style="background:var(--bg-main); padding:16px; border-radius:8px; margin-bottom:20px; display:flex; gap:10px;">
                    <input type="text" id="inv-name" class="form-control" placeholder="Nama Barang">
                    <input type="number" id="inv-stock" class="form-control" placeholder="Jumlah Stok Awal">
                    <button class="btn btn-primary" id="btn-save-inv">Simpan</button>
                </div>
                ` : ''}
                
                <div class="tabs">
                    <div class="tab ${currentTab === 'Consumable' ? 'active' : ''}" data-type="Consumable">Barang Habis Pakai (Gas, dll)</div>
                    <div class="tab ${currentTab === 'Asset (Sewa)' ? 'active' : ''}" data-type="Asset (Sewa)">Aset Tetap (Tenda, dll)</div>
                </div>
                
                <div class="table-container" style="margin-top:20px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode/QR</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok Tersedia</th>
                                <th>Kondisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows.length > 0 ? rows : '<tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>'}
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

        // Save
        const saveBtn = container.querySelector('#btn-save-inv');
        if(saveBtn) {
            saveBtn.addEventListener('click', () => {
                const name = container.querySelector('#inv-name').value;
                const stock = container.querySelector('#inv-stock').value;
                
                if(name && stock) {
                    Store.addInventory({
                        name: name,
                        stock: parseInt(stock),
                        category: currentTab
                    });
                    if(window.navigate) window.navigate('inventori');
                }
            });
        }

        // Edit Stock Live
        container.querySelectorAll('.btn-stock-plus').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const storeData = Store.getData();
                const item = storeData.inventory.find(i => i.id === id);
                if(item) {
                    item.stock += 1;
                    Store.saveData(storeData);
                    render();
                }
            });
        });
        
        container.querySelectorAll('.btn-stock-minus').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const storeData = Store.getData();
                const item = storeData.inventory.find(i => i.id === id);
                if(item && item.stock > 0) {
                    item.stock -= 1;
                    Store.saveData(storeData);
                    render();
                }
            });
        });

        // Delete
        container.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const storeData = Store.getData();
                storeData.inventory = storeData.inventory.filter(i => i.id !== id);
                Store.saveData(storeData);
                if(window.navigate) window.navigate('inventori');
            });
        });
    }

    render();
    return container;
}
