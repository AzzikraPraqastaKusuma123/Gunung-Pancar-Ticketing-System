<?php

namespace App\Filament\Clusters\CommandCenter\Resources\Devices\Pages;

use App\Filament\Clusters\CommandCenter\Resources\Devices\DeviceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDevice extends CreateRecord
{
    protected static string $resource = DeviceResource::class;
}
