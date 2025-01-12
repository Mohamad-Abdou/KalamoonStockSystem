<?php

namespace App\Policies;

use App\Models\User;

class AnnualRequestFlowPolicy
{
    public function __construct(User $user)
    {
        return true;
    }
}
