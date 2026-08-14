<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LetterOfAgreement;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterOfAgreementPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LetterOfAgreement');
    }

    public function view(AuthUser $authUser, LetterOfAgreement $letterOfAgreement): bool
    {
        return $authUser->can('View:LetterOfAgreement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LetterOfAgreement');
    }

    public function update(AuthUser $authUser, LetterOfAgreement $letterOfAgreement): bool
    {
        return $authUser->can('Update:LetterOfAgreement');
    }

    public function delete(AuthUser $authUser, LetterOfAgreement $letterOfAgreement): bool
    {
        return $authUser->can('Delete:LetterOfAgreement');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LetterOfAgreement');
    }

    public function restore(AuthUser $authUser, LetterOfAgreement $letterOfAgreement): bool
    {
        return $authUser->can('Restore:LetterOfAgreement');
    }

    public function forceDelete(AuthUser $authUser, LetterOfAgreement $letterOfAgreement): bool
    {
        return $authUser->can('ForceDelete:LetterOfAgreement');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LetterOfAgreement');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LetterOfAgreement');
    }

    public function replicate(AuthUser $authUser, LetterOfAgreement $letterOfAgreement): bool
    {
        return $authUser->can('Replicate:LetterOfAgreement');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LetterOfAgreement');
    }

}