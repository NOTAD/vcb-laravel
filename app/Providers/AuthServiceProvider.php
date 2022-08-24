<?php

namespace App\Providers;

use App\Enums\Permissions;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define permissions via gate
        foreach (Permissions::toArray() as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->canDo($permission);
            });
        }
    }
}
