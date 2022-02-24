<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\HomeController::class,'index'])->name('home');
Route::get('/article/{article}',[\App\Http\Controllers\ArticleController::class,'index'])->name('article');

Route::middleware('auth')->group(function () {
    Route::get('/account',[\App\Http\Controllers\AccountController::class,'dashboard'])->name('account');

    Route::get('/account/{page}',[\App\Http\Controllers\AccountController::class,'page'])->name('account.page');
    Route::post('/account/{page}',[\App\Http\Controllers\AccountController::class,'save'])->name('account.save');

    Route::get('/logout',[\App\Http\Controllers\Auth\AuthenticatedSessionController::class,'destroy'])->name('logout');
});

Route::middleware('role:Admin')->group(function (){
    Route::get('/admin',[\App\Http\Controllers\Admin\DashboardController::class,'index'])->name('admin');

    Route::resource('/admin/article',\App\Http\Controllers\Admin\ArticleController::class)->names('admin.article');
    Route::get('/admin/article/{article}/hide',[\App\Http\Controllers\Admin\ArticleController::class,'hide'])->name('admin.article.hide');
    Route::get('/admin/article/{article}/restore',[\App\Http\Controllers\Admin\ArticleController::class,'restore'])->name('admin.article.restore');

    Route::resource('/admin/user',\App\Http\Controllers\Admin\UserController::class)->names('admin.user');
    Route::get('/admin/user/{user}/hide',[\App\Http\Controllers\Admin\ArticleController::class,'hide'])->name('admin.user.hide');
    Route::get('/admin/user/{user}/restore',[\App\Http\Controllers\Admin\ArticleController::class,'restore'])->name('admin.user.restore');

    Route::get('/admin/settings',[\App\Http\Controllers\Admin\SettingsController::class,'index'])->name('admin.settings');
    Route::post('/admin/settings',[\App\Http\Controllers\Admin\SettingsController::class,'save'])->name('admin.settings.save');
});

require __DIR__.'/auth.php';
