<?php

namespace App\Filament\Clusters\CommandCenter\Pages;


use App\Models\Device;
use Filament\Pages\Page;

class LiveCctvMonitoring extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'Live CCTV Wall';
    protected static ?string $title = 'Live CCTV Monitoring Wall';
    protected static string | \UnitEnum | null $navigationGroup = 'Command Center';
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'security']);
    }

    protected string $view = 'filament.clusters.command-center.pages.live-cctv-monitoring';

    public function getActiveCctvs()
    {
        return Device::where('type', 'cctv')
            ->where('status', 'active')
            ->get();
    }
}
