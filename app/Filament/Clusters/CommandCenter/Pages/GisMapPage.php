<?php

namespace App\Filament\Clusters\CommandCenter\Pages;


use Filament\Pages\Page;

class GisMapPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Peta Monitoring GIS';
    protected static ?string $title = 'Peta Monitoring GIS';
    protected string $view = 'filament.clusters.command-center.pages.gis-map-page';
    protected static string | \UnitEnum | null $navigationGroup = 'Command Center';
    protected static ?int $navigationSort = 1;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Clusters\CommandCenter\Widgets\CommandCenterStats::class,
        ];
    }

    public function getDevices()
    {
        return \App\Models\Device::all()->map(function ($device) {
            // Seed a consistent random position based on ID for dummy data
            srand($device->id);
            return [
                'id' => $device->id,
                'name' => $device->name,
                'type' => $device->type,
                'status' => $device->status,
                'ip_address' => $device->ip_address,
                'location' => $device->location,
                'x' => $device->longitude ?: rand(10, 90),
                'y' => $device->latitude ?: rand(10, 90),
            ];
        })->toJson();
    }

    #[\Livewire\Attributes\On('open-detail-cctv')]
    public function openDetailCctv($deviceId)
    {
        $this->mountAction('detail_cctv', ['device_id' => $deviceId]);
    }

    #[\Livewire\Attributes\On('open-tambah-cctv')]
    public function openTambahCctv()
    {
        $this->mountAction('tambah_cctv');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('detail_cctv')
                ->hidden()
                ->model(\App\Models\Device::class)
                ->infolist(function (\Filament\Infolists\Infolist $infolist, array $arguments) {
                    $device = \App\Models\Device::find($arguments['device_id'] ?? null);
                    return $infolist
                        ->record($device)
                        ->schema([
                            \Filament\Infolists\Components\Section::make('Detail Perangkat')
                                ->schema([
                                    \Filament\Infolists\Components\TextEntry::make('name')->label('Nama Perangkat'),
                                    \Filament\Infolists\Components\TextEntry::make('type')->label('Tipe')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'cctv' => 'success',
                                            'router' => 'info',
                                            'switch' => 'warning',
                                            'ap' => 'primary',
                                            'server' => 'danger',
                                            default => 'gray',
                                        }),
                                    \Filament\Infolists\Components\TextEntry::make('ip_address')->label('IP / Channel'),
                                    \Filament\Infolists\Components\TextEntry::make('mac_address')->label('MAC Address'),
                                    \Filament\Infolists\Components\TextEntry::make('status')->label('Status')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'active' => 'success',
                                            'warning' => 'warning',
                                            'offline' => 'danger',
                                            default => 'gray',
                                        }),
                                    \Filament\Infolists\Components\ImageEntry::make('thumbnail_url')->label('Live Feed Thumbnail'),
                                ])
                                ->columns(2),
                        ]);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),

            \Filament\Actions\Action::make('tambah_cctv')
                ->label('Tambah CCTV')
                ->icon('heroicon-o-plus')
                ->model(\App\Models\Device::class)
                ->form(fn (\Filament\Schemas\Schema $schema) => \App\Filament\Clusters\CommandCenter\Resources\Devices\Schemas\DeviceForm::configure($schema))
                ->action(function (array $data) {
                    \App\Models\Device::create($data);
                    \Filament\Notifications\Notification::make()
                        ->title('Perangkat Berhasil Ditambahkan')
                        ->success()
                        ->send();
                    
                    // Dispatch event to update the JS map
                    $this->dispatch('device-added');
                })
                ->hidden() // Hide from header, since we trigger it via sidebar
        ];
    }
}
