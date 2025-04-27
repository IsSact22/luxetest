<?php

use App\Http\Controllers\Api\v1\AdminRateController;
use App\Http\Controllers\Api\v1\AircraftController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BrandAircraftController;
use App\Http\Controllers\Api\v1\CamoActivityController;
use App\Http\Controllers\Api\v1\CamoController;
use App\Http\Controllers\Api\v1\DashboardInfoController;
use App\Http\Controllers\Api\v1\EngineTypeController;
use App\Http\Controllers\Api\v1\LaborRateController;
use App\Http\Controllers\Api\v1\MediaController;
use App\Http\Controllers\Api\v1\ModelAircraftController;
use App\Http\Controllers\Api\v1\PermissionController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Http\Controllers\Api\v1\RoleController;
use App\Http\Controllers\Api\v1\RoleFilterController;
use App\Http\Controllers\Api\v1\RolePermissionController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Auth (no protegidas)
Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('api.v1.login');
    Route::post('register', [AuthController::class, 'register'])->name('api.v1.register');
    
});

// Rutas protegidas
Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('api.v1.logout');
    // Users
    Route::apiResource('users', UserController::class)
        ->except(['update'])
        ->names([
            'index' => 'api.users.index',
            'show' => 'api.users.show',
            'store' => 'api.users.store',
            'destroy' => 'api.users.destroy'
        ]);
    Route::match(['put', 'patch'], 'users/{user}', [UserController::class, 'update'])
        ->name('api.users.update'); // Más RESTful
    // Roles & Permissions (modularizado)
    Route::prefix('permissions')->group(function () {
        Route::apiResource('roles', RolePermissionController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names([
                'index' => 'api.roles.index',
                'store' => 'api.roles.store',
                'update' => 'api.roles.update',
                'destroy' => 'api.roles.destroy'
            ]);
        
        Route::post('roles/{role}/{guard}/assign', [RolePermissionController::class, 'assignPermissions']);
        Route::post('roles/{role}/revoke', [RolePermissionController::class, 'revokePermissions']);
        
        Route::apiResource('permissions', PermissionController::class)
            ->except(['show'])
            ->names([
                'index' => 'api.permissions.index',
                'store' => 'api.permissions.store',
                'update' => 'api.permissions.update',
                'destroy' => 'api.permissions.destroy'
            ]);
    });
    
    // Ruta especial para filtro
    Route::get('users/{role}/by-role', [RoleFilterController::class, 'filterByRole']);
    
    // Rutas de Aircraft
    Route::apiResource('aircrafts', AircraftController::class)
        ->names([
            'index' => 'api.aircrafts.index',
            'show' => 'api.aircrafts.show',
            'store' => 'api.aircrafts.store',
            'update' => 'api.aircrafts.update',
            'destroy' => 'api.aircrafts.destroy'
        ]);

    // Rutas de AdminRate
    Route::apiResource('admin-rates', AdminRateController::class)
        ->names([
            'index' => 'api.admin-rates.index',
            'show' => 'api.admin-rates.show',
            'store' => 'api.admin-rates.store',
            'update' => 'api.admin-rates.update',
            'destroy' => 'api.admin-rates.destroy'
        ]);

    // Rutas de BrandAircraft
    Route::apiResource('brand-aircrafts', BrandAircraftController::class)
        ->names([
            'index' => 'api.brand-aircrafts.index',
            'show' => 'api.brand-aircrafts.show',
            'store' => 'api.brand-aircrafts.store',
            'update' => 'api.brand-aircrafts.update',
            'destroy' => 'api.brand-aircrafts.destroy'
        ]);

    // Rutas de CamoActivity
    Route::apiResource('camo-activities', CamoActivityController::class)
        ->names([
            'index' => 'api.camo-activities.index',
            'show' => 'api.camo-activities.show',
            'store' => 'api.camo-activities.store',
            'update' => 'api.camo-activities.update',
            'destroy' => 'api.camo-activities.destroy'
        ]);

    // Rutas de Camo
    Route::apiResource('camos', CamoController::class)
        ->names([
            'index' => 'api.camos.index',
            'show' => 'api.camos.show',
            'store' => 'api.camos.store',
            'update' => 'api.camos.update',
            'destroy' => 'api.camos.destroy'
        ]);

    // Rutas de EngineType
    Route::apiResource('engine-types', EngineTypeController::class)
        ->names([
            'index' => 'api.engine-types.index',
            'show' => 'api.engine-types.show',
            'store' => 'api.engine-types.store',
            'update' => 'api.engine-types.update',
            'destroy' => 'api.engine-types.destroy'
        ]);

    // Rutas de LaborRate
    Route::apiResource('labor-rates', LaborRateController::class)
        ->names([
            'index' => 'api.labor-rates.index',
            'show' => 'api.labor-rates.show',
            'store' => 'api.labor-rates.store',
            'update' => 'api.labor-rates.update',
            'destroy' => 'api.labor-rates.destroy'
        ]);

    // Rutas de ModelAircraft
    Route::apiResource('model-aircrafts', ModelAircraftController::class)
        ->names([
            'index' => 'api.model-aircrafts.index',
            'show' => 'api.model-aircrafts.show',
            'store' => 'api.model-aircrafts.store',
            'update' => 'api.model-aircrafts.update',
            'destroy' => 'api.model-aircrafts.destroy'
        ]);

    // Rutas de Profile
    Route::get('profile', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('api.profile.update');

    // Rutas de DashboardInfo
    Route::get('dashboard-info', [DashboardInfoController::class, 'index'])->name('api.dashboard-info.index');

    // Rutas de Media
    Route::post('media/upload', [MediaController::class, 'upload'])->name('api.media.upload');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('api.media.destroy');
});