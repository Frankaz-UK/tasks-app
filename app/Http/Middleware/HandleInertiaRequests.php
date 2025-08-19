<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Spatie\Permission\Models\Role;
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
                    'role' => $request->user()->roles() ? $request->user()->roles()->pluck('name') : [],
                    'roles' => Role::all()->pluck('name')->toArray(),
                    'can' => [
                        'task-create' => Auth::user()->can('task-create', User::class),
                        'task-delete' => Auth::user()->can('task-delete', User::class),
                        'task-update' => Auth::user()->can('task-update', User::class),
                        'task-complete' => Auth::user()->can('task-complete', User::class),
                        'task-list' => Auth::user()->can('task-list', User::class),
                        'task-show' => Auth::user()->can('task-show', User::class),
                        'user-create' => Auth::user()->can('user-create', User::class),
                        'user-delete' => Auth::user()->can('user-delete', User::class),
                        'user-update' => Auth::user()->can('user-update', User::class),
                        'user-list' => Auth::user()->can('user-list', User::class),
                        'user-show' => Auth::user()->can('user-show', User::class),
                    ],
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
/*
 *



            'task-create',
            'task-delete',
            'task-update',
            'task-complete',
            'task-list',
            'task-show',
            'user-create',
            'user-delete',
            'user-update',
            'user-list',
            'user-show',
 */
