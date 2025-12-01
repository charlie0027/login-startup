<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'success' => fn() => $request->session()->get('success'),
                'error'   => fn() => $request->session()->get('error'),
            ],
            'auth' => [
                'id' => $request->user()?->id,
                'username' => $request->user()?->username,
                'last_name' => $request->user()?->last_name,
                'first_name' => $request->user()?->first_name,
                'middle_name' => $request->user()?->middle_name,
                'name_extension' => $request->user()?->name_extension,
            ]
        ]);
    }
}
