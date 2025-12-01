<?php

namespace App\Providers;


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

        // Share authorization state with all Inertia pages
        Inertia::share([
            'can' => function () {
                return [
                    'viewLibraries' => Gate::allows('viewAny', UserDetail::class),
                    'updateUserDetails' => Gate::allows('updateUserDetails', UserDetail::class),
                ];
            },
        ]);
    }
}
