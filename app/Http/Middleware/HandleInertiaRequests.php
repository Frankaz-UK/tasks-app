<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        if (Auth::check()) {
            return array_merge(parent::share($request), [
                'appName' => config('app.name'),
                'auth' => [
                    'user' => $request->user(),
                    'full_name' => $request->user()->full_name ?? null,
                    'roles' => $request->user()->roles() ? $request->user()->roles()->pluck('name') : [],
                    'can' => $request->user()->getAllPermissions() ? $request->user()->getAllPermissions()->pluck('name', 'name') : []
                ],
                'ziggy' => function () use ($request) {
                    return array_merge((new Ziggy)->toArray(), [
                        'location' => $request->url(),
                    ]);
                },
            ]);
        } else {
            return array_merge(parent::share($request), [
                'appName' => config('app.name'),
            ]);
        }
    }
}
