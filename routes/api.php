<?php

use App\Http\Controllers\Api\v1\RolePermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\PermissionController;
use App\Http\Controllers\Api\v1\RoleFilterController;
use App\Http\Controllers\Api\v1\AircraftController;

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


});