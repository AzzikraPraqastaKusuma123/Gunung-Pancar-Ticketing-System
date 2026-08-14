<?php

namespace App\Filament\Resources\TourPackages\Pages;

use App\Filament\Resources\TourPackages\TourPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTourPackages extends ListRecords
{
    protected static string $resource = TourPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
