<?php

namespace App\Filament\Resources\Patrols\Pages;

use App\Filament\Resources\Patrols\PatrolResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatrol extends EditRecord
{
    protected static string $resource = PatrolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
