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

Route::get('/turn/state',[\App\Http\Controllers\Game\Api\TurnController::class,'state']);
Route::post('/turn/stand',[\App\Http\Controllers\Game\Api\TurnController::class,'stand'])
    ->middleware('auth:sanctum','ability:User')->name('stand');
Route::get('/turn/leave',[\App\Http\Controllers\Game\Api\TurnController::class,'leave'])
    ->middleware('auth:sanctum','ability:User')->name('leave');
