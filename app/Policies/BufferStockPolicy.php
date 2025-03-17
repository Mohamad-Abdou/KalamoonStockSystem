<?php

namespace App\Policies;

use App\Models\User;

class BufferStockPolicy
{
    public function viewany(User $user)
    {
        return $user->type === "2";
    }
}
