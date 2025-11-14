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
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmLoginController;



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
// Session confirmation routes //
Route::get('/confirm-login', [ConfirmLoginController::class, 'show'])->name('confirm.login');
Route::post('/confirm-login/continue', [ConfirmLoginController::class, 'continue'])->name('auth.continue-session');
Route::post('/confirm-login/cancel', [ConfirmLoginController::class, 'cancel'])->name('auth.cancel-login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/session/check', function () {
    if (!auth()->check()) {
        return ['valid' => false];
    }

    $session = \App\Models\UserSession::where('user_id', auth()->id())->first();

    return [
        'valid' => $session && $session->session_id === session()->getId()
    ];
});
// End of Session confirmation routes //

// General (for all admin roles)
Route::middleware(['auth'])->group(function () {

    // routes existing
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [GeneralController::class, 'dashboard'])->name('dashboard');
    Route::post('/check-kode-barang', [GeneralController::class, 'checkKodeBarang'])->name('check.kode.barang');
    Route::resource('/warehouse', WarehouseController::class);
});
// End of General

// General Affair
Route::middleware(['auth', 'role:General Affair'])->group(function () {
    Route::resource('/goods-in', GoodsInController::class);
    Route::resource('/add-stock', AddStockController::class);
    Route::resource('/goods-in-status', GoodsInStatusController::class);
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
});
// End of General Affair

// Warehouse
route::middleware(['auth', 'role:Warehouse'])->group(function () {
    Route::resource('/supply-orders', SupplyOrdersController::class);
    Route::post('/supply-orders/{id}/approve', [SupplyOrdersController::class, 'approve'])->name('supply-orders.approve');
    Route::post('/supply-orders/{id}/reject', [SupplyOrdersController::class, 'reject'])->name('supply-orders.reject');
    Route::resource('/delivery-orders', DeliveryOrdersController::class);
});
// End of Warehouse

// Supervisor
Route::middleware(['auth', 'role:Supervisor'])->group(function () {

    Route::get('/incoming', [AdminPTController::class, 'incoming'])->name('admin.incoming');
    Route::get('/approved-orders', [AdminPTController::class, 'approved'])->name('admin.approved');
    Route::get('/orders/{id}', [AdminPTController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/approve', [AdminPTController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{id}/reject', [AdminPTController::class, 'reject'])->name('orders.reject');
    Route::get('/orders/history', [AdminPTController::class, 'history'])->name('orders.history');
});
// End of Supervisor

// Sales
Route::middleware(['auth', 'role:Sales'])->group(function () {
    Route::get('/request-order', [RequestOrderController::class, 'create'])->name('requestorder.create');
    Route::post('/request-order', [RequestOrderController::class, 'store'])->name('requestorder.store');
    // Sales-facing list (Sales Order page)
    Route::get('/sales-order', [RequestOrderController::class, 'salesIndex'])->name('sales.order');
    Route::get('/request-order/list', [RequestOrderController::class, 'index'])->name('requestorder.index');
    Route::get('/request-order/{order}', [RequestOrderController::class, 'show'])->name('requestorder.show');
});
// End of Sales
// === End Admin Routes === //
require __DIR__ . '/auth.php';
