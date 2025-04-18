<?php

namespace App\Policies;

use App\Models\AppConfiguration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppConfigurationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->type === '0'; // مدير النظام
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user)
    {
        return $user->type === '0'; // مدير النظام
        
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->type === '0'; // مدير النظام
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppConfiguration $appConfiguration)
    {
        return $user->type === '0'; // مدير النظام
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AppConfiguration $appConfiguration)
    {
        return $user->type === '0'; // مدير النظام
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppConfiguration $appConfiguration)
    {
        return $user->type === '0'; // مدير النظام
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppConfiguration $appConfiguration)
    {
        return $user->type === '0'; // مدير النظام
    }
}
