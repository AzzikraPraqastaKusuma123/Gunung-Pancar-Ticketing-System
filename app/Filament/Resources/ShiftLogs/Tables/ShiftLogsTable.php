<?php

namespace App\Filament\Resources\ShiftLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShiftLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Petugas')
                    ->sortable(),
                TextColumn::make('shift_type')
                    ->label('Tipe Shift')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'morning' => 'success',
                        'afternoon' => 'warning',
                        'night' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->label('Status Jaga')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ongoing' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
