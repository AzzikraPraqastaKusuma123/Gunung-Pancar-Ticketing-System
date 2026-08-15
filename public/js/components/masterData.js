function renderMasterData() {
    const container = document.createElement('div');
    const data = Store.getData();
    let currentFilter = 'Area Camping';
    let showForm = false;
    
    function render() {
        const filteredData = data.master_data.filter(d => d.type === currentFilter);
        
        const rows = filteredData.map(d => `
            <tr>
                <td>${d.id}</td>
                <td>${d.name}</td>
                <td>${d.capacity}</td>
                <td>Rp ${d.price.toLocaleString('id-ID')}</td>
                <td>
                    <select class="form-control status-select" data-id="${d.id}" style="width:130px; padding:4px; font-size:0.8rem; background-color: ${d.status === 'Tersedia' ? 'var(--success)' : 'var(--warning)'}; color:white; border:none; border-radius:4px;">
                        <option value="Tersedia" ${d.status === 'Tersedia' ? 'selected' : ''}>Tersedia</option>
                        <option value="Maintenance" ${d.status === 'Maintenance' ? 'selected' : ''}>Maintenance</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-outline btn-delete" data-id="${d.id}" style="padding: 4px 8px; font-size: 0.8rem; color:var(--danger); border-color:var(--danger);"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('');

        container.innerHTML = `
            <div class="card mb-8">
                <div class="tabs">
                    <div class="tab ${currentFilter === 'Area Camping' ? 'active' : ''}" data-type="Area Camping">Area Camping</div>
                    <div class="tab ${currentFilter === 'Glamping' ? 'active' : ''}" data-type="Glamping">Glamping</div>
                    <div class="tab ${currentFilter === 'Villa' ? 'active' : ''}" data-type="Villa">Villa</div>
                    <div class="tab ${currentFilter === 'Gazebo' ? 'active' : ''}" data-type="Gazebo">Gazebo</div>
                    <div class="tab ${currentFilter === 'Meeting Room' ? 'active' : ''}" data-type="Meeting Room">Meeting Room</div>
                </div>
                
                <div class="section-header" style="margin-top: 24px;">
                    <h2>Data ${currentFilter}</h2>
                    <button class="btn btn-primary" id="btn-toggle-form"><i class="fa-solid fa-plus"></i> Tambah Data</button>
                </div>
                
                ${showForm ? `
                <div style="background:var(--bg-main); padding:16px; border-radius:8px; margin-bottom:20px; display:flex; gap:10px;">
                    <input type="text" id="md-name" class="form-control" placeholder="Nama / Lokasi">
                    <input type="text" id="md-cap" class="form-control" placeholder="Kapasitas (misal: 4 Orang)">
                    <input type="number" id="md-price" class="form-control" placeholder="Harga/Malam">
                    <select id="md-status" class="form-control">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                    <button class="btn btn-primary" id="btn-save-md">Simpan</button>
                </div>
                ` : ''}
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama / Lokasi</th>
                                <th>Kapasitas</th>
                                <th>Harga / Malam</th>
                                <th>Status</th>
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
                currentFilter = e.target.dataset.type;
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
        const saveBtn = container.querySelector('#btn-save-md');
        if(saveBtn) {
            saveBtn.addEventListener('click', () => {
                const name = container.querySelector('#md-name').value;
                const cap = container.querySelector('#md-cap').value;
                const price = container.querySelector('#md-price').value;
                const status = container.querySelector('#md-status').value;
                if(name && cap && price) {
                    const storeData = Store.getData();
                    storeData.master_data.push({
                        id: 'MD-' + Date.now(),
                        name: name,
                        capacity: cap,
                        price: parseInt(price),
                        type: currentFilter,
                        status: status
                    });
                    Store.saveData(storeData);
                    if(window.navigate) window.navigate('master-data');
                }
            });
        }

        // Change Status Live
        container.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', (e) => {
                const id = e.target.dataset.id;
                const newStatus = e.target.value;
                const storeData = Store.getData();
                const item = storeData.master_data.find(d => d.id === id);
                if(item) {
                    item.status = newStatus;
                    Store.saveData(storeData);
                    render(); // Re-render to update color
                }
            });
        });

        // Delete
        container.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const storeData = Store.getData();
                storeData.master_data = storeData.master_data.filter(d => d.id !== id);
                Store.saveData(storeData);
                if(window.navigate) window.navigate('master-data');
            });
        });
    }

    render();
    return container;
}
