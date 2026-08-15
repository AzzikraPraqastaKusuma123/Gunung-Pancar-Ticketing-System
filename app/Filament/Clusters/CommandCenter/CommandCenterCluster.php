<?php

namespace App\Filament\Clusters\CommandCenter;

use Filament\Clusters\Cluster;

class CommandCenterCluster extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-server-stack';
    protected static ?string $navigationLabel = 'Command Center';
    protected static ?string $clusterBreadcrumb = 'Command Center';

    public static function getNavigationItems(): array
    {
        $items = parent::getNavigationItems();

        $items[] = \Filament\Navigation\NavigationItem::make('Tambah CCTV (GIS)')
            ->icon('heroicon-o-plus-circle')
            ->url("javascript:window.Livewire.dispatch('open-tambah-cctv')")
            ->visible(fn() => request()->routeIs('filament.admin.command-center.pages.gis-map-page'))
            ->sort(100);

        $items[] = \Filament\Navigation\NavigationItem::make('3D Mode (GIS)')
            ->icon('heroicon-o-cube')
            ->url("javascript:document.getElementById('btn-toggle-3d')?.click();")
            ->visible(fn() => request()->routeIs('filament.admin.command-center.pages.gis-map-page'))
            ->sort(101);

        return $items;
    }
}
