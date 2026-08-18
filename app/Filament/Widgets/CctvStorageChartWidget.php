<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class CctvStorageChartWidget extends ChartWidget
{
    protected ?string $heading = 'Kapasitas Storage DVR';
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('security');
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Storage (TB)',
                    'data' => [15.8, 1.2, 3.0], // Used, System, Free
                    'backgroundColor' => [
                        '#10b981', // Emerald for Used Archive
                        '#eab308', // Yellow for System Reserve
                        '#374151', // Dark Gray for Free
                    ],
                    'borderWidth' => 0,
                    'hoverOffset' => 4
                ],
            ],
            'labels' => ['Arsip Rekaman (15.8 TB)', 'Sistem & Backup (1.2 TB)', 'Ruang Kosong (3.0 TB)'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
