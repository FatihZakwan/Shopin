<?php

use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\TripayCallbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa Diakses Publik / Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Guest Routes (Khusus Pengguna yang Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Callback Webhook Tripay (Tanpa CSRF/Auth)
|--------------------------------------------------------------------------
*/
Route::post('/api/tripay/callback', [TripayCallbackController::class, 'handle'])->name('tripay.callback');

/*
|--------------------------------------------------------------------------
| Protected Routes (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Auth Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Return URL dari Tripay (Tanpa parameter {id} agar sesuai dengan TripayService)
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/pay/{id}', [CheckoutController::class, 'payNow'])->name('checkout.pay');

<<<<<<< HEAD
    // Orders Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Panel Admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD Produk Admin
        Route::resource('products', AdminProductController::class);
    });
});

// Route Publik / User
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route khusus ADMIN & SUPER ADMIN (Kelola Produk)
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminProductController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
});

// Route khusus SUPER ADMIN (Kelola Pengguna & Role)
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::patch('/users/{id}/role', [SuperAdminController::class, 'updateRole'])->name('users.updateRole');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::patch('/users/{id}/role', [SuperAdminController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{id}', [SuperAdminController::class, 'destroy'])->name('users.destroy'); // Route baru
});

Route::get('/test-email', function () {
    try {
        Mail::raw('Halo! Ini adalah email uji coba dari aplikasi SHOPIN Laravel.', function ($message) {
            $message->to('email_tujuan_anda@gmail.com') // Ganti dengan alamat Gmail tujuan
                    ->subject('Uji Coba Pengiriman Email SHOPIN');
        });

        return 'Email berhasil dikirim! Silakan periksa kotak masuk/spam Gmail Anda.';
    } catch (\Exception $e) {
        return 'Gagal mengirim email: ' . $e->getMessage();
    }
=======
   Route::middleware(['auth'])->group(function () {
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});
// Rute khusus untuk Halaman Dashboard Admin
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

>>>>>>> 6b2c316134978983c0a7ef62f70651855ad77a04
});