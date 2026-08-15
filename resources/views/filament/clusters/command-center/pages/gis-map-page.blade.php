<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <div id="app-map" class="camping-theme-wrapper" style="min-height: 60vh; position: relative; padding: 1rem; border-radius: var(--radius-lg, 16px);">
        <!-- The JS component will render here -->
    </div>
    <script>
        // Mock the Store object expected by pemetaanJaringan.js
        window.Store = {
            getData: function() {
                return {
                    network_nodes: {!! $this->getDevices() !!}
                };
            },
            addNetworkNode: function(node) {
                // mock adding logic or keep it empty if just visual
                return Object.assign({id: Math.random()}, node);
            }
        };
        window.isViewer = false;
    </script>
    <script src="{{ asset('js/components/pemetaanJaringan.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('app-map');
            if(typeof renderPemetaanJaringan === 'function') {
                const component = renderPemetaanJaringan();
                container.appendChild(component);
            }
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('device-added', () => {
                // Refresh the page to load the new device from the database
                window.location.reload();
            });
        });
    </script>
    <style>
        .camping-theme-wrapper {
            --bg-dark: #000000;
            --bg-card: #0a0a0a;
            --primary: #10b981;
            --secondary: #059669;
            --accent: #34d399;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --border-color: #1f2937;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --success: #22c55e;
            color: var(--text-main);
        }
    </style>
</x-filament-panels::page>
