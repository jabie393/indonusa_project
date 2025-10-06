<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\GoodsInController;
use App\Http\Controllers\Admin\AddStockController;
use App\Http\Controllers\Admin\GoodsInStatusController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\SupplyOrdersController;
use App\Http\Controllers\Admin\DeliveryOrdersController;
use App\Http\Controllers\Guest\OrderController;
use App\Http\Controllers\Guest\KeranjangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPTController;
use App\Http\Controllers\Guest\ProductController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\RequestOrderController;


// === Guest Routes === //
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
// === End guest routes === //

// === Admin Routes === //
// General (for all admin roles)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [AdminPTController::class, 'dashboard'])->name('dashboard');
    Route::post('/check-kode-barang', [GeneralController::class, 'checkKodeBarang'])->name('check.kode.barang');
    Route::resource('/warehouse', WarehouseController::class);
});
// End of General

// Admin Supply
Route::middleware(['auth', 'role:admin_supply'])->group(function () {
    Route::resource('/goods-in', GoodsInController::class);
    Route::resource('/add-stock', AddStockController::class);
    Route::resource('/goods-in-status', GoodsInStatusController::class);
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
});
// End of Admin Supply

// Admin Warehouse
route::middleware(['auth', 'role:admin_warehouse'])->group(function () {
    Route::resource('/supply-orders', SupplyOrdersController::class);
    Route::post('/supply-orders/{id}/approve', [SupplyOrdersController::class, 'approve'])->name('supply-orders.approve');
    Route::post('/supply-orders/{id}/reject', [SupplyOrdersController::class, 'reject'])->name('supply-orders.reject');
    Route::resource('/delivery-orders', DeliveryOrdersController::class);
});
// End of Admin Warehouse

// Admin PT
// Route lain khusus admin_PT
Route::middleware(['auth', 'role:admin_PT'])->group(function () {
    
    Route::get('/incoming', [AdminPTController::class, 'incoming'])->name('admin.incoming');
    Route::get('/orders/{id}', [AdminPTController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/approve', [AdminPTController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{id}/reject', [AdminPTController::class, 'reject'])->name('orders.reject');
    Route::get('/orders/history', [AdminPTController::class, 'history'])->name('orders.history');
});

// Route khusus admin_sales
Route::middleware(['auth', 'role:admin_sales'])->group(function () {
    Route::get('/request-order', [RequestOrderController::class, 'create'])->name('requestorder.create');
    Route::post('/request-order', [RequestOrderController::class, 'store'])->name('requestorder.store');
    Route::get('/request-order/list', [RequestOrderController::class, 'index'])->name('requestorder.index');
    Route::get('/request-order/{order}', [RequestOrderController::class, 'show'])->name('requestorder.show');
});
// === End Admin Routes === //
require __DIR__ . '/auth.php';
