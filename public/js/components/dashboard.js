function renderDashboard() {
    const container = document.createElement('div');
    const data = Store.getData();
    
    // Calculate metrics
    const totalBookings = data.bookings.length;
    const todayRevenue = data.finance
        .filter(t => t.type === 'Pendapatan' && t.date === new Date().toISOString().split('T')[0])
        .reduce((sum, t) => sum + t.amount, 0);
        
    // Format currency
    const formatRp = (num) => 'Rp ' + num.toLocaleString('id-ID');
    
    // Generate Check-ins HTML
    const checkinHtml = data.bookings.map(b => `
        <tr>
            <td style="font-weight: 500;">${b.name}</td>
            <td><i class="fa-solid fa-map-location-dot" style="color:var(--text-muted); font-size:0.8rem; margin-right:4px;"></i> ${b.area}</td>
            <td><span class="status ${b.status === 'Confirmed' || b.status === 'Check-in' ? 'active' : 'pending'}">${b.status}</span></td>
        </tr>
    `).join('');

    // Dynamic Capacity Calculation
    const totalArea = data.master_data.filter(m => m.status === 'Tersedia').length || 15;
    const filledArea = data.bookings.filter(b => b.status === 'Check-in').length;

    const statsHtml = `
        <div class="grid grid-cols-4 mb-8">
            <div class="card stat-card bg-primary-light">
                <div class="stat-icon" style="color:var(--primary);"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Total Tamu (Bulan Ini)</span>
                    <span class="stat-value">${data.bookings.length} Orang</span>
                </div>
            </div>
            <div class="card stat-card bg-success-light">
                <div class="stat-icon" style="color:var(--success);"><i class="fa-solid fa-money-bill-wave"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Pendapatan (Bulan Ini)</span>
                    <span class="stat-value" style="font-size: 1.2rem;">Rp ${(data.finance.reduce((acc, curr) => curr.type === 'Pendapatan' ? acc + curr.amount : acc, 0)).toLocaleString('id-ID')}</span>
                </div>
            </div>
            <div class="card stat-card bg-warning-light">
                <div class="stat-icon" style="color:var(--warning);"><i class="fa-solid fa-campground"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Area Terisi (Sedang Check-in)</span>
                    <span class="stat-value">${filledArea}/${totalArea}</span>
                </div>
            </div>
            <div class="card stat-card bg-info-light">
                <div class="stat-icon" style="color:var(--info);"><i class="fa-solid fa-tree"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Area Tersedia (Kosong)</span>
                    <span class="stat-value">${totalArea - filledArea}</span>
                </div>
            </div>
        </div>
    `;

    container.innerHTML = `
        ${statsHtml}
        
        <div class="grid grid-cols-2" style="gap: 24px;">
            <div class="card" style="height: 450px; display:flex; flex-direction:column;">
                <div class="section-header">
                    <h2>Daftar Booking Terbaru</h2>
                    <button class="btn btn-outline" onclick="navigate('manajemen')" style="padding: 4px 12px; font-size: 0.8rem; border-radius: 20px;">Kelola</button>
                </div>
                <div class="table-container" style="flex:1; overflow-y:auto; margin-top: 12px;">
                    <table style="margin: 0;">
                        <thead>
                            <tr>
                                <th style="position: sticky; top: 0; background: var(--bg-main); z-index: 10;">Nama Tamu</th>
                                <th style="position: sticky; top: 0; background: var(--bg-main); z-index: 10;">Area</th>
                                <th style="position: sticky; top: 0; background: var(--bg-main); z-index: 10;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${checkinHtml.length > 0 ? checkinHtml : '<tr><td colspan="3" style="text-align:center;">Belum ada tamu</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card" style="height: 450px; display:flex; flex-direction:column;">
                <div class="section-header">
                    <h2>Kapasitas Area (%)</h2>
                </div>
                <div style="flex:1; display:flex; align-items:flex-end; gap:24px; padding-top:20px; padding-bottom:10px;">
                    <!-- CSS Bar Chart Mockup with Animation and Gradients -->
                    <div style="flex:1; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; gap:12px; height: 100%;">
                        <div class="chart-bar" style="width:100%; max-width: 60px; background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%); height:0%; border-radius:6px 6px 0 0; transition: height 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: 0 10px 20px rgba(0,0,0,0.1);" data-target="80%"></div>
                        <span style="font-size:0.85rem; font-weight: 600; color:var(--text-muted)">Camping</span>
                    </div>
                    <div style="flex:1; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; gap:12px; height: 100%;">
                        <div class="chart-bar" style="width:100%; max-width: 60px; background: linear-gradient(180deg, var(--accent) 0%, #a8841a 100%); height:0%; border-radius:6px 6px 0 0; transition: height 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.1s; box-shadow: 0 10px 20px rgba(0,0,0,0.1);" data-target="60%"></div>
                        <span style="font-size:0.85rem; font-weight: 600; color:var(--text-muted)">Glamping</span>
                    </div>
                    <div style="flex:1; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; gap:12px; height: 100%;">
                        <div class="chart-bar" style="width:100%; max-width: 60px; background: linear-gradient(180deg, var(--info) 0%, #2980b9 100%); height:0%; border-radius:6px 6px 0 0; transition: height 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.2s; box-shadow: 0 10px 20px rgba(0,0,0,0.1);" data-target="45%"></div>
                        <span style="font-size:0.85rem; font-weight: 600; color:var(--text-muted)">Villa</span>
                    </div>
                    <div style="flex:1; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; gap:12px; height: 100%;">
                        <div class="chart-bar" style="width:100%; max-width: 60px; background: linear-gradient(180deg, var(--warning) 0%, #d68910 100%); height:0%; border-radius:6px 6px 0 0; transition: height 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.1);" data-target="30%"></div>
                        <span style="font-size:0.85rem; font-weight: 600; color:var(--text-muted)">Gazebo</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Trigger chart animation after mount
    setTimeout(() => {
        const bars = container.querySelectorAll('.chart-bar');
        bars.forEach(bar => {
            bar.style.height = bar.dataset.target;
        });
    }, 50);
    
    return container;
}
