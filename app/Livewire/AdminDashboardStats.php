<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardStats extends Component
{
    public function render()
    {
        // 1. Basic Stats
        $totalRevenue = Order::sum('total_amount');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('usertype', 'user')->count();

        // 2. Recent Orders & Stock
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $lowStockItems = Product::where('stock_quantity', '<', 10)->get();

        // 3. Revenue Chart Data
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueLabels[] = $date->format('M d');
            $revenueData[] = Order::whereDate('created_at', $date)->sum('total_amount');
        }

        // 4. Category Chart Data
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

        return view('livewire.admin-dashboard-stats', compact(
            'totalRevenue', 'totalOrders', 'pendingOrders', 'totalCustomers',
            'recentOrders', 'lowStockItems',
            'revenueLabels', 'revenueData', 'categoryLabels', 'categoryData'
        ));
    }
}