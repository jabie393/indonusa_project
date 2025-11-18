# Modul Sales - Dokumentasi

## Gambaran Umum

Modul Sales adalah sistem manajemen penjualan yang mencakup dua fitur utama:

1. **Request Order (Penawaran)** - Penawaran awal kepada pelanggan sebelum menjadi Sales Order
2. **Sales Order (Pesanan Penjualan)** - Pesanan penjualan final dari Request Order yang sudah disetujui

## Alur Proses Sales

```
┌─────────────────┐
│  Request Order  │  ← Penawaran awal dari Sales
│   (Pending)     │
└────────┬────────┘
         │
         ├─→ [REJECTED] → Alasan penolakan
         │
         └─→ [APPROVED] → dapat dikonversi
                │
                └─→ ┌──────────────────┐
                    │  Sales Order     │  ← Pesanan final
                    │   (Pending)      │
                    └────────┬─────────┘
                             │
                    Status Updates:
                    - in_process (dalam proses)
                    - shipped (dikirim)
                    - completed (selesai)
                    - cancelled (dibatalkan)
```

## Model dan Database

### RequestOrder Model
- **File**: `app/Models/RequestOrder.php`
- **Tabel**: `request_orders`
- **Relationships**:
  - `items()` - RequestOrderItem (one-to-many)
  - `sales()` - User pemilik Request Order
  - `approvedBy()` - User yang menyetujui
  - `salesOrder()` - Sales Order hasil konversi

**Fields**:
- `request_number` (unique) - No. penawaran (format: REQ-XXXXXXXX)
- `sales_id` - ID Sales yang membuat
- `customer_name` - Nama customer
- `customer_id` - ID customer (optional)
- `status` - pending, approved, rejected, converted
- `reason` - Alasan penolakan (jika ada)
- `tanggal_kebutuhan` - Tanggal kebutuhan barang
- `catatan_customer` - Catatan dari customer
- `approved_by` - ID user yang approve (jika ada)
- `approved_at` - Waktu approval
- Timestamps

### RequestOrderItem Model
- **File**: `app/Models/RequestOrderItem.php`
- **Tabel**: `request_order_items`
- **Fields**:
  - `request_order_id` - Foreign key ke request_orders
  - `barang_id` - Foreign key ke barangs
  - `quantity` - Jumlah barang
  - `harga` - Harga satuan
  - `subtotal` - Total harga (quantity × harga)

### SalesOrder Model
- **File**: `app/Models/SalesOrder.php`
- **Tabel**: `sales_orders`
- **Relationships**:
  - `items()` - SalesOrderItem (one-to-many)
  - `requestOrder()` - Request Order asal
  - `sales()` - User pemilik
  - `supervisor()` - Supervisor jika ada
  - `warehouse()` - Warehouse jika ada
  - `approvedBy()` - User yang approve

**Fields**:
- `sales_order_number` (unique) - No. pesanan (format: SO-XXXXXXXX)
- `request_order_id` - Foreign key ke request_orders
- `sales_id` - ID Sales pemilik
- `customer_name` - Nama customer
- `customer_id` - ID customer
- `status` - pending, in_process, shipped, completed, cancelled
- `reason` - Alasan pembatalan
- `tanggal_kebutuhan` - Tanggal kebutuhan
- `catatan_customer` - Catatan customer
- `supervisor_id` - ID supervisor (optional)
- `warehouse_id` - ID warehouse (optional)
- `approved_by` - ID user yang approve (optional)
- `approved_at` - Waktu approval
- Timestamps

### SalesOrderItem Model
- **File**: `app/Models/SalesOrderItem.php`
- **Tabel**: `sales_order_items`
- **Fields**:
  - `sales_order_id` - Foreign key ke sales_orders
  - `request_order_item_id` - Reference ke request_order_items (optional)
  - `barang_id` - Foreign key ke barangs
  - `quantity` - Jumlah dipesan
  - `delivered_quantity` - Jumlah yang sudah dikirim
  - `harga` - Harga satuan
  - `subtotal` - Total harga
  - `status_item` - pending, partial, completed

