<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class CctvBandwidthChartWidget extends ChartWidget
{
    protected ?string $heading = 'Live Network Bandwidth (CCTV)';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('security');
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Download (Mbps)',
                    'data' => [120, 115, 125, 130, 128, 140, 135, 122, 130, 135, 145, 140],
                    'borderColor' => '#10b981', // Emerald 500
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Upload (Mbps)',
                    'data' => [40, 38, 45, 42, 48, 55, 50, 48, 52, 47, 50, 45],
                    'borderColor' => '#3b82f6', // Blue 500
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => ['-11s', '-10s', '-9s', '-8s', '-7s', '-6s', '-5s', '-4s', '-3s', '-2s', '-1s', 'Live'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
