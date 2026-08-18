<?php

namespace App\Filament\Resources\Blacklists\Pages;

use App\Filament\Resources\Blacklists\BlacklistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlacklists extends ListRecords
{
    protected static string $resource = BlacklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
