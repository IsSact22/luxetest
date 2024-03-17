<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
]));

Route::get('dashboard', fn () => Inertia::render('Dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function ($route) {
    $route->get('/profile', fn (\Illuminate\Http\Request $request): \Inertia\Response => (new \App\Http\Controllers\ProfileController)->edit($request))->name('profile.edit');
    $route->patch('/profile', fn (\App\Http\Requests\ProfileUpdateRequest $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\ProfileController)->update($request))->name('profile.update');
    $route->delete('/profile', fn (\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse => (new \App\Http\Controllers\ProfileController)->destroy($request))->name('profile.destroy');

    $route->resource('roles', \App\Http\Controllers\RoleController::class);
    $route->resource('permissions', \App\Http\Controllers\PermissionController::class);
    $route->resource('users', \App\Http\Controllers\UserController::class);
    $route->resource('clients', \App\Http\Controllers\ClientController::class);
    $route->resource('services', \App\Http\Controllers\ServiceController::class);
    $route->resource('aircraft', \App\Http\Controllers\AircraftController::class);
    $route->resource('projects', \App\Http\Controllers\ProjectController::class);
});

require __DIR__.'/auth.php';
