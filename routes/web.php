<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\GoodsInController;
use App\Http\Controllers\Admin\AddStockController;
use App\Http\Controllers\Admin\GoodsInStatusController;
use App\Http\Controllers\Admin\AkunSalesController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PicsController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\Dashboard\WarehouseDashboardController;
use App\Http\Controllers\Admin\SupplyOrdersController;
use App\Http\Controllers\Admin\DeliveryOrdersController;
use App\Http\Controllers\Guest\OrderController;
use App\Http\Controllers\Guest\KeranjangController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminPTController;
use App\Http\Controllers\Admin\SupervisorController;
use App\Http\Controllers\Guest\ProductController;
use App\Http\Controllers\Admin\RequestOrderController;
use App\Http\Controllers\Admin\CustomPenawaranController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Admin\ImportExcelController;
use App\Http\Controllers\Admin\ImportStockExcelController;
use App\Http\Controllers\Auth\ConfirmLoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Guest\GuestCatalogController;

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

Route::get('/catalogs', [GuestCatalogController::class, 'index'])->name('catalogs');


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
    Route::get('/warehouse/{id}/logs', [WarehouseController::class, 'getLogs'])->name('warehouse.logs');
    Route::resource('/customer', CustomerController::class);
    Route::patch('/customer/{id}/status', [CustomerController::class, 'updateStatus'])->name('customer.status.update');
    Route::get('/admin/customer/{id}/pics', [CustomerController::class, 'getPics'])->name('customer.pics');
});
// End of General

// General Affair
Route::middleware(['auth', 'role:General Affair'])->group(function () {
    // Sales Order (read-only untuk GA)
    Route::get('/sales-order-invoice', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'index'])->name('sales-order-invoice.index');
    Route::get('/sales-order-invoice/export', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'exportGaSalesOrders'])->name('sales-order-invoice.export');
    Route::get('/sales-order-invoice/search', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'search'])->name('sales-order-invoice.search');
    Route::get('/invoice/{id}', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'showInvoice'])->name('invoice.index');
    Route::get('/sales-order-invoice/{id}/invoice-history', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'getInvoiceHistory'])->name('sales-order-invoice.invoice-history');
    Route::post('/invoice/{id}/excel', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'downloadInvoiceExcel'])->name('invoice.excel');
    Route::get('/invoice/batch/{batchId}', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'showBatchInvoice'])->name('invoice.batch.invoice');
    Route::post('/invoice/batch/{batchId}/excel', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'downloadBatchInvoiceExcel'])->name('invoice.batch.excel');
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

    // Dashboard Chart Data for GA
    Route::get('/admin/dashboard/general-affair/data', [\App\Http\Controllers\Admin\Dashboard\GeneralAffairDashboardController::class, 'chartData'])
        ->name('dashboard.general-affair.chart.data');

    // Catalog Manager
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::post('/catalog', [CatalogController::class, 'store'])->name('catalog.store');
    Route::post('/catalog/upload', [CatalogController::class, 'upload'])->name('catalog.upload');
    Route::get('/catalog/{id}/edit', [CatalogController::class, 'edit'])->name('catalog.edit');
    Route::put('/catalog/{id}', [CatalogController::class, 'update'])->name('catalog.update');
    Route::delete('/catalog/{id}', [CatalogController::class, 'destroy'])->name('catalog.destroy');
});
// End of General Affair

