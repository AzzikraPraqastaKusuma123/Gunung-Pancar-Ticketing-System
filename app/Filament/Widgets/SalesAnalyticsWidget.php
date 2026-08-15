<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Lead;
use App\Models\Booking;
use App\Models\LetterOfAgreement;

use Illuminate\Support\Facades\Auth;

class SalesAnalyticsWidget extends StatsOverviewWidget
{

    protected static ?int $sort = -1;

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['super_admin', 'sales']);
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Prospek', Lead::count())
                ->description('Total prospek keseluruhan')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Total Pesanan Lunas', Booking::where('status', 'paid')->count())
                ->description('Prospek yang deal')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Total Pendapatan', 'Rp ' . number_format(Booking::where('status', 'paid')->sum('total_price'), 0, ',', '.'))
                ->description('Pesanan lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('LOA Aktif', LetterOfAgreement::whereIn('status', ['draft', 'sent'])->count())
                ->description('Menunggu tanda tangan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
        ];
    }
}
