<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShopController;

// ... cart routes ...
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


//di ko rin alam
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');


// Public "Shop" Route (no auth required)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');


// Welcome route
Route::get('/', function () {
    return view('welcome');
});

// Profile routes and Category routes inside one auth group
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Product Management (CRUD)
    Route::resource('products', ProductController::class);

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Shopping Cart Routes (can be public or protected depending on your design)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
