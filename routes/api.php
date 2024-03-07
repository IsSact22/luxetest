<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\RolePermissionController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
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

Route::get('v1', fn () => response()->json(['message' => 'welcome'], 200));

Route::group(['namespace' => 'api', 'prefix' => 'v1'], function () {
    Route::post('login', fn (\Illuminate\Http\Request $request): ApiSuccessResponse|ApiErrorResponse => (new AuthController)
        ->login($request))
        ->name('api.v1.login');
    Route::post('logout', fn (\Illuminate\Http\Request $request): ApiSuccessResponse|ApiErrorResponse => (new AuthController)
        ->logout($request))
        ->name('api.v1.logout');
    Route::post('register', fn (\Illuminate\Http\Request $request): ApiSuccessResponse|ApiErrorResponse => (new AuthController)
        ->register($request))
        ->name('api.v1.register');
});

Route::group([
    'namespace' => 'api',
    'prefix' => 'v1',
], function () {
    Route::get('users', fn (\Illuminate\Http\Request $request): ApiSuccessResponse|ApiErrorResponse => (new UserController)
        ->getUsers($request))
        ->name('api.v1.users');
    Route::match(['put', 'patch'], 'users', fn (\Illuminate\Http\Request $request, $id): ApiSuccessResponse|ApiErrorResponse => (new UserController)
        ->update($request, $id))
        ->name('api.users.update');
    // Roles
    Route::get('permissions/roles', [RolePermissionController::class, 'getRoles'])
        ->name('api.v1.permissions.roles');
    Route::post('permissions/roles', [RolePermissionController::class, 'storeRole'])
        ->name('api.v1.permissions.roles.store');
    Route::patch( 'permissions/{id}/roles', [RolePermissionController::class, 'updateRole'])
        ->name('api.v1.permissions.roles.update');
    Route::delete('permissions/{id}/roles', [RolePermissionController::class, 'deleteRole'])
        ->name('api.v1.permissions.roles.delete');
    //Permissions
    Route::get('permissions', [RolePermissionController::class, 'getPermissions'])
        ->name('api.v1.permissions');
    Route::post('permissions', [RolePermissionController::class, 'storePermission'])
        ->name('api.v1.permissions.store');
    Route::patch('permissions/{id}', [RolePermissionController::class, 'updatePermission'])
        ->name('api.v1.permissions.update');
    Route::delete('permissions/{id}', [RolePermissionController::class, 'deletePermission'])
        ->name('api.v1.permissions.delete');
});
