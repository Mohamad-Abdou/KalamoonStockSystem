<?php

namespace App\Policies;

use App\Models\User;

class ItemPolicy
{
    // عرض صفحة المواد
    public function viewany(User $user): bool
    {
        return $user->type === '1';
    }

    // إمكانية إضافة المواد
    public function create(User $user): bool
    {
        return $user->type === '1';
    }

    // امكانية التعديل على المواد
    public function update(User $user): bool
    {
        return $user->type === '1';
    }

    public function delete(User $user): bool
    {
        return $user->type === '1';
    }
}
