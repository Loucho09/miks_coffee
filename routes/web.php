<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Barista\QueueController;
use App\Http\Controllers\Shop\CartController;

// Models
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Fetch 3 random active products with sizes for the "Signature Series" section
    $featured = Product::where('is_active', true)->with('sizes')->inRandomOrder()->take(3)->get();
    return view('welcome', compact('featured'));
})->name('welcome');

// Public Menu (Visible to Guests)
Route::get('/menu', function (Request $request) {
    $query = Product::with(['category', 'sizes'])->where('is_active', true);
    
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
    }
    
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }
    
    $products = $query->get();
    $categories = Category::all();
    return view('public_menu', compact('products', 'categories'));
})->name('menu.index');

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATED ROUTES (Customers, Baristas, Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        return view('dashboard', compact('recentOrders'));
    })->name('dashboard');

    /**
     * SHOPPING HOME: The internal catalog for ordering
     */
    Route::get('/home', function (Request $request) {
        // 🟢 CRITICAL FIX: We MUST include 'sizes' in the with() method
        $query = Product::with(['category', 'sizes'])->where('is_active', true);
        
        if ($request->filled('search')) $query->where('name', 'like', '%' . $request->search . '%');
        if ($request->filled('category')) $query->where('category_id', $request->category);
        
        $products = $query->get();
        $categories = Category::all();

        return view('cafe.index', compact('products', 'categories'));
    })->name('home');

    // Rewards System
    Route::get('/rewards', function () {
        return view('cafe.rewards');
    })->name('rewards.index');

    Route::post('/claim-reward', [OrderController::class, 'claimReward'])->name('rewards.claim');

    // Cart Management
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/add-to-cart', 'add')->name('cart.add'); 
        Route::delete('/remove-from-cart', 'remove')->name('cart.remove');
    });

    // Checkout & Order Tracking
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('barista')->name('barista.')->group(function () {
        Route::get('/queue', [QueueController::class, 'index'])->name('queue');
        Route::post('/update-status/{id}', [QueueController::class, 'updateStatus'])->name('update_status');
    });

    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        
        Route::controller(CustomerController::class)->prefix('customers')->name('customers.')->group(function () {
            Route::get('/', 'index')->name('index');           
            Route::get('/{id}', 'show')->name('show');         
            Route::put('/{id}/password', 'resetPassword')->name('reset_password');
        });

        Route::controller(MenuController::class)->prefix('menu')->name('menu.')->group(function () {
            Route::get('/', 'index')->name('index'); 
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
    });

});

require __DIR__.'/auth.php';