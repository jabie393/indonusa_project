# Customer Management Documentation

**Last Updated**: November 13, 2025  
**Status**: Completed & Integrated  
**Module**: Sales Module  

## Overview

Customer Management adalah fitur yang memungkinkan Sales users untuk mengelola data pelanggan dengan lebih efisien. Fitur ini terintegrasi dengan Request Order dan Sales Order, sehingga Sales dapat memilih pelanggan dari daftar yang sudah ada atau menambahkan pelanggan baru dengan mudah.

---

## Table of Contents

1. [Features Overview](#features-overview)
2. [Database Structure](#database-structure)
3. [Models & Relationships](#models--relationships)
4. [API Endpoints](#api-endpoints)
5. [Controller Methods](#controller-methods)
6. [Views & UI](#views--ui)
7. [Integration Points](#integration-points)
8. [Usage Examples](#usage-examples)
9. [Permissions & Authorization](#permissions--authorization)

---

## Features Overview

### 1. **Customer List (Index)**
- Menampilkan daftar semua pelanggan dengan pagination (20 items per halaman)
- Fitur pencarian dan filter berdasarkan status
- Menampilkan informasi: Nama, Email, Telepon, Kota, Tipe Customer, Status
- Action buttons: View, Edit, Delete dengan konfirmasi

**Route**: `GET /customer` → `sales.customer.index`

### 2. **Create Customer**
- Form untuk membuat pelanggan baru
- Fields:
  - Informasi Dasar: Nama Customer, Email (unique), Telepon, Tipe Customer, Status
  - Alamat: Alamat (textarea), Kota, Provinsi, Kode Pos
- Validasi server-side lengkap
- JSON response untuk AJAX requests

**Route**: `GET /customer/create` → `sales.customer.create` (Form)  
**Route**: `POST /customer` → `sales.customer.store` (Submit)

### 3. **View Customer Details**
- Menampilkan detail lengkap pelanggan
- Informasi yang ditampilkan:
  - Informasi Dasar: Nama, Tipe, Email, Telepon, Status
  - Alamat: Alamat lengkap dengan breakdown kota, provinsi, kode pos
  - Riwayat: Created by, Created at, Updated by, Updated at
  - Statistik: Jumlah Request Order, Jumlah Sales Order
- Quick actions: Edit, Delete dengan validasi

**Route**: `GET /customer/{customer}` → `sales.customer.show`

### 4. **Edit Customer**
- Form untuk edit data pelanggan yang sudah ada
- Pre-populated dengan data pelanggan saat ini
- Same fields sebagai create form
- Maintains old values on validation error
- Updated by & Updated at timestamp diupdate otomatis

**Route**: `GET /customer/{customer}/edit` → `sales.customer.edit` (Form)  
**Route**: `PUT /customer/{customer}` → `sales.customer.update` (Submit)

### 5. **Delete Customer**
- Soft delete dengan validasi
- Tidak dapat dihapus jika memiliki Request Order atau Sales Order
- Error message yang jelas: "Customer tidak dapat dihapus karena memiliki pesanan"
- Confirmasi dialog sebelum delete

**Route**: `DELETE /customer/{customer}` → `sales.customer.destroy`

### 6. **Customer Search API**
- API endpoint untuk autocomplete/search functionality
- Filter berdasarkan status 'active' saja
- Search fields: nama_customer, email, telepon
- Return limit: 20 results
- Return format: JSON array dengan fields: id, nama_customer, email, telepon, kota

**Route**: `GET /customer/api/search?q=keyword` → `sales.customer.search`

### 7. **Customer Selection in Request Order**
- Dropdown select untuk memilih customer saat membuat/edit Request Order
- Auto-fill fields (nama, email, telepon, kota) ketika customer dipilih
- Display informasi customer dari database
- Quick add modal untuk menambah customer tanpa keluar dari form

---

## Database Structure

### Customers Table

```sql
CREATE TABLE customers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nama_customer VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    telepon VARCHAR(20),
    alamat LONGTEXT,
    kota VARCHAR(100),
    provinsi VARCHAR(100),
    kode_pos VARCHAR(10),
    tipe_customer ENUM('retail', 'wholesale', 'distributor'),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Relationships to Other Tables

- **customers.created_by** → users.id (Creator)
- **customers.updated_by** → users.id (Last Updater)
- **request_orders.customer_id** → customers.id (One-to-Many)
- **sales_orders.customer_id** → customers.id (One-to-Many)

---

## Models & Relationships

### Customer Model

**File**: `app/Models/Customer.php`

```php
class Customer extends Model
{
    protected $fillable = [
        'nama_customer',
        'email',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'tipe_customer',
        'created_by',
        'updated_by',
        'status'
    ];

    // Relationships
    public function requestOrders() { return $this->hasMany(RequestOrder::class); }
    public function salesOrders() { return $this->hasMany(SalesOrder::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }

    // Accessor
    public function getFullAddressAttribute() { ... }
}
```

### RequestOrder Model Updates

**customer_id** field ditambahkan untuk foreign key relationship:

```php
public function customer()
{
    return $this->belongsTo(Customer::class);
}
```

### SalesOrder Model Updates

**customer_id** field sudah ada untuk foreign key relationship.

---

## API Endpoints

### Create Customer (AJAX)

**Endpoint**: `POST /customer`

**Request Headers**:
```
Content-Type: application/x-www-form-urlencoded
X-Requested-With: XMLHttpRequest (untuk AJAX detection)
```

**Request Body**:
```json
{
    "nama_customer": "PT. Maju Jaya",
    "email": "contact@majujaya.com",
    "telepon": "021-1234567",
    "tipe_customer": "distributor",
    "status": "active",
    "alamat": "Jl. Gatot Subroto No. 123",
    "kota": "Jakarta",
    "provinsi": "DKI Jakarta",
    "kode_pos": "12345"
}
```

**Success Response** (JSON):
```json
{
    "success": true,
    "customer": {
        "id": 1,
        "nama_customer": "PT. Maju Jaya",
        "email": "contact@majujaya.com",
        "telepon": "021-1234567",
        "kota": "Jakarta",
        "tipe_customer": "distributor",
        "status": "active"
    }
}
```

**Error Response** (JSON):
```json
{
    "success": false,
    "errors": {
        "email": ["Email sudah digunakan."],
        "nama_customer": ["Nama customer harus diisi."]
    }
}
```

### Search Customer API

**Endpoint**: `GET /customer/api/search?q=keyword`

**Query Parameters**:
- `q` (required): Search keyword

**Response** (JSON):
```json
[
    {
        "id": 1,
        "nama_customer": "PT. Maju Jaya",
        "email": "contact@majujaya.com",
        "telepon": "021-1234567",
        "kota": "Jakarta"
    },
    {
        "id": 2,
        "nama_customer": "UD. Jaya Sentosa",
        "email": "info@jayasentosa.com",
        "telepon": "031-7654321",
        "kota": "Surabaya"
    }
]
```

---

## Controller Methods

### CustomerController

**File**: `app/Http/Controllers/Admin/CustomerController.php`

#### 1. index()
```php
public function index()
{
    $customers = Customer::paginate(20);
    return view('admin.sales.customer.index', compact('customers'));
}
```
- Paginated list of all customers
- 20 items per page

#### 2. create()
```php
public function create()
{
    return view('admin.sales.customer.create');
}
```
- Return create form view

#### 3. store()
```php
public function store(Request $request)
{
    $validated = $request->validate([...]);
    $customer = Customer::create([...]);
    
    if ($request->wantsJson()) {
        return response()->json(['success' => true, 'customer' => $customer]);
    }
    return redirect()->route('sales.customer.show', $customer);
}
```
- Validate input
- Create customer record with created_by = Auth::id()
- Return JSON for AJAX, redirect for form submit

#### 4. show()
```php
public function show(Customer $customer)
{
    $customer->load('createdBy', 'updatedBy', 'requestOrders', 'salesOrders');
    return view('admin.sales.customer.show', compact('customer'));
}
```
- Display customer details with related data

#### 5. edit()
```php
public function edit(Customer $customer)
{
    return view('admin.sales.customer.edit', compact('customer'));
}
```
- Return edit form with pre-populated data

#### 6. update()
```php
public function update(Request $request, Customer $customer)
{
    $validated = $request->validate([...]);
    $customer->update([..., 'updated_by' => Auth::id()]);
    return redirect()->route('sales.customer.show', $customer);
}
```
- Update customer with validation
- Updates updated_by timestamp

#### 7. destroy()
```php
public function destroy(Customer $customer)
{
    if ($customer->requestOrders()->exists() || $customer->salesOrders()->exists()) {
        return back()->withErrors('Customer tidak dapat dihapus karena memiliki pesanan.');
    }
    $customer->delete();
    return redirect()->route('sales.customer.index');
}
```
- Check if customer has orders
- Prevent deletion if has orders
- Delete customer

#### 8. search()
```php
public function search(Request $request)
{
    $keyword = $request->get('q');
    $customers = Customer::where('status', 'active')
        ->where(function($query) use ($keyword) {
            $query->where('nama_customer', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%")
                  ->orWhere('telepon', 'like', "%$keyword%");
        })
        ->limit(20)
        ->get(['id', 'nama_customer', 'email', 'telepon', 'kota']);
    
    return response()->json($customers);
}
```
- Search by nama_customer, email, telepon
- Filter by status = 'active'
- Return 20 results max

---

## Views & UI

### 1. Customer Index View
**File**: `resources/views/admin/sales/customer/index.blade.php`

**Features**:
- Responsive table with Bootstrap 5
- Pagination controls
- Status badges (Aktif = green, Nonaktif = red)
- Type badges (Retail, Wholesale, Distributor)
- Action buttons with icons
- Empty state message
- "Tambah Customer" button in header

### 2. Customer Create View
**File**: `resources/views/admin/sales/customer/create.blade.php`

**Layout**:
- Header with title and back button
- Main form card
- Two sections: Informasi Dasar dan Alamat
- Sidebar dengan info tentang tipe customer
- Submit buttons: Simpan, Batal

### 3. Customer Show View
**File**: `resources/views/admin/sales/customer/show.blade.php`

**Layout**:
- Customer information cards
- Statistics sidebar (Request Order count, Sales Order count)
- Riwayat (Created by, Updated by dengan timestamps)
- Quick action buttons (Edit, Delete)
- Info badge showing relationship

### 4. Customer Edit View
**File**: `resources/views/admin/sales/customer/edit.blade.php`

**Layout**:
- Similar to create form
- Pre-populated with current data
- Uses `old()` helper for validation error preservation
- Updates created_by field automatically

### 5. Request Order Create - Customer Section
**File**: `resources/views/admin/sales/request-order/create.blade.php`

**Features**:
- Customer select dropdown (required field)
- Auto-fill display fields (nama, email, telepon, kota)
- "Tambah Customer Baru" button
- Modal dialog untuk quick add customer
- JavaScript function `populateCustomerData()` untuk auto-fill

### 6. Request Order Edit - Customer Section
**File**: `resources/views/admin/sales/request-order/edit.blade.php`

**Features**:
- Same as create form
- Pre-selects current customer
- Auto-populates fields on page load
- Modal dialog untuk add/change customer

---

## Integration Points

### 1. Request Order Creation Flow

**File**: `resources/views/admin/sales/request-order/create.blade.php`

```blade
<!-- Customer Selection -->
<select id="customer_id" name="customer_id" onchange="populateCustomerData(this.value)">
    @foreach($customers as $c)
        <option value="{{ $c->id }}" 
                data-email="{{ $c->email }}" 
                data-telepon="{{ $c->telepon }}" 
                data-kota="{{ $c->kota }}">
            {{ $c->nama_customer }} ({{ $c->email }})
        </option>
    @endforeach
</select>

<!-- Display Fields (Read-only) -->
<input type="text" id="customer_name" readonly>
<input type="email" id="customer_email" readonly>
<input type="text" id="customer_telepon" readonly>
<input type="text" id="customer_kota" readonly>
```

**JavaScript Auto-Population**:
```javascript
function populateCustomerData(customerId) {
    const option = document.querySelector(`#customer_id option[value="${customerId}"]`);
    document.getElementById('customer_name').value = option.textContent.split('(')[0].trim();
    document.getElementById('customer_email').value = option.dataset.email || '';
    document.getElementById('customer_telepon').value = option.dataset.telepon || '';
    document.getElementById('customer_kota').value = option.dataset.kota || '';
}
```

### 2. Quick Add Customer Modal

**File**: `resources/views/admin/sales/request-order/create.blade.php` & `edit.blade.php`

```blade
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <form id="addCustomerForm" method="POST">
        <!-- Form fields: nama_customer, email, telepon, tipe_customer, status, alamat, kota, provinsi, kode_pos -->
    </form>
</div>
```

**Modal Form Submission** (AJAX):
```javascript
addCustomerForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const response = await fetch('{{ route("sales.customer.store") }}', {
        method: 'POST',
        body: new FormData(this),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await response.json();
    
    if (data.success) {
        // Add option to dropdown
        const option = document.createElement('option');
        option.value = data.customer.id;
        option.textContent = data.customer.nama_customer;
        option.dataset.email = data.customer.email;
        document.getElementById('customer_id').appendChild(option);
        
        // Select new customer
        option.selected = true;
        populateCustomerData(data.customer.id);
    }
});
```

### 3. RequestOrderController Updates

**File**: `app/Http/Controllers/Admin/RequestOrderController.php`

```php
public function create()
{
    $customers = Customer::where('status', 'active')
        ->orderBy('nama_customer')
        ->get();
    
    return view('admin.sales.request-order.create', compact('barangs', 'customers'));
}

public function edit(RequestOrder $requestOrder)
{
    $customers = Customer::where('status', 'active')
        ->orderBy('nama_customer')
        ->get();
    
    return view('admin.sales.request-order.edit', compact('requestOrder', 'barangs', 'customers'));
}
```

### 4. Sidebar Navigation

**File**: `resources/views/admin/layouts/sidebar.blade.php`

Added menu item under Sales Module:
```blade
<li class="w-[179px]">
    <a href="{{ route('sales.customer.index') }}"
        class="...">
        <svg>...</svg>
        <span>Customer Management</span>
    </a>
</li>
```

---

## Usage Examples

### Example 1: Create New Customer

1. Buka **Sales Module → Customer Management**
2. Klik **Tambah Customer** button
3. Isi form:
   - Nama Customer: "PT. Global Trade"
   - Email: "sales@globaltrade.com"
   - Telepon: "021-9876543"
   - Tipe Customer: "Wholesale"
   - Status: "Aktif"
   - Alamat & info lokasi
4. Klik **Simpan Customer**
5. Redirect ke detail customer dengan success message

### Example 2: Create Request Order with Existing Customer

1. Buka **Sales Module → Request Order → Buat Request Order Baru**
2. Di section "Informasi Customer":
   - Select customer dari dropdown
   - Fields akan auto-populate (nama, email, telepon, kota)
3. Isi tanggal kebutuhan dan catatan
4. Tambah barang-barang yang diperlukan
5. Klik **Buat Request Order**

### Example 3: Add New Customer During Request Order Creation

1. Di **Request Order Create Form**
2. Klik **Tambah Customer Baru** button
3. Modal dialog muncul
4. Isi data customer baru
5. Klik **Simpan Customer** di modal
6. Customer otomatis ditambahkan ke dropdown dan dipilih
7. Lanjutkan membuat Request Order

### Example 4: Edit Customer

1. Buka **Sales Module → Customer Management**
2. Cari customer, klik **Edit** (pencil icon)
3. Update data yang dibutuhkan
4. Klik **Simpan Perubahan**
5. Redirect ke detail dengan updated_at timestamp terupdate

### Example 5: Delete Customer

1. Buka detail customer (klik View icon)
2. Klik **Hapus** button
3. Dialog konfirmasi muncul
4. Jika customer memiliki pesanan, akan muncul error
5. Jika tidak, customer dihapus dan redirect ke list

---

## Permissions & Authorization

### Route Middleware

Semua customer routes dilindungi dengan:
- `auth`: User harus login
- `role:Sales`: User harus memiliki role Sales

```php
Route::middleware(['auth', 'role:Sales'])->group(function () {
    Route::resource('customer', CustomerController::class)->names('customer');
    Route::get('/customer/api/search', [CustomerController::class, 'search'])->name('customer.search');
});
```

### Controller-Level Authorization

**edit()** & **update()**: Hanya bisa jika status = 'pending'
```php
if ($requestOrder->status !== 'pending') {
    return back()->withErrors('Hanya Request Order yang pending dapat diubah.');
}
```

**show()**: Dapat dilihat oleh Sales yang create atau Supervisor/Admin
```php
if ($requestOrder->sales_id !== Auth::id() && 
    !in_array(Auth::user()->role, ['Supervisor', 'Warehouse', 'Admin'])) {
    abort(403);
}
```

### Data Isolation

- List customer menampilkan semua customer (tidak ada filtering by creator)
- Customer adalah master data yang shared antar Sales users
- Audit trail: created_by dan updated_by tracks siapa yang create/update

---

## Validation Rules

### Store & Update Customer

```php
$validated = $request->validate([
    'nama_customer' => 'required|string|max:255',
    'email' => 'nullable|email|unique:customers' . ($update ? ',' . $customer->id : ''),
    'telepon' => 'nullable|string|max:20',
    'alamat' => 'nullable|string',
    'kota' => 'nullable|string|max:100',
    'provinsi' => 'nullable|string|max:100',
    'kode_pos' => 'nullable|string|max:10',
    'tipe_customer' => 'nullable|in:retail,wholesale,distributor',
    'status' => 'required|in:active,inactive',
]);
```

### Store Request Order (with customer_id)

```php
$validated = $request->validate([
    'customer_id' => 'required|exists:customers,id',
    'tanggal_kebutuhan' => 'nullable|date',
    'catatan_customer' => 'nullable|string',
    'barang_id' => 'required|array|min:1',
    'barang_id.*' => 'required|exists:barangs,id',
    // ... other fields
]);
```

---

## Error Handling

### Customer Creation Errors

- **Email unique violation**: "Email sudah digunakan"
- **Missing required field**: "Nama customer harus diisi"
- **Invalid enum value**: "Tipe customer tidak valid"

### Delete Customer Errors

- **Has related orders**: "Customer tidak dapat dihapus karena memiliki pesanan"

### Request Order Errors

- **Missing customer**: "Customer harus dipilih"
- **Invalid customer_id**: "Customer dipilih tidak ditemukan"

---

## Future Enhancements

1. **Customer Groups** - Organize customers by region/type
2. **Credit Limit** - Track customer payment capacity
3. **Contact Persons** - Multiple contacts per customer
4. **Price Agreements** - Custom pricing per customer
5. **Bulk Import** - Import customer data from CSV
6. **Customer Activity Timeline** - View all orders/interactions
7. **Duplicate Detection** - Warn when creating similar customer
8. **Merge Customers** - Combine duplicate customer records

---

## Troubleshooting

### Issue: Customer dropdown tidak muncul di Request Order form

**Solution**: 
- Verify $customers variable passed to view: `compact('barangs', 'customers')`
- Check if customer with status='active' exists in database
- Check browser console for JavaScript errors

### Issue: Auto-fill tidak bekerja

**Solution**:
- Check data attributes on option elements: `data-email`, `data-telepon`, `data-kota`
- Verify JavaScript function `populateCustomerData()` defined
- Check browser DevTools > Console for JS errors

### Issue: Modal form tidak submit

**Solution**:
- Verify route `sales.customer.store` exists and correct
- Check network tab in DevTools for response
- Verify CSRF token included in form
- Check server logs for errors

### Issue: Cannot delete customer (error message)

**Solution**:
- Customer has related Request Orders or Sales Orders
- View orders linked to customer
- Delete/reassign orders before deleting customer
- Contact admin if needed to force delete

---

## Related Documentation

- [Sales Module Documentation](./SALES_MODULE_DOCUMENTATION.md)
- [Request Order Documentation](./QUICK_REFERENCE_SALES_MODULE.md)
- [Database Schema](./app/Models/)

---

## Changelog

- **v1.0** (November 13, 2025) - Initial implementation
  - Customer CRUD operations
  - Integration with Request Order
  - Customer selection in forms
  - Quick add modal
  - API search endpoint
