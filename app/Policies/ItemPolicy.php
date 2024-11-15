<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{   
    // عرض صفحة المواد
    public function viewany(User $user): bool
    {
        return $user->type === '3';// مدير المالية فقط
    }

    // إمكانية إضافة المواد
    public function create(User $user): bool
    {
        return $user->type === '3'; // مدير المالية فقط
    }

    // امكانية التعديل على المواد
    public function update(User $user): bool
    {
        return $user->type === '3'; // مدير المالية فقط
    }

    public function delete(User $user): bool
    {
        return $user->type === '3'; // مدير المالية فقط
    }
}
