<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Override;
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
    #[Override]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'avatarUrl' => Auth::user() ? Auth::user()->getFirstMediaUrl('avatars', 'thumb') : null,
                'userRoles' => $request->user() ? $request->user()->roles->pluck('name') : [],
                'userPermissions' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
            ],
            'ziggy' => static fn () => array_merge((new Ziggy)->toArray(), [
                'location' => $request->url(),
            ]),
            'flash' => [
                'message' => static fn () => $request->session()->get('message'),
                'type' => static fn () => $request->session()->get('type', 'success'),
            ],
        ];
    }
}
