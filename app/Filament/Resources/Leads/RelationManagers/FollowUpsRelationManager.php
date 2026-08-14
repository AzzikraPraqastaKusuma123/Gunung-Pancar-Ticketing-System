<?php

namespace App\Filament\Resources\Leads\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FollowUpsRelationManager extends RelationManager
{
    protected static string $relationship = 'followUps';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\DateTimePicker::make('follow_up_date')
                    ->required(),
                Forms\Components\Textarea::make('result')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('next_follow_up_date'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('follow_up_date')
            ->columns([
                Tables\Columns\TextColumn::make('follow_up_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('result')
                    ->limit(50),
                Tables\Columns\TextColumn::make('next_follow_up_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
