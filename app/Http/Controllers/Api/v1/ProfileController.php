<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v1\PermissionController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Response;


class ProfileController extends Controller
{
    public function __construct(PermissionController $permissionController)
    {

    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): ApiSuccessResponse
    {

        return new ApiSuccessResponse(
            'Profile edit page',
            [
                'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
                'status' => session('status'),
            ]
        );
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
