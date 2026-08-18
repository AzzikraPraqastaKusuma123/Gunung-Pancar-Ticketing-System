<?php

namespace App\Filament\Resources\Patrols\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class PatrolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_url')
                    ->label('Foto')
                    ->circular(),
                TextColumn::make('user.name')
                    ->label('Petugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location_name')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('coordinates')
                    ->label('Koordinat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'safe' => 'success',
                        'suspicious' => 'warning',
                        'incident' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'safe' => 'Aman',
                        'suspicious' => 'Mencurigakan',
                        'incident' => 'Insiden',
                        default => $state,
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Waktu Check-in')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Waktu Update')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
