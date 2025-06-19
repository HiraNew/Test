<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\ServiceProvider;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Paginator::useBootstrap(); // Enables Bootstrap styles
         View::composer('Vendor.partials.sidebar', function ($view) {
            $vendor = auth()->guard('vendor')->user();
            if(isset($vendor)){
            $newOrderNotifications = Payment::where('vendor_id', $vendor->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->count();

            $newProductNotifications = Product::where('vendor_id', $vendor->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->count();

            $view->with(compact('newOrderNotifications', 'newProductNotifications'));
            }
        });
    }
}
