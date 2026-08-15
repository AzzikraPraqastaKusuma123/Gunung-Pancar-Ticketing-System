<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketAnalyticsWidget extends ChartWidget
{

    protected static ?int $sort = 1;
    public ?string $filter = 'year';

    public function getHeading(): string
    {
        return 'Grafik Pemindaian Tiket';
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['super_admin', 'ticketing']);
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $data = [];
        $labels = [];

        if ($activeFilter === 'today') {
            for ($i = 0; $i < 24; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            }
            
            $tickets = Ticket::whereIn('status', ['used'])
                ->whereDate('used_at', Carbon::today())
                ->get(['used_at']);
                
            $grouped = $tickets->groupBy(fn($t) => Carbon::parse($t->used_at)->format('G')); // 0-23
                
            for ($i = 0; $i < 24; $i++) {
                $data[] = isset($grouped[$i]) ? $grouped[$i]->count() : 0;
            }
        } elseif ($activeFilter === 'week') {
            $startOfWeek = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $labels[] = $date->translatedFormat('l');
            }
            
            $tickets = Ticket::whereIn('status', ['used'])
                ->whereBetween('used_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->get(['used_at']);
                
            $grouped = $tickets->groupBy(fn($t) => Carbon::parse($t->used_at)->format('w')); // 0=Sunday, 1=Monday
            
            // Map labels (0-6 starting from Monday based on above logic)
            // But PHP format('w') gives 0 for Sunday.
            // Labels are generated starting from startOfWeek() which is Monday (1)
            $phpDays = [1, 2, 3, 4, 5, 6, 0]; // Monday to Sunday
            foreach ($phpDays as $day) {
                $data[] = isset($grouped[$day]) ? $grouped[$day]->count() : 0;
            }
        } elseif ($activeFilter === 'month') {
            $daysInMonth = Carbon::now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = $i;
            }
            
            $tickets = Ticket::whereIn('status', ['used'])
                ->whereMonth('used_at', Carbon::now()->month)
                ->whereYear('used_at', Carbon::now()->year)
                ->get(['used_at']);
                
            $grouped = $tickets->groupBy(fn($t) => Carbon::parse($t->used_at)->format('j')); // 1-31
                
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $data[] = isset($grouped[$i]) ? $grouped[$i]->count() : 0;
            }
        } else {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $labels = $months;
            
            $tickets = Ticket::whereIn('status', ['used'])
                ->whereYear('used_at', Carbon::now()->year)
                ->get(['used_at']);
                
            $grouped = $tickets->groupBy(fn($t) => Carbon::parse($t->used_at)->format('n')); // 1-12
                
            foreach (range(1, 12) as $m) {
                $data[] = isset($grouped[$m]) ? $grouped[$m]->count() : 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tiket Dipindai',
                    'data' => $data,
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
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
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
