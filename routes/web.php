<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\GoodsInController;
use App\Http\Controllers\Admin\AddStockController;
use App\Http\Controllers\Admin\GoodsInStatusController;
use App\Http\Controllers\Admin\SalesAgentsController;
use App\Http\Controllers\Admin\CustomersController;
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
use App\Http\Controllers\Guest\ProductController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\CustomQuotationController;
use App\Http\Controllers\Admin\QuotationApprovalController;
use App\Http\Controllers\Admin\CustomQuotationApprovalController;
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

Route::get('/quotation-preview-static', function () {
    return view('admin.pdf.quotation');
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
    Route::resource('/customers', CustomersController::class);
    Route::patch('/customers/{id}/status', [CustomersController::class, 'updateStatus'])->name('customers.status.update');
    Route::get('/admin/customers/{id}/pics', [CustomersController::class, 'getPics'])->name('customers.pics');
});
// End of General

// General Affair
Route::middleware(['auth', 'role:General Affair'])->group(function () {
    // Sales Order (read-only untuk GA)
    Route::get('/sales-order-invoices', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'index'])->name('sales-order-invoices.index');
    Route::get('/sales-order-invoices/export', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'exportGaSalesOrders'])->name('sales-order-invoices.export');
    Route::get('/sales-order-invoices/search', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'search'])->name('sales-order-invoices.search');
    Route::get('/invoice/{id}', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'showInvoice'])->name('invoice.index');
    Route::get('/invoice/{id}/receipt', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'printReceipt'])->name('invoice.receipt');
    Route::get('/sales-order-invoices/{id}/invoice-history', [App\Http\Controllers\Admin\SalesOrderInvoiceController::class, 'getInvoiceHistory'])->name('sales-order-invoices.invoice-history');
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
    Route::resource('/sales-agents', SalesAgentsController::class);
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

    // Procurement / Pengadaan (General Affair)
    Route::get('/procurement', [\App\Http\Controllers\Admin\ProcurementController::class, 'index'])->name('general-affair.procurement.index');
    Route::get('/procurement/create/{customQuotation}', [\App\Http\Controllers\Admin\ProcurementController::class, 'create'])->name('general-affair.procurement.create');
    Route::post('/procurement', [\App\Http\Controllers\Admin\ProcurementController::class, 'store'])->name('general-affair.procurement.store');
    Route::post('/procurement/store-modal', [\App\Http\Controllers\Admin\ProcurementController::class, 'storeModal'])->name('general-affair.procurement.store-modal');
    Route::get('/procurement/{procurement}', [\App\Http\Controllers\Admin\ProcurementController::class, 'show'])->name('general-affair.procurement.show');
    Route::get('/procurement/{procurement}/detail-html', [\App\Http\Controllers\Admin\ProcurementController::class, 'detailHtml'])->name('general-affair.procurement.detail-html');
    Route::post('/procurement/arrival/{procurement}', [\App\Http\Controllers\Admin\ProcurementController::class, 'recordArrival'])->name('general-affair.procurement.record-arrival');
    Route::post('/procurement/{procurement}/force-complete', [\App\Http\Controllers\Admin\ProcurementController::class, 'forceComplete'])->name('general-affair.procurement.force-complete');
    Route::post('/procurement/receipt/{receipt}/update', [\App\Http\Controllers\Admin\ProcurementController::class, 'updateReceipt'])->name('general-affair.procurement.update-receipt');
    Route::delete('/procurement/receipt/{receipt}', [\App\Http\Controllers\Admin\ProcurementController::class, 'destroyReceipt'])->name('general-affair.procurement.destroy-receipt');
});
// End of General Affair

