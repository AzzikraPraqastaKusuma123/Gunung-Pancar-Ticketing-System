<?php

namespace App\Filament\Resources\LeadFollowUps;

use App\Filament\Resources\LeadFollowUps\Pages\CreateLeadFollowUp;
use App\Filament\Resources\LeadFollowUps\Pages\EditLeadFollowUp;
use App\Filament\Resources\LeadFollowUps\Pages\ListLeadFollowUps;
use App\Filament\Resources\LeadFollowUps\Schemas\LeadFollowUpForm;
use App\Filament\Resources\LeadFollowUps\Tables\LeadFollowUpsTable;
use App\Models\LeadFollowUp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeadFollowUpResource extends Resource
{
    protected static ?string $model = LeadFollowUp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LeadFollowUpForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadFollowUpsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeadFollowUps::route('/'),
            'create' => CreateLeadFollowUp::route('/create'),
            'edit' => EditLeadFollowUp::route('/{record}/edit'),
        ];
    }
}
