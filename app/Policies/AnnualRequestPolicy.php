<?php

namespace App\Policies;

use App\Http\Middleware\CheckRequestPeriod;
use App\Models\AnnualRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnnualRequestPolicy
{

    public function viewAny(User $user)
    {
        return $user->type > 1;
    }

    public function view(User $user, AnnualRequest $annualRequest)
    {
        return $annualRequest->user_id === $user->id || $user->getIsPartOfTheAnnualFlowAttribute();
    }

    public function create(User $user)
    {
        return $user->type > 1 && !$user->getActiveRequest();
    }

    public function resetYear(User $user)
    {
        return $user->id === 3;
    }

    public function update(User $user, AnnualRequest $annualRequest)
    {
        return $annualRequest->user_id === $user->id;
    }
    
    public function delete(User $user, AnnualRequest $annualRequest)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AnnualRequest $annualRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AnnualRequest $annualRequest)
    {
        //
    }
}
