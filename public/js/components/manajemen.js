function renderManajemen() {
    const container = document.createElement('div');
    const data = Store.getData();
    let currentTab = 'Booking Baru';
    
    function render() {
        const bookings = data.bookings.sort((a, b) => b.id.localeCompare(a.id));
        const bookingRows = bookings.map(b => `
            <tr>
                <td><span style="font-family: monospace; font-weight: 600; color: var(--primary);">${b.id}</span></td>
                <td style="font-weight: 500;">${b.name}</td>
                <td><i class="fa-solid fa-map-location-dot" style="color:var(--text-muted); font-size:0.8rem; margin-right:4px;"></i> ${b.area}</td>
                <td><i class="fa-regular fa-calendar-check" style="color:var(--text-muted); font-size:0.8rem; margin-right:4px;"></i> ${b.checkIn}</td>
                <td><i class="fa-regular fa-calendar-xmark" style="color:var(--text-muted); font-size:0.8rem; margin-right:4px;"></i> ${b.checkOut}</td>
                <td><span class="status ${b.status === 'Confirmed' || b.status === 'Check-in' ? 'active' : 'pending'}">${b.status}</span></td>
                <td>
                    ${b.status === 'Confirmed' ? `<button class="btn btn-outline btn-checkin" data-id="${b.id}" style="padding: 4px 12px; font-size: 0.8rem; color:var(--primary); border-color:var(--primary); border-radius: 20px;"><i class="fa-solid fa-right-to-bracket"></i> Check-in</button>` : ''}
                    ${b.status === 'Check-in' ? `<button class="btn btn-outline btn-checkout" data-id="${b.id}" style="padding: 4px 12px; font-size: 0.8rem; color:var(--warning); border-color:var(--warning); border-radius: 20px;"><i class="fa-solid fa-right-from-bracket"></i> Check-out</button>` : ''}
                    ${b.status === 'Selesai' ? `<span style="font-size:0.8rem; color:var(--text-muted);"><i class="fa-solid fa-check"></i> Selesai</span>` : ''}
                </td>
            </tr>
        `).join('');

        const totalBookings = bookings.length;
        const activeCheckins = bookings.filter(b => b.status === 'Check-in').length;
        const upcoming = bookings.filter(b => b.status === 'Confirmed').length;
        const completed = bookings.filter(b => b.status === 'Selesai').length;

        container.innerHTML = `
            <div class="section-header">
                <h2>Manajemen Booking & Reservasi</h2>
                <button class="btn btn-primary" id="btn-toggle-form"><i class="fa-solid fa-calendar-plus"></i> Buat Reservasi Baru</button>
            </div>
            
            <div class="grid grid-cols-4" style="margin-bottom: 24px;">
                <div class="card stat-card bg-primary-light">
                    <div class="stat-icon"><i class="fa-solid fa-book-bookmark"></i></div>
                    <div class="stat-info"><span class="stat-label">Total Booking</span><span class="stat-value">${totalBookings}</span></div>
                </div>
                <div class="card stat-card bg-warning-light">
                    <div class="stat-icon"><i class="fa-regular fa-clock"></i></div>
                    <div class="stat-info"><span class="stat-label">Akan Datang</span><span class="stat-value">${upcoming}</span></div>
                </div>
                <div class="card stat-card bg-success-light">
                    <div class="stat-icon"><i class="fa-solid fa-people-roof"></i></div>
                    <div class="stat-info"><span class="stat-label">Tamu Menginap</span><span class="stat-value">${activeCheckins}</span></div>
                </div>
                <div class="card stat-card bg-info-light">
                    <div class="stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                    <div class="stat-info"><span class="stat-label">Selesai</span><span class="stat-value">${completed}</span></div>
                </div>
            </div>
            
            <div class="card mb-8">
                <div class="tabs">
                    <div class="tab active">Daftar Semua Reservasi</div>
                </div>
                
                <div class="table-container" style="margin-top:20px;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Booking</th>
                                <th>Nama Tamu</th>
                                <th>Tipe Area</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${bookingRows.length > 0 ? bookingRows : '<tr><td colspan="7" style="text-align:center;">Tidak ada data reservasi.</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Booking Baru -->
            <div class="global-modal-overlay" id="bk-modal">
                <div class="global-modal" style="width: 600px;">
                    <div class="modal-header-g">
                        <i class="fa-solid fa-calendar-plus" style="font-size: 1.2rem;"></i>
                        <h3>Form Reservasi Baru</h3>
                        <button class="modal-close-g" id="btn-close-bk-modal"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body-g">
                        <div class="grid grid-cols-2" style="gap: 16px;">
                            <div>
                                <div class="form-group" style="margin-bottom: 12px;">
                                    <label>Nama Tamu</label>
                                    <input type="text" id="b-name" class="form-control" placeholder="Masukkan nama lengkap">
                                </div>
                                <div class="form-group" style="margin-bottom: 12px;">
                                    <label>Nomor HP / WhatsApp</label>
                                    <input type="text" id="b-hp" class="form-control" placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Tipe Area</label>
                                    <select id="b-area" class="form-control">
                                        <option>Area Camping</option>
                                        <option>Glamping</option>
                                        <option>Villa</option>
                                        <option>Gazebo</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <div class="form-group" style="margin-bottom: 12px;">
                                    <label>Tanggal Check-in</label>
                                    <input type="date" id="b-in" class="form-control">
                                </div>
                                <div class="form-group" style="margin-bottom: 12px;">
                                    <label>Tanggal Check-out</label>
                                    <input type="date" id="b-out" class="form-control">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Jumlah Orang</label>
                                    <input type="number" id="b-pax" class="form-control" placeholder="1" value="1">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" id="btn-save-booking" style="width: 100%; margin-top: 12px;">
                            <i class="fa-solid fa-save"></i> Simpan Booking
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Modal Logic
        const bkModal = container.querySelector('#bk-modal');
        const toggleBtn = container.querySelector('#btn-toggle-form');
        const closeBtn = container.querySelector('#btn-close-bk-modal');

        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                bkModal.classList.add('show');
                const today = new Date().toISOString().split('T')[0];
                container.querySelector('#b-in').value = today;
                
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                container.querySelector('#b-out').value = tomorrow.toISOString().split('T')[0];
            });
        }
        if(closeBtn) {
            closeBtn.addEventListener('click', () => {
                bkModal.classList.remove('show');
            });
        }

        // Save Booking Logic
        const btnSave = container.querySelector('#btn-save-booking');
        if(btnSave) {
            btnSave.addEventListener('click', () => {
                const name = container.querySelector('#b-name').value;
                const area = container.querySelector('#b-area').value;
                const checkIn = container.querySelector('#b-in').value;
                const checkOut = container.querySelector('#b-out').value;
                const pax = container.querySelector('#b-pax').value;

                if(!name || !checkIn || !checkOut) {
                    alert('Mohon lengkapi Nama dan Tanggal!');
                    return;
                }

                Store.addBooking({ name, area, checkIn, checkOut, pax });
                bkModal.classList.remove('show');
                if(window.navigate) window.navigate('manajemen');
            });
        }
        
        // Check-in / out actions
        container.querySelectorAll('.btn-checkin').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if(confirm('Proses Check-in tamu sekarang?')) {
                    const id = e.currentTarget.dataset.id;
                    const storeData = Store.getData();
                    const b = storeData.bookings.find(x => x.id === id);
                    if(b) b.status = 'Check-in';
                    Store.saveData(storeData);
                    if(window.navigate) window.navigate('manajemen');
                }
            });
        });
        
        container.querySelectorAll('.btn-checkout').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if(confirm('Proses Check-out tamu? Transaksi akan diselesaikan.')) {
                    const id = e.currentTarget.dataset.id;
                    const storeData = Store.getData();
                    const b = storeData.bookings.find(x => x.id === id);
                    if(b) b.status = 'Selesai';
                    Store.saveData(storeData);
                    if(window.navigate) window.navigate('manajemen');
                }
            });
        });
    }

    render();
    return container;
}
