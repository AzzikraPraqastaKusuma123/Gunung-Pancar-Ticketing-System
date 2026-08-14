<?php

namespace App\Filament\Resources\TourPackages\Pages;

use App\Filament\Resources\TourPackages\TourPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTourPackage extends EditRecord
{
    protected static string $resource = TourPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