## Controllers

### RequestOrderController
- **File**: `app/Http/Controllers/Admin/RequestOrderController.php`
- **Routes Prefix**: `/request-order`

**Methods**:
- `index()` - Daftar Request Order milik Sales
- `create()` - Form buat Request Order baru
- `store()` - Simpan Request Order baru
- `show()` - Detail Request Order
- `edit()` - Form edit (hanya status pending)
- `update()` - Update Request Order
- `convertToSalesOrder()` - Konversi ke Sales Order (hanya status approved)

### SalesOrderController
- **File**: `app/Http/Controllers/Admin/SalesOrderController.php`
- **Routes Prefix**: `/sales-order`

**Methods**:
- `index()` - Daftar Sales Order milik Sales
- `show()` - Detail Sales Order dengan tracking
- `updateStatus()` - Update status (PUT)
- `updateDeliveredQty()` - Update jumlah terkirim per item
- `cancel()` - Batalkan Sales Order dengan alasan

## Routes

Semua routes berada di `routes/web.php` dengan middleware `auth` dan `role:Sales`.

```php
// Request Order Routes
Route::get('/request-order', [RequestOrderController::class, 'index'])->name('sales.request-order.index');
Route::get('/request-order/create', [RequestOrderController::class, 'create'])->name('sales.request-order.create');
Route::post('/request-order', [RequestOrderController::class, 'store'])->name('sales.request-order.store');
Route::get('/request-order/{requestOrder}', [RequestOrderController::class, 'show'])->name('sales.request-order.show');
Route::get('/request-order/{requestOrder}/edit', [RequestOrderController::class, 'edit'])->name('sales.request-order.edit');
Route::put('/request-order/{requestOrder}', [RequestOrderController::class, 'update'])->name('sales.request-order.update');
Route::post('/request-order/{requestOrder}/convert', [RequestOrderController::class, 'convertToSalesOrder'])->name('sales.request-order.convert');

// Sales Order Routes
Route::get('/sales-order', [SalesOrderController::class, 'index'])->name('sales.sales-order.index');
Route::get('/sales-order/{salesOrder}', [SalesOrderController::class, 'show'])->name('sales.sales-order.show');
Route::put('/sales-order/{salesOrder}/status', [SalesOrderController::class, 'updateStatus'])->name('sales.sales-order.status');
Route::put('/sales-order-item/{item}/delivered', [SalesOrderController::class, 'updateDeliveredQty'])->name('sales.sales-order-item.delivered');
Route::post('/sales-order/{salesOrder}/cancel', [SalesOrderController::class, 'cancel'])->name('sales.sales-order.cancel');
```

## Views

### Request Order Views
- `resources/views/admin/sales/request-order/index.blade.php` - Daftar Request Order
- `resources/views/admin/sales/request-order/create.blade.php` - Form buat Request Order
- `resources/views/admin/sales/request-order/edit.blade.php` - Form edit Request Order
- `resources/views/admin/sales/request-order/show.blade.php` - Detail Request Order

### Sales Order Views
- `resources/views/admin/sales/sales-order/index.blade.php` - Daftar Sales Order
- `resources/views/admin/sales/sales-order/show.blade.php` - Detail Sales Order dengan tracking

## Fitur Utama

### 1. Request Order (Penawaran)
- ✅ Buat penawaran baru dengan detail barang
- ✅ Edit penawaran (status pending)
- ✅ Lihat detail penawaran
- ✅ Konversi penawaran ke Sales Order (status approved)
- ✅ Tracking status penawaran

**Validasi**:
- Customer name wajib diisi
- Minimal 1 item barang
- Quantity harus positif
- Barang harus tersedia di database

### 2. Sales Order (Pesanan Penjualan)
- ✅ Otomatis dibuat dari Request Order yang approved
- ✅ Lihat daftar Sales Order
- ✅ Tracking pengiriman per item (delivered_quantity)
- ✅ Update status pesanan (pending → in_process → shipped → completed)
- ✅ Batalkan pesanan dengan alasan
- ✅ Progress bar pengiriman
- ✅ Timeline/riwayat status

