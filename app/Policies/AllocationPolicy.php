<?php

namespace App\Policies;

use App\Models\Allocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AllocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Allocation $allocation)
    {
        //
    }



    public function waitingRecommendation(User $user): bool
    {
        return $user->hasPermissionTo('Allocation Recommendation') ? true : false;
    }

    public function waitingApproval(User $user): bool
    {
        return $user->hasPermissionTo('Allocation Approval') ? true : false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create Allocation') ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Allocation $allocation)
    {
        return $user->hasPermissionTo('update allocation') ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Allocation $allocation)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Allocation $allocation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Allocation $allocation)
    {
        //
    }
}
