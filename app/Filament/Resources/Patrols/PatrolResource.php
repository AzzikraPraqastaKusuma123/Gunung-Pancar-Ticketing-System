<?php

namespace App\Filament\Resources\Patrols;

use App\Filament\Resources\Patrols\Pages\CreatePatrol;
use App\Filament\Resources\Patrols\Pages\EditPatrol;
use App\Filament\Resources\Patrols\Pages\ListPatrols;
use App\Filament\Resources\Patrols\Schemas\PatrolForm;
use App\Filament\Resources\Patrols\Tables\PatrolsTable;
use App\Models\Patrol;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatrolResource extends Resource
{
    protected static ?string $model = Patrol::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Command Center';
    protected static ?int $navigationSort = 8;
    
    protected static ?string $modelLabel = 'Patroli Keamanan';
    protected static ?string $pluralModelLabel = 'Data Patroli';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $recordTitleAttribute = 'location_name';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'security']);
    }

    public static function form(Schema $schema): Schema
    {
        return PatrolForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatrolsTable::configure($table);
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
            'index' => ListPatrols::route('/'),
            'create' => CreatePatrol::route('/create'),
            'edit' => EditPatrol::route('/{record}/edit'),
        ];
    }
}