**Status Flow**:
- `pending` - Pesanan baru
- `in_process` - Sedang diproses
- `shipped` - Dikirim
- `completed` - Selesai
- `cancelled` - Dibatalkan

## Integrasi Menu Sidebar

Menu di sidebar sudah terupdate dengan struktur dropdown:
- **Sales Module** (dropdown)
  - Request Order (Penawaran)
  - Sales Order (Pesanan Penjualan)

## Security & Authorization

Semua fitur hanya bisa diakses oleh user dengan role `Sales`:

```php
Route::middleware(['auth', 'role:Sales'])->group(function () {
    // All sales routes here
});
```

### Policy:
- Hanya Sales pemilik yang bisa edit/lihat Request Order mereka
- Hanya Sales pemilik yang bisa lihat/update Sales Order mereka
- Supervisor dan Warehouse bisa view-only

## Contoh Penggunaan

### 1. Membuat Request Order
1. Klik menu "Sales Module" → "Request Order (Penawaran)"
2. Klik "+ Request Order Baru"
3. Isi informasi customer dan detail barang
4. Tentukan jumlah dan harga per item
5. Klik "Buat Request Order"

### 2. Konversi ke Sales Order
1. Buka detail Request Order yang sudah approved
2. Klik tombol "Konversi ke Sales Order"
3. System otomatis membuat Sales Order
4. Redirect ke detail Sales Order

### 3. Update Status Pengiriman
1. Buka detail Sales Order
2. Klik "Update Status"
3. Pilih status baru dan catatan (optional)
4. Simpan perubahan

### 4. Batalkan Sales Order
1. Buka detail Sales Order
2. Klik "Batalkan Pesanan"
3. Isi alasan pembatalan (minimal 10 karakter)
4. Konfirmasi pembatalan

## Database Diagram

```
request_orders
├── id (pk)
├── request_number (unique)
├── sales_id (fk → users)
├── customer_name
├── customer_id
├── status (enum)
├── reason
├── tanggal_kebutuhan
├── catatan_customer
├── approved_by (fk → users)
├── approved_at
└── timestamps

request_order_items
├── id (pk)
├── request_order_id (fk → request_orders)
├── barang_id (fk → barangs)
├── quantity
├── harga
├── subtotal
└── timestamps

sales_orders
├── id (pk)
├── sales_order_number (unique)
├── request_order_id (fk → request_orders)
├── sales_id (fk → users)
├── customer_name
├── customer_id
├── status (enum)
├── reason
├── tanggal_kebutuhan
├── catatan_customer
├── supervisor_id (fk → users)
├── warehouse_id (fk → users)
├── approved_by (fk → users)
├── approved_at
└── timestamps

sales_order_items
├── id (pk)
├── sales_order_id (fk → sales_orders)
├── request_order_item_id (fk → request_order_items)
├── barang_id (fk → barangs)
├── quantity
├── delivered_quantity
├── harga
├── subtotal
├── status_item (enum)
└── timestamps
```

## Future Enhancements

Fitur-fitur yang bisa ditambahkan di masa depan:
- [ ] Generate PDF Report untuk Request Order
- [ ] Generate PDF Invoice untuk Sales Order
- [ ] Email notification untuk approval/rejection
- [ ] Payment tracking
- [ ] Multi-currency support
- [ ] Customer portal untuk lihat penawaran
- [ ] Approval workflow dengan multiple levels
- [ ] Discount/promo management
- [ ] Stock allocation & reservation
- [ ] Return/replacement handling

## Troubleshooting

### Request Order tidak muncul di list
- Pastikan user memiliki role "Sales"
- Cek apakah Request Order milik user yang login

### Tidak bisa konversi ke Sales Order
- Request Order harus berstatus "approved"
- Pastikan tidak ada duplikasi Sales Order dari Request Order yang sama

### Delivered quantity tidak update
- Pastikan Sales Order berstatus selain "completed" atau "cancelled"
- Delivered quantity tidak boleh melebihi quantity yang dipesan

## Support & Maintenance

Untuk pertanyaan atau issue, silakan hubungi tim development.
