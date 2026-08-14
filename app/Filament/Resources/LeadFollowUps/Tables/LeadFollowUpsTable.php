<?php

namespace App\Filament\Resources\LeadFollowUps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use App\Filament\Resources\LeadFollowUps\LeadFollowUpResource;

class LeadFollowUpsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn (\App\Models\LeadFollowUp $record): string => LeadFollowUpResource::getUrl('edit', ['record' => $record]),
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('lead.name')
                    ->label('Nama Lead')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('follow_up_date')
                    ->label('Tanggal Follow Up')
                    ->dateTime()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('result')
                    ->label('Hasil/Keterangan')
                    ->limit(50)
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('next_follow_up_date')
                    ->label('Follow Up Selanjutnya')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
