function renderKasir() {
    const container = document.createElement('div');
    const data = Store.getData();
    let currentTab = 'Point of Sale (Kasir)';
    let showInvoice = null;
    
    function render() {
        const formatRp = (num) => 'Rp ' + num.toLocaleString('id-ID');
        
        // --- TAB: Laporan Kasir ---
        const historyRows = data.pos_orders.sort((a,b) => b.id.localeCompare(a.id)).map(o => `
            <tr>
                <td><span style="font-family: monospace; font-weight: 600; color: var(--primary);">${o.id}</span></td>
                <td><i class="fa-regular fa-clock" style="color:var(--text-muted); font-size:0.8rem; margin-right:4px;"></i> ${o.date}</td>
                <td><i class="fa-solid fa-mug-hot" style="color:var(--text-muted); font-size:0.8rem; margin-right:4px;"></i> ${o.items}</td>
                <td style="font-weight:700; color:var(--success);">${formatRp(o.total)}</td>
                <td><span class="status active">Lunas</span></td>
                <td><button class="btn btn-outline btn-print-history" data-id="${o.id}" style="padding: 4px 12px; font-size: 0.8rem; border-radius: 20px;"><i class="fa-solid fa-print"></i> Cetak</button></td>
            </tr>
        `).join('');

        // --- TAB: POS ---
        const menuHtml = data.pos_menu.map(m => `
            <div class="pos-item-card" data-id="${m.id}" data-name="${m.name}" data-price="${m.price}">
                <div class="pos-item-img" style="background: linear-gradient(135deg, var(--bg-main) 0%, #e0e6e8 100%); transition: var(--transition);">
                    <i class="fa-solid ${m.icon}" style="color: var(--primary-light);"></i>
                </div>
                <div class="pos-item-details" style="padding: 12px;">
                    <div class="pos-item-title" style="font-size: 0.95rem; margin-bottom: 4px;">${m.name}</div>
                    <div class="pos-item-price" style="font-size: 0.9rem;">${formatRp(m.price)}</div>
                </div>
            </div>
        `).join('');

        let subtotal = 0;
        const cartHtml = data.pos_cart.map((item, index) => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;
            return `
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border-color); padding-bottom:12px; margin-bottom:12px;">
                    <div>
                        <div style="font-weight:600; font-size: 0.9rem;">${item.name}</div>
                        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">
                            ${item.qty} x ${formatRp(item.price)}
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                        <div style="font-weight:700; font-size: 0.9rem;">${formatRp(itemTotal)}</div>
                        <button class="btn btn-outline btn-remove-cart" data-index="${index}" style="padding: 2px 8px; font-size:0.75rem; border-radius: 12px; border-color:var(--danger); color:var(--danger);"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `;
        }).join('');
        
        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        container.innerHTML = `
            <div class="section-header">
                <h2>Point of Sale (Kasir Cafe)</h2>
            </div>
            
            <div class="tabs" style="margin-bottom: 24px;">
                <div class="tab ${currentTab === 'Point of Sale (Kasir)' ? 'active' : ''}" data-type="Point of Sale (Kasir)">Point of Sale (Kasir)</div>
                <div class="tab ${currentTab === 'Laporan Kasir' ? 'active' : ''}" data-type="Laporan Kasir">Laporan & History Penjualan</div>
            </div>
            
            ${currentTab === 'Point of Sale (Kasir)' ? `
            <div class="pos-layout" style="height: calc(100vh - 200px); align-items: flex-start;">
                <!-- Menu Grid -->
                <div class="card" style="display:flex; flex-direction:column; overflow:hidden; height: 100%;">
                    <div class="search-bar" style="width:100%; margin-bottom:16px;">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" placeholder="Cari Menu Makanan/Minuman...">
                    </div>
                    <div class="pos-items" style="flex:1; overflow-y:auto; padding-right:8px; align-content: flex-start;" id="menu-grid">
                        ${menuHtml}
                    </div>
                </div>
                
                <!-- Cart Sidebar (Sticky-like behavior achieved by flex container height) -->
                <div class="card" style="display:flex; flex-direction:column; height: 100%; background: linear-gradient(180deg, #ffffff 0%, var(--bg-main) 100%); border: 1px solid var(--primary-light);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                        <h3 style="margin:0; color: var(--primary);"><i class="fa-solid fa-cart-shopping"></i> Keranjang</h3>
                        <button class="btn btn-outline" id="btn-clear-cart" style="padding:4px 12px; font-size:0.8rem; border-radius:20px;">Bersihkan</button>
                    </div>
                    <div style="flex:1; overflow-y:auto; padding-right: 8px;">
                        ${data.pos_cart.length > 0 ? cartHtml : `
                            <div style="text-align:center; color:var(--text-muted); margin-top:40px; display:flex; flex-direction:column; align-items:center; gap: 12px;">
                                <i class="fa-solid fa-basket-shopping" style="font-size: 3rem; opacity: 0.2;"></i>
                                <span>Keranjang Belanja Kosong</span>
                            </div>
                        `}
                    </div>
                    
                    <div style="margin-top:16px; padding-top:16px; border-top: 2px dashed var(--border-color);">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.9rem;">
                            <span style="color:var(--text-muted);">Subtotal</span>
                            <span style="font-weight:600;">${formatRp(subtotal)}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:16px; font-size:0.9rem;">
                            <span style="color:var(--text-muted);">Pajak (10%)</span>
                            <span style="font-weight:600;">${formatRp(tax)}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:1.4rem; font-weight:800; color:var(--primary-dark); margin-bottom:24px; padding: 12px; background: rgba(39, 174, 96, 0.1); border-radius: 8px;">
                            <span>Total</span>
                            <span>${formatRp(total)}</span>
                        </div>
                        
                        <button class="btn btn-primary" id="btn-checkout" style="width:100%; height: 50px; font-size: 1.1rem; background-color:var(--success); border-color:var(--success); box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3); ${data.pos_cart.length === 0 ? 'opacity:0.5; pointer-events:none;' : ''}">
                            <i class="fa-solid fa-print"></i> Proses & Cetak Bill
                        </button>
                    </div>
                </div>
            </div>
            ` : `
            <div class="card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Waktu Transaksi</th>
                                <th>Item Terjual</th>
                                <th>Total Penjualan</th>
                                <th>Status</th>
                                <th>Cetak Ulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${historyRows.length > 0 ? historyRows : '<tr><td colspan="6" style="text-align:center;">Tidak ada history penjualan</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>
            `}

            <!-- INVOICE MODAL -->
            <div class="global-modal-overlay ${showInvoice ? 'show' : ''}" id="invoice-modal">
                ${showInvoice ? `
                <div class="global-modal" style="width:400px;">
                    <div class="modal-header-g" style="background: var(--bg-card); color: var(--text-main); border-bottom: 1px dashed var(--border-color); justify-content: center;">
                        <h2 style="margin:0; font-weight: 800; font-size: 1.8rem; color: var(--primary);">CAMPING CAFE</h2>
                        <button class="modal-close-g" id="btn-close-invoice" style="color: var(--text-muted); background: var(--bg-main);"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body-g" style="padding: 30px;">
                        <div style="text-align:center; margin-bottom:24px;">
                            <div style="font-family: monospace; color:var(--text-muted); font-size:0.9rem; margin-bottom: 4px;">Invoice: ${showInvoice.id}</div>
                            <div style="color:var(--text-muted); font-size:0.85rem;">${showInvoice.date}</div>
                        </div>
                        
                        <div style="border-top:2px dashed var(--border-color); border-bottom:2px dashed var(--border-color); padding:20px 0; margin-bottom:24px;">
                            <div style="margin-bottom:12px; font-weight:700; font-size: 0.9rem; color: var(--text-muted); letter-spacing: 1px;">DAFTAR PESANAN:</div>
                            <div style="font-size:0.95rem; line-height:1.8; font-family: monospace;">
                                ${showInvoice.items.split(', ').map(item => `<div style="display:flex; gap:8px;"><span style="color:var(--primary);">-</span> <span>${item}</span></div>`).join('')}
                            </div>
                        </div>
                        
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:1.4rem; font-weight:800; color:var(--primary-dark); margin-bottom:30px; background: var(--bg-main); padding: 16px; border-radius: 8px;">
                            <span>TOTAL</span>
                            <span>${formatRp(showInvoice.total)}</span>
                        </div>
                        
                        <div style="text-align:center; margin-bottom: 24px; color: var(--text-muted); font-size: 0.85rem;">
                            Terima kasih atas kunjungan Anda!
                        </div>
                        
                        <button class="btn btn-primary" id="btn-print-real" style="width:100%; height: 45px;"><i class="fa-solid fa-print"></i> Cetak Struk (Browser)</button>
                    </div>
                </div>
                ` : ''}
            </div>
        `;

        // Bind events
        container.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                currentTab = e.target.dataset.type;
                render();
            });
        });

        if(currentTab === 'Point of Sale (Kasir)') {
            // Add to cart
            container.querySelectorAll('.pos-item-card').forEach(card => {
                card.addEventListener('click', () => {
                    const id = card.dataset.id;
                    const name = card.dataset.name;
                    const price = parseInt(card.dataset.price);
                    
                    const storeData = Store.getData();
                    const existing = storeData.pos_cart.find(i => i.id === id);
                    if(existing) existing.qty += 1;
                    else storeData.pos_cart.push({ id, name, price, qty: 1 });
                    Store.saveData(storeData);
                    data.pos_cart = storeData.pos_cart;
                    render();
                });
            });

            // Remove cart
            container.querySelectorAll('.btn-remove-cart').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const idx = parseInt(btn.dataset.index);
                    const storeData = Store.getData();
                    storeData.pos_cart.splice(idx, 1);
                    Store.saveData(storeData);
                    data.pos_cart = storeData.pos_cart;
                    render();
                });
            });

            // Clear cart
            const btnClear = container.querySelector('#btn-clear-cart');
            if(btnClear) btnClear.addEventListener('click', () => {
                const storeData = Store.getData();
                storeData.pos_cart = [];
                Store.saveData(storeData);
                data.pos_cart = storeData.pos_cart;
                render();
            });

            // Checkout
            const btnCheckout = container.querySelector('#btn-checkout');
            if(btnCheckout) btnCheckout.addEventListener('click', () => {
                const storeData = Store.getData();
                const cartCopy = [...storeData.pos_cart];
                const orderId = Store.posCheckout(total, cartCopy);
                
                if(orderId) {
                    data.pos_cart = [];
                    data.pos_orders = Store.getData().pos_orders;
                    
                    const order = data.pos_orders.find(o => o.id === orderId);
                    showInvoice = order;
                    render();
                }
            });
        }
        
        // Print History
        container.querySelectorAll('.btn-print-history').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                showInvoice = data.pos_orders.find(o => o.id === id);
                render();
            });
        });

        // Close Invoice
        const btnCloseInv = container.querySelector('#btn-close-invoice');
        if(btnCloseInv) btnCloseInv.addEventListener('click', () => {
            showInvoice = null;
            render();
        });
        
        // Mock actual browser print
        const btnPrintReal = container.querySelector('#btn-print-real');
        if(btnPrintReal) btnPrintReal.addEventListener('click', () => {
            window.print();
        });
    }

    render();
    return container;
}
