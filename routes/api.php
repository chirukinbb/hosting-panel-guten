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

Route::post('/admin/upload/image',[\App\Http\Controllers\Api\UploadController::class,'image'])
    ->middleware('auth:sanctum','ability:Admin')->name('admin.upload.image');

Route::post('/turn/state',[\App\Http\Controllers\Game\Api\TurnController::class,'state'])
    ->middleware('auth:sanctum','ability:User,Admin')->name('state');
Route::post('/turn/stand',[\App\Http\Controllers\Game\Api\TurnController::class,'stand'])
    ->middleware('auth:sanctum','ability:User,Admin')->name('stand');
Route::post('/turn/leave',[\App\Http\Controllers\Game\Api\TurnController::class,'leave'])
    ->middleware('auth:sanctum','ability:User,Admin')->name('leave');

Route::post('/table/action', [\App\Http\Controllers\Game\Api\TableController::class,'action'])
    ->middleware('auth:sanctum','ability:User,Admin')->name('action');
Route::post('/table/leave', [\App\Http\Controllers\Game\Api\TableController::class,'leave'])
    ->middleware('auth:sanctum','ability:User,Admin')->name('leave');

Broadcast::routes(
    [
        'middleware' =>
            [
                'auth:sanctum',
                'ability:User,Admin'
            ]
    ]
);
