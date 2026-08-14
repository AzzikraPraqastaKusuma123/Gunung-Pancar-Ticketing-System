<?php

namespace App\Filament\Resources\Leads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use App\Filament\Resources\Leads\LeadResource;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn (\App\Models\Lead $record): string => LeadResource::getUrl('edit', ['record' => $record]),
            )
            ->columns([
                TextColumn::make('name')->label('Nama Pelanggan')->searchable()->sortable(),
                TextColumn::make('phone')->label('No. Telepon')->searchable(),
                TextColumn::make('customer_segment')->label('Segmen Pelanggan')->sortable(),
                TextColumn::make('activity_type')->label('Tipe Aktivitas')->sortable(),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'Cancelled',
                        'warning' => fn ($state) => in_array($state, ['New Lead', 'Contacted']),
                        'primary' => fn ($state) => in_array($state, ['Qualified', 'Quotation', 'Negotiation']),
                        'success' => fn ($state) => in_array($state, ['Booked', 'Completed']),
                    ]),
                TextColumn::make('pic_sales')->label('Sales'),
                TextColumn::make('next_follow_up')->label('Jadwal Follow-up')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Filter Status')
                    ->options([
                        'New Lead' => 'Prospek Baru',
                        'Contacted' => 'Sudah Dihubungi',
                        'Qualified' => 'Kualifikasi Masuk',
                        'Quotation' => 'Kirim Penawaran',
                        'Negotiation' => 'Negosiasi',
                        'Booked' => 'Deal / Booked',
                        'Completed' => 'Selesai',
                    ]),
                SelectFilter::make('pic_sales')->label('Sales Person'),
            ])
            ->recordActions([
                Action::make('convert_to_booking')
                    ->label('Convert to Booking')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'Booked' && $record->status !== 'Completed')
                    ->action(function ($record) {
                        $booking = \App\Models\Booking::create([
                            'lead_id' => $record->id,
                            'customer_name' => $record->name,
                            'customer_phone' => $record->phone,
                            'customer_segment' => $record->customer_segment,
                            'activity_type' => $record->activity_type,
                            'pic_sales' => $record->pic_sales,
                            'booking_date' => now(),
                            'visit_date' => $record->event_date ?? now(),
                            'special_requirements' => $record->needs,
                            'status' => 'pending',
                        ]);
                        $record->update(['status' => 'Booked']);
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
