<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:0.5rem;">
            <div>
                <p style="font-size:0.75rem; font-weight:500; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; margin:0;">HARDWARE STATUS</p>
                <h2 class="text-gray-900 dark:text-gray-100" style="font-size:1.125rem; font-weight:700; margin:0.25rem 0 0;">Kondisi Perangkat</h2>
            </div>
        </div>

        <div style="overflow-x:auto; -webkit-overflow-scrolling:touch; margin:0 -0.25rem;">
        <div class="border-gray-200 dark:border-white/5 border" style="border-radius:0.75rem; overflow:hidden; min-width:560px;">
            <!-- Header -->
            <div class="bg-gray-100 dark:bg-gray-800/50 border-gray-200 dark:border-white/5 border-b" style="display:grid; grid-template-columns:1fr 100px 120px 120px 160px; padding:0.75rem 1.25rem;">
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em;">Perangkat</span>
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em; text-align:center;">Status</span>
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em; text-align:right;">Latency</span>
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em; text-align:right;">Suhu</span>
                <span style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em; text-align:right;">Uptime</span>
            </div>

            @foreach ($this->getDevicesHealth() as $index => $device)
                @php
                    $isOnline = $device['status'] === 'online';
                    $tempVal = (int) str_replace('°C', '', $device['temperature']);
                    $tempColor = '#10b981';
                    if ($tempVal > 50) $tempColor = '#f59e0b';
                    if ($tempVal > 60) $tempColor = '#ef4444';
                    if ($device['temperature'] === '-') $tempColor = '#4b5563';
                    $isLast = $index === count($this->getDevicesHealth()) - 1;
                @endphp
                <div class="hover:bg-gray-50 dark:hover:bg-white/5 {{ $isLast ? '' : 'border-b border-gray-200 dark:border-white/5' }}"
                     style="display:grid; grid-template-columns:1fr 100px 120px 120px 160px; padding:1rem 1.25rem; align-items:center; transition:background 0.15s;">
                    <span class="text-gray-900 dark:text-gray-100" style="font-size:0.875rem; font-weight:500;">{{ $device['name'] }}</span>
                    <div style="text-align:center;">
                        @if($isOnline)
                            <span style="display:inline-flex; align-items:center; gap:0.375rem; font-size:0.75rem; font-weight:600; color:#10b981;">
                                <span style="width:6px; height:6px; background:#10b981; border-radius:50%; flex-shrink:0;"></span>
                                Online
                            </span>
                        @else
                            <span style="display:inline-flex; align-items:center; gap:0.375rem; font-size:0.75rem; font-weight:600; color:#ef4444;">
                                <span style="width:6px; height:6px; background:#ef4444; border-radius:50%; flex-shrink:0;"></span>
                                Offline
                            </span>
                        @endif
                    </div>
                    <div style="text-align:right; font-size:0.85rem; font-weight:500; font-variant-numeric:tabular-nums;" class="{{ $device['latency'] === 'RTO' ? 'text-red-500' : 'text-gray-500 dark:text-gray-300' }}">
                        {{ $device['latency'] }}
                    </div>
                    <div style="text-align:right; font-size:0.85rem; font-weight:600; color:{{ $tempColor }};">
                        {{ $device['temperature'] }}
                    </div>
                    <div style="text-align:right; font-size:0.8rem; color:#6b7280;">{{ $device['uptime'] }}</div>
                </div>
            @endforeach
        </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
