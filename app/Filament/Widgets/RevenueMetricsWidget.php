<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class RevenueMetricsWidget extends ChartWidget
{

    protected ?string $heading = 'Grafik Pendapatan';
    protected static ?int $sort = 3;
    protected ?string $maxHeight = '250px';

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['super_admin', 'sales']);
    }

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(fn ($m) => date('F', mktime(0, 0, 0, $m, 10)));
        $revenue = [];
        
        foreach (range(1, 12) as $m) {
            $revenue[] = Booking::where('status', 'paid')
                ->whereMonth('created_at', $m)
                ->whereYear('created_at', date('Y'))
                ->sum('total_price');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pendapatan (Rp)',
                    'data' => $revenue,
                    'borderColor' => '#10b981', // Emerald neon
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => 'start',
                    'tension' => 0.4,
                    'borderWidth' => 3,
                    'pointBackgroundColor' => '#059669',
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
