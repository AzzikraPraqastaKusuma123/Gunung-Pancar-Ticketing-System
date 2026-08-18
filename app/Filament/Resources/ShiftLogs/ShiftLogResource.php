<?php

namespace App\Filament\Resources\ShiftLogs;

use App\Filament\Resources\ShiftLogs\Pages\CreateShiftLog;
use App\Filament\Resources\ShiftLogs\Pages\EditShiftLog;
use App\Filament\Resources\ShiftLogs\Pages\ListShiftLogs;
use App\Filament\Resources\ShiftLogs\Schemas\ShiftLogForm;
use App\Filament\Resources\ShiftLogs\Tables\ShiftLogsTable;
use App\Models\ShiftLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShiftLogResource extends Resource
{
    protected static ?string $model = ShiftLog::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Command Center';
    protected static ?int $navigationSort = 6;
    
    protected static ?string $modelLabel = 'Buku Jaga (Shift Log)';
    protected static ?string $pluralModelLabel = 'Buku Jaga Digital';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'shift_type';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'security']);
    }

    public static function form(Schema $schema): Schema
    {
        return ShiftLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShiftLogsTable::configure($table);
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
            'index' => ListShiftLogs::route('/'),
            'create' => CreateShiftLog::route('/create'),
            'edit' => EditShiftLog::route('/{record}/edit'),
        ];
    }
}
