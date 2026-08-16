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

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['super_admin', 'sales']);
    }

    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 'full',
            'md'      => 'full',
            'xl'      => 'full',
        ];
    }

    protected function getColumns(): int | array | null
    {
        return [
            'default' => 2,  // 2 kolom di mobile → 2×2 grid
            'sm'      => 2,
            'md'      => 4,  // 4 kolom di desktop
            'xl'      => 4,
        ];
    }

    protected function getStats(): array
    {
        $totalPendapatan = Booking::where('status', 'paid')->sum('total_price');
        if ($totalPendapatan >= 1000000) {
            $formattedPendapatan = 'Rp ' . number_format($totalPendapatan / 1000000, 1, ',', '.') . ' Jt';
        } elseif ($totalPendapatan >= 1000) {
            $formattedPendapatan = 'Rp ' . number_format($totalPendapatan / 1000, 0, ',', '.') . ' Rb';
        } else {
            $formattedPendapatan = 'Rp ' . number_format($totalPendapatan, 0, ',', '.');
        }

        $responsivePendapatan = new \Illuminate\Support\HtmlString('
            <span class="text-2xl font-bold" style="white-space: nowrap;">' . $formattedPendapatan . '</span>
        ');

        return [
            Stat::make('Total Prospek', Lead::count())
                ->description('Total prospek keseluruhan')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Total Pesanan Lunas', Booking::where('status', 'paid')->count())
                ->description('Prospek yang deal')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Total Pendapatan', $responsivePendapatan)
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
