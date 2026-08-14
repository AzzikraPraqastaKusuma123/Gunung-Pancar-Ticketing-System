<?php

namespace App\Filament\Resources\Bookings\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    public function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Tickets\Schemas\TicketForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return \App\Filament\Resources\Tickets\Tables\TicketsTable::configure($table)
            ->headerActions([
                \Filament\Tables\Actions\CreateAction::make(),
            ]);
    }
}
