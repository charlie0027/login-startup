<?php

namespace App\Providers;

use App\Models\Libraries\Permission;
use App\Models\UserDetail;
use App\Policies\UserRolePolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(UserDetail::class, UserRolePolicy::class);

        Permission::pluck('name')->each(function ($name) {
            Gate::define($name, function ($user) use ($name) {
                return $user->userDetail?->hasPermission($name);
            });
        });

        // Share authorization state with all Inertia pages
        Inertia::share([
            'can' => fn() => collect(Permission::pluck('name'))->mapWithKeys(fn($name) => [$name => Gate::allows($name)])
        ]);
    }
}
