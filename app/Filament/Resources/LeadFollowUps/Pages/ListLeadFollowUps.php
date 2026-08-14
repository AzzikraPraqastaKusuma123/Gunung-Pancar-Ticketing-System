<?php

namespace App\Filament\Resources\LeadFollowUps\Pages;

use App\Filament\Resources\LeadFollowUps\LeadFollowUpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeadFollowUps extends ListRecords
{
    protected static string $resource = LeadFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
