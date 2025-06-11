<?php

// use App\Http\Controllers\UserDashboard\ProductController;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserDashboard\UserProfileController;
use App\Http\Controllers\UserDashboard\UserWishListController;
use App\Http\Controllers\WishlistController;
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
Route::get('/mainantance', [App\Http\Controllers\UserDashboard\ProductController::class, 'mainance']);
Route::get('/search-products', [App\Http\Controllers\UserDashboard\ProductController::class, 'searchProducts'])->name('search.products');
Route::post('/send-otp', [RegisterController::class, 'sendOtp'])->name('send.otp');
Route::post('/register', [RegisterController::class, 'register'])->name('registerUser');
Route::get('/detail/{id}', [App\Http\Controllers\UserDashboard\ProductController::class, 'detail'])->name('detail');
Route::get('/live-search', [App\Http\Controllers\UserDashboard\ProductController::class, 'liveSearch'])->name('products.liveSearch');
// Route::get('/product/{id}', [App\Http\Controllers\UserDashboard\ProductController::class, 'show'])->name('product.show');
Route::get('/category/{slug}', [App\Http\Controllers\UserDashboard\ProductController::class, 'categoryView'])->name('category.view');






Route::middleware('auth')->group(function () {
    // Define your routes that require authentication here
    Route::get('/carting', [App\Http\Controllers\UserDashboard\ProductController::class, 'carting'])->name('carting');
    Route::get('/wishlist', [UserWishListController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/bulk-delete', [UserWishListController::class, 'bulkDelete'])->name('wishlist.bulkDelete');
    Route::post('/cart/bulk-delete', [UserWishListController::class, 'cartBulkDelete'])->name('cart.bulkDelete');
    Route::get('/userAccount', [UserProfileController::class, 'userAccount'])->name('user.account');
    Route::get('/payments', [UserProfileController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [UserProfileController::class, 'show'])->name('payments.show');
    // update address start
    Route::get('/states/{countryId}', [UserProfileController::class, 'getStates']);
    Route::get('/cities/{stateId}', [UserProfileController::class, 'getCities']);
    Route::put('/address/update/{payment}', [UserProfileController::class, 'update'])->name('address.update');
    Route::put('/order/cancel/{payment}', [UserProfileController::class, 'cancel'])->name('order.cancel');
    // Update address end
    
    
    Route::match(['get', 'post'],'/addTocart/{id}', [App\Http\Controllers\UserDashboard\ProductController::class, 'addTocart'])->name('addCart');
    // Review Related route start
    Route::post('/product/{product}/review', [App\Http\Controllers\UserDashboard\ProductController::class, 'storeReview'])->name('reviews.store');
    Route::post('/review/{id}/like', [App\Http\Controllers\UserDashboard\ProductController::class, 'like'])->name('review.like');
    Route::post('/review/vote', [App\Http\Controllers\UserDashboard\ProductController::class, 'vote'])->middleware('auth');
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


    //not decide
      Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
      // web.php
    // Route::post('/wishlist/toggled/{id}', [WishlistController::class, 'toggled'])->name('wishlist.toggled');

    
});