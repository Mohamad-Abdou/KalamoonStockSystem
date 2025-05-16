<?php

namespace App\Policies;

use App\Models\TemporaryRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TemporaryRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->getIsPartOfTheAnnualFlowAttribute();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TemporaryRequest $temporaryRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->type == 0;
    }
}
