<?php

use App\Http\Controllers\Api\v1\RolePermissionController;
use Illuminate\Support\Facades\Route;
// Removed duplicate import of AuthController

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
use App\Http\Controllers\Api\v1\{
    AuthController,
    UserController,
    RoleFilterController,
    PermissionController
};


// Ruta de bienvenida
Route::get('v1', fn () => response()->json(['message' => 'LuxePlus API v1']));

// Auth (no protegidas)
Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('api.v1.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('api.v1.logout');
});

// Rutas protegidas
Route::middleware('auth:api')->prefix('v1')->group(function () {
    // Users
    Route::apiResource('users', UserController::class)->except(['update']);
    Route::match(['put', 'patch'], 'users/{user}', [UserController::class, 'update']); // Más RESTful

    // Roles & Permissions (modularizado)
    Route::prefix('permissions')->group(function () {
        Route::apiResource('roles', RolePermissionController::class)->only([
            'index', 'store', 'update', 'destroy'
        ]);

        Route::post('roles/{role}/{guard}/assign', [RolePermissionController::class, 'assignPermissions']);
        Route::post('roles/{role}/revoke', [RolePermissionController::class, 'revokePermissions']);

        Route::apiResource('permissions', PermissionController::class)->except(['show']);
    });

    // Ruta especial para filtro
    Route::get('users/{role}/by-role', [RoleFilterController::class, 'filterByRole']);
});

