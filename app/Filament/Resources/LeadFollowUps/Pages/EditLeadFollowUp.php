<?php

namespace App\Filament\Resources\LeadFollowUps\Pages;

use App\Filament\Resources\LeadFollowUps\LeadFollowUpResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeadFollowUp extends EditRecord
{
    protected static string $resource = LeadFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
