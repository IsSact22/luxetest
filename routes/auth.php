<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precognitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(static function () {
    Route::get('register', static fn (): \Inertia\Response => (new RegisteredUserController)->create())
        ->name('register');
    Route::post('register', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new RegisteredUserController)
        ->store($request))->middleware(Precognitive::class);
    Route::get('login', static fn (): \Inertia\Response => (new AuthenticatedSessionController)->create())
        ->name('login');
    Route::post('login', static fn (\App\Http\Requests\Auth\LoginRequest $request): RedirectResponse => (new AuthenticatedSessionController)->store($request));
    Route::get('forgot-password', static fn (): \Inertia\Response => (new PasswordResetLinkController)->create())
        ->name('password.request');
    Route::post('forgot-password', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new PasswordResetLinkController)->store($request))
        ->name('password.email');
    Route::get('reset-password/{token}', static fn (\Illuminate\Http\Request $request): \Inertia\Response => (new NewPasswordController)->create($request))
        ->name('password.reset');
    Route::post('reset-password', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new NewPasswordController)->store($request))
        ->name('password.store');
});

Route::middleware('auth')->group(static function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new \App\Http\Controllers\Auth\EmailVerificationNotificationController)->store($request))
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', static fn (): \Inertia\Response => (new \App\Http\Controllers\Auth\ConfirmablePasswordController)->show())
        ->name('password.confirm');
    Route::post('confirm-password', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new \App\Http\Controllers\Auth\ConfirmablePasswordController)->store($request));
    Route::put('password', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new \App\Http\Controllers\Auth\PasswordController)->update($request))->name('password.update');
    Route::post('logout', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new AuthenticatedSessionController)->destroy($request))
        ->name('logout');
});
