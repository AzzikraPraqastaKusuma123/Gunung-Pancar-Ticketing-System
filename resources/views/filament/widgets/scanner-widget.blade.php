<x-filament-widgets::widget>
    <style>
        /* Light mode colors */
        .sw-bg-base { background-color: #ffffff; }
        .sw-bg-subtle { background-color: #f9fafb; }
        .sw-border { border-color: #e5e7eb; }
        .sw-text-base { color: #111827; }
        .sw-text-muted { color: #6b7280; }
        .sw-text-subtle { color: #9ca3af; }
        
        .sw-card-latest { background-color: #ecfdf5; border-color: #a7f3d0; }
        .sw-icon-latest { background-color: #d1fae5; color: #059669; }
        
        /* Dark mode colors */
        .dark .sw-bg-base { background-color: rgba(9, 9, 11, 0.5); }
        .dark .sw-bg-subtle { background-color: rgba(9, 9, 11, 0.8); }
        .dark .sw-border { border-color: rgba(255, 255, 255, 0.05); }
        .dark .sw-text-base { color: #f8fafc; }
        .dark .sw-text-muted { color: #94a3b8; }
        .dark .sw-text-subtle { color: #64748b; }
        
        .dark .sw-card-latest { background-color: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); }
        .dark .sw-icon-latest { background-color: rgba(16, 185, 129, 0.2); color: #34d399; }
    </style>

    <x-filament::section>
        <x-slot name="heading">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 0.5rem; height: 0.5rem; border-radius: 9999px; background-color: #10b981; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                Scan Tiket QR
            </div>
        </x-slot>
        <x-slot name="description">Arahkan kamera ke QR Code pengunjung untuk melakukan check-in</x-slot>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; align-items: flex-start; margin-top: 0.5rem;">

            {{-- LEFT: QR Scanner Camera --}}
            <div class="sw-bg-subtle sw-border" style="position: relative; border-radius: 0.75rem; overflow: hidden; border-width: 1px; border-style: solid; min-height: 400px; width: 100%;">
                <iframe src="/scanner?widget=true" allow="camera" style="position: absolute; inset: 0; width: 100%; height: 100%; border: none; background: transparent;">
                </iframe>
            </div>

            {{-- RIGHT: Recent Scans Panel --}}
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                {{-- Header --}}
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <x-filament::icon icon="heroicon-o-clock" class="sw-text-subtle" style="width: 1.25rem; height: 1.25rem;" />
                        <span class="sw-text-base" style="font-size: 0.875rem; font-weight: 600;">Riwayat Scan Terbaru</span>
                    </div>
                    <span class="sw-text-subtle" style="font-size: 0.75rem; font-weight: 500;">Auto-refresh setiap 3s</span>
                </div>

                {{-- Scan List --}}
                <div style="display: flex; flex-direction: column; gap: 0.75rem; overflow-y: auto; max-height: 360px; padding-right: 0.5rem;">
                    @forelse($recentScans as $scan)
                        <div class="{{ $scan['is_latest'] ? 'sw-card-latest' : 'sw-bg-base sw-border' }}" style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; border-radius: 0.75rem; border-width: 1px; border-style: solid; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: all 0.2s;">

                            {{-- Status Icon --}}
                            <div style="flex-shrink: 0; margin-top: 0.125rem;">
                                @if($scan['is_latest'])
                                    <div class="sw-icon-latest" style="width: 2.25rem; height: 2.25rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center;">
                                        <x-filament::icon icon="heroicon-m-check" style="width: 1.25rem; height: 1.25rem;" />
                                    </div>
                                @else
                                    <div class="sw-bg-subtle" style="width: 2.25rem; height: 2.25rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center;">
                                        <x-filament::icon icon="heroicon-m-qr-code" class="sw-text-subtle" style="width: 1.25rem; height: 1.25rem;" />
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                                    <div>
                                        <p class="sw-text-base" style="font-size: 0.875rem; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $scan['customer_name'] }}
                                            @if($scan['participant_name'] !== '-')
                                                <span class="sw-text-subtle" style="font-weight: 400;"> · {{ $scan['participant_name'] }}</span>
                                            @endif
                                        </p>
                                        <p class="sw-text-muted" style="font-size: 0.75rem; margin: 0.125rem 0 0 0; font-family: monospace; letter-spacing: 0.025em;">
                                            {{ $scan['ticket_number'] }}
                                        </p>
                                    </div>
                                    @if($scan['is_latest'])
                                        <x-filament::badge color="success" size="sm">BARU</x-filament::badge>
                                    @endif
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;">
                                    {{-- Category Badge --}}
                                    <span style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.7rem; font-weight: 500; padding: 0.125rem 0.5rem; border-radius: 9999px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2);">
                                        <x-filament::icon icon="heroicon-m-tag" style="width: 0.75rem; height: 0.75rem;" />
                                        {{ $scan['category'] }}
                                    </span>
                                    {{-- Pax --}}
                                    <span class="sw-text-subtle" style="font-size: 0.7rem;">
                                        {{ $scan['participant_count'] }} Pax
                                    </span>
                                    {{-- Time --}}
                                    <span class="sw-text-subtle" style="font-size: 0.7rem; margin-left: auto;">
                                        {{ $scan['used_at_human'] }} · {{ $scan['used_at'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Empty State --}}
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 0; text-align: center;">
                            <div class="sw-bg-subtle" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem;">
                                <x-filament::icon icon="heroicon-o-qr-code" class="sw-text-subtle" style="width: 1.75rem; height: 1.75rem;" />
                            </div>
                            <p class="sw-text-muted" style="font-size: 0.875rem; font-weight: 500; margin: 0;">Belum ada tiket yang di-scan</p>
                            <p class="sw-text-subtle" style="font-size: 0.75rem; margin: 0.25rem 0 0 0;">Data akan muncul otomatis setelah scan</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
