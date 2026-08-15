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
                \Filament\Actions\Action::make('send_wa')
                    ->label('Kirim E-Ticket WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->action(function (\App\Models\Booking $record) {
                        $phone = $record->customer_phone;
                        if (!$phone) {
                            \Filament\Notifications\Notification::make()
                                ->title('Nomor Telepon Kosong')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        $message = "Halo *{$record->customer_name}*,\n\nTerima kasih telah memesan tiket di *Gunung Pancar*. Pembayaran Anda telah kami terima!\n\nSilakan unduh E-Ticket Anda melalui tautan berikut:\n" . route('ticket.download', $record->uuid) . "\n\nTerima kasih!";
                        
                        $result = app(\App\Services\WhatsappService::class)->sendMessage($phone, $message);
                        
                        if ($result) {
                            \Filament\Notifications\Notification::make()
                                ->title('Pesan WhatsApp Berhasil Dikirim')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Mengirim WhatsApp')
                                ->body('Pastikan integrasi Meta API berjalan (cek .env).')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn ($record) => $record->status === 'paid'),
                \Filament\Actions\Action::make('print')
                    ->label(fn ($record) => str_starts_with((string)$record->customer_email, 'walkin_') ? 'Struk Thermal' : 'E-Ticket')
                    ->icon(fn ($record) => str_starts_with((string)$record->customer_email, 'walkin_') ? 'heroicon-o-printer' : 'heroicon-o-ticket')
                    ->color(fn ($record) => str_starts_with((string)$record->customer_email, 'walkin_') ? 'gray' : 'success')
                    ->url(fn ($record) => str_starts_with((string)$record->customer_email, 'walkin_') 
                        ? route('booking.pos_print', $record->uuid) 
                        : route('ticket.download', $record->uuid))
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
