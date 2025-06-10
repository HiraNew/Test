<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->middleware(['web'])->group(function () {
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/', 'Auth\LoginController@login')->name('admin.login.submit');

    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout', 'Auth\LoginController@logout')->name('admin.logout');

        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
            Route::get('/data', [DashboardController::class, 'fetchDashboardData'])->name('admin.dashboard.data');
        });
    });
});

