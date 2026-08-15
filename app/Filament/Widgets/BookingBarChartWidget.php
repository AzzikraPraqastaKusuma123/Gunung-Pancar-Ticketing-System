<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingBarChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;
    public ?string $filter = 'week';

    public function getHeading(): string
    {
        return 'Grafik Penjualan Tiket';
    }

    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['super_admin', 'sales', 'ticketing']);
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
            for ($i = 8; $i <= 20; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            }
            
            $bookings = Booking::where('status', 'paid')
                ->whereDate('created_at', Carbon::today())
                ->get(['created_at']);
                
            $grouped = $bookings->groupBy(fn($b) => Carbon::parse($b->created_at)->format('G')); // 0-23
                
            for ($i = 8; $i <= 20; $i++) {
                $data[] = isset($grouped[$i]) ? $grouped[$i]->count() : 0;
            }
        } elseif ($activeFilter === 'week') {
            $startOfWeek = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $labels[] = $date->translatedFormat('l');
            }
            
            $bookings = Booking::where('status', 'paid')
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->get(['created_at']);
                
            $grouped = $bookings->groupBy(fn($b) => Carbon::parse($b->created_at)->format('w')); // 0=Sunday, 1=Monday
                
            $phpDays = [1, 2, 3, 4, 5, 6, 0]; // Monday to Sunday
            foreach ($phpDays as $day) {
                $data[] = isset($grouped[$day]) ? $grouped[$day]->count() : 0;
            }
        } elseif ($activeFilter === 'month') {
            $daysInMonth = Carbon::now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = $i;
            }
            
            $bookings = Booking::where('status', 'paid')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->get(['created_at']);
                
            $grouped = $bookings->groupBy(fn($b) => Carbon::parse($b->created_at)->format('j')); // 1-31
                
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $data[] = isset($grouped[$i]) ? $grouped[$i]->count() : 0;
            }
        } else {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $labels = $months;
            
            $bookings = Booking::where('status', 'paid')
                ->whereYear('created_at', Carbon::now()->year)
                ->get(['created_at']);
                
            $grouped = $bookings->groupBy(fn($b) => Carbon::parse($b->created_at)->format('n')); // 1-12
                
            foreach (range(1, 12) as $m) {
                $data[] = isset($grouped[$m]) ? $grouped[$m]->count() : 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi Lunas',
                    'data' => $data,
                    'backgroundColor' => '#06b6d4', // Cyan
                    'borderRadius' => 6,
                    'borderSkipped' => false,
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
        return 'bar';
    }
}
