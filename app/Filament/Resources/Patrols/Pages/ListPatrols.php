<?php

namespace App\Filament\Resources\Patrols\Pages;

use App\Filament\Resources\Patrols\PatrolResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatrols extends ListRecords
{
    protected static string $resource = PatrolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
