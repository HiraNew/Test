<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function index()
        {
            $completedStatus = 'completed';
            $canceledStatus = 'canceled';
            $pendingStatus = 'pending';

            // Time filters
            $today = today();
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();
            $currentMonth = now()->month;

            // Stats Data (modified + added)
            $stats = [
                ['title' => 'Total Registered Users', 'value' => User::count()],
                ['title' => 'Total Logged-in Users', 'value' => User::whereNotNull('last_login_at')->count()],
                ['title' => 'Total Revenue', 'value' => '$' . Payment::sum('amount')],
                ['title' => 'Users in Portfolio', 'value' => DB::table('portfolios')->count()],
                ['title' => 'Monthly Revenue', 'value' => '$' . Payment::whereMonth('created_at', now()->month)->sum('amount')],
                ['title' => 'Weekly Revenue', 'value' => '$' . Payment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount')],
                ['title' => 'Today\'s Revenue', 'value' => '$' . Payment::whereDate('created_at', today())->sum('amount')],
                ['title' => 'Total Vendors', 'value' => User::where('role', 'vendor')->count()],
                ['title' => 'Total Products', 'value' => Product::count()],
                ['title' => 'Total Categories', 'value' => Category::count()],
                ['title' => 'Total Orders', 'value' => Payment::count()],
                ['title' => 'Orders Today', 'value' => Payment::whereDate('created_at', today())->count()],
            ];

            // Monthly Revenue Chart
            $monthlyData = Payment::whereMonth('created_at', now()->month)
                ->selectRaw('DAY(created_at) as day, SUM(amount) as revenue')
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $monthlyLabels = $monthlyData->pluck('day')->map(fn($d) => "Day $d");
            // dd($monthlyLabels);
            $monthlyRevenue = $monthlyData->pluck('revenue');

            // Weekly Revenue Chart
            $weeklyData = Payment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->selectRaw('DAYNAME(created_at) as day, SUM(amount) as revenue')
                ->groupBy('day')
                ->orderByRaw("FIELD(day, 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
                ->get();

            $weeklyLabels = $weeklyData->pluck('day');
            $weeklyRevenue = $weeklyData->pluck('revenue');

            // Daily Revenue Chart
            $dailyData = Payment::whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) as hour, SUM(amount) as revenue')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            $dailyLabels = $dailyData->pluck('hour')->map(fn($h) => "$h:00");
            $dailyRevenue = $dailyData->pluck('revenue');

            // Recent Payments with User
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



            return view('admin/dashboard/index', [
                'stats' => $stats,
                'monthlyLabels' => $monthlyLabels,
                'monthlyRevenue' => $monthlyRevenue,
                'weeklyLabels' => $weeklyLabels,
                'weeklyRevenue' => $weeklyRevenue,
                'dailyLabels' => $dailyLabels,
                'dailyRevenue' => $dailyRevenue,
                'recentOrders' => $recentOrders,
            ]);
        }
}
