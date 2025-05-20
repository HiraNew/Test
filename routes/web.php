<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Protfolio\ContactController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

Route::get('/', function () {
    return Inertia::render('Portfolio/firstpage');
});
Route::post('/contact-us', [ContactController::class, 'store']);
// Route::get('/privacyPolicy', [ContactController::class, 'privacyPolicy']);
Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
});
Route::get('/about-us', function () {
    return Inertia::render('AboutUs');
});
// Route::get('/privacy&policy', function () {
//     return Inertia::render('p&v');
// });

Route::get('/services', function () {
    return Inertia::render('Service');
});

Route::get('/contact', function () {
    return Inertia::render('ContactUs');
});