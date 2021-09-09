<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerRoles();
    }

    private function registerRoles()
    {
        foreach (Role::all() as $role) {
            Gate::define($role->role, function (User $user) use ($role) {
                return $user->roles()->filterByRole($role->role)->count();
                // return $user->roles()->filterByRole($role->role)->count() ? Response::allow() : Response::deny('Unauthorized');
            });
        }
    }
}
