<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
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



Route::middleware('throttle:10000,1')->group(function () {
    Route::get('/', [PageController::class, 'homePage']);
    Route::get('/logout', [PageController::class, 'logoutPage']);
    Route::get('/login', [PageController::class, 'loginPage']);
    Route::get('/register', [PageController::class, 'registerPage']);
    Route::get('/form/team', [PageController::class, 'teamFormPage']);
    Route::get('search/team', [PageController::class, 'searchTeam']);
    Route::post('/register', [PageController::class, 'register']);
    Route::post('/login', [PageController::class, 'login']);
    Route::post('/teams', [PageController::class, 'addTeam']);
    Route::delete('/teams/{id}', [PageController::class, 'deleteTeam']);
    Route::get('/edit/teams/{id}', [PageController::class, 'updateTeamPage']);
    Route::post('/teams/{id}', [PageController::class, 'updateTeam']);
    Route::get('/view/teams/{id}', [PageController::class, 'viewTeamPage']);
    Route::get('/drivers', [PageController::class, 'driversPage']);
    Route::delete('/drivers/{id}', [PageController::class, 'deleteDriver']);
    Route::get('/drivers/{id}', [PageController::class, 'viewDriverPage']);
    Route::get('/edit/drivers/{id}', [PageController::class, 'updateDriverPage']);
    Route::post('/drivers/update/{id}', [PageController::class, 'updateDriver']);
    Route::post('/drivers', [PageController::class, 'addDriver']);
    Route::get('/view/drivers/{id}', [PageController::class, 'viewDriverPage']);
    Route::get('/search/driver', [PageController::class, 'searchDriver']);
    Route::get('/form/driver', [PageController::class, 'driverFormPage']);
});


