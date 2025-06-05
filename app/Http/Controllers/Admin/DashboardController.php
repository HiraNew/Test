<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $now = now();
        $successful = 'successful';
        $pending = 'pending';

        // Grouped Stats
        $stats = [
            'Users & Vendors' => [
                ['title' => 'Total Registered Users', 'value' => User::count()],
                ['title' => 'Total Logged-in Users', 'value' => User::where('status', 1)->count()],
                ['title' => 'Total Vendors', 'value' => User::where('role', 'vendor')->count()],
            ],
            'Catalog' => [
                ['title' => 'Total Categories', 'value' => Category::count()],
                ['title' => 'Total Subcategories', 'value' => Subcategory::count()],
                ['title' => 'Total Products Available', 'value' => Product::where('status', 'available')->count()],
            ],
            'Revenue (Successful Orders)' => [
                ['title' => 'Total Revenue', 'value' => Payment::where('status', $successful)->sum('amount')],
                ['title' => 'Monthly Revenue', 'value' => Payment::where('status', $successful)->whereMonth('created_at', $now->month)->sum('amount')],
                ['title' => 'Weekly Revenue', 'value' => Payment::where('status', $successful)->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()])->sum('amount')],
                ['title' => 'Today\'s Revenue', 'value' => Payment::where('status', $successful)->whereDate('created_at', $today)->sum('amount')],
            ],
            'Pending Orders' => [
                ['title' => 'Total Pending Orders', 'value' => Payment::where('status', $pending)->count()],
                ['title' => 'Total Pending Revenue', 'value' => Payment::where('status', $pending)->sum('amount')],
                ['title' => 'Pending Orders Today', 'value' => Payment::where('status', $pending)->whereDate('created_at', $today)->count()],
            ]
        ];

        // Chart Data
        $monthlyData = Payment::where('status', $successful)
            ->whereMonth('created_at', $now->month)
            ->selectRaw('DAY(created_at) as day, SUM(amount) as revenue')
            ->groupBy('day')->orderBy('day')->get();

        $weeklyData = Payment::where('status', $successful)
            ->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()])
            ->selectRaw('DAYNAME(created_at) as day, SUM(amount) as revenue')
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
            ->get();

        $dailyData = Payment::where('status', $successful)
            ->whereDate('created_at', $today)
            ->selectRaw('HOUR(created_at) as hour, SUM(amount) as revenue')
            ->groupBy('hour')->orderBy('hour')->get();

        $monthlyLabels = $monthlyData->pluck('day')->map(fn($d) => "Day $d");
        $monthlyRevenue = $monthlyData->pluck('revenue');

        $weeklyLabels = $weeklyData->pluck('day');
        $weeklyRevenue = $weeklyData->pluck('revenue');

        $dailyLabels = $dailyData->pluck('hour')->map(fn($h) => "$h:00");
        $dailyRevenue = $dailyData->pluck('revenue');

        $recentOrders = Payment::with('user')
            ->selectRaw('
                orderid,
                user_id,
                MAX(status) as status,
                MAX(payment_mode) as payment_mode,
                SUM(amount) as total_amount,
                COUNT(*) as item_count,
                MAX(order_date) as order_date
            ')
            ->groupBy('orderid', 'user_id')
            ->orderByDesc('order_date')
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'monthlyLabels', 'monthlyRevenue',
            'weeklyLabels', 'weeklyRevenue',
            'dailyLabels', 'dailyRevenue',
            'recentOrders'
        ));
    }
}
