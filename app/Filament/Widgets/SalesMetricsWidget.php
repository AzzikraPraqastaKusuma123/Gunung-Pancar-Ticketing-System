<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class SalesMetricsWidget extends ChartWidget
{

    protected ?string $heading = 'Grafik Pertumbuhan Prospek (Leads)';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '250px';

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['super_admin', 'sales']);
    }

    protected function getData(): array
    {
        // Simple mock for chart since flowframe/laravel-trend isn't installed
        $months = collect(range(1, 12))->map(fn ($m) => date('F', mktime(0, 0, 0, $m, 10)));
        $counts = [];
        
        foreach (range(1, 12) as $m) {
            $counts[] = Lead::whereMonth('created_at', $m)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Prospek Baru',
                    'data' => $counts,
                    'borderColor' => '#0ea5e9', // Sky blue / cyan
                    'backgroundColor' => 'rgba(14, 165, 233, 0.15)',
                    'fill' => 'start',
                    'tension' => 0.4,
                    'borderWidth' => 3,
                    'pointBackgroundColor' => '#0284c7',
                    'pointBorderColor' => '#ffffff',
                    'pointRadius' => 0,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(156, 163, 175, 0.1)',
                        'drawBorder' => false,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                        'drawBorder' => false,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
