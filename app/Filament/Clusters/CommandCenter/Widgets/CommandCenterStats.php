<?php

namespace App\Filament\Clusters\CommandCenter\Widgets;

use App\Models\Device;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommandCenterStats extends BaseWidget
{
    protected ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $devices = Device::all();
        
        $total = $devices->count();
        $online = $devices->where('status', 'active')->count();
        $warning = $devices->where('status', 'warning')->count();
        $offline = $devices->where('status', 'offline')->count();

        return [
            Stat::make('Total Perangkat', $total)
                ->description('Semua perangkat jaringan')
                ->descriptionIcon('heroicon-m-server-stack')
                ->color('primary'),
                
            Stat::make('Online / Aktif', $online)
                ->description('Perangkat beroperasi normal')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Peringatan', $warning)
                ->description('Perangkat dengan kendala')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
                
            Stat::make('Offline / Mati', $offline)
                ->description('Perangkat tidak terhubung')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
