<x-filament-panels::page>
    <style>
        .ro-container { display: flex; flex-direction: column; gap: 1.5rem; }
        .ro-form { flex: 1; width: 100%; }
        .ro-sidebar { width: 100%; position: relative; }
        .ro-card { background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden; height: max-content; }
        
        /* Dark Mode Support */
        .dark .ro-card {
            background: linear-gradient(145deg, #18181b 0%, #111113 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.8) !important;
        }
        .dark .ro-title, .dark .ro-text-gray { color: #9ca3af !important; }
        .dark .ro-price { color: #60a5fa !important; }
        .dark .ro-text-dark { color: #f3f4f6 !important; }
        .dark .ro-border-dashed { border-top-color: rgba(255, 255, 255, 0.1) !important; }
        
        @media (min-width: 1024px) {
            .ro-container { flex-direction: row; align-items: flex-start; }
            .ro-form { flex: 0 0 65%; max-width: 65%; }
            .ro-sidebar { flex: 0 0 calc(35% - 1.5rem); max-width: calc(35% - 1.5rem); position: sticky; top: 6rem; }
        }
    </style>
    <form wire:submit="submit">
        <div class="ro-container">
            <!-- Bagian Form (Kiri) -->
            <div class="ro-form">
                {{ $this->form }}
            </div>

            <!-- Bagian Total & Tombol (Kanan) -->
            <div class="ro-sidebar">
                <div class="ro-card">
                    <div style="padding: 1.25rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <h3 class="ro-title" style="font-size: 0.875rem; font-weight: 600; color: #6b7280; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">Total Pembayaran</h3>
                                <p class="ro-price" style="font-size: 2rem; font-weight: 800; color: #1e40af; margin-top: 0.25rem; margin-bottom: 0; line-height: 1.1; letter-spacing: -0.02em;">Rp {{ number_format($this->harga_total, 0, ',', '.') }}</p>
                                
                                <div class="ro-border-dashed" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                                    <p class="ro-text-gray" style="font-size: 0.875rem; color: #6b7280; margin: 0;">Total dicetak</p>
                                    <span class="ro-text-dark" style="font-weight: 700; color: #111827; font-size: 1rem;">{{ $this->jumlah_tiket }} Tiket</span>
                                </div>
                            </div>
                            
                            <x-filament::button type="submit" size="md" color="primary" icon="heroicon-o-printer" style="width: 100%; justify-content: center; border-radius: 0.5rem; font-weight: 600;">
                                Cetak Tiket Sekarang
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-filament-panels::page>
