<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\GoodsInController;
use App\Http\Controllers\Admin\AddStockController;
use App\Http\Controllers\Admin\GoodsInStatusController;
use App\Http\Controllers\Admin\AkunSalesController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PicsController;
use App\Http\Controllers\Admin\GoodsReceiptsController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\Dashboard\WarehouseDashboardController;
use App\Http\Controllers\Admin\SupplyOrdersController;
use App\Http\Controllers\Admin\DeliveryOrdersController;
use App\Http\Controllers\Guest\OrderController;
use App\Http\Controllers\Guest\KeranjangController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminPTController;
use App\Http\Controllers\Guest\ProductController;
use App\Http\Controllers\Admin\RequestOrderController;
use App\Http\Controllers\Admin\CustomPenawaranController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Admin\ImportExcelController;
use App\Http\Controllers\Admin\ImportStockExcelController;
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
    if (!Auth::check()) {
        return ['valid' => false];
    }

    $session = \App\Models\UserSession::where('user_id', Auth::id())->first();

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
    Route::resource('/customer', CustomerController::class);
    Route::get('/admin/customer/{id}/pics', [CustomerController::class, 'getPics'])->name('customer.pics');
});
// End of General

// General Affair
Route::middleware(['auth', 'role:General Affair'])->group(function () {
    Route::resource('/goods-in', GoodsInController::class);
    Route::resource('/add-stock', AddStockController::class);
    // Excel Import
    Route::resource('/import-excel', ImportExcelController::class);
    Route::post('/import-excel/import', [ImportExcelController::class, 'import'])->name('import-excel.import');
    // End Excel Import
    // Excel Stock Import
    Route::get('/import-stock-excel', [ImportStockExcelController::class, 'index'])->name('import-stock-excel.index');
    Route::post('/import-stock-excel/store', [ImportStockExcelController::class, 'store'])->name('import-stock-excel.store');
    Route::post('/import-stock-excel/import', [ImportStockExcelController::class, 'import'])->name('import-stock-excel.import');
    Route::get('/import-stock-excel/export', [ImportStockExcelController::class, 'export'])->name('import-stock-excel.export');
    // End Excel Stock Import
    Route::resource('/goods-in-status', GoodsInStatusController::class);
    Route::resource('/akun-sales', AkunSalesController::class);
    Route::resource('/pics', PicsController::class);
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/goods-receipts', [GoodsReceiptsController::class, 'index'])->name('goods-receipts.index');
    Route::get('/goods-receipts/{id}/logs', [GoodsReceiptsController::class, 'getLogs'])->name('goods-receipts.logs');
});
// End of General Affair

// Warehouse
route::middleware(['auth', 'role:Warehouse'])->group(function () {
    Route::post('/supply-orders/bulk/approve', [SupplyOrdersController::class, 'bulkApprove'])->name('supply-orders.bulk-approve');
    Route::post('/supply-orders/bulk/reject', [SupplyOrdersController::class, 'bulkReject'])->name('supply-orders.bulk-reject');
    Route::resource('/supply-orders', SupplyOrdersController::class);
    Route::post('/supply-orders/{id}/approve', [SupplyOrdersController::class, 'approve'])->name('supply-orders.approve');
    Route::post('/supply-orders/{id}/reject', [SupplyOrdersController::class, 'reject'])->name('supply-orders.reject');
    Route::resource('/delivery-orders', DeliveryOrdersController::class);
    Route::post('/delivery-orders/{id}/approve', [DeliveryOrdersController::class, 'approve'])->name('delivery-orders.approve');
    Route::post('/delivery-orders/{id}/reject', [DeliveryOrdersController::class, 'reject'])->name('delivery-orders.reject');
    Route::post('/delivery-orders/{id}/partial-approve', [DeliveryOrdersController::class, 'partialApprove'])->name('delivery-orders.partial-approve');
    Route::get('/delivery-orders/{id}/items', [DeliveryOrdersController::class, 'getItems'])->name('delivery-orders.items');
    Route::get('/delivery-orders/{id}/pdf', [DeliveryOrdersController::class, 'pdf'])->name('delivery-orders.pdf');
    Route::get('/admin/dashboard/warehouse/data', [WarehouseDashboardController::class, 'chartData'])
        ->name('dashboard.chart.data');
});
// End of Warehouse

// Supervisor (use auth only; controllers perform case-insensitive role checks)
Route::middleware(['auth'])->group(function () {

    // Support old URLs/names: map approved-orders and diskon-approved to the sentPenawaran controller
    Route::get('/approved-orders', [AdminPTController::class, 'sentPenawaran'])->name('admin.approved');
    Route::get('/diskon-approved', [AdminPTController::class, 'sentPenawaran'])->name('admin.diskon_approved');

    Route::get('/sent-penawaran', [AdminPTController::class, 'sentPenawaran'])->name('admin.sent_penawaran');
    // Supervisor approval route for Custom Penawaran (allow Supervisor to POST approve/reject)
    Route::post('/supervisor/custom-penawaran/{customPenawaran}/approval', [CustomPenawaranController::class, 'approval'])->name('admin.custom-penawaran.approval');
    // Supervisor view detail for custom penawaran (so Supervisor can access without Sales role)
    Route::get('/supervisor/custom-penawaran/{customPenawaran}', [CustomPenawaranController::class, 'show'])->name('admin.custom-penawaran.show');
    Route::get('/supervisor/custom-penawaran', [CustomPenawaranController::class, 'supervisorIndex'])->name('supervisor.custom-penawaran.index');
    Route::post('/supervisor/custom-penawaran/bulk-approval', [CustomPenawaranController::class, 'bulkApproval'])->name('supervisor.custom-penawaran.bulk-approval');
    Route::get('/orders/{id}', [AdminPTController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/approve', [AdminPTController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{id}/reject', [AdminPTController::class, 'reject'])->name('orders.reject');
    Route::get('/orders/history', [AdminPTController::class, 'history'])->name('orders.history');

    // Supervisor approval for Request Orders (from Sales)
    Route::post('/request-order/{requestOrder}/approve', [RequestOrderController::class, 'supervisorApprove'])->name('supervisor.request-order.approve');
    Route::post('/request-order/{requestOrder}/reject', [RequestOrderController::class, 'supervisorReject'])->name('supervisor.request-order.reject');
    // Supervisor view for Request Order detail (so Supervisor can view without Sales role)
    Route::get('/supervisor/request-order/{requestOrder}', [RequestOrderController::class, 'show'])->name('admin.request-order.show');
});
// End of Supervisor

// Sales
Route::middleware(['auth', 'role:Sales'])->group(function () {
    // Customer Routes for Sales (Consolidated to global customer.store)
    
    // Request Order Routes
    Route::get('/request-order', [RequestOrderController::class, 'index'])->name('sales.request-order.index');
    Route::get('/request-order/create', [RequestOrderController::class, 'create'])->name('sales.request-order.create');
    Route::post('/request-order', [RequestOrderController::class, 'store'])->name('sales.request-order.store');
    Route::get('/request-order/{requestOrder}', [RequestOrderController::class, 'show'])->name('sales.request-order.show');
    Route::get('/request-order/{requestOrder}/pdf', [RequestOrderController::class, 'pdf'])->name('sales.request-order.pdf');
    Route::get('/request-order/{requestOrder}/edit', [RequestOrderController::class, 'edit'])->name('sales.request-order.edit');
    Route::put('/request-order/{requestOrder}', [RequestOrderController::class, 'update'])->name('sales.request-order.update');
    Route::post('/request-order/{requestOrder}/status', [RequestOrderController::class, 'updateStatus'])->name('sales.request-order.status');
    Route::delete('/request-order/{requestOrder}', [RequestOrderController::class, 'destroy'])->name('sales.request-order.destroy');
    Route::post('/request-order/bulk/delete', [RequestOrderController::class, 'bulkDelete'])->name('sales.request-order.bulk-delete');
    Route::post('/request-order/bulk/send-to-warehouse', [RequestOrderController::class, 'bulkSendToWarehouse'])->name('sales.request-order.bulk-send-to-warehouse');
    Route::post('/request-order/{requestOrder}/sent-to-warehouse', [RequestOrderController::class, 'sentToWarehouse'])->name('sales.request-order.sent-to-warehouse');

    // Custom Penawaran Routes (Child of Request Order)
    Route::get('/custom-penawaran', [CustomPenawaranController::class, 'index'])->name('sales.custom-penawaran.index');
    Route::get('/custom-penawaran/create', [CustomPenawaranController::class, 'create'])->name('sales.custom-penawaran.create');
    Route::post('/custom-penawaran', [CustomPenawaranController::class, 'store'])->name('sales.custom-penawaran.store');
    Route::post('/custom-penawaran/bulk/delete', [CustomPenawaranController::class, 'bulkDelete'])->name('sales.custom-penawaran.bulk-delete');
    Route::post('/custom-penawaran/bulk/send-to-warehouse', [CustomPenawaranController::class, 'bulkSendToWarehouse'])->name('sales.custom-penawaran.bulk-send-to-warehouse');
    Route::get('/custom-penawaran/{customPenawaran}', [CustomPenawaranController::class, 'show'])->name('sales.custom-penawaran.show');
    Route::get('/custom-penawaran/{customPenawaran}/edit', [CustomPenawaranController::class, 'edit'])->name('sales.custom-penawaran.edit');
    Route::put('/custom-penawaran/{customPenawaran}', [CustomPenawaranController::class, 'update'])->name('sales.custom-penawaran.update');
    Route::delete('/custom-penawaran/{customPenawaran}', [CustomPenawaranController::class, 'destroy'])->name('sales.custom-penawaran.destroy');
    Route::get('/custom-penawaran/{customPenawaran}/pdf', [CustomPenawaranController::class, 'pdf'])->name('sales.custom-penawaran.pdf');
    Route::post('/custom-penawaran/{customPenawaran}/sent-to-warehouse', [CustomPenawaranController::class, 'sentToWarehouse'])->name('sales.custom-penawaran.sent-to-warehouse');

    // Sales Order Routes
    Route::get('/sales-order', [SalesOrderController::class, 'index'])->name('sales.sales-order.index');
    Route::get('/sales-order/search', [SalesOrderController::class, 'search'])->name('sales.sales-order.search');
    Route::get('/sales-order/penawaran-detail', [SalesOrderController::class, 'getPenawaranDetail'])->name('sales.sales-order.penawaran-detail');
    Route::get('/sales-order/create', [SalesOrderController::class, 'create'])->name('sales.sales-order.create');
    Route::post('/sales-order', [SalesOrderController::class, 'store'])->name('sales.sales-order.store');
    Route::get('/sales-order/{salesOrder}', [SalesOrderController::class, 'show'])->name('sales.sales-order.show');
    Route::get('/sales-order/{salesOrder}/edit', [SalesOrderController::class, 'edit'])->name('sales.sales-order.edit');
    Route::put('/sales-order/{salesOrder}', [SalesOrderController::class, 'update'])->name('sales.sales-order.update');
    Route::delete('/sales-order/{salesOrder}', [SalesOrderController::class, 'destroy'])->name('sales.sales-order.destroy');
});
// End of Sales

// === End Admin Routes === //
require __DIR__ . '/auth.php';
