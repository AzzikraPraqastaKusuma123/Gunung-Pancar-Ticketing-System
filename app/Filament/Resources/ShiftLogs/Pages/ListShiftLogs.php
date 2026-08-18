<?php

namespace App\Filament\Resources\ShiftLogs\Pages;

use App\Filament\Resources\ShiftLogs\ShiftLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShiftLogs extends ListRecords
{
    protected static string $resource = ShiftLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
