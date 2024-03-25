<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
]));

Route::get('dashboard', fn () => Inertia::render('Dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('roles/select', \App\Http\Controllers\Invokes\RoleController::class)->name('roles.select');
    Route::get('owners/select', \App\Http\Controllers\Invokes\OwnerController::class)->name('owners.select');
    Route::get('cams/select', \App\Http\Controllers\Invokes\CamController::class)->name('cams.select');
    Route::get('camos/activities', \App\Http\Controllers\Invokes\ActivityController::class)
        ->name('camos.activities');
    Route::get('profile', fn (\Illuminate\Http\Request $request): \Inertia\Response => (new \App\Http\Controllers\ProfileController)->edit($request))->name('profile.edit');
    Route::patch('profile', fn (\App\Http\Requests\ProfileUpdateRequest $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\ProfileController)->update($request))->name('profile.update');
    Route::delete('profile', fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\ProfileController)->destroy($request))->name('profile.destroy');

    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::get('camos/dashboard', [\App\Http\Controllers\DashboardInfoController::class, 'dashboardCamo'])->name('camos.dashboard');
    Route::resource('camos', \App\Http\Controllers\CamoController::class)->except(['store', 'update']);
    Route::middleware(HandlePrecognitiveRequests::class)
        ->post('camos', [\App\Http\Controllers\CamoController::class, 'store'])
        ->name('camos.store');
    Route::middleware(HandlePrecognitiveRequests::class)
        ->match(['put', 'patch'], 'camos', [\App\Http\Controllers\CamoController::class, 'store'])
        ->name('camos.update');
    Route::resource('camo_activities', \App\Http\Controllers\CamoActivityController::class);
});

require __DIR__.'/auth.php';
