<?php

namespace App\Filament\Resources\TourPackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\TourPackages\TourPackageResource;

class TourPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn (\App\Models\TourPackage $record): string => TourPackageResource::getUrl('edit', ['record' => $record]),
            )
            ->columns([
                Filament\Tables\Columns\ImageColumn::make('image'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('base_price')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
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
