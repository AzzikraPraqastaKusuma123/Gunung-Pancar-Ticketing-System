<?php

namespace App\Filament\Resources\LetterOfAgreements\Pages;

use App\Filament\Resources\LetterOfAgreements\LetterOfAgreementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLetterOfAgreements extends ListRecords
{
    protected static string $resource = LetterOfAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
