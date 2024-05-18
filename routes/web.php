<?php

use App\Http\Controllers\CamoActivityController;
use App\Http\Controllers\CamoController;
use App\Http\Controllers\CamoRateController;
use App\Http\Controllers\DashboardInfoController;
use App\Http\Controllers\EngineTypeController;
use App\Http\Controllers\Invokes\ActivityController;
use App\Http\Controllers\Invokes\AddActivityController;
use App\Http\Controllers\Invokes\CamController;
use App\Http\Controllers\Invokes\HandleActivityController;
use App\Http\Controllers\Invokes\MediaActivityController;
use App\Http\Controllers\Invokes\OwnerController;
use App\Http\Controllers\Invokes\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', static fn () => Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
]));

Route::get('dashboard', static fn () => Inertia::render('Dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(static function ($route) {
    // Invokes Controllers
    $route->get('roles/select', \App\Http\Controllers\Invokes\RoleController::class)->name('roles.select');
    $route->get('permissions/select', PermissionController::class)->name('permissions.select');
    $route->get('owners/select', OwnerController::class)->name('owners.select');
    $route->get('cams/select', CamController::class)->name('cams.select');
    $route->get('camo-rates/select', \App\Http\Controllers\Invokes\EngineTypeController::class)->name('camo-rates.select');
    $route->get('camos/activities', ActivityController::class)
        ->name('camos.activities');
    $route->match(['put', 'patch'], 'camo_activities/{id}/handle', HandleActivityController::class)
        ->name('camo_activities.handle');
    $route->post('camo_activities/add', AddActivityController::class)
        ->name('camo_activities.add');
    // Roles
    $route->resource('roles', RoleController::class);
    // Users
    $route->resource('users', UserController::class);
    // Profile
    $route->get('profile', static fn (\Illuminate\Http\Request $request): \Inertia\Response => (new ProfileController)->edit($request))->name('profile.edit');
    $route->patch('profile', static fn (ProfileUpdateRequest $request): RedirectResponse => (new ProfileController)->update($request))->name('profile.update');
    $route->delete('profile', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new ProfileController)->destroy($request))->name('profile.destroy');
    // Camo Rates
    $route->resource('camo-rates', CamoRateController::class);
    // Engine Types
    $route->resource('engine-types', EngineTypeController::class);
    // Camos
    $route->get('camos/dashboard', [DashboardInfoController::class, 'dashboardCamo'])->name('camos.dashboard');
    $route->resource('camos', CamoController::class);
    // Media Camo Activities
    $route->post('camo_activities/add-images', MediaActivityController::class)
        ->name('camo_activities.add_images');
    // Camo Activities
    $route->resource('camo_activities', CamoActivityController::class);
});

require __DIR__.'/auth.php';
