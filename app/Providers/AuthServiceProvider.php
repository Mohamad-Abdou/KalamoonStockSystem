<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Item;          // Import your model(s)
use App\Models\Items_group;
use App\Policies\ItemPolicy;  // Import corresponding policy/policies
use App\Policies\ItemsGroupPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Item::class => ItemPolicy::class,
        Items_group::class => ItemsGroupPolicy::class, 
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // You can define any additional Gates here, if needed
        // Gate::define('some-ability', function (User $user) {
        //     return $user->role === 'admin';
        // });
    }
}
