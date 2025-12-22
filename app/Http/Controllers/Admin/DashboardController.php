<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Basic Stats
        $totalRevenue = Order::sum('total_price'); 
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('usertype', 'user')->count();

        // 2. NEW FEATURE: Daily Sale Summary Reports
        $todayRevenue = Order::whereDate('created_at', Carbon::today())->sum('total_price');
        $yesterdayRevenue = Order::whereDate('created_at', Carbon::yesterday())->sum('total_price');
        $todayOrderCount = Order::whereDate('created_at', Carbon::today())->count();
        
        // Calculate growth percentage
        $revenueChange = $yesterdayRevenue > 0 
            ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 
            : 0;

        // 3. Recent Orders & Stock Alerts
        $recentOrders = Order::with('user')->latest()->take(10)->get();
        // Updated Alert: Highlighting critical stock items (less than 10)
        $lowStockItems = Product::where('stock_quantity', '<', 10)
                                ->orderBy('stock_quantity', 'asc')
                                ->get();

        // 4. Prepare Revenue Chart Data (Last 7 Days)
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueLabels[] = $date->format('M d'); 
            $revenueData[] = Order::whereDate('created_at', $date)->sum('total_price');
        }

        // 5. Prepare Category Chart Data
        $categoryStats = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        $categoryLabels = $categoryStats->pluck('name');
        $categoryData = $categoryStats->pluck('total');

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'totalOrders', 
            'pendingOrders', 
            'totalCustomers',
            'recentOrders',
            'lowStockItems',
            'revenueLabels',
            'revenueData',
            'categoryLabels',
            'categoryData',
            'todayRevenue',
            'yesterdayRevenue',
            'todayOrderCount',
            'revenueChange'
        ));
    }
}