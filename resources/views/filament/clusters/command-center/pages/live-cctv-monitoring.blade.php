<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($this->getActiveCctvs() as $cctv)
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex flex-col overflow-hidden transition duration-300 hover:shadow-md">
                <div class="relative w-full aspect-video bg-gray-950 flex items-center justify-center overflow-hidden">
                    @if($cctv->stream_url)
                        <!-- If there's an actual stream URL, you might embed an iframe or video player here -->
                        <img src="{{ $cctv->thumbnail_url ?: 'https://images.unsplash.com/photo-1557053964-937650ddbfce?q=80&w=600&auto=format&fit=crop' }}" alt="{{ $cctv->name }}" class="w-full h-full object-cover opacity-80 mix-blend-screen" />
                    @else
                        <img src="{{ $cctv->thumbnail_url ?: 'https://images.unsplash.com/photo-1557053964-937650ddbfce?q=80&w=600&auto=format&fit=crop' }}" alt="{{ $cctv->name }}" class="w-full h-full object-cover opacity-80" />
                    @endif
                    
                    <div class="absolute top-3 right-3 flex gap-2">
                        <span class="inline-flex items-center gap-x-1.5 rounded-full bg-danger-500/10 px-2 py-1 text-xs font-medium text-danger-600 dark:bg-danger-400/10 dark:text-danger-400 ring-1 ring-inset ring-danger-500/20">
                            <span class="h-1.5 w-1.5 rounded-full bg-danger-500 animate-pulse"></span>
                            LIVE
                        </span>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-gray-950/80 to-transparent p-4 pt-8">
                        <h3 class="text-white font-medium truncate">{{ $cctv->name }}</h3>
                        <p class="text-gray-300 text-xs">{{ $cctv->location ?: 'Lokasi Tidak Diketahui' }}</p>
                    </div>
                </div>
                
                <div class="p-4 border-t border-gray-100 dark:border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-m-signal" class="w-4 h-4 text-success-500" />
                        <span>Koneksi Stabil</span>
                    </div>
                    <x-filament::button size="xs" color="gray" icon="heroicon-m-arrows-pointing-out" outlined>
                        Fokus
                    </x-filament::button>
                </div>
            </div>
        @empty
            <div class="col-span-full fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-12 text-center flex flex-col items-center justify-center">
                <x-filament::icon icon="heroicon-o-video-camera-slash" class="w-12 h-12 text-gray-400 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tidak Ada CCTV Aktif</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Belum ada perangkat CCTV yang berstatus aktif saat ini.</p>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
