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
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupportController; 
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\HomeController;

// Models
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

/* |--------------------------------------------------------------------------
   | 1. PUBLIC ROUTES
   | -------------------------------------------------------------------------- */

Route::get('/', [HomeController::class, 'index'])->name('welcome');

Route::get('/menu', function (Request $request) {
    $query = Product::with(['category', 'sizes'])->where('is_active', true);
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('category')) $query->where('category_id', $request->category);
    $products = $query->get();
    $categories = Category::all();
    return view('public_menu', compact('products', 'categories'));
})->name('menu.index');

Route::get('/support', [SupportController::class, 'index'])->name('support.index');
Route::post('/support/send', [SupportController::class, 'send'])->name('support.send');

Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms', 'legal.terms')->name('terms');

/* |--------------------------------------------------------------------------
   | 2. AUTHENTICATED ROUTES
   | -------------------------------------------------------------------------- */
Route::middleware(['auth', 'verified'])->group(function () {

    /* --- CUSTOMER ONLY FEATURES --- */
    // Note: 'role:customer' middleware should allow Admin bypass internally
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/dashboard', function () {
            /** @var User $user */
            $user = Auth::user();
            $user->updateStreak();
            $recentOrders = Order::where('user_id', $user->id)->with(['items.product', 'items.review', 'items.order'])->latest()->take(5)->get();
            $supportTickets = \App\Models\SupportTicket::where('user_id', $user->id)->with(['replies.user'])->latest()->take(5)->get();
            return view('dashboard', compact('recentOrders', 'supportTickets'));
        })->name('dashboard');

        Route::get('/home', function (Request $request) {
            $query = Product::with(['category', 'sizes'])->where('is_active', true);
            if ($request->filled('search')) $query->where('name', 'like', '%' . $request->search . '%');
            if ($request->filled('category')) $query->where('category_id', $request->category);
            $products = $query->get();
            $categories = Category::all();
            return view('cafe.index', compact('products', 'categories'));
        })->name('home');

        Route::get('/rewards', function () {
            /** @var User $user */
            $user = Auth::user();
            $points = $user->points ?? 0; 
            $goal = ($points >= 200) ? 500 : (($points >= 100) ? 200 : 100);
            return view('cafe.rewards', compact('user', 'points', 'goal'));
        })->name('rewards.index');

        Route::post('/claim-reward', [OrderController::class, 'claimReward'])->name('rewards.claim');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

        Route::controller(CartController::class)->group(function () {
            Route::get('/cart', 'index')->name('cart.index');
            Route::post('/add-to-cart', 'add')->name('cart.add'); 
            Route::delete('/remove-from-cart', 'remove')->name('cart.remove');
        });

        Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });

    /* --- COMMON AUTH ROUTES --- */
    Route::get('/orders/{id}/receipt', function ($id) {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        /** @var User $user */
        $user = Auth::user();
        if ($order->user_id !== Auth::id() && $user->usertype !== 'admin') abort(403);
        $pts = $order->user->points ?? 0;
        $tier = $pts >= 500 ? 'Gold' : ($pts >= 200 ? 'Silver' : 'Bronze');
        return view('emails.order_receipt', compact('order', 'tier'));
    })->name('orders.receipt');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/data-report', [ProfileController::class, 'showDataReport'])->name('profile.data_report');
    Route::post('/profile/export', [ProfileController::class, 'exportData'])->name('profile.export');
    Route::get('/settings-privacy', [ProfileController::class, 'settings'])->name('profile.settings');

    /* --- BARISTA ONLY FEATURES --- */
    Route::middleware(['role:barista'])->prefix('barista')->name('barista.')->group(function () {
        Route::get('/queue', [QueueController::class, 'index'])->name('queue');
        Route::post('/update-status/{id}', [QueueController::class, 'updateStatus'])->name('update_status');
        Route::get('/active-orders', [QueueController::class, 'getActiveOrders'])->name('active_orders');
        Route::get('/active-redemptions', [QueueController::class, 'getActiveRedemptions'])->name('active_redemptions');
        Route::post('/redemption/{id}/fulfill', [QueueController::class, 'fulfillRedemption'])->name('redemption.fulfill');
    });

    /* --- ADMIN ONLY FEATURES --- */
    Route::middleware(['admin', 'admin.single_session'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders/export', ExportController::class)->name('orders.export');
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        
        Route::controller(CustomerController::class)->prefix('customers')->name('customers.')->group(function () {
            Route::get('/', 'index')->name('index');           
            Route::get('/{id}', 'show')->name('show');         
            Route::put('/{id}/password', 'resetPassword')->name('reset_password');
        });

        Route::controller(SupportController::class)->prefix('support-requests')->name('support.')->group(function () {
            Route::get('/', 'adminIndex')->name('admin_index');
            Route::post('/{id}/resolve', 'resolve')->name('resolve');
            Route::post('/{ticket}/reply', 'reply')->name('reply');
            Route::get('/active-tickets', 'getActiveTickets')->name('active_tickets');
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