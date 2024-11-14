<?php

use App\Http\Controllers\AdminRateController;
use App\Http\Controllers\AircraftController;
use App\Http\Controllers\CamoActivityController;
use App\Http\Controllers\CamoController;
use App\Http\Controllers\DashboardInfoController;
use App\Http\Controllers\EngineTypeController;
use App\Http\Controllers\Invokes\ActivityController;
use App\Http\Controllers\Invokes\AddActivityController;
use App\Http\Controllers\Invokes\BrandAircraftController;
use App\Http\Controllers\Invokes\CamController;
use App\Http\Controllers\Invokes\HandleActivityController;
use App\Http\Controllers\Invokes\MediaActivityController;
use App\Http\Controllers\Invokes\OwnerController;
use App\Http\Controllers\Invokes\PendingRateController;
use App\Http\Controllers\Invokes\PermissionController;
use App\Http\Controllers\Invokes\SetOwnerAircraftController;
use App\Http\Controllers\LaborRateController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ModelAircraftController;
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
    $route->get('brand-aircrafts/select', BrandAircraftController::class)->name('brand-aircrafts.select');
    $route->get('engine-types/select', \App\Http\Controllers\Invokes\EngineTypeController::class)->name('engine-types.select');
    $route->get('model-aircrafts/select', \App\Http\Controllers\Invokes\ModelAircraftController::class)->name('model-aircrafts.select');
    $route->get('aircrafts/select', \App\Http\Controllers\Invokes\AircraftController::class)->name('aircrafts.select');
    $route->get('labor-rates/select', \App\Http\Controllers\Invokes\LaborRateController::class)->name('labor-rates.select');
    $route->get('camos/{id?}/select', \App\Http\Controllers\Invokes\CamoController::class)->name('camos.select');
    $route->get('camos/activities', ActivityController::class)->name('camos.activities');
    $route->match(['put', 'patch'], 'camo_activities/{id}/handle', HandleActivityController::class)
        ->name('camo_activities.handle');
    $route->post('camo_activities/add', AddActivityController::class)
        ->name('camo_activities.add');
    $route->post('set-owner-aircraft', SetOwnerAircraftController::class)->name('set-owner-aircraft');
    $route->get('has-pending-rates', PendingRateController::class)->name('has-pending-rates');
    // Roles
    $route->resource('roles', RoleController::class);
    // Permissions
    $route->resource('permissions', \App\Http\Controllers\PermissionController::class);
    // Users
    $route->resource('users', UserController::class);
    // Profile
    $route->get('profile', static fn (\Illuminate\Http\Request $request): \Inertia\Response => (new ProfileController)->edit($request))->name('profile.edit');
    $route->patch('profile', static fn (ProfileUpdateRequest $request): RedirectResponse => (new ProfileController)->update($request))->name('profile.update');
    $route->delete('profile', static fn (\Illuminate\Http\Request $request): RedirectResponse => (new ProfileController)->destroy($request))->name('profile.destroy');
    // Admin Rate
    $route->resource('admin-rates', AdminRateController::class);
    // Labor Rates
    $route->resource('labor-rates', LaborRateController::class);
    // Engine Types
    $route->resource('engine-types', EngineTypeController::class);
    // Model Aircraft
    $route->resource('model-aircrafts', ModelAircraftController::class);
    // Brand Aircraft
    $route->resource('brand-aircrafts', \App\Http\Controllers\BrandAircraftController::class);
    // Aircrafts
    $route->resource('aircrafts', AircraftController::class);
    // Camos
    $route->get('camos/dashboard', [DashboardInfoController::class, 'dashboardCamo'])->name('camos.dashboard');

    $route->get('camos/{camo}/get-images', [App\Http\Controllers\MediaController::class, 'getMedia'])->name('camos.images');
    $route->resource('camos', CamoController::class);
    // Media Camo Activities
    $route->get('camo/{camo}/has-images-in-activities', [MediaController::class, 'hasImagesInActivities'])->name('camo.has-images-in-activities');
    $route->post('camo_activities/add-images', MediaActivityController::class)
        ->name('camo_activities.add_images');
    // Camo Activities
    $route->resource('camo_activities', CamoActivityController::class);
});

require __DIR__.'/auth.php';
