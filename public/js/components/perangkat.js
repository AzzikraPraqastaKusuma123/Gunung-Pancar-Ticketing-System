function renderPerangkat() {
    const container = document.createElement('div');
    container.className = 'page-container fade-in';
    
    // Internal Style
    const style = document.createElement('style');
    style.textContent = `
        .device-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .device-table th, .device-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .device-table th {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .device-table td {
            color: var(--text-main);
            font-size: 0.95rem;
        }
        .device-table tbody tr:hover {
            background-color: var(--bg-hover);
        }
        
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(255,255,255,0.1);
        }
    `;
    container.appendChild(style);

    const nodes = Store.getData().network_nodes || [];

    const header = document.createElement('div');
    header.innerHTML = `
        <div class="card" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin-bottom: 4px;">Manajemen Perangkat</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola semua perangkat Kamera CCTV dan DVR (Digital Video Recorder)</p>
                </div>
                ${window.isViewer ? '' : '<button class="btn btn-primary" id="btn-add-table-device"><i class="fa-solid fa-plus"></i> Tambah Perangkat</button>'}
            </div>
            
            <table class="device-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Perangkat</th>
                        <th>Tipe</th>
                        <th>IP / Channel</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="device-tbody">
                    <!-- Rows injected via JS -->
                </tbody>
            </table>
        </div>
    `;
    container.appendChild(header);

    const tbody = container.querySelector('#device-tbody');
    
    function renderTable() {
        tbody.innerHTML = '';
        const currentNodes = Store.getData().network_nodes || [];
        
        currentNodes.forEach(node => {
            const tr = document.createElement('tr');
            
            let typeIcon = 'fa-wifi';
            let typeColor = 'var(--primary)';
            if(node.type === 'cctv') { typeIcon = 'fa-video'; typeColor = 'var(--info)'; }
            if(node.type === 'dvr') { typeIcon = 'fa-server'; typeColor = 'var(--accent)'; }
            
            let statusColor = node.status === 'active' ? 'var(--success)' : node.status === 'warning' ? 'var(--warning)' : 'var(--danger)';
            
            tr.innerHTML = `
                <td>#${node.id}</td>
                <td style="font-weight: 600;">${node.name}</td>
                <td>
                    <span class="type-badge" style="color: ${typeColor}; border: 1px solid ${typeColor};">
                        <i class="fa-solid ${typeIcon}"></i> ${node.type.toUpperCase()}
                    </span>
                </td>
                <td style="font-family: monospace;">${node.type === 'cctv' && !node.ip.includes('.') ? 'CH ' + node.ip : node.type === 'cctv' ? 'DVR ' + node.ip : node.ip}</td>
                <td>
                    <span class="badge" style="background: ${statusColor}; color: white;">${node.status.toUpperCase()}</span>
                </td>
                <td>
                    ${window.isViewer ? '<span style="color: var(--text-muted); font-size: 0.8rem;">Akses Dibatasi</span>' : `
                    <button class="btn-icon btn-edit" data-id="${node.id}" style="color: var(--primary);"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-icon btn-delete" data-id="${node.id}" style="color: var(--danger);"><i class="fa-solid fa-trash"></i></button>
                    `}
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Attach listeners
        tbody.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(e.currentTarget.getAttribute('data-id'));
                // Assuming confirm might be blocked, use direct delete for now or showToast
                Store.deleteNetworkNode(id);
                renderTable();
                showToast('Perangkat berhasil dihapus.', 'success');
            });
        });

        tbody.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(e.currentTarget.getAttribute('data-id'));
                showToast('Fitur edit untuk perangkat ID ' + id + ' sedang dalam pengembangan.', 'info');
            });
        });
    }
    
    setTimeout(() => {
        renderTable();
        
        const btnAdd = container.querySelector('#btn-add-table-device');
        if (btnAdd) {
            btnAdd.addEventListener('click', () => {
                showToast('Untuk menambah perangkat dengan koordinat Map, silakan gunakan tombol "+ Tambah Node Baru" di Dashboard Peta Monitoring.', 'info');
            });
        }
    }, 0);

    return container;
}
