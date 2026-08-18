<?php

namespace App\Filament\Resources\Blacklists;

use App\Filament\Resources\Blacklists\Pages\CreateBlacklist;
use App\Filament\Resources\Blacklists\Pages\EditBlacklist;
use App\Filament\Resources\Blacklists\Pages\ListBlacklists;
use App\Filament\Resources\Blacklists\Schemas\BlacklistForm;
use App\Filament\Resources\Blacklists\Tables\BlacklistsTable;
use App\Models\Blacklist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BlacklistResource extends Resource
{
    protected static ?string $model = Blacklist::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Command Center';
    protected static ?int $navigationSort = 7;
    
    protected static ?string $modelLabel = 'Daftar Hitam (Blacklist)';
    protected static ?string $pluralModelLabel = 'Daftar Hitam';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNoSymbol;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'security']);
    }

    public static function form(Schema $schema): Schema
    {
        return BlacklistForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlacklistsTable::configure($table);
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
            'index' => ListBlacklists::route('/'),
            'create' => CreateBlacklist::route('/create'),
            'edit' => EditBlacklist::route('/{record}/edit'),
        ];
    }
}
