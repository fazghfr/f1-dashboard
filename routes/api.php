<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DriverController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum', 'throttle:10000,1')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // definisi route teams
    Route::get('/teams', [TeamController::class, 'index']);
    Route::get('/teams/names', [TeamController::class, 'getAllNames']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::get('/teams/{id}', [TeamController::class, 'show']);
    Route::put('/teams/{id}', [TeamController::class, 'update']);
    Route::delete('/teams/{id}', [TeamController::class, 'destroy']);
    Route::get('/search/teams', [TeamController::class, 'search']);

    // definisi route drivers
    Route::get('/drivers', [DriverController::class, 'index']);
    Route::get('/drivers/{id}', [DriverController::class, 'show']);
    Route::get('/drivers/team/{id}', [DriverController::class, 'showbyTeamID']);
    Route::put('/drivers/{id}', [DriverController::class, 'update']);
    Route::post('/drivers', [DriverController::class, 'store']);
    Route::delete('/drivers/{id}', [DriverController::class, 'destroy']);
    Route::get('/search/drivers', [DriverController::class, 'search']);
});

