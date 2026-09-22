<?php

use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController; // <-- Tambahkan ini

// Catalog Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes (Harus Login)
Route::middleware('auth')->group(function () {
    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout & Order Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/pay/{id}', [CheckoutController::class, 'payNow'])->name('checkout.pay');

   Route::middleware(['auth'])->group(function () {
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});
// Rute khusus untuk Halaman Dashboard Admin
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

});