<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <div id="app-map" class="camping-theme-wrapper" style="min-height: 60vh; position: relative; padding: 1rem; border-radius: var(--radius-lg, 16px);">
        <!-- The JS component will render here -->
    </div>
    <script>
        // Store object: getData membaca dari PHP/DB, addNetworkNode membuka Filament action
        window.Store = {
            getData: function() {
                return {
                    network_nodes: {!! $this->getDevices() !!}
                };
            },
            // Alih-alih menyimpan hanya di memori JS, buka Filament modal form yang terhubung ke DB
            addNetworkNode: function(node) {
                // Trigger Livewire event untuk membuka Filament action 'tambah_cctv'
                window.dispatchEvent(new CustomEvent('filament-open-tambah-cctv'));
                // Kembalikan node sementara agar UI tidak error (data asli di-reload setelah save)
                return Object.assign({ id: 'pending-' + Date.now() }, node);
            }
        };
        window.isViewer = false;

        // Listener untuk tombol "Tambah CCTV" di dalam petaMonitoring.js
        // Mengarahkan ke Filament action yang benar-benar menyimpan ke DB
        window.addEventListener('filament-open-tambah-cctv', function() {
            // Dispatch Livewire event untuk membuka action modal di GisMapPage
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('open-tambah-cctv');
            }
        });
        
        window.addEventListener('filament-open-detail-cctv', function(e) {
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('open-detail-cctv', { deviceId: e.detail.id });
            }
        });
    </script>
    {{-- PERBAIKAN: Pakai petaMonitoring.js (bukan pemetaanJaringan.js) dan panggil renderPetaMonitoring() --}}
    <script src="{{ asset('js/components/petaMonitoring.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('app-map');
            if(typeof renderPetaMonitoring === 'function') {
                const component = renderPetaMonitoring();
                container.appendChild(component);
            } else if(typeof renderPemetaanJaringan === 'function') {
                // fallback ke versi lama jika ada
                const component = renderPemetaanJaringan();
                container.appendChild(component);
            }
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('device-added', () => {
                // Reload halaman setelah device baru disimpan ke DB via Filament action
                window.location.reload();
            });
        });
    </script>
    <style>
        /* Light Mode Default */
        .camping-theme-wrapper {
            --bg-dark: transparent;
            --bg-card: #ffffff;
            --bg-subtle: #f3f4f6;
            --primary: #10b981;
            --secondary: #059669;
            --accent: #34d399;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --success: #22c55e;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            color: var(--text-main);
        }

        /* Dark Mode Override */
        .dark .camping-theme-wrapper {
            --bg-dark: transparent;
            --bg-card: rgba(255, 255, 255, 0.03);
            --bg-subtle: rgba(255, 255, 255, 0.08);
            --primary: #10b981;
            --secondary: #059669;
            --accent: #34d399;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --border-color: rgba(255, 255, 255, 0.1);
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --success: #22c55e;
            --shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }
    </style>
</x-filament-panels::page>
