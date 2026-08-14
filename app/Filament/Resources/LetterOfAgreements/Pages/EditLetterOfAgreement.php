<?php

namespace App\Filament\Resources\LetterOfAgreements\Pages;

use App\Filament\Resources\LetterOfAgreements\LetterOfAgreementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLetterOfAgreement extends EditRecord
{
    protected static string $resource = LetterOfAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