route::middleware(['auth', 'role:Warehouse'])->group(function () {
    Route::post('/supply-orders/bulk/approve', [SupplyOrdersController::class, 'bulkApprove'])->name('supply-orders.bulk-approve');
    Route::post('/supply-orders/bulk/reject', [SupplyOrdersController::class, 'bulkReject'])->name('supply-orders.bulk-reject');
    Route::resource('/supply-orders', SupplyOrdersController::class);
    Route::post('/supply-orders/{id}/approve', [SupplyOrdersController::class, 'approve'])->name('supply-orders.approve');
    Route::post('/supply-orders/{id}/reject', [SupplyOrdersController::class, 'reject'])->name('supply-orders.reject');
    Route::post('/supply-orders/procurement/{receipt}/approve', [SupplyOrdersController::class, 'approveProcurement'])->name('supply-orders.approve-procurement');
    Route::post('/supply-orders/procurement/{receipt}/reject', [SupplyOrdersController::class, 'rejectProcurement'])->name('supply-orders.reject-procurement');
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
    Route::get('/quotation-approval', [QuotationApprovalController::class, 'index'])->name('admin.quotation_approval');
    // Supervisor approval route for Custom Quotation (allow Supervisor to POST approve/reject)
    Route::post('/custom-quotation-approval/{customQuotation}/approval', [CustomQuotationApprovalController::class, 'approve'])->name('admin.custom-quotation-approval.approval');
    // Supervisor view detail for custom quotation (so Supervisor can access without Sales role)
    Route::get('/detail-custom-quotation-approval/{customQuotation}', [CustomQuotationController::class, 'show'])->name('admin.custom-quotation-approval.show');
    Route::get('/custom-quotation-approval', [CustomQuotationApprovalController::class, 'index'])->name('supervisor.custom-quotation-approval.index');
    Route::post('/custom-quotation-approval/bulk-approval', [CustomQuotationApprovalController::class, 'bulkApproval'])->name('supervisor.custom-quotation-approval.bulk-approval');
    Route::get('/orders/{id}', [QuotationApprovalController::class, 'incomingShow'])->name('orders.show');
    Route::post('/orders/{id}/approve', [QuotationApprovalController::class, 'incomingApprove'])->name('orders.approve');
    Route::post('/orders/{id}/reject', [QuotationApprovalController::class, 'incomingReject'])->name('orders.reject');
    Route::get('/orders/history', [QuotationApprovalController::class, 'incomingHistory'])->name('orders.history');

    // Supervisor approval for Quotations (from Sales)
    Route::post('/quotation/{quotation}/approve', [QuotationApprovalController::class, 'approve'])->name('supervisor.quotation.approve');
    Route::post('/quotation/{quotation}/reject', [QuotationApprovalController::class, 'reject'])->name('supervisor.quotation.reject');
    Route::get('/custom-quotation-approval/{customQuotation}/pdf', [CustomQuotationController::class, 'pdf'])->name('admin.custom-quotation-approval.pdf');

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
    Route::get('/quotation-history', [QuotationApprovalController::class, 'history'])->name('supervisor.history');
});
// End of Supervisor

