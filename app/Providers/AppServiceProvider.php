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
                    'view' => Gate::allows('view', UserDetail::class),
                    'create' => Gate::allows('create', UserDetail::class),
                    'update' => Gate::allows('update', UserDetail::class),
                    'delete' => Gate::allows('delete', UserDetail::class),
                    'export' => Gate::allows('export', UserDetail::class),
                    'print' => Gate::allows('print', UserDetail::class),
                    'view_sidenav' => Gate::allows('view_sidenav', UserDetail::class),
                    'view_tab' => Gate::allows('view_tab', UserDetail::class),
                ];
            },
        ]);
    }
}
