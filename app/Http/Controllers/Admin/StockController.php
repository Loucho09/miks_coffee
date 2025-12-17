<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display the stock inventory.
     */
    public function index()
    {
        // Fetch products ordered by lowest stock first
        $stocks = Product::with('category')
                        ->orderBy('stock_quantity', 'asc')
                        ->get();

        // Calculate Stats
        $lowStockCount = $stocks->where('stock_quantity', '<', 10)->where('stock_quantity', '>', 0)->count();
        $outOfStockCount = $stocks->where('stock_quantity', 0)->count();
        $totalItems = $stocks->count();

        return view('admin.stock.index', compact('stocks', 'lowStockCount', 'outOfStockCount', 'totalItems'));
    }
}