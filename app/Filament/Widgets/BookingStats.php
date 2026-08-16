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

        // Format revenue short (e.g., 1.295.000 -> 1,29 Jt) for both mobile & desktop
        if ($totalRevenue >= 1000000) {
            $formattedRevenue = 'Rp ' . number_format($totalRevenue / 1000000, 1, ',', '.') . ' Jt';
        } elseif ($totalRevenue >= 1000) {
            $formattedRevenue = 'Rp ' . number_format($totalRevenue / 1000, 0, ',', '.') . ' Rb';
        } else {
            $formattedRevenue = 'Rp ' . number_format($totalRevenue, 0, ',', '.');
        }

        $responsiveRevenue = new \Illuminate\Support\HtmlString('
            <span class="text-2xl font-bold" style="white-space: nowrap;">' . $formattedRevenue . '</span>
        ');

        return [
            Stat::make('Pengunjung', $ticketsScanned)
                ->description('Hadir / Check-in')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Reservasi', $totalBookings)
                ->description($periodLabel)
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
                
            Stat::make('Pendapatan', $responsiveRevenue)
                ->description($periodLabel)
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
                
            Stat::make('Leads / Prospek', \App\Models\Lead::count())
                ->description('Total Prospek')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('warning'),
        ];
    }

    protected function getColumns(): int | array | null
    {
        return [
            'default' => 2,
            'sm' => 2,
            'md' => 4,
            'xl' => 4,
        ];
    }
}
