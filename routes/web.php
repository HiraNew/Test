<?php

// use App\Http\Controllers\UserDashboard\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/home1', [App\Http\Controllers\InternetCheckController::class, 'checkInternetConnection']);
// Route::get('/', function () {
//     return view('home');
// });
Auth::routes();

// Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
// Route::get('/hiralal', [App\Http\Controllers\HomeController::class, 'hiralal']);
Route::get('/', [App\Http\Controllers\UserDashboard\ProductController::class, 'product'])->name('products');



Route::middleware('auth')->group(function () {
    // Define your routes that require authentication here
    Route::get('/carting', [App\Http\Controllers\UserDashboard\ProductController::class, 'carting'])->name('carting');
    
    
    Route::get('/detail/{id}', [App\Http\Controllers\UserDashboard\ProductController::class, 'detail'])->name('detail');
    Route::get('/addTocart/{id}', [App\Http\Controllers\UserDashboard\ProductController::class, 'addTocart'])->name('addCart');
    // Review Related route start
    Route::post('/product/{product}/review', [App\Http\Controllers\UserDashboard\ProductController::class, 'storeReview'])->name('reviews.store');
    Route::post('/review/{id}/like', [App\Http\Controllers\UserDashboard\ProductController::class, 'like'])->name('review.like');
    Route::post('/review/vote', [App\Http\Controllers\UserDashboard\ProductController::class, 'vote'])->name('review.vote');
    Route::post('/review/{id}/dislike', [App\Http\Controllers\UserDashboard\ProductController::class, 'dislike'])->name('review.dislike');

    // review related route end
    Route::get('/removeTocart/{id}', [App\Http\Controllers\UserDashboard\ProductController::class, 'removeTocart'])->name('removeCart');
    Route::get('/removeItemTocart/{id}', [App\Http\Controllers\UserDashboard\ProductController::class, 'removeItemTocart'])->name('removeItemTocart');
    Route::get('/cart', [App\Http\Controllers\UserDashboard\ProductController::class, 'cartView'])->name('cartView'); 
    Route::get('/updateAddress', [App\Http\Controllers\UserDashboard\ProductController::class, 'updateAddress'])->name('updateAddress');
    Route::match(['get', 'post'], '/cart-Proceed', [App\Http\Controllers\UserDashboard\ProductController::class, 'cartProceed'])->name('cart-Proceed');
    Route::get('/orderNow', [App\Http\Controllers\UserDashboard\ProductController::class, 'orderNow'])->name('orderNow');
    Route::get('/paymentMethod', [App\Http\Controllers\UserDashboard\ProductController::class, 'paymentMethod'])->name('paymentMethod');
    Route::post('/paymentMethod/proceed', [App\Http\Controllers\UserDashboard\ProductController::class, 'paymentMethodProceed'])->name('paymentMethod.proceed');
    Route::get('/notification', [App\Http\Controllers\UserDashboard\ProductController::class, 'notification'])->name('notification');
    Route::get('/notificationView', [App\Http\Controllers\UserDashboard\ProductController::class, 'notificationView'])->name('notificationView');
    
});