<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\GoodsInController;
use App\Http\Controllers\Admin\AddStockController;
use App\Http\Controllers\Admin\GoodsInStatusController;
use App\Http\Controllers\Admin\AkunSalesController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PicsController;
use App\Http\Controllers\Admin\CustomerController2;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\SupplyOrdersController;
use App\Http\Controllers\Admin\DeliveryOrdersController;
use App\Http\Controllers\Guest\OrderController;
use App\Http\Controllers\Guest\KeranjangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPTController;
use App\Http\Controllers\Guest\ProductController;
use App\Http\Controllers\Admin\RequestOrderController;
use App\Http\Controllers\Admin\CustomPenawaranController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Auth\ConfirmLoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


// === Guest Routes === //
Route::get('/', function () {
    return view('guest.welcome');
});

Route::get('/penawaran', function () {
    return view('admin.pdf.penawaran');
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
    Route::get('/get-stock/{kode}', [GeneralController::class, 'getStock']);
    Route::post('/check-email', [GeneralController::class, 'checkEmail'])->name('check.email');
    Route::post('/check-kode-barang', [GeneralController::class, 'checkKodeBarang'])->name('check.kode.barang');
    Route::resource('/warehouse', WarehouseController::class);
});
// End of General

// General Affair
Route::middleware(['auth', 'role:General Affair'])->group(function () {
    Route::resource('/goods-in', GoodsInController::class);
    Route::resource('/add-stock', AddStockController::class);
    Route::resource('/goods-in-status', GoodsInStatusController::class);
    Route::resource('/akun-sales', AkunSalesController::class);
    Route::resource('/customer', CustomerController::class);
    Route::resource('/pics', PicsController::class);
    Route::get('/admin/customer/{id}/pics', [CustomerController::class, 'getPics'])->name('customer.pics');
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

    // Supervisor approval for Request Orders (from Sales)
    Route::post('/request-order/{requestOrder}/approve', [RequestOrderController::class, 'supervisorApprove'])->name('supervisor.request-order.approve');
    Route::post('/request-order/{requestOrder}/reject', [RequestOrderController::class, 'supervisorReject'])->name('supervisor.request-order.reject');
});
// End of Supervisor

// Sales
Route::middleware(['auth', 'role:Sales'])->group(function () {
    // Customer Routes
    Route::get('/customer2', [CustomerController2::class, 'index'])->name('sales.customer.index');
    Route::get('/customer2/create', [CustomerController2::class, 'create'])->name('sales.customer.create');
    Route::post('/customer2', [CustomerController2::class, 'store'])->name('sales.customer.store');
    Route::get('/customer2/{customer}', [CustomerController2::class, 'show'])->name('sales.customer.show');
    Route::get('/customer2/{customer}/edit', [CustomerController2::class, 'edit'])->name('sales.customer.edit');
    Route::put('/customer2/{customer}', [CustomerController2::class, 'update'])->name('sales.customer.update');
    Route::delete('/customer2/{customer}', [CustomerController2::class, 'destroy'])->name('sales.customer.destroy');
    Route::get('/customer2/api/search', [CustomerController2::class, 'search'])->name('sales.customer.search');

    // Request Order Routes
    Route::get('/request-order', [RequestOrderController::class, 'index'])->name('sales.request-order.index');
    Route::get('/request-order/create', [RequestOrderController::class, 'create'])->name('sales.request-order.create');
    Route::post('/request-order', [RequestOrderController::class, 'store'])->name('sales.request-order.store');
    Route::get('/request-order/{requestOrder}', [RequestOrderController::class, 'show'])->name('sales.request-order.show');
    Route::get('/request-order/{requestOrder}/pdf', [RequestOrderController::class, 'pdf'])->name('sales.request-order.pdf');
    Route::get('/request-order/{requestOrder}/edit', [RequestOrderController::class, 'edit'])->name('sales.request-order.edit');
    Route::put('/request-order/{requestOrder}', [RequestOrderController::class, 'update'])->name('sales.request-order.update');
    Route::post('/request-order/{requestOrder}/convert', [RequestOrderController::class, 'convertToSalesOrder'])->name('sales.request-order.convert');

    // Custom Penawaran Routes (Child of Request Order)
    Route::get('/custom-penawaran', [CustomPenawaranController::class, 'index'])->name('sales.custom-penawaran.index');
    Route::get('/custom-penawaran/create', [CustomPenawaranController::class, 'create'])->name('sales.custom-penawaran.create');
    Route::post('/custom-penawaran', [CustomPenawaranController::class, 'store'])->name('sales.custom-penawaran.store');
    Route::get('/custom-penawaran/{customPenawaran}', [CustomPenawaranController::class, 'show'])->name('sales.custom-penawaran.show');
    Route::get('/custom-penawaran/{customPenawaran}/edit', [CustomPenawaranController::class, 'edit'])->name('sales.custom-penawaran.edit');
    Route::put('/custom-penawaran/{customPenawaran}', [CustomPenawaranController::class, 'update'])->name('sales.custom-penawaran.update');
    Route::delete('/custom-penawaran/{customPenawaran}', [CustomPenawaranController::class, 'destroy'])->name('sales.custom-penawaran.destroy');
    Route::get('/custom-penawaran/{customPenawaran}/pdf', [CustomPenawaranController::class, 'pdf'])->name('sales.custom-penawaran.pdf');

    // Saless Order Routes
    Route::get('/sales-order', [SalesOrderController::class, 'index'])->name('sales.sales-order.index');
    Route::get('/sales-order/{salesOrder}', [SalesOrderController::class, 'show'])->name('sales.sales-order.show');
    Route::put('/sales-order/{salesOrder}/status', [SalesOrderController::class, 'updateStatus'])->name('sales.sales-order.status');
    Route::put('/sales-order-item/{item}/delivered', [SalesOrderController::class, 'updateDeliveredQty'])->name('sales.sales-order-item.delivered');
    Route::post('/sales-order/{salesOrder}/cancel', [SalesOrderController::class, 'cancel'])->name('sales.sales-order.cancel');
});
// End of Sales
// === End Admin Routes === //
require __DIR__ . '/auth.php';
