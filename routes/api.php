<?php

use App\Http\Controllers\Api\v1\AircraftController;
use App\Http\Controllers\Api\v1\AircraftModelController;
use App\Http\Controllers\Api\v1\FlightHourController;
use App\Http\Controllers\Api\v1\ManufacturerController;
use App\Http\Controllers\Api\v1\RolePermissionController;
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
    Route::post('login', fn (\Illuminate\Http\Request $request): \App\Http\Responses\ApiSuccessResponse|\App\Http\Responses\ApiErrorResponse => (new \App\Http\Controllers\Api\v1\AuthController)->login($request))->name('api.v1.login');
    Route::post('logout', fn (\Illuminate\Http\Request $request): \App\Http\Responses\ApiSuccessResponse|\App\Http\Responses\ApiErrorResponse => (new \App\Http\Controllers\Api\v1\AuthController)->logout($request))->name('api.v1.logout');
});

Route::group([
    'namespace' => 'api',
    'prefix' => 'v1',
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('users/{role}/byRole', fn ($role): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\App\Http\Responses\ApiErrorResponse => (new \App\Http\Controllers\Api\v1\RoleFilterController)->filterByRole($role))
        ->name('api.v1.users.byRole');
    Route::get('users', fn (\Illuminate\Http\Request $request): \App\Http\Responses\ApiSuccessResponse|\App\Http\Responses\ApiErrorResponse => (new \App\Http\Controllers\Api\v1\UserController)->index($request))->name('api.v1.users');
    Route::post('users', fn (\Illuminate\Http\Request $request): \App\Http\Responses\ApiSuccessResponse|\App\Http\Responses\ApiErrorResponse => (new \App\Http\Controllers\Api\v1\UserController)->store($request))->name('api.v1.users.store');
    Route::match(['put', 'patch'], 'users', fn (\Illuminate\Http\Request $request, $id): \App\Http\Responses\ApiSuccessResponse|\App\Http\Responses\ApiErrorResponse => (new \App\Http\Controllers\Api\v1\UserController)->update($request, $id))
        ->name('api.v1.users.update');
    // Roles
    Route::get('permissions/roles', [RolePermissionController::class, 'getRoles'])
        ->name('api.v1.permissions.roles');
    Route::post('permissions/roles', [RolePermissionController::class, 'storeRole'])
        ->name('api.v1.permissions.roles.store');
    Route::patch('permissions/{id}/roles', [RolePermissionController::class, 'updateRole'])
        ->name('api.v1.permissions.roles.update');
    Route::delete('permissions/{id}/roles', [RolePermissionController::class, 'deleteRole'])
        ->name('api.v1.permissions.roles.delete');
    Route::post('permissions/roles/{id}/{guard}/assign', [RolePermissionController::class, 'assignPermissions'])
        ->name('api.v1.permissions.assign');
    Route::post('permissions/roles/{id}/revoke', [RolePermissionController::class, 'revokePermissions'])
        ->name('api.v1.permissions.revoke');
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

// Manufacturer
Route::namespace('api')
    ->prefix('v1')
    ->name('api.v1.')
    ->middleware('auth:sanctum')
    ->apiResource('v1/manufacturer', ManufacturerController::class);

// Aircraft
Route::namespace('api')
    ->prefix('v1')
    ->name('api.v1.')
    ->middleware('auth:sanctum')
    ->apiResource('aircraft/models', AircraftModelController::class);

Route::namespace('api')
    ->prefix('v1')
    ->name('api.v1.')
    ->middleware('auth:sanctum')
    ->apiResource('aircraft', AircraftController::class);

Route::namespace('api')
    ->prefix('v1')
    ->name('api.v1.')
    ->middleware('auth:sanctum')
    ->apiResource('aircraft/flight_hours', FlightHourController::class);
