<x-filament-panels::page>
    <div id="app" class="camping-theme-wrapper" style="min-height: 60vh; position: relative; padding: 1rem; border-radius: var(--radius-lg, 16px);">
        <!-- The JS component will render here -->
    </div>

    <!-- Scripts from Project Camping Ground -->
    <script>
        // Mock the Store object expected by pemetaanJaringan.js
        window.Store = {
            getData: function() {
                return {
                    network_nodes: {!! $this->getDevices() !!}
                };
            }
        };
    </script>
    <script src="{{ asset('js/components/networkTopology.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('app');
            // Assuming renderNetworkTopology returns a DOM element
            if(typeof renderNetworkTopology === 'function') {
                const component = renderNetworkTopology();
                container.appendChild(component);
            }
        });
    </script>
</x-filament-panels::page>
