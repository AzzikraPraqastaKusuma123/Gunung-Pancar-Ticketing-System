function renderHR() {
    const container = document.createElement('div');
    const data = Store.getData();
    
    function render() {
        const rows = data.employees.map(e => `
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="https://ui-avatars.com/api/?name=${e.name.replace(' ', '+')}&background=random" style="width:30px; height:30px; border-radius:50%;">
                        <span style="font-weight: 500;">${e.name}</span>
                    </div>
                </td>
                <td>${e.role}</td>
                <td><span style="font-family: monospace; color: var(--text-muted);">${e.time || '-'}</span></td>
                <td><span class="status ${e.status === 'Hadir' ? 'active' : 'pending'}">${e.status}</span></td>
                <td>
                    ${e.status === 'Hadir' ? '<i class="fa-solid fa-location-dot" style="color:var(--success);"></i> Dalam Area Pos' : '-'}
                </td>
                <td>
                    ${e.status !== 'Hadir' ? `<button class="btn btn-outline btn-absen" data-id="${e.id}" style="padding: 4px 12px; font-size: 0.8rem; border-radius: 20px;">Absen Masuk</button>` : `<button class="btn btn-outline btn-delete" data-id="${e.id}" style="padding: 4px 12px; font-size: 0.8rem; border-radius: 20px; color:var(--danger); border-color:var(--danger);"><i class="fa-solid fa-trash"></i></button>`}
                </td>
            </tr>
        `).join('');

        const presentCount = data.employees.filter(e => e.status === 'Hadir').length;
        const totalCount = data.employees.length;
        const absentCount = totalCount - presentCount;

        container.innerHTML = `
            <div class="section-header">
                <h2>Manajemen SDM & Kehadiran</h2>
                <button class="btn btn-primary" id="btn-toggle-form"><i class="fa-solid fa-user-plus"></i> Tambah Karyawan</button>
            </div>
            
            <div class="grid grid-cols-4" style="margin-bottom: 24px;">
                <div class="card stat-card bg-primary-light">
                    <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-info"><span class="stat-label">Total Karyawan</span><span class="stat-value">${totalCount}</span></div>
                </div>
                <div class="card stat-card bg-success-light">
                    <div class="stat-icon"><i class="fa-solid fa-user-check"></i></div>
                    <div class="stat-info"><span class="stat-label">Hadir Hari Ini</span><span class="stat-value">${presentCount}</span></div>
                </div>
                <div class="card stat-card bg-warning-light">
                    <div class="stat-icon"><i class="fa-solid fa-user-clock"></i></div>
                    <div class="stat-info"><span class="stat-label">Belum Absen</span><span class="stat-value">${absentCount}</span></div>
                </div>
                <div class="card stat-card bg-info-light">
                    <div class="stat-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="stat-info"><span class="stat-label">Shift Saat Ini</span><span class="stat-value">Pagi</span></div>
                </div>
            </div>
            
            <div class="card mb-8">
                <div class="tabs">
                    <div class="tab active">Data Kehadiran & Karyawan</div>
                </div>
                
                <div class="table-container" style="margin-top:20px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>Posisi / Divisi</th>
                                <th>Jam Masuk</th>
                                <th>Status Absen</th>
                                <th>Lokasi GPS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows.length > 0 ? rows : '<tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Tambah Karyawan -->
            <div class="global-modal-overlay" id="hr-modal">
                <div class="global-modal">
                    <div class="modal-header-g">
                        <i class="fa-solid fa-user-plus" style="font-size: 1.2rem;"></i>
                        <h3>Tambah Karyawan Baru</h3>
                        <button class="modal-close-g" id="btn-close-hr-modal"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body-g">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Nama Lengkap</label>
                            <input type="text" id="hr-name" class="form-control" placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Posisi / Divisi</label>
                            <input type="text" id="hr-role" class="form-control" placeholder="Contoh: Security">
                        </div>
                        <button class="btn btn-primary" id="btn-save-hr" style="width: 100%; margin-top: 8px;">
                            Simpan Karyawan
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Modal Logic
        const hrModal = container.querySelector('#hr-modal');
        const toggleBtn = container.querySelector('#btn-toggle-form');
        const closeBtn = container.querySelector('#btn-close-hr-modal');

        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                hrModal.classList.add('show');
            });
        }
        if(closeBtn) {
            closeBtn.addEventListener('click', () => {
                hrModal.classList.remove('show');
            });
        }

        // Save
        const saveBtn = container.querySelector('#btn-save-hr');
        if(saveBtn) {
            saveBtn.addEventListener('click', () => {
                const name = container.querySelector('#hr-name').value;
                const role = container.querySelector('#hr-role').value;
                if(name && role) {
                    Store.addEmployee({ name, role });
                    hrModal.classList.remove('show');
                    if(window.navigate) window.navigate('hr');
                } else {
                    alert('Lengkapi nama dan posisi!');
                }
            });
        }
        
        // Absen In
        container.querySelectorAll('.btn-absen').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const storeData = Store.getData();
                const emp = storeData.employees.find(x => x.id === id);
                if(emp) {
                    emp.status = 'Hadir';
                    const d = new Date();
                    emp.time = d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0') + ' WIB';
                }
                Store.saveData(storeData);
                if(window.navigate) window.navigate('hr');
            });
        });
        
        // Delete
        container.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if(confirm('Hapus data karyawan ini?')) {
                    const id = e.currentTarget.dataset.id;
                    const storeData = Store.getData();
                    storeData.employees = storeData.employees.filter(x => x.id !== id);
                    Store.saveData(storeData);
                    if(window.navigate) window.navigate('hr');
                }
            });
        });
    }

    render();
    return container;
}
