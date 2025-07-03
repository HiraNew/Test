<?php

// use App\Http\Controllers\UserDashboard\ProductController;

use App\Http\Controllers\AdminNotifyController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Partner\DeliveryPartnarController;
use App\Http\Controllers\UserDashboard\UserProfileController;
use App\Http\Controllers\UserDashboard\UserWishListController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

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
Broadcast::routes(['middleware' => ['auth:sanctum']]);
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
    Route::get('/villages/{cityId}', [UserProfileController::class, 'getVillages']);
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
    Route::get('/notifications-data', [App\Http\Controllers\UserDashboard\ProductController::class, 'getNotificationsData'])->name('notifications.data');
    
    


    //not decide
      Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
      // web.php
    // Route::post('/wishlist/toggled/{id}', [WishlistController::class, 'toggled'])->name('wishlist.toggled');

    
});

// Delivery Partner related route start
Route::prefix('partner')->name('partner.')->group(function () {
    Route::get('/login', [DeliveryPartnarController::class, 'getPartnerLoginForm'])->name('login');
    Route::post('/login', [DeliveryPartnarController::class, 'login'])->name('login.submit');
    Route::middleware('auth:partner')->group(function () {
      Route::get('/delivery', [DeliveryPartnarController::class, 'getListOfProductForDelivered'])->name('delivery');
      Route::post('/send-otp', [DeliveryPartnarController::class, 'sendOtp'])->name('send.otp');
      Route::post('/verify-otp', [DeliveryPartnarController::class, 'verifyOtp'])->name('verify.otp');
      Route::post('/logout', [DeliveryPartnarController::class, 'partnerLogout'])->name('logout');
    });

    
});
Route::get('/share-location/{orderid}', [DeliveryPartnarController::class, 'showForm'])->name('location.form');
Route::post('/submit-location', [DeliveryPartnarController::class, 'store'])->name('location.store');

// Delivery partner related route end

Route::prefix('vendor')->name('vendor.')->group(function () {
  Route::get('/login', [VendorController::class, 'getVendorLoginForm'])->name('login');
  Route::post('/login', [VendorController::class, 'login'])->name('login.submit');
  Route::middleware('auth:vendor')->group(function () {
    Route::get('/vendorDashboard', [VendorController::class, 'vendorDashboard'])->name('dashboard');
    Route::post('/logout', [VendorController::class, 'vendorLogout'])->name('logout');
    // Vendor Product Controller start
    Route::resource('products', VendorProductController::class);
    // Route::delete('/extra-images/{image}', [VendorProductController::class, 'deleteExtraImage'])->name('extra-images.destroy');
    Route::patch('products/{product}/toggle-status', [VendorProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::get('orders', [VendorProductController::class, 'vendorOrderList'])->name('orders.index');
    Route::post('orders/confirm/{id}', [VendorProductController::class, 'confirm'])->name('orders.confirm');
    Route::post('orders/ship', [VendorProductController::class, 'ship'])->name('orders.ship');
    Route::post('/vendor/orders/send-notification', [VendorProductController::class, 'sendNotification'])->name('orders.sendNotification');
    Route::get('/notifications/sent', [VendorProductController::class, 'sentNotifications'])->name('notifications.sent');
    Route::get('/allUsers', [VendorProductController::class, 'user'])->name('orders.users');

    // Vendor Product controller end
  });
});
// Route::delete('vendor/products/images/{image}', [VendorProductController::class, 'deleteExtraImage'])
//     ->name('vendor.products.images.destroy');

// // for send notification route 
// Route::post('/notify/all', [AdminNotifyController::class, 'sendToAll'])->name('notify.all');
// Route::post('/notify/user/{user}', [AdminNotifyController::class, 'sendToUser'])->name('notify.user');

