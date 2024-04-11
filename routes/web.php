<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
]));

Route::get('dashboard', fn () => Inertia::render('Dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function ($router) {

    // Invokes Controllers
    $router->get('roles/select', \App\Http\Controllers\Invokes\RoleController::class)->name('roles.select');
    $router->get('permissions/select', \App\Http\Controllers\Invokes\PermissionController::class)->name('permissions.select');
    $router->get('owners/select', \App\Http\Controllers\Invokes\OwnerController::class)->name('owners.select');
    $router->get('cams/select', \App\Http\Controllers\Invokes\CamController::class)->name('cams.select');
    $router->get('camos/activities', \App\Http\Controllers\Invokes\ActivityController::class)
        ->name('camos.activities');
    $router->match(['put', 'patch'], 'camo_activities/{id}/handle', \App\Http\Controllers\Invokes\HandleActivityController::class)
        ->name('camo_activities.handle');
    $router->post('camo_activities/add', \App\Http\Controllers\Invokes\AddActivityController::class)
        ->name('camo_activities.add');

    // Roles
    $router->resource('roles', \App\Http\Controllers\RoleController::class);

    // Users
    $router->resource('users', \App\Http\Controllers\UserController::class);

    // Profile
    $router->get('profile', fn (\Illuminate\Http\Request $request): \Inertia\Response => (new \App\Http\Controllers\ProfileController)->edit($request))->name('profile.edit');
    $router->patch('profile', fn (\App\Http\Requests\ProfileUpdateRequest $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\ProfileController)->update($request))->name('profile.update');
    $router->delete('profile', fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\ProfileController)->destroy($request))->name('profile.destroy');

    // Camos
    $router->get('camos/dashboard', [\App\Http\Controllers\DashboardInfoController::class, 'dashboardCamo'])->name('camos.dashboard');
    $router->resource('camos', \App\Http\Controllers\CamoController::class);

    // Camo Activities
    $router->resource('camo_activities', \App\Http\Controllers\CamoActivityController::class);
});

require __DIR__.'/auth.php';
