<?php

namespace App\Policies;

use App\Models\User;

class AnnualRequestFlowPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(User $user)
    {
        return true;
    }
}
