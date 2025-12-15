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
                $user = Auth::user();
                $detail = $user?->userDetail;
                return [
                    'view' => Gate::allows('view', $detail),
                    'create' => Gate::allows('create', $detail),
                    'update' => Gate::allows('update', $detail),
                    'delete' => Gate::allows('delete', $detail),
                    'export' => Gate::allows('export', $detail),
                    'print' => Gate::allows('print', $detail),
                    'view_sidenav' => Gate::allows('view_sidenav', $detail),
                    'view_tab' => Gate::allows('view_tab', $detail),
                ];
            },
        ]);
    }
}
