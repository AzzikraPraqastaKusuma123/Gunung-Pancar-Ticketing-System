<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class BookingStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'sales', 'ticketing']);
    }

    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 'full',
            'md' => 'full',
            'xl' => 'full',
        ];
    }

    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $period = $this->filters['period'] ?? 'monthly';

        $queryBookings = Booking::where('status', 'paid');
        $queryRevenue = Booking::where('status', 'paid');
        $queryTickets = Ticket::where('status', 'used');

        $periodLabel = 'Bulan Ini';

        switch ($period) {
            case 'today':
                $queryBookings->whereDate('created_at', today());
                $queryRevenue->whereDate('created_at', today());
                $queryTickets->whereDate('used_at', today());
                $periodLabel = 'Hari Ini';
                break;
            case 'weekly':
                $queryBookings->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $queryRevenue->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $queryTickets->whereBetween('used_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $periodLabel = 'Minggu Ini';
                break;
            case 'monthly':
                $queryBookings->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                $queryRevenue->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                $queryTickets->whereMonth('used_at', now()->month)->whereYear('used_at', now()->year);
                $periodLabel = 'Bulan Ini';
                break;
            case 'yearly':
                $queryBookings->whereYear('created_at', now()->year);
                $queryRevenue->whereYear('created_at', now()->year);
                $queryTickets->whereYear('used_at', now()->year);
                $periodLabel = 'Tahun Ini';
                break;
            case 'all':
                $periodLabel = 'Semua Waktu';
                break;
        }

        $totalBookings = $queryBookings->count();
        $totalRevenue = $queryRevenue->sum('total_price');
        $ticketsScanned = $queryTickets->count();

        // Format revenue short (e.g., 1.295.000 -> 1,29 Jt) for mobile
        if ($totalRevenue >= 1000000) {
            $shortRevenue = number_format($totalRevenue / 1000000, 1, ',', '.') . ' Jt';
        } elseif ($totalRevenue >= 1000) {
            $shortRevenue = number_format($totalRevenue / 1000, 0, ',', '.') . ' Rb';
        } else {
            $shortRevenue = 'Rp ' . $totalRevenue;
        }

        // Format revenue full (e.g., Rp 3.200.000) for desktop
        $fullRevenue = 'Rp ' . number_format($totalRevenue, 0, ',', '.');
        
        $responsiveRevenue = new \Illuminate\Support\HtmlString('
            <style>
                .rev-full { display: none; }
                .rev-short { display: inline; }
                @media (min-width: 768px) {
                    .rev-full { display: inline; }
                    .rev-short { display: none; }
                }
            </style>
            <span class="rev-full" style="white-space: nowrap;">' . $fullRevenue . '</span>
            <span class="rev-short" style="white-space: nowrap;">' . $shortRevenue . '</span>
        ');

        return [
            Stat::make('Pemesanan', $totalBookings)
                ->description('Total')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
                
            Stat::make('Pendapatan', $responsiveRevenue)
                ->description('Lunas')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
                
            Stat::make('Pengunjung', $ticketsScanned)
                ->description('Hadir')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
        ];
    }

    protected function getColumns(): int | array | null
    {
        return [
            'default' => 3,
            'sm' => 3,
            'md' => 3,
            'xl' => 3,
        ];
    }
}
