<?php

namespace App\Policies;

use App\Models\User;

class ItemGroupPolicy
{
    // إمكانية عرض صفحة إدارة المجموعات
    public function viewAny(User $user): bool
    {
        return $user->type === '3'; // مدير المالية
    }

    public function create(User $user): bool
    {
        return $user->type === '3'; // مدير المالية
    }

    public function update(User $user): bool
    {
        return $user->type === '3'; // مدير المالية
    }
    
    public function delete(User $user): bool
    {
        return $user->type === '3'; // مدير المالية
    }
    /**
     * Determine whether the user can view the model.
     */
    /*public function view(User $user, ItemGroup $itemsGroup): bool
    {
        //
    }





    public function restore(User $user, ItemGroup $itemsGroup): bool
    {
        //
    }

    public function forceDelete(User $user, ItemGroup $itemsGroup): bool
    {
        //
    }*/
}
