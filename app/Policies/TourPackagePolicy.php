<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TourPackage;
use Illuminate\Auth\Access\HandlesAuthorization;

class TourPackagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TourPackage');
    }

    public function view(AuthUser $authUser, TourPackage $tourPackage): bool
    {
        return $authUser->can('View:TourPackage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TourPackage');
    }

    public function update(AuthUser $authUser, TourPackage $tourPackage): bool
    {
        return $authUser->can('Update:TourPackage');
    }

    public function delete(AuthUser $authUser, TourPackage $tourPackage): bool
    {
        return $authUser->can('Delete:TourPackage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TourPackage');
    }

    public function restore(AuthUser $authUser, TourPackage $tourPackage): bool
    {
        return $authUser->can('Restore:TourPackage');
    }

    public function forceDelete(AuthUser $authUser, TourPackage $tourPackage): bool
    {
        return $authUser->can('ForceDelete:TourPackage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TourPackage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TourPackage');
    }

    public function replicate(AuthUser $authUser, TourPackage $tourPackage): bool
    {
        return $authUser->can('Replicate:TourPackage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TourPackage');
    }

}