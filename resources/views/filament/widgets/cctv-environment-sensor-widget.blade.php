<x-filament-widgets::widget>
    @php $data = $this->getEnvironmentData(); @endphp

    <x-filament::section>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.5rem;">
            <div>
                <p style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em; margin:0;">ENVIRONMENTAL</p>
                <h2 class="text-gray-900 dark:text-gray-100" style="font-size:1.125rem; font-weight:700; margin:0.2rem 0 0;">Cuaca & Lingkungan</h2>
            </div>
            <div style="display:flex; align-items:center; gap:0.4rem; padding:0.3rem 0.75rem; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:9999px;">
                <span style="width:5px; height:5px; background:#10b981; border-radius:50%;"></span>
                <span style="font-size:0.7rem; font-weight:600; color:#10b981;">LIVE</span>
            </div>
        </div>

        <!-- Suhu utama -->
        <div class="bg-gray-100 dark:bg-gray-800/50 border border-gray-200 dark:border-white/5" style="border-radius:0.75rem; padding:1.25rem; margin-bottom:0.75rem; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:0.7rem; color:#6b7280; font-weight:500; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 0.4rem;">Suhu Area</p>
                <div style="display:flex; align-items:baseline; gap:0.25rem;">
                    <span class="text-gray-900 dark:text-gray-100" style="font-size:2.5rem; font-weight:800; line-height:1;">{{ $data['temperature'] }}</span>
                    <span style="font-size:1rem; color:#6b7280;">°C</span>
                </div>
                <p style="font-size:0.75rem; color:#9ca3af; margin:0.4rem 0 0;">{{ $data['weather_status'] }}</p>
            </div>
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.8; flex-shrink:0;"><path d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
        </div>

        <!-- 3 metrik bawah -->
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:0.625rem;">
            <div class="bg-gray-100 dark:bg-gray-800/50 border border-gray-200 dark:border-white/5" style="border-radius:0.75rem; padding:0.875rem;">
                <p style="font-size:0.65rem; color:#6b7280; font-weight:500; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 0.5rem;">Kelembapan</p>
                <p style="font-size:1.5rem; font-weight:700; color:#3b82f6; margin:0; line-height:1;">{{ $data['humidity'] }}<span style="font-size:0.8rem; color:#6b7280; font-weight:400;">%</span></p>
            </div>
            <div class="bg-gray-100 dark:bg-gray-800/50 border border-gray-200 dark:border-white/5" style="border-radius:0.75rem; padding:0.875rem;">
                <p style="font-size:0.65rem; color:#6b7280; font-weight:500; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 0.5rem;">Angin</p>
                <p class="text-gray-900 dark:text-gray-100" style="font-size:1.5rem; font-weight:700; margin:0; line-height:1;">{{ $data['wind_speed'] }}<span style="font-size:0.7rem; color:#6b7280; font-weight:400;"> km/h</span></p>
            </div>
            <div class="bg-gray-100 dark:bg-gray-800/50 border border-gray-200 dark:border-white/5" style="border-radius:0.75rem; padding:0.875rem;">
                <p style="font-size:0.65rem; color:#6b7280; font-weight:500; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 0.5rem;">Risiko</p>
                <p style="font-size:1rem; font-weight:700; color:#10b981; margin:0; line-height:1.4;">Normal</p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
