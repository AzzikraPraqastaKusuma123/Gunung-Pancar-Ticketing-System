<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Lead;
use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WeeklySummaryWidget extends BaseWidget
{

    protected static ?int $sort = 5;
    protected ?string $pollingInterval = '15s';

    public static function canView(): bool
    {
        // Hanya super_admin yang melihat ringkasan seluruh divisi
        return Auth::user()->hasRole('super_admin');
    }

    protected function getStats(): array
    {
        // Time ranges
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekEnd = Carbon::now()->endOfWeek();
        
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        // 1. Prospek Baru (Leads)
        $leadsThisWeek = Lead::whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->count();
        $leadsLastWeek = Lead::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        
        $leadDiff = $leadsThisWeek - $leadsLastWeek;
        $leadIcon = $leadDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $leadColor = $leadDiff >= 0 ? 'success' : 'danger';
        $leadDesc = abs($leadDiff) . ' vs minggu lalu';

        // 2. Omset (Bookings)
        $revenueThisWeek = Booking::where('status', 'paid')
                            ->whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])
                            ->sum('total_price');
        $revenueLastWeek = Booking::where('status', 'paid')
                            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                            ->sum('total_price');
                            
        $revenueDiff = $revenueThisWeek - $revenueLastWeek;
        $revenueIcon = $revenueDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $revenueColor = $revenueDiff >= 0 ? 'success' : 'danger';
        $revenueDesc = ($revenueDiff >= 0 ? '+' : '-') . 'Rp ' . number_format(abs($revenueDiff), 0, ',', '.') . ' vs minggu lalu';

        // 3. Tiket Dipindai (Tickets used)
        $ticketsThisWeek = Ticket::where('status', 'used')
                            ->whereBetween('used_at', [$thisWeekStart, $thisWeekEnd])
                            ->count();
        $ticketsLastWeek = Ticket::where('status', 'used')
                            ->whereBetween('used_at', [$lastWeekStart, $lastWeekEnd])
                            ->count();
                            
        $ticketDiff = $ticketsThisWeek - $ticketsLastWeek;
        $ticketIcon = $ticketDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $ticketColor = $ticketDiff >= 0 ? 'success' : 'danger';
        $ticketDesc = abs($ticketDiff) . ' vs minggu lalu';

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($revenueThisWeek, 0, ',', '.'))
                ->description($revenueDesc)
                ->descriptionIcon($revenueIcon)
                ->color($revenueColor)
                ->chart([7, 4, 6, 8, 12, 10, $revenueThisWeek > 0 ? 15 : 5]), // Mock sparkline
                
            Stat::make('Prospek Baru', $leadsThisWeek)
                ->description($leadDesc)
                ->descriptionIcon($leadIcon)
                ->color($leadColor)
                ->chart([1, 2, 1, 4, 3, 5, $leadsThisWeek > 0 ? 8 : 2]),

            Stat::make('Tiket Dipindai', $ticketsThisWeek)
                ->description($ticketDesc)
                ->descriptionIcon($ticketIcon)
                ->color($ticketColor)
                ->chart([10, 15, 20, 18, 25, 30, $ticketsThisWeek > 0 ? 40 : 10]),
        ];
    }
}
