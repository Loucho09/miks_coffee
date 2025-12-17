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
        // FIX: Using 'total_price' to match your database
        $totalRevenue = Order::sum('total_price'); 
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('usertype', 'user')->count();

        // 2. Recent Orders & Low Stock
        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $lowStockItems = Product::where('stock_quantity', '<', 10)->get();

        // 3. Prepare Revenue Chart Data (Last 7 Days)
        $revenueData = [];
        $revenueLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueLabels[] = $date->format('M d'); 
            // FIX: Summing 'total_price'
            $revenueData[] = Order::whereDate('created_at', $date)->sum('total_price');
        }

        // 4. Prepare Category Chart Data
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
            'categoryData'
        ));
    }
}