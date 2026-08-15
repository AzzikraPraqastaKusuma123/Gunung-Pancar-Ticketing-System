<?php

namespace App\Filament\Clusters\CommandCenter\Resources\Devices;

use App\Filament\Clusters\CommandCenter\CommandCenterCluster;
use App\Filament\Clusters\CommandCenter\Resources\Devices\Pages\CreateDevice;
use App\Filament\Clusters\CommandCenter\Resources\Devices\Pages\EditDevice;
use App\Filament\Clusters\CommandCenter\Resources\Devices\Pages\ListDevices;
use App\Filament\Clusters\CommandCenter\Resources\Devices\Schemas\DeviceForm;
use App\Filament\Clusters\CommandCenter\Resources\Devices\Tables\DevicesTable;
use App\Models\Device;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $cluster = CommandCenterCluster::class;
    
    protected static ?string $navigationLabel = 'Manajemen Perangkat';
    protected static ?string $modelLabel = 'Perangkat Jaringan';
    protected static ?string $pluralModelLabel = 'Perangkat Jaringan';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return DeviceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevicesTable::configure($table);
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
            'index' => ListDevices::route('/'),
            'create' => CreateDevice::route('/create'),
            'edit' => EditDevice::route('/{record}/edit'),
        ];
    }
}
