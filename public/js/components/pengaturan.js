function renderPengaturan() {
    const container = document.createElement('div');
    container.className = 'page-container fade-in';
    
    const style = document.createElement('style');
    style.textContent = `
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }
        .settings-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .settings-card h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }
        .toggle-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .toggle-switch:last-child {
            border-bottom: none;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(255,255,255,0.2); transition: .4s; border-radius: 24px;
        }
        .slider:before {
            position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
            background-color: white; transition: .4s; border-radius: 50%;
        }
        input:checked + .slider { background-color: var(--success); }
        input:checked + .slider:before { transform: translateX(20px); }
        
        /* Modal Style */
        .custom-modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            z-index: 10000; opacity: 0; pointer-events: none; transition: opacity 0.3s;
        }
        .custom-modal-overlay.active {
            opacity: 1; pointer-events: all;
        }
        .custom-modal {
            background: var(--bg-card); border-radius: var(--radius-lg);
            border: 1px solid var(--border-color); width: 100%; max-width: 400px;
            padding: 24px; transform: translateY(20px); transition: transform 0.3s;
        }
        .custom-modal-overlay.active .custom-modal {
            transform: translateY(0);
        }
    `;
    container.appendChild(style);

    container.innerHTML += `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="margin-bottom: 4px;">Konfigurasi Sistem</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola preferensi operasional Command Center dan hak akses pengguna</p>
            </div>
            <button class="btn btn-primary" onclick="showToast('Semua pengaturan berhasil disimpan!', 'success')"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
        </div>
        
        <div class="settings-grid">
            <div class="settings-card">
                <h3><i class="fa-solid fa-desktop" style="color: var(--info);"></i> Sistem & Tampilan</h3>
                <div class="form-group">
                    <label>Tema Antarmuka</label>
                    <select class="form-control"><option selected>Dark Forest Glassmorphism</option></select>
                </div>
                <div class="toggle-switch">
                    <span>Tampilkan Status Timestamp di Feed</span>
                    <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
                </div>
            </div>

            <div class="settings-card">
                <h3><i class="fa-solid fa-hard-drive" style="color: var(--warning);"></i> Penyimpanan DVR</h3>
                <div class="form-group">
                    <label>Kualitas Rekaman Default</label>
                    <select class="form-control"><option selected>1080p (Standar)</option></select>
                </div>
                <div class="toggle-switch">
                    <span>Backup Otomatis ke Cloud AWS</span>
                    <label class="switch"><input type="checkbox"><span class="slider"></span></label>
                </div>
            </div>

            <div class="settings-card">
                <h3><i class="fa-solid fa-shield-halved" style="color: var(--danger);"></i> Keamanan & Alert</h3>
                <div class="toggle-switch">
                    <span>Bunyikan Alarm jika CCTV Mati</span>
                    <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
                </div>
                <div class="toggle-switch">
                    <span>Autentikasi Dua Langkah (2FA)</span>
                    <label class="switch"><input type="checkbox"><span class="slider"></span></label>
                </div>
            </div>
        </div>
        
        <!-- Manajemen Pengguna -->
        <div class="card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
                <h3 style="font-size: 1.1rem; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-users-gear" style="color: var(--primary);"></i> Manajemen Pengguna & Role</h3>
                ${isViewer ? '' : '<button class="btn btn-outline" id="btn-show-add-user" style="padding: 6px 16px; font-size: 0.85rem;"><i class="fa-solid fa-user-plus"></i> Tambah User Baru</button>'}
            </div>
            
            <div style="background: rgba(0,0,0,0.3); border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color); background: rgba(255,255,255,0.05);">
                            <th style="padding: 14px 16px; font-size: 0.85rem; font-weight: 600;">Nama Pengguna</th>
                            <th style="padding: 14px 16px; font-size: 0.85rem; font-weight: 600;">Jabatan / Posisi</th>
                            <th style="padding: 14px 16px; font-size: 0.85rem; font-weight: 600;">Role Sistem</th>
                            <th style="padding: 14px 16px; font-size: 0.85rem; font-weight: 600;">Status</th>
                            <th style="padding: 14px 16px; font-size: 0.85rem; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        <!-- Injected dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Modal Tambah/Edit User -->
        <div class="custom-modal-overlay" id="add-user-modal">
            <div class="custom-modal">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;" id="modal-title">Tambah Pengguna Baru</h3>
                <input type="hidden" id="modal-user-id" value="">
                <div class="form-group" style="margin-bottom: 12px;">
                    <label>Nama Pengguna</label>
                    <input type="text" class="form-control" id="modal-nama" placeholder="Contoh: Budi">
                </div>
                <div class="form-group" style="margin-bottom: 12px;">
                    <label>Jabatan / Posisi</label>
                    <input type="text" class="form-control" id="modal-jabatan" placeholder="Contoh: Security">
                </div>
                <div class="form-group" style="margin-bottom: 12px;">
                    <label>Kata Sandi</label>
                    <input type="password" class="form-control" id="modal-password" placeholder="Ketik sandi baru (kosongkan jika tetap)">
                </div>
                <div class="form-group" style="margin-bottom: 24px;">
                    <label>Pilih Role</label>
                    <select class="form-control" id="modal-role">
                        <option value="Superuser">Superuser</option>
                        <option value="Operator">Operator Jaringan</option>
                        <option value="Role User">Role User (Viewer)</option>
                    </select>
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button class="btn btn-outline" id="btn-cancel-user">Batal</button>
                    <button class="btn btn-primary" id="btn-save-user">Simpan</button>
                </div>
            </div>
        </div>
    `;

    setTimeout(() => {
        const tbody = container.querySelector('#users-tbody');
        const modal = container.querySelector('#add-user-modal');
        const btnAdd = container.querySelector('#btn-show-add-user');
        const btnCancel = container.querySelector('#btn-cancel-user');
        const btnSave = container.querySelector('#btn-save-user');
        
        function getRoleBadge(role) {
            if(role === 'Superuser') return '<span style="background: var(--success); color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Superuser (Full)</span>';
            if(role === 'Operator') return '<span style="background: var(--warning); color: #000; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Operator Jaringan</span>';
            return '<span style="background: var(--info); color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Role User (Viewer)</span>';
        }

        function getStatusBadge(status, lastActive) {
            if(status === 'Online') return `<i class="fa-solid fa-circle" style="color: var(--success); font-size: 0.6rem;"></i> Online`;
            return `<i class="fa-solid fa-circle" style="color: var(--text-muted); font-size: 0.6rem;"></i> Offline (${lastActive})`;
        }

        function renderUsers() {
            tbody.innerHTML = '';
            let data = {};
            try { data = Store.getData() || {}; } catch(e) { console.warn('Store error', e); }
            let users = data.users;
            
            // Self-healing if store.js was cached and didn't run the migration
            if (!users || users.length === 0) {
                 users = [
                    { id: 1, name: 'Admin Camp', title: 'Kepala Operasional', role: 'Superuser', status: 'Online', lastActive: 'Sekarang' },
                    { id: 2, name: 'Pak Yanto', title: 'Security Shift Malam', role: 'Role User', status: 'Offline', lastActive: '2j lalu' },
                    { id: 3, name: 'Budi IT', title: 'Teknisi Jaringan', role: 'Operator', status: 'Online', lastActive: 'Sekarang' }
                 ];
                 data.users = users;
                 try {
                     if(Store.saveData) Store.saveData(data);
                     else localStorage.setItem('camp_erp_data', JSON.stringify(data));
                 } catch(e) {}
            }
            
            users.forEach(u => {
                const tr = document.createElement('tr');
                tr.style.borderBottom = '1px solid rgba(255,255,255,0.05)';
                
                let actions = '';
                if(isViewer) {
                    actions = '<span style="color: var(--text-muted); font-size: 0.8rem;">Akses Dibatasi</span>';
                } else if(u.id === 1) {
                    actions = `
                        <div style="display: flex; gap: 8px;">
                            <button class="btn-icon btn-edit-user" data-id="${u.id}" style="color: var(--info);"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon" disabled style="opacity: 0.5;" title="Superuser tidak bisa dihapus"><i class="fa-solid fa-lock"></i></button>
                        </div>
                    `;
                } else {
                    actions = `
                        <div style="display: flex; gap: 8px;">
                            <button class="btn-icon btn-edit-user" data-id="${u.id}" style="color: var(--info);"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon btn-del-user" data-id="${u.id}" style="color: var(--danger);"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    `;
                }

                tr.innerHTML = `
                    <td style="padding: 14px 16px; font-weight: 500;">${u.name}</td>
                    <td style="padding: 14px 16px; color: var(--text-muted);">${u.title}</td>
                    <td style="padding: 14px 16px;">${getRoleBadge(u.role)}</td>
                    <td style="padding: 14px 16px;">${getStatusBadge(u.status, u.lastActive)}</td>
                    <td style="padding: 14px 16px;">${actions}</td>
                `;
                tbody.appendChild(tr);
            });

            // Bind Delete events
            tbody.querySelectorAll('.btn-del-user').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = parseInt(e.currentTarget.getAttribute('data-id'));
                    
                    try {
                        if (Store.deleteUser) {
                            Store.deleteUser(id);
                        } else {
                            const data = Store.getData() || {};
                            data.users = (data.users || []).filter(u => u.id !== id);
                            localStorage.setItem('camp_erp_data', JSON.stringify(data));
                        }
                    } catch(err) { console.warn(err); }
                    
                    renderUsers();
                    showToast('Pengguna berhasil dihapus dari sistem.', 'success');
                });
            });

            // Bind Edit events
            tbody.querySelectorAll('.btn-edit-user').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = parseInt(e.currentTarget.getAttribute('data-id'));
                    const data = Store.getData() || {};
                    const user = (data.users || []).find(u => u.id === id);
                    if(user) {
                        document.getElementById('modal-title').textContent = 'Edit Pengguna';
                        document.getElementById('modal-user-id').value = user.id;
                        document.getElementById('modal-nama').value = user.name;
                        document.getElementById('modal-jabatan').value = user.title;
                        document.getElementById('modal-role').value = user.role;
                        document.getElementById('modal-password').value = '';
                        modal.classList.add('active');
                    }
                });
            });
        }
        
        try { renderUsers(); } catch(e) { console.error('Render Users Failed:', e); tbody.innerHTML = '<tr><td colspan="5">Gagal memuat pengguna.</td></tr>'; }
        
        // Modal logic
        if(btnAdd) {
            btnAdd.addEventListener('click', () => {
                document.getElementById('modal-title').textContent = 'Tambah Pengguna Baru';
                document.getElementById('modal-user-id').value = '';
                document.getElementById('modal-nama').value = '';
                document.getElementById('modal-jabatan').value = '';
                document.getElementById('modal-role').value = 'Operator';
                document.getElementById('modal-password').value = '';
                modal.classList.add('active');
            });
        }
        btnCancel.addEventListener('click', () => {
            modal.classList.remove('active');
        });
        btnSave.addEventListener('click', () => {
            const userId = document.getElementById('modal-user-id').value;
            const name = document.getElementById('modal-nama').value;
            const title = document.getElementById('modal-jabatan').value;
            const role = document.getElementById('modal-role').value;
            const password = document.getElementById('modal-password').value;
            
            if(!name || !title) {
                showToast('Mohon isi nama dan jabatan', 'danger');
                return;
            }
            
            try {
                const data = Store.getData() || {};
                const currentUsers = data.users || [];
                
                if (userId) {
                    // Update existing
                    const user = currentUsers.find(u => u.id === parseInt(userId));
                    if (user) {
                        user.name = name;
                        user.title = title;
                        user.role = role;
                        if (password.trim() !== '') {
                            user.password = password;
                        }
                    }
                    showToast('Detail pengguna berhasil diperbarui!', 'success');
                } else {
                    // Create new
                    const maxId = Math.max(0, ...currentUsers.map(u => u.id), 0);
                    const finalPassword = password.trim() !== '' ? password : 'user123';
                    const newUser = { id: maxId + 1, name, title, role, status: 'Online', lastActive: 'Baru Saja', password: finalPassword };
                    currentUsers.push(newUser);
                    showToast('Pengguna baru berhasil ditambahkan!', 'success');
                }
                
                data.users = currentUsers;
                localStorage.setItem('camp_erp_data', JSON.stringify(data));
                
                // If using store update event, we can try to call Store.saveData but localStorage is safer here
                if(Store.saveData) {
                    Store.saveData(data);
                }
            } catch(e) { console.warn(e); }
            
            modal.classList.remove('active');
            try { renderUsers(); } catch(e) {}
        });

    }, 0);

    return container;
}

