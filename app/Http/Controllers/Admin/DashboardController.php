<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\PointTransaction;
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

        // 2. Daily Sale Summary Reports
        $today = Carbon::today();
        $todayRevenue = Order::whereDate('created_at', $today)->sum('total_price');
        $yesterdayRevenue = Order::whereDate('created_at', Carbon::yesterday())->sum('total_price');
        $todayOrderCount = Order::whereDate('created_at', $today)->count();
        
        // Calculate growth percentage
        $revenueChange = $yesterdayRevenue > 0 
            ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 
            : 0;

        // 🟢 NEW FEATURE: Loyalty Snapshot Metrics
        $pointsIssued = PointTransaction::whereDate('created_at', $today)
                            ->where('amount', '>', 0)->sum('amount');
        $pointsRedeemed = abs(PointTransaction::whereDate('created_at', $today)
                            ->where('amount', '<', 0)->sum('amount'));

        // Identify Top Customer Today
        $topCustomer = User::where('usertype', 'user')
            ->withCount(['orders' => function($q) use ($today) {
                $q->whereDate('created_at', $today);
            }])
            ->orderBy('orders_count', 'desc')
            ->first();

        // 3. Recent Orders & Stock Alerts
        $recentOrders = Order::with('user')->latest()->take(10)->get();
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
            'revenueChange',
            'pointsIssued',
            'pointsRedeemed',
            'topCustomer'
        ));
    }
}