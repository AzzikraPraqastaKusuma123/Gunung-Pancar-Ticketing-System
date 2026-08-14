<?php

namespace App\Filament\Resources\LetterOfAgreements;

use App\Filament\Resources\LetterOfAgreements\Pages\CreateLetterOfAgreement;
use App\Filament\Resources\LetterOfAgreements\Pages\EditLetterOfAgreement;
use App\Filament\Resources\LetterOfAgreements\Pages\ListLetterOfAgreements;
use App\Filament\Resources\LetterOfAgreements\Schemas\LetterOfAgreementForm;
use App\Filament\Resources\LetterOfAgreements\Tables\LetterOfAgreementsTable;
use App\Models\LetterOfAgreement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LetterOfAgreementResource extends Resource
{
    protected static ?string $model = LetterOfAgreement::class;

    protected static ?string $modelLabel = 'Surat Perjanjian (LOA)';
    protected static ?string $pluralModelLabel = 'Surat Perjanjian (LOA)';
    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Prospek';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LetterOfAgreementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LetterOfAgreementsTable::configure($table);
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
            'index' => ListLetterOfAgreements::route('/'),
            'create' => CreateLetterOfAgreement::route('/create'),
            'edit' => EditLetterOfAgreement::route('/{record}/edit'),
        ];
    }
}
