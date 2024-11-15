<?php

namespace App\Policies;

use App\Models\PeriodicRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PeriodicRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, PeriodicRequest $periodicRequest)
    {
        //
    }


    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PeriodicRequest $periodicRequest)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PeriodicRequest $periodicRequest)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PeriodicRequest $periodicRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PeriodicRequest $periodicRequest)
    {
        //
    }
}
