<?php

namespace App\Filament\Clusters\CommandCenter\Resources\Devices\Pages;

use App\Filament\Clusters\CommandCenter\Resources\Devices\DeviceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDevice extends EditRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
