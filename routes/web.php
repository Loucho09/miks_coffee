<?php

use App\Http\Controllers\ProfileController;
use App\Models\Product; // <--- THIS must be at the top!
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $products = Product::all(); // Now this will work
    return view('dashboard', ['products' => $products]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   
    
    // Add this at the VERY TOP

// ... existing dashboard route ...

Route::middleware('auth')->group(function () {
    // Add these two lines:
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    
    // ... existing profile routes ...
});
});

require __DIR__.'/auth.php';