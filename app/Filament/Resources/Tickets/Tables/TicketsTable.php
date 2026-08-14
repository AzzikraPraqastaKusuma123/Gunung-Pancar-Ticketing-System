<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Filament\Resources\Tickets\TicketResource;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultPaginationPageOption(5)
            ->recordUrl(
                fn (\App\Models\Ticket $record): string => TicketResource::getUrl('edit', ['record' => $record]),
            )
            ->columnToggleFormColumns(2)
            ->columns([
                TextColumn::make('ticket_number')->label('Nomor Tiket')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('participant_name')->label('Nama Pengunjung')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->getStateUsing(fn (\App\Models\Ticket $record) => $record->participant_name ?: $record->booking?->customer_name),
                TextColumn::make('category')->label('Kategori')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('participant_count')->label('Jumlah Peserta')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'booked',
                        'success' => fn ($state) => in_array($state, ['printed', 'used']),
                    ])
                    ->searchable(),
                TextColumn::make('scannedBy.name')->label('Dipindai Oleh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('used_at')->label('Digunakan Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Action::make('Print Manual')
                    ->label('Cetak Fisik')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->iconButton()
                    ->tooltip('Cetak Fisik')
                    ->url(fn (\App\Models\Ticket $record): string => route('ticket.print_manual', $record))
                    ->openUrlInNewTab(),
                Action::make('Print')
                    ->label('E-Ticket')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->iconButton()
                    ->tooltip('Cetak E-Ticket')
                    ->url(fn (\App\Models\Ticket $record): string => route('ticket.print', $record))
                    ->openUrlInNewTab(),
                EditAction::make()->iconButton()->tooltip('Edit'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
