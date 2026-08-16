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
        return [
            \App\Filament\Widgets\WelcomeBannerWidget::class,
        ];
    }

    public function getWidgets(): array
    {
        $widgets = parent::getWidgets();
        // Hapus WelcomeBannerWidget dari widget utama (bawah filter) karena sudah di atas
        return array_filter($widgets, fn (string $widget): bool => $widget !== \App\Filament\Widgets\WelcomeBannerWidget::class);
    }
}
