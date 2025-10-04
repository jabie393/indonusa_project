<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\GoodsInController;
use App\Http\Controllers\Admin\AddStockController;
use App\Http\Controllers\Admin\GoodsInStatusController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\SupplyOrdersController;
use App\Http\Controllers\Guest\OrderController;
use App\Http\Controllers\Guest\KeranjangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPTController;
use App\Http\Controllers\Guest\ProductController;
use App\Http\Controllers\Admin\HistoryController;

// Guest Routes
Route::get('/', function () {
    return view('guest.welcome');
});

// Route user untuk lihat daftar barang
Route::get('/order', [OrderController::class, 'index'])->name('order');

Route::get('files/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);

    if (!\Illuminate\Support\Facades\File::exists($file)) {
        abort(404);
    }

    return response()->file($file);
})->where('path', '.*');

// Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/product/{id}', [ProductController::class, 'barang'])->name('product.barang');

Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/kurangi/{id}', [KeranjangController::class, 'kurangi'])->name('keranjang.kurangi');
Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::post('/keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');

// End guest routes

// Admin Routes
// General (for all admin roles)
Route::post('/check-kode-barang', [GeneralController::class, 'checkKodeBarang'])->name('check.kode.barang');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// End of General

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Supply
Route::resource('/goods-in', GoodsInController::class);
Route::resource('/add-stock', AddStockController::class);
Route::resource('/goods-in-status', GoodsInStatusController::class);
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
// End of Admin Supply

Route::resource('/warehouse', WarehouseController::class);

// Admin Warehouse
Route::resource('/supply-orders', SupplyOrdersController::class);
Route::post('/supply-orders/{id}/approve', [SupplyOrdersController::class, 'approve'])->name('supply-orders.approve');
Route::post('/supply-orders/{id}/reject', [SupplyOrdersController::class, 'reject'])->name('supply-orders.reject');
// End of Admin Warehouse

// End Admin Routes


// Admin PT
// Dashboard untuk SEMUA admin (auth saja, tanpa filter role)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminPTController::class, 'dashboard'])->name('dashboard');
});

// Route lain khusus admin_PT
Route::middleware(['auth', 'role:admin_PT'])->group(function () {
    Route::get('/orders/incoming', [AdminPTController::class, 'incoming'])->name('orders.incoming');
    Route::get('/orders/{id}', [AdminPTController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/approve', [AdminPTController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{id}/reject', [AdminPTController::class, 'reject'])->name('orders.reject');
    Route::get('/orders/history', [AdminPTController::class, 'history'])->name('orders.history');
});

require __DIR__ . '/auth.php';
