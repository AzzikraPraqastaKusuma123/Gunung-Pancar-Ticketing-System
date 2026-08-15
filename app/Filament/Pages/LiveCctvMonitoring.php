<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LiveCctvMonitoring extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'Live CCTV Wall';
    protected static ?string $title = 'Live CCTV Wall';
    protected static ?string $cluster = \App\Filament\Clusters\CommandCenter\CommandCenterCluster::class;
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.live-cctv-monitoring';

    protected function getViewData(): array
    {
        return [
            'cameras' => \App\Models\Device::where('type', 'cctv')->get()
        ];
    }
}
