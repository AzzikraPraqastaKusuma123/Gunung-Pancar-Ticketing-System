<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Bookings\BookingResource;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn (\App\Models\Booking $record): string => BookingResource::getUrl('edit', ['record' => $record]),
            )
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('customer_name')->label('Nama Pelanggan')
                    ->searchable(),
                TextColumn::make('customer_email')->label('Email')
                    ->searchable(),
                TextColumn::make('customer_phone')->label('No. Telepon')
                    ->searchable(),
                TextColumn::make('booking_date')->label('Tgl Pesan')
                    ->date()
                    ->sortable(),
                TextColumn::make('visit_date')->label('Tgl Kunjungan')
                    ->date()
                    ->sortable(),
                TextColumn::make('items_summary')
                    ->label('Rincian Tiket')
                    ->getStateUsing(function (\App\Models\Booking $record) {
                        return $record->items->map(function ($item) {
                            return $item->quantity . 'x ' . ucfirst(str_replace('_', ' ', $item->category));
                        })->implode(', ');
                    })
                    ->wrap(),
                TextColumn::make('total_price')->label('Total Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('created_at')->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\Action::make('download_ticket')
                    ->label('E-Ticket')
                    ->icon('heroicon-o-ticket')
                    ->color('success')
                    ->url(fn ($record) => route('ticket.download', $record->uuid))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->status === 'paid'),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \Filament\Actions\ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\BookingExporter::class)
                        ->formats([\Filament\Actions\Exports\Enums\ExportFormat::Csv, \Filament\Actions\Exports\Enums\ExportFormat::Xlsx])
                        ->columnMapping(false)
                        ->icon('heroicon-o-document-arrow-down')
                        ->label('Ekspor yang Dipilih')
                        ->color('success'),
                ]),
            ])
            ->headerActions([
                \Filament\Actions\ExportAction::make()
                    ->exporter(\App\Filament\Exports\BookingExporter::class)
                    ->formats([\Filament\Actions\Exports\Enums\ExportFormat::Csv, \Filament\Actions\Exports\Enums\ExportFormat::Xlsx])
                    ->columnMapping(false)
                    ->icon('heroicon-o-document-arrow-down')
                    ->label('Ekspor Semua Data')
                    ->color('success'),
            ]);
    }
}
