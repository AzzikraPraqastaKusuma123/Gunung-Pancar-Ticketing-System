<?php

namespace App\Filament\Resources\ShiftLogs\Pages;

use App\Filament\Resources\ShiftLogs\ShiftLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShiftLog extends EditRecord
{
    protected static string $resource = ShiftLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
