<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'namespace' => 'App\Http\Controllers\Admin',
    'middleware' => ['web'],
], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/', 'Auth\LoginController@login')->name('admin.login.submit');

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::post('/logout', 'Auth\LoginController@logout')->name('admin.logout');

        Route::prefix('dashboard')->group(function () {
            Route::get('/', 'DashboardController@index')->name('admin.dashboard');
        });
    });
});
