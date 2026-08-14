<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Carbon\Carbon;

class RecentBookingsWidget extends TableWidget
{

    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 'full',
            'md' => 'full',
            'xl' => 'full',
        ];
    }
    
    protected static ?string $heading = 'Laporan Pesanan';

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'sales']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Booking::where('status', 'paid')->latest('booking_date'))
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')->label('Pelanggan')->searchable(),
                Tables\Columns\TextColumn::make('customer_phone')->label('Telepon')->searchable(),
                Tables\Columns\TextColumn::make('activity_type')->label('Paket Dasar')->badge(),
                Tables\Columns\TextColumn::make('booking_date')->label('Tgl Pesan')->date(),
                Tables\Columns\TextColumn::make('total_price')->label('Total Harga')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'success' => 'paid',
                    ]),
            ])
            ->filters([
                Filter::make('periode')
                    ->form([
                        Select::make('periode')
                            ->label('Pilih Periode Laporan')
                            ->options([
                                'minggu_ini' => 'Minggu Ini',
                                'bulan_ini' => 'Bulan Ini',
                                'semua' => 'Semua Waktu',
                            ])
                            ->default('minggu_ini'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['periode'] === 'minggu_ini',
                                fn (Builder $query): Builder => $query->whereBetween('booking_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            )
                            ->when(
                                $data['periode'] === 'bulan_ini',
                                fn (Builder $query): Builder => $query->whereBetween('booking_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                            );
                    }),
            ]);
    }
}
