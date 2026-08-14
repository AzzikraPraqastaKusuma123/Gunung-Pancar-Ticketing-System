<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeadFollowUp;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadFollowUpPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeadFollowUp');
    }

    public function view(AuthUser $authUser, LeadFollowUp $leadFollowUp): bool
    {
        return $authUser->can('View:LeadFollowUp');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeadFollowUp');
    }

    public function update(AuthUser $authUser, LeadFollowUp $leadFollowUp): bool
    {
        return $authUser->can('Update:LeadFollowUp');
    }

    public function delete(AuthUser $authUser, LeadFollowUp $leadFollowUp): bool
    {
        return $authUser->can('Delete:LeadFollowUp');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LeadFollowUp');
    }

    public function restore(AuthUser $authUser, LeadFollowUp $leadFollowUp): bool
    {
        return $authUser->can('Restore:LeadFollowUp');
    }

    public function forceDelete(AuthUser $authUser, LeadFollowUp $leadFollowUp): bool
    {
        return $authUser->can('ForceDelete:LeadFollowUp');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeadFollowUp');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeadFollowUp');
    }

    public function replicate(AuthUser $authUser, LeadFollowUp $leadFollowUp): bool
    {
        return $authUser->can('Replicate:LeadFollowUp');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeadFollowUp');
    }

}