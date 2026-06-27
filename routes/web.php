<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WarungController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PembeliController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\RatingController;




// ===== AUTH & LOGOUT =====
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ===== PUBLIC =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/warung/{warung}', [HomeController::class, 'warung'])->name('warung.show');
Route::get('/warungs', [WarungController::class, 'index'])->name('warungs.index');

// ===== PROTECTED =====
Route::middleware(['auth'])->group(function () {

    // Dashboard Role Redirect
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'penjual' => redirect()->route('warungs.dashboard'),
            'pembeli' => redirect()->route('home'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{menu}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/decrease/{menu}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::delete('/cart/remove/{menu}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/buy-now', [CheckoutController::class, 'buyNow'])->name('checkout.buy-now');
    Route::post('/checkout/process-now', [CheckoutController::class, 'processNow'])->name('checkout.process-now');
    Route::get('/checkout/buy-now', function () {
        return redirect()->route('home')->with('error', 'Akses tidak valid.');
    })->name('checkout.buy-now.get');

    // Riwayat Pesanan Pembeli
    Route::get('/pesanan-saya', [PembeliController::class, 'orders'])->name('pembeli.orders');
    Route::post('/pesanan/{order}/rating', [RatingController::class, 'store'])->name('orders.rating');

    // ===== PENJUAL =====
    Route::prefix('warungs')->name('warungs.')->group(function () {
        Route::get('/dashboard', [WarungController::class, 'dashboard'])->name('dashboard');

        // Warung
        Route::get('/create', [WarungController::class, 'create'])->name('create');
        Route::post('/store', [WarungController::class, 'store'])->name('store');
        Route::post('/toggle', [WarungController::class, 'toggleStatus'])->name('toggle');
        Route::delete('/menu/image/{image}', [MenuController::class, 'destroyImage'])->name('menu.image.destroy');

        // Pesanan
        Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan');
        Route::patch('/pesanan/{order}/status', [OrderController::class, 'updateStatus'])->name('pesanan.status');

        // Profil
        Route::get('/profil', [WarungController::class, 'profil'])->name('profil');
        Route::put('/profil', [WarungController::class, 'updateProfil'])->name('profil.update');
        Route::delete('/profil', [WarungController::class, 'destroyWarung'])->name('profil.destroy');

        // Menu
        Route::get('/menu', [MenuController::class, 'index'])->name('menu');
        Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
        Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
    });

    // ===== ADMIN =====
// ===== ADMIN =====
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Verifikasi Warung
        Route::get('/warungs', [AdminController::class, 'warungs'])->name('warungs');
        Route::post('/warungs/{id}/verify', [AdminController::class, 'verify'])->name('warungs.verify');
        Route::post('/warungs/{id}/reject', [AdminController::class, 'reject'])->name('warungs.reject');

        // Manajemen User
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Transaksi
        Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');

        // Pengaturan
        Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('pengaturan');
        Route::post('/pengaturan', [AdminController::class, 'updatePengaturan'])->name('pengaturan.update');
    });
});

require __DIR__ . '/auth.php';