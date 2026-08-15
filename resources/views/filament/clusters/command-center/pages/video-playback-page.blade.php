<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Sidebar: Video List / Archive -->
        <div class="lg:col-span-1 fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Arsip Rekaman</h3>
            
            <div class="space-y-4">
                <div class="relative">
                    <x-filament::input.wrapper icon="heroicon-m-magnifying-glass">
                        <x-filament::input type="text" placeholder="Cari rekaman..." />
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-2 mt-4 max-h-[600px] overflow-y-auto pr-2">
                    <!-- Dummy Video List -->
                    @for($i = 1; $i <= 5; $i++)
                        <button class="w-full text-left p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition border border-transparent hover:border-gray-200 dark:hover:border-white/10 flex gap-3 items-center">
                            <div class="relative w-16 h-12 bg-gray-100 dark:bg-gray-800 rounded flex-shrink-0 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1557053964-937650ddbfce?q=80&w=150&auto=format&fit=crop" class="w-full h-full object-cover opacity-75" />
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <x-filament::icon icon="heroicon-s-play-circle" class="w-6 h-6 text-white drop-shadow" />
                                </div>
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">Rekaman Gate {{ $i }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ now()->subDays($i)->format('d M Y, H:i') }}</p>
                            </div>
                        </button>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Main Video Player Area -->
        <div class="lg:col-span-3 flex flex-col gap-6">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                <div class="aspect-video bg-black relative flex items-center justify-center">
                    <!-- Placeholder for Video Player -->
                    <x-filament::icon icon="heroicon-o-film" class="w-24 h-24 text-gray-700" />
                    
                    <div class="absolute bottom-0 w-full bg-gradient-to-t from-black/80 to-transparent p-4 flex items-center justify-between">
                        <div class="flex items-center gap-4 text-white">
                            <button class="hover:text-primary-400 transition"><x-filament::icon icon="heroicon-s-play" class="w-8 h-8" /></button>
                            <div class="text-sm font-medium">00:00 / 01:23:45</div>
                        </div>
                        <div class="flex items-center gap-4 text-white">
                            <button class="hover:text-primary-400 transition"><x-filament::icon icon="heroicon-s-cog-8-tooth" class="w-6 h-6" /></button>
                            <button class="hover:text-primary-400 transition"><x-filament::icon icon="heroicon-s-arrows-pointing-out" class="w-6 h-6" /></button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Rekaman Gate 1 - Shift Malam</h2>
                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1"><x-filament::icon icon="heroicon-m-calendar" class="w-4 h-4" /> {{ now()->subDays(1)->format('d M Y') }}</span>
                        <span class="flex items-center gap-1"><x-filament::icon icon="heroicon-m-clock" class="w-4 h-4" /> 18:00 - 06:00</span>
                        <span class="flex items-center gap-1"><x-filament::icon icon="heroicon-m-video-camera" class="w-4 h-4" /> DVR Induk 01</span>
                    </div>
                </div>
            </div>
            
            <!-- Controls / Timeline -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Timeline Controller</h3>
                <div class="h-16 bg-gray-100 dark:bg-gray-800 rounded-lg relative flex items-end">
                    <!-- Mock Timeline -->
                    <div class="absolute inset-y-0 left-0 bg-primary-500/20 w-1/3 rounded-l-lg border-r-2 border-primary-500"></div>
                    <div class="w-full flex justify-between px-2 text-[10px] text-gray-400 pb-1">
                        <span>18:00</span><span>20:00</span><span>22:00</span><span>00:00</span><span>02:00</span><span>04:00</span><span>06:00</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