route::middleware(['auth', 'role:Warehouse'])->group(function () {
    Route::post('/supply-orders/bulk/approve', [SupplyOrdersController::class, 'bulkApprove'])->name('supply-orders.bulk-approve');
    Route::post('/supply-orders/bulk/reject', [SupplyOrdersController::class, 'bulkReject'])->name('supply-orders.bulk-reject');
    Route::resource('/supply-orders', SupplyOrdersController::class);
    Route::post('/supply-orders/{id}/approve', [SupplyOrdersController::class, 'approve'])->name('supply-orders.approve');
    Route::post('/supply-orders/{id}/reject', [SupplyOrdersController::class, 'reject'])->name('supply-orders.reject');
    Route::resource('/delivery-orders', DeliveryOrdersController::class)->except(['index']);
    Route::post('/delivery-orders/{id}/approve', [DeliveryOrdersController::class, 'approve'])->name('delivery-orders.approve');
    Route::post('/delivery-orders/{id}/reject', [DeliveryOrdersController::class, 'reject'])->name('delivery-orders.reject');
    Route::post('/delivery-orders/{id}/partial-approve', [DeliveryOrdersController::class, 'partialApprove'])->name('delivery-orders.partial-approve');
    Route::get('/admin/dashboard/warehouse/data', [WarehouseDashboardController::class, 'chartData'])
        ->name('dashboard.chart.data');
});
// End of Warehouse

// Shared between Warehouse and Sales
Route::middleware(['auth', 'role:Warehouse,Sales'])->group(function () {
    Route::get('/delivery-orders', [\App\Http\Controllers\Admin\DeliveryOrdersController::class, 'index'])->name('delivery-orders.index');
    Route::get('/delivery-orders/{id}/items', [\App\Http\Controllers\Admin\DeliveryOrdersController::class, 'getItems'])->name('delivery-orders.items');
    Route::get('/delivery-orders/{id}/history', [\App\Http\Controllers\Admin\DeliveryOrdersController::class, 'getHistory'])->name('delivery-orders.history');
    Route::get('/delivery-orders/{id}/pdf', [\App\Http\Controllers\Admin\DeliveryOrdersController::class, 'pdf'])->name('delivery-orders.pdf');
    Route::get('/delivery-orders/batch/{batchId}/pdf', [\App\Http\Controllers\Admin\DeliveryOrdersController::class, 'printBatch'])->name('delivery-orders.batch-pdf');
    Route::get('/delivery-orders/batch/{batchId}/invoice', [\App\Http\Controllers\Admin\SalesOrderController::class, 'showBatchInvoice'])->name('delivery-orders.batch.invoice');
    Route::post('/delivery-orders/batch/{batchId}/invoice-excel', [\App\Http\Controllers\Admin\SalesOrderController::class, 'downloadBatchInvoiceExcel'])->name('delivery-orders.batch.invoice-excel');
});

