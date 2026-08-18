<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function getHeading(): string
    {
        return '';
    }

    public function filtersForm(Schema $schema): Schema
    {
        if (auth()->user()->hasRole('security')) {
            return $schema->schema([]);
        }

        return $schema
            ->schema([
                Select::make('period')
                    ->label('Periode Waktu')
                    ->options([
                        'today' => 'Hari Ini',
                        'weekly' => 'Minggu Ini',
                        'monthly' => 'Bulan Ini',
                        'yearly' => 'Tahun Ini',
                        'all' => 'Semua Waktu',
                    ])
                    ->default('monthly')
                    ->selectablePlaceholder(false),
            ])
            ->columns(3);
    }

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 2,
        ];
    }

    public function getHeaderWidgets(): array
    {
        if (auth()->user()->hasRole('security')) {
            return []; // No welcome banner for security to save space for CCTV widgets
        }

        return [
            \App\Filament\Widgets\WelcomeBannerWidget::class,
        ];
    }

    public function getWidgets(): array
    {
        if (auth()->user()->hasRole('security')) {
            return [
                \App\Filament\Widgets\CctvStatusWidget::class,
                \App\Filament\Widgets\CctvLiveFeedsWidget::class,
                \App\Filament\Widgets\CctvBandwidthChartWidget::class,
                \App\Filament\Widgets\CctvStorageChartWidget::class,
                \App\Filament\Widgets\CctvEnvironmentSensorWidget::class,
                \App\Filament\Widgets\CctvQuickActionWidget::class,
                \App\Filament\Widgets\CctvRecentAlertsWidget::class,
                \App\Filament\Widgets\CctvDeviceHealthWidget::class,
            ];
        }

        $widgets = parent::getWidgets();
        // Hapus WelcomeBannerWidget & CCTV Widgets dari widget utama (bawah filter)
        return array_filter($widgets, function (string $widget): bool {
            return $widget !== \App\Filament\Widgets\WelcomeBannerWidget::class &&
                   strpos($widget, 'Cctv') === false;
        });
    }
}
