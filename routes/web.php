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

Route::middleware('auth')->group(function ($route) {

    // Invokes Controllers
    $route->get('roles/select', \App\Http\Controllers\Invokes\RoleController::class)->name('roles.select');
    $route->get('permissions/select', \App\Http\Controllers\Invokes\PermissionController::class)->name('permissions.select');
    $route->get('owners/select', \App\Http\Controllers\Invokes\OwnerController::class)->name('owners.select');
    $route->get('cams/select', \App\Http\Controllers\Invokes\CamController::class)->name('cams.select');
    $route->get('camos/activities', \App\Http\Controllers\Invokes\ActivityController::class)
        ->name('camos.activities');
    $route->match(['put', 'patch'], 'camo_activities/{id}/handle', \App\Http\Controllers\Invokes\HandleActivityController::class)
        ->name('camo_activities.handle');
    $route->post('camo_activities/add', \App\Http\Controllers\Invokes\AddActivityController::class)
        ->name('camo_activities.add');

    // Roles
    $route->resource('roles', \App\Http\Controllers\RoleController::class);

    // Users
    $route->resource('users', \App\Http\Controllers\UserController::class);

    // Profile
    $route->get('profile', fn (\Illuminate\Http\Request $request): \Inertia\Response => (new \App\Http\Controllers\ProfileController)->edit($request))->name('profile.edit');
    $route->patch('profile', fn (\App\Http\Requests\ProfileUpdateRequest $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\ProfileController)->update($request))->name('profile.update');
    $route->delete('profile', fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\ProfileController)->destroy($request))->name('profile.destroy');

    // Camos
    $route->get('camos/dashboard', [\App\Http\Controllers\DashboardInfoController::class, 'dashboardCamo'])->name('camos.dashboard');
    $route->resource('camos', \App\Http\Controllers\CamoController::class);

    // Camo Activities
    $route->resource('camo_activities', \App\Http\Controllers\CamoActivityController::class);

    // Media
    $route->get('/add-images/{modelName}', [\App\Http\Controllers\CamoActivityController::class, 'addImage'])->name('add-image');
    $route->post('/add-images-to-model/{modelName}', [\App\Http\Controllers\MediaController::class, 'addImagesToModel'])
        ->name('add-image-to-model');
});

require __DIR__.'/auth.php';