// Sales
Route::middleware(['auth', 'role:Sales'])->group(function () {
    // Customer Routes for Sales (Consolidated to global customer.store)

    // Quotation Routes
    Route::get('/quotation', [QuotationController::class, 'index'])->name('sales.quotation.index');
    Route::get('/quotation/create', [QuotationController::class, 'create'])->name('sales.quotation.create');
    Route::post('/quotation', [QuotationController::class, 'store'])->name('sales.quotation.store');

    Route::get('/quotation/{quotation}/edit', [QuotationController::class, 'edit'])->name('sales.quotation.edit');
    Route::put('/quotation/{quotation}', [QuotationController::class, 'update'])->name('sales.quotation.update');
    Route::post('/quotation/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('sales.quotation.status');
    Route::delete('/quotation/{quotation}', [QuotationController::class, 'destroy'])->name('sales.quotation.destroy');
    Route::post('/quotation/bulk/delete', [QuotationController::class, 'bulkDelete'])->name('sales.quotation.bulk-delete');
    Route::post('/quotation/bulk/send-to-warehouse', [QuotationController::class, 'bulkSendToWarehouse'])->name('sales.quotation.bulk-send-to-warehouse');
    Route::post('/quotation/{quotation}/sent-to-warehouse', [QuotationController::class, 'sentToWarehouse'])->name('sales.quotation.sent-to-warehouse');
    Route::post('/quotation/{quotation}/upload-image-po', [QuotationController::class, 'uploadImagePO'])->name('request-order.upload-image-po');
    Route::delete('/quotation/{quotation}/upload-image-po', [QuotationController::class, 'deleteImagePO'])->name('request-order.delete-image-po');
    Route::post('/quotation/{quotation}/upload-pdf-po', [QuotationController::class, 'uploadPdfPO'])->name('request-order.upload-pdf-po');
    Route::delete('/quotation/{quotation}/upload-pdf-po', [QuotationController::class, 'deletePdfPO'])->name('request-order.delete-pdf-po');
    Route::post('/quotation/{quotation}/update-no-po', [QuotationController::class, 'updateNoPO'])->name('request-order.update-no-po');

    // Custom Quotation Routes
    Route::get('/custom-quotation', [CustomQuotationController::class, 'index'])->name('sales.custom-quotation.index');
    Route::get('/custom-quotation/create', [CustomQuotationController::class, 'create'])->name('sales.custom-quotation.create');
    Route::post('/custom-quotation', [CustomQuotationController::class, 'store'])->name('sales.custom-quotation.store');
    Route::post('/custom-quotation/bulk/delete', [CustomQuotationController::class, 'bulkDelete'])->name('sales.custom-quotation.bulk-delete');
    Route::post('/custom-quotation/bulk/send-to-warehouse', [CustomQuotationController::class, 'bulkSendToWarehouse'])->name('sales.custom-quotation.bulk-send-to-warehouse');
    Route::get('/detail-custom-quotation/{customQuotation}', [CustomQuotationController::class, 'show'])->name('sales.custom-quotation.show');
    Route::get('/custom-quotation/{customQuotation}/edit', [CustomQuotationController::class, 'edit'])->name('sales.custom-quotation.edit');
    Route::put('/custom-quotation/{customQuotation}', [CustomQuotationController::class, 'update'])->name('sales.custom-quotation.update');
    Route::delete('/custom-quotation/{customQuotation}', [CustomQuotationController::class, 'destroy'])->name('sales.custom-quotation.destroy');
    Route::get('/custom-quotation/{customQuotation}/pdf', [CustomQuotationController::class, 'pdf'])->name('sales.custom-quotation.pdf');
    Route::post('/custom-quotation/{customQuotation}/sent-to-warehouse', [CustomQuotationController::class, 'sentToWarehouse'])->name('sales.custom-quotation.sent-to-warehouse');

    // Sent to Quotation
    Route::post(
        '/custom-quotation/{customQuotation}/sent-to-quotation',
        [CustomQuotationController::class, 'sentToQuotation']
    )
        ->name('sales.custom-quotation.sent-to-quotation');

    // Sales Order Routes
    Route::get('/sales-orders', [SalesOrderController::class, 'index'])->name('sales.sales-orders.index');
    Route::get('/sales-orders/search', [SalesOrderController::class, 'search'])->name('sales.sales-orders.search');
    Route::get('/sales-orders/quotation-detail', [SalesOrderController::class, 'getQuotationDetail'])->name('sales.sales-orders.quotation-detail');
    Route::get('/sales-orders/create', [SalesOrderController::class, 'create'])->name('sales.sales-orders.create');
    Route::post('/sales-orders', [SalesOrderController::class, 'store'])->name('sales.sales-orders.store');
    Route::get('/detail-sales-order/{salesOrder}', [SalesOrderController::class, 'show'])->name('sales.sales-orders.show');
    Route::get('/sales-orders/{salesOrder}/edit', [SalesOrderController::class, 'edit'])->name('sales.sales-orders.edit');
    Route::put('/sales-orders/{salesOrder}', [SalesOrderController::class, 'update'])->name('sales.sales-orders.update');
    Route::delete('/sales-orders/{salesOrder}', [SalesOrderController::class, 'destroy'])->name('sales.sales-orders.destroy');

    // Sent to Warehouse dari Sales Order
    Route::post('/sales-orders/{salesOrder}/sent-to-warehouse', [SalesOrderController::class, 'sentToWarehouse'])
        ->name('sales.sales-orders.sent-to-warehouse');

    // Sent to Warehouse dari Quotation (yang muncul di halaman SO)
    Route::post('/quotation-so/{quotation}/sent-to-warehouse', [SalesOrderController::class, 'sentRequestOrderToWarehouse'])
        ->name('sales.quotation.sent-to-warehouse-from-so');

    // Dashboard Chart Data for Sales
    Route::get('/admin/dashboard/sales/data', [\App\Http\Controllers\Admin\Dashboard\SalesDashboardController::class, 'chartData'])
        ->name('dashboard.sales.chart.data');

    Route::get('/admin/dashboard/sales/export-quotations', [\App\Http\Controllers\Admin\Dashboard\SalesDashboardController::class, 'exportQuotations'])
        ->name('dashboard.sales.export.quotations');
});
// End of Sales

// System Settings (Supervisor Only)
Route::middleware(['auth', 'role:Supervisor'])->group(function () {
    Route::get('/wms-settings', [App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('wms-settings.index');
    Route::post('/wms-settings', [App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])->name('wms-settings.update');
});

// Sales Report (Sales, GA, Supervisor)
Route::middleware(['auth', 'role:Sales,General Affair,Supervisor'])->group(function () {
    Route::get('/sales-report', [App\Http\Controllers\Admin\SalesReportController::class, 'index'])->name('sales-report.index');
    Route::get('/sales-report/excel', [App\Http\Controllers\Admin\SalesReportController::class, 'exportExcel'])->name('sales-report.excel');
    Route::get('/sales-report/pdf', [App\Http\Controllers\Admin\SalesReportController::class, 'exportPdf'])->name('sales-report.pdf');
});

// Shared Detail and PDF views for Quotation (registered below specific routes to avoid parameter clashes)
Route::middleware(['auth'])->group(function () {
    Route::get('/detail-quotation/{quotation}', [QuotationController::class, 'show'])->name('sales.quotation.show');
    Route::get('/detail-quotation-approval/{quotation}', [QuotationController::class, 'show'])->name('admin.quotation-approval.show');
    Route::get('/quotation/{quotation}/pdf', [QuotationController::class, 'pdf'])->name('sales.quotation.pdf');

    // Fallback redirect routes for old URLs to prevent MethodNotAllowedHttpException on old bookmarks/refreshes
    Route::get('/quotation/{quotation}', function ($quotation) {
        return redirect()->route('sales.quotation.show', $quotation);
    });
    Route::get('/quotation-approval/{quotation}', function ($quotation) {
        return redirect()->route('admin.quotation-approval.show', $quotation);
    });
    Route::get('/custom-quotation/{customQuotation}', function ($customQuotation) {
        return redirect()->route('sales.custom-quotation.show', $customQuotation);
    });
    Route::get('/custom-quotation-approval/{customQuotation}', function ($customQuotation) {
        return redirect()->route('admin.custom-quotation-approval.show', $customQuotation);
    });
});

// === End Admin Routes === //
require __DIR__ . '/auth.php';
