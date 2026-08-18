<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <div>
                <p style="font-size:0.75rem; font-weight:500; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; margin:0;">ACTIVITY LOG</p>
                <h2 class="text-gray-900 dark:text-gray-100" style="font-size:1.125rem; font-weight:700; margin:0.25rem 0 0;">Deteksi & Peristiwa</h2>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <span style="width:6px; height:6px; background:#10b981; border-radius:50%; display:inline-block; animation:pulse 2s infinite;"></span>
                <span style="font-size:0.75rem; font-weight:500; color:#6b7280;">Pemantauan aktif</span>
            </div>
        </div>

        <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
        <div class="border-gray-200 dark:border-white/5 border" style="border-radius:0.75rem; overflow:hidden; min-width:520px;">
            <!-- Header -->
            <div class="bg-gray-100 dark:bg-gray-800/50 border-gray-200 dark:border-white/5 border-b" style="display:grid; grid-template-columns:110px 220px 1fr 120px; padding:0.75rem 1.25rem;">
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em;">Waktu</span>
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em;">Sumber</span>
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em;">Peristiwa</span>
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em; text-align:right;">Status</span>
            </div>

            <!-- Rows -->
            @foreach ($this->getAlerts() as $index => $alert)
                @php
                    $badgeColor = match($alert['level']) {
                        'info' => 'primary',
                        'warning' => 'warning',
                        'danger' => 'danger',
                        'success' => 'success',
                        default => 'gray',
                    };
                    $isLast = $index === count($this->getAlerts()) - 1;
                @endphp
                <div class="hover:bg-gray-50 dark:hover:bg-white/5 {{ $isLast ? '' : 'border-b border-gray-200 dark:border-white/5' }}"
                     style="display:grid; grid-template-columns:110px 220px 1fr 120px; padding:1rem 1.25rem; align-items:center; transition:background 0.15s;">
                    <span style="font-size:0.8rem; color:#6b7280; font-variant-numeric:tabular-nums;">{{ $alert['time'] }}</span>
                    <span class="text-gray-900 dark:text-gray-100" style="font-size:0.85rem; font-weight:500;">{{ $alert['camera'] }}</span>
                    <span class="text-gray-600 dark:text-gray-400" style="font-size:0.85rem; padding-right:1rem;">{{ $alert['message'] }}</span>
                    <div style="text-align:right;">
                        <x-filament::badge :color="$badgeColor" size="sm">{{ strtoupper($alert['level']) }}</x-filament::badge>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
