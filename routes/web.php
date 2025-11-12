<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;

// ======================
// PUBLIC ROUTES
// ======================
Route::get('/', function () {
    return view('welcome');
});

// Public "Shop" Route
Route::get('/shop', [ProductController::class, 'shop'])->name('shop.index');

// Public Cart Routes (view, remove, clear)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// ======================
// AUTHENTICATED USER ROUTES
// ======================
Route::middleware('auth')->group(function () {

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Product & Category Management
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    // Cart Add Route
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// ======================
// ADMIN ROUTES
// ======================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    
// Admin Dashboard (URL: /admin/dashboard)
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Product Management (URL: /admin/products)
        Route::resource('products', ProductController::class);

        // Category Management (URL: /admin/categories)
        Route::resource('categories', CategoryController::class);

        // Order Management (URL: /admin/orders)
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    });

// ======================
// AUTH ROUTES (Breeze/Fortify)
// ======================
require __DIR__ . '/auth.php';
