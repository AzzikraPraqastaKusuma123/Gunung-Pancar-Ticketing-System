<?php

namespace App\Filament\Clusters\CommandCenter\Pages;


use Filament\Pages\Page;

class NetworkTopologyPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationLabel = 'Pemetaan Jaringan';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Network Topology';

    protected string $view = 'filament.clusters.command-center.pages.network-topology-page';

    protected static string | \UnitEnum | null $navigationGroup = 'Command Center';

    protected static ?int $navigationSort = 2;

    public function getDevices()
    {
        return \App\Models\Device::all()->map(function ($device) {
            return [
                'id' => $device->id,
                'name' => $device->name,
                'type' => $device->type,
                'status' => $device->status,
                'ip_address' => $device->ip_address,
                'location' => $device->location,
            ];
        })->toJson();
    }
}
