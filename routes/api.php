<?php

use Illuminate\Support\Facades\Artisan;
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

Route::prefix('v1')->middleware(['auth:sanctum', 'ability:User,Admin'])->group(function () {
    Route::prefix('game')->group(function () {
        Route::get('check', [\App\Http\Controllers\Game\Api\TableController::class, 'check']);
        Route::get('fold', [\App\Http\Controllers\Game\Api\TableController::class, 'fold']);
        Route::get('raise/{amount}', [\App\Http\Controllers\Game\Api\TableController::class, 'raise']);
        Route::get('default', [\App\Http\Controllers\Game\Api\TableController::class, 'default']);
        Route::get('call', [\App\Http\Controllers\Game\Api\TableController::class, 'call']);
    });
    Route::prefix('wait')->group(function () {
        Route::get('stand/{className}', [\App\Http\Controllers\Game\Api\TurnController::class, 'stand']);
        Route::get('leave', [\App\Http\Controllers\Game\Api\TurnController::class, 'leave']);
    });
    Route::prefix('user')->group(function () {
        Route::get('profile', [\App\Http\Controllers\Game\Api\UserController::class, 'profile']);
    });
    Route::prefix('lobby')->group(function () {
        Route::get('list', [\App\Http\Controllers\Game\Api\LobbyController::class, 'list']);
    });
});

Broadcast::routes(
    [
        'middleware' =>
            [
                'auth:sanctum',
                'ability:User,Admin'
            ]
    ]
);

Route::get('/next', function () {
    return Artisan::call('queue:work --once');
});
