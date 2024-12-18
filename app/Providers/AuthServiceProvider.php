<?php

namespace App\Providers;

use App\Http\Controllers\AnnualRequestFlowController;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\AnnualRequest;
use App\Models\Item;          // Import your model(s)
use App\Models\ItemGroup;
use App\Policies\AnnualRequestFlowPolicy;
use App\Policies\AnnualRequestPolicy;
use App\Policies\ItemGroupPolicy;
use App\Policies\ItemPolicy;  // Import corresponding policy/policies


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Item::class => ItemPolicy::class,
        ItemGroup::class => ItemGroupPolicy::class, 
        AnnualRequest::class => AnnualRequestPolicy::class,
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