// Supervisor (use auth only; controllers perform case-insensitive role checks)
Route::middleware(['auth'])->group(function () {

    // Support old URLs/names: map approved-orders and diskon-approved to the sentPenawaran controller
    Route::get('/approved-orders', [AdminPTController::class, 'sentPenawaran'])->name('admin.approved');
    Route::get('/diskon-approved', [AdminPTController::class, 'sentPenawaran'])->name('admin.diskon_approved');

    Route::get('/quotation-approval', [AdminPTController::class, 'sentPenawaran'])->name('admin.quotation_approval');
    // Supervisor approval route for Custom Quotation (allow Supervisor to POST approve/reject)
    Route::post('/custom-quotation-approval/{customPenawaran}/approval', [CustomPenawaranController::class, 'approval'])->name('admin.custom-quotation-approval.approval');
    // Supervisor view detail for custom quotation (so Supervisor can access without Sales role)
    Route::get('/custom-quotation-approval/{customPenawaran}', [CustomPenawaranController::class, 'show'])->name('admin.custom-quotation-approval.show');
    Route::get('/custom-quotation-approval', [CustomPenawaranController::class, 'supervisorIndex'])->name('supervisor.custom-quotation-approval.index');
    Route::post('/custom-quotation-approval/bulk-approval', [CustomPenawaranController::class, 'bulkApproval'])->name('supervisor.custom-quotation-approval.bulk-approval');
    Route::get('/orders/{id}', [AdminPTController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/approve', [AdminPTController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{id}/reject', [AdminPTController::class, 'reject'])->name('orders.reject');
    Route::get('/orders/history', [AdminPTController::class, 'history'])->name('orders.history');

    // Supervisor approval for Request Orders (from Sales)
    Route::post('/quotation/{requestOrder}/approve', [RequestOrderController::class, 'supervisorApprove'])->name('supervisor.quotation.approve');
    Route::post('/quotation/{requestOrder}/reject', [RequestOrderController::class, 'supervisorReject'])->name('supervisor.quotation.reject');
    // Supervisor/Sales view for Request Order detail (accessible by both)
    Route::get('/quotation/{requestOrder}', [RequestOrderController::class, 'show'])->name('sales.quotation.show');
    Route::get('/quotation/{requestOrder}/pdf', [RequestOrderController::class, 'pdf'])->name('sales.quotation.pdf');
    Route::get('/custom-quotation-approval/{customPenawaran}/pdf', [CustomPenawaranController::class, 'pdf'])->name('admin.custom-quotation-approval.pdf');

    // Supervisor Dashboard
    Route::get('/admin/dashboard/supervisor', [\App\Http\Controllers\Admin\Dashboard\SupervisorDashboardController::class, 'dashboard'])
        ->name('dashboard.supervisor');
    Route::get('/admin/dashboard/supervisor/data', [\App\Http\Controllers\Admin\Dashboard\SupervisorDashboardController::class, 'chartData'])
        ->name('dashboard.supervisor.chart.data');
    Route::get('/admin/dashboard/supervisor/export-performance', [\App\Http\Controllers\Admin\Dashboard\SupervisorDashboardController::class, 'exportPerformance'])
        ->name('dashboard.supervisor.export.performance');
    Route::get('/admin/dashboard/supervisor/export-quotations', [\App\Http\Controllers\Admin\Dashboard\SupervisorDashboardController::class, 'exportQuotations'])
        ->name('dashboard.supervisor.export.quotations');
    Route::get('/admin/dashboard/supervisor/export-semua-barang', [\App\Http\Controllers\Admin\Dashboard\SupervisorDashboardController::class, 'exportSemuaBarang'])
        ->name('dashboard.supervisor.export.semua-barang');


    // Supervisor History (all approval processes)
    Route::get('/supervisor/history', [SupervisorController::class, 'history'])->name('supervisor.history');
});
// End of Supervisor

// Sales
Route::middleware(['auth', 'role:Sales'])->group(function () {
    // Customer Routes for Sales (Consolidated to global customer.store)

    // Quotation Routes
    Route::get('/quotation', [RequestOrderController::class, 'index'])->name('sales.quotation.index');
    Route::get('/quotation/create', [RequestOrderController::class, 'create'])->name('sales.quotation.create');
    Route::post('/quotation', [RequestOrderController::class, 'store'])->name('sales.quotation.store');

    Route::get('/quotation/{requestOrder}/edit', [RequestOrderController::class, 'edit'])->name('sales.quotation.edit');
    Route::put('/quotation/{requestOrder}', [RequestOrderController::class, 'update'])->name('sales.quotation.update');
    Route::post('/quotation/{requestOrder}/status', [RequestOrderController::class, 'updateStatus'])->name('sales.quotation.status');
    Route::delete('/quotation/{requestOrder}', [RequestOrderController::class, 'destroy'])->name('sales.quotation.destroy');
    Route::post('/quotation/bulk/delete', [RequestOrderController::class, 'bulkDelete'])->name('sales.quotation.bulk-delete');
    Route::post('/quotation/bulk/send-to-warehouse', [RequestOrderController::class, 'bulkSendToWarehouse'])->name('sales.quotation.bulk-send-to-warehouse');
    Route::post('/quotation/{requestOrder}/sent-to-warehouse', [RequestOrderController::class, 'sentToWarehouse'])->name('sales.quotation.sent-to-warehouse');
    Route::post('/quotation/{requestOrder}/upload-image-so', [RequestOrderController::class, 'uploadImageSO'])->name('request-order.upload-image-so');
    Route::delete('/quotation/{requestOrder}/upload-image-so', [RequestOrderController::class, 'deleteImageSO'])->name('request-order.delete-image-so');
    Route::post('/quotation/{requestOrder}/upload-image-po', [RequestOrderController::class, 'uploadImagePO'])->name('request-order.upload-image-po');
    Route::delete('/quotation/{requestOrder}/upload-image-po', [RequestOrderController::class, 'deleteImagePO'])->name('request-order.delete-image-po');
    Route::post('/quotation/{requestOrder}/upload-pdf-po', [RequestOrderController::class, 'uploadPdfPO'])->name('request-order.upload-pdf-po');
    Route::delete('/quotation/{requestOrder}/upload-pdf-po', [RequestOrderController::class, 'deletePdfPO'])->name('request-order.delete-pdf-po');
    Route::post('/quotation/{requestOrder}/update-no-po', [RequestOrderController::class, 'updateNoPO'])->name('request-order.update-no-po');

    // Custom Quotation Routes
    Route::get('/custom-quotation', [CustomPenawaranController::class, 'index'])->name('sales.custom-quotation.index');
    Route::get('/custom-quotation/create', [CustomPenawaranController::class, 'create'])->name('sales.custom-quotation.create');
    Route::post('/custom-quotation', [CustomPenawaranController::class, 'store'])->name('sales.custom-quotation.store');
    Route::post('/custom-quotation/bulk/delete', [CustomPenawaranController::class, 'bulkDelete'])->name('sales.custom-quotation.bulk-delete');
    Route::post('/custom-quotation/bulk/send-to-warehouse', [CustomPenawaranController::class, 'bulkSendToWarehouse'])->name('sales.custom-quotation.bulk-send-to-warehouse');
    Route::get('/custom-quotation/{customPenawaran}', [CustomPenawaranController::class, 'show'])->name('sales.custom-quotation.show');
    Route::get('/custom-quotation/{customPenawaran}/edit', [CustomPenawaranController::class, 'edit'])->name('sales.custom-quotation.edit');
    Route::put('/custom-quotation/{customPenawaran}', [CustomPenawaranController::class, 'update'])->name('sales.custom-quotation.update');
    Route::delete('/custom-quotation/{customPenawaran}', [CustomPenawaranController::class, 'destroy'])->name('sales.custom-quotation.destroy');
    Route::get('/custom-quotation/{customPenawaran}/pdf', [CustomPenawaranController::class, 'pdf'])->name('sales.custom-quotation.pdf');
    Route::post('/custom-quotation/{customPenawaran}/sent-to-warehouse', [CustomPenawaranController::class, 'sentToWarehouse'])->name('sales.custom-quotation.sent-to-warehouse');

    // Sent to Quotation
    Route::post(
        '/custom-quotation/{customPenawaran}/sent-to-penawaran',
        [CustomPenawaranController::class, 'sentToPenawaran']
    )
        ->name('sales.custom-quotation.sent-to-penawaran');

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
    Route::post('/sales-order/{salesOrder}/upload-image', [SalesOrderController::class, 'uploadImage'])->name('sales-order.upload-image');
    Route::delete('/sales-order/{salesOrder}/upload-image', [SalesOrderController::class, 'deleteImage'])->name('sales-order.delete-image');

    // Sent to Warehouse dari Sales Order
    Route::post('/sales-order/{salesOrder}/sent-to-warehouse', [SalesOrderController::class, 'sentToWarehouse'])
        ->name('sales.sales-order.sent-to-warehouse');

    // Sent to Warehouse dari Quotation (yang muncul di halaman SO)
    Route::post('/quotation-so/{requestOrder}/sent-to-warehouse', [SalesOrderController::class, 'sentRequestOrderToWarehouse'])
        ->name('sales.quotation.sent-to-warehouse-from-so');

    // Dashboard Chart Data for Sales
    Route::get('/admin/dashboard/sales/data', [\App\Http\Controllers\Admin\Dashboard\SalesDashboardController::class, 'chartData'])
        ->name('dashboard.sales.chart.data');

    Route::get('/admin/dashboard/sales/export-quotations', [\App\Http\Controllers\Admin\Dashboard\SalesDashboardController::class, 'exportQuotations'])
        ->name('dashboard.sales.export.quotations');
});
// End of Sales

// === End Admin Routes === //
require __DIR__ . '/auth.php';
