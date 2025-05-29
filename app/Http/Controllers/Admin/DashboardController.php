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
                'Users' => [
                    ['title' => 'Total Registered Users', 'value' => User::count()],
                    ['title' => 'Total Logged-in Users', 'value' => User::where('status', 1)->count()],
                    ['title' => 'Users in Portfolio', 'value' => DB::table('portfolios')->count()],
                    ['title' => 'Total Vendors', 'value' => User::where('role', 'vendor')->count()],
                ],
                'Revenue' => [
                    ['title' => 'Total Revenue', 'value' => '$' . Payment::where('status', 'completed')->sum('amount')],
                    ['title' => 'Monthly Revenue', 'value' => '$' . Payment::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount')],
                    ['title' => 'Weekly Revenue', 'value' => '$' . Payment::where('status', 'completed')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount')],
                    ['title' => 'Today\'s Revenue', 'value' => '$' . Payment::where('status', 'completed')->whereDate('created_at', today())->sum('amount')],
                    ['title' => 'Total Canceled Revenue', 'value' => '$' . Payment::where('status', 'canceled')->sum('amount')],
                    ['title' => 'Canceled Revenue (Monthly)', 'value' => '$' . Payment::where('status', 'canceled')->whereMonth('created_at', now()->month)->sum('amount')],
                    ['title' => 'Canceled Revenue (Weekly)', 'value' => '$' . Payment::where('status', 'canceled')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount')],
                    ['title' => 'Canceled Revenue (Today)', 'value' => '$' . Payment::where('status', 'canceled')->whereDate('created_at', today())->sum('amount')],
                    ['title' => 'Total Pending Revenue', 'value' => '$' . Payment::where('status', 'pending')->sum('amount')],
                    ['title' => 'Pending Revenue (Monthly)', 'value' => '$' . Payment::where('status', 'pending')->whereMonth('created_at', now()->month)->sum('amount')],
                    ['title' => 'Pending Revenue (Weekly)', 'value' => '$' . Payment::where('status', 'pending')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount')],
                    ['title' => 'Pending Revenue (Today)', 'value' => '$' . Payment::where('status', 'pending')->whereDate('created_at', today())->sum('amount')],
                ],
                'Orders' => [
                    ['title' => 'Total Orders', 'value' => Payment::count()],
                    ['title' => 'Orders Today', 'value' => Payment::whereDate('created_at', today())->count()],
                    ['title' => 'Total Canceled Orders', 'value' => Payment::where('status', 'canceled')->count()],
                    ['title' => 'Canceled Orders (Monthly)', 'value' => Payment::where('status', 'canceled')->whereMonth('created_at', now()->month)->count()],
                    ['title' => 'Canceled Orders (Weekly)', 'value' => Payment::where('status', 'canceled')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()],
                    ['title' => 'Canceled Orders (Today)', 'value' => Payment::where('status', 'canceled')->whereDate('created_at', today())->count()],
                    ['title' => 'Completed Orders (Monthly)', 'value' => Payment::where('status', 'completed')->whereMonth('created_at', now()->month)->count()],
                    ['title' => 'Completed Orders (Weekly)', 'value' => Payment::where('status', 'completed')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()],
                    ['title' => 'Completed Orders (Today)', 'value' => Payment::where('status', 'completed')->whereDate('created_at', today())->count()],
                ],
                'Catalog' => [
                    ['title' => 'Total Products', 'value' => Product::count()],
                    ['title' => 'Total Categories', 'value' => Category::count()],
                ]
            ];




            $monthlyData = Payment::where('status', $completedStatus)
                ->whereMonth('created_at', now()->month)
                ->selectRaw('DAY(created_at) as day, SUM(amount) as revenue')
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $weeklyData = Payment::where('status', $completedStatus)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->selectRaw('DAYNAME(created_at) as day, SUM(amount) as revenue')
                ->groupBy('day')
                ->orderByRaw("FIELD(day, 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
                ->get();

            $dailyData = Payment::where('status', $completedStatus)
                ->whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) as hour, SUM(amount) as revenue')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();




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
