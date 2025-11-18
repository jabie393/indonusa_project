# Customer Management Architecture & Flow Diagrams

---

## 📐 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SALES MODULE - CUSTOMER MANAGEMENT                  │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────────┐
│                              USER INTERFACE                                   │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌────────────────────┐  ┌────────────────────┐  ┌────────────────────┐  │
│  │   Customer List    │  │ Request Order Form │  │    Sidebar Menu    │  │
│  │  ┌──────────────┐  │  │  ┌──────────────┐  │  │  ┌──────────────┐  │  │
│  │  │ Index View   │  │  │  │ Dropdown     │  │  │  │ Customer     │  │  │
│  │  │ Create Form  │  │  │  │ Select       │  │  │  │ Management   │  │  │
│  │  │ Edit Form    │  │  │  │ Modal Dialog │  │  │  │              │  │  │
│  │  │ View Details │  │  │  │ Auto-fill    │  │  │  └──────────────┘  │  │
│  │  │ Delete Btn   │  │  │  │ Fields       │  │  │                    │  │
│  │  └──────────────┘  │  │  └──────────────┘  │  │                    │  │
│  └────────────────────┘  └────────────────────┘  └────────────────────┘  │
│                                                                              │
│  JavaScript Functions:                                                     │
│  • populateCustomerData(customerId) - Auto-fill from dropdown             │
│  • Modal form submission (AJAX)                                           │
│  • Field validation and error display                                     │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
                                    ↑ ↓
┌──────────────────────────────────────────────────────────────────────────────┐
│                           ROUTING & MIDDLEWARE                                │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  GET    /customer              → CustomerController@index (List)            │
│  GET    /customer/create       → CustomerController@create (Create Form)   │
│  POST   /customer              → CustomerController@store (Save)           │
│  GET    /customer/{id}         → CustomerController@show (View)            │
│  GET    /customer/{id}/edit    → CustomerController@edit (Edit Form)      │
│  PUT    /customer/{id}         → CustomerController@update (Update)        │
│  DELETE /customer/{id}         → CustomerController@destroy (Delete)       │
│  GET    /customer/api/search   → CustomerController@search (API)           │
│                                                                              │
│  Middleware: auth, role:Sales                                              │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
                                    ↑ ↓
┌──────────────────────────────────────────────────────────────────────────────┐
│                          CONTROLLERS & BUSINESS LOGIC                         │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  CustomerController (8 methods)                                            │
│  ├── index()    - Paginate & display customers                             │
│  ├── create()   - Return create form                                       │
│  ├── store()    - Validate & save customer (JSON or redirect)              │
│  ├── show()     - Load relationships & display details                     │
│  ├── edit()     - Return pre-populated edit form                           │
│  ├── update()   - Validate, update, track updated_by                       │
│  ├── destroy()  - Validate (no orders), delete customer                    │
│  └── search()   - Search by name/email/phone, return JSON                  │
│                                                                              │
│  RequestOrderController (1 method updated)                                  │
│  ├── edit()     - Load $customers = Customer::where('status', 'active')   │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
                                    ↑ ↓
┌──────────────────────────────────────────────────────────────────────────────┐
│                          MODELS & RELATIONSHIPS                              │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Customer Model                                                             │
│  ├─ hasMany(RequestOrder)     → Get all customer's request orders          │
│  ├─ hasMany(SalesOrder)       → Get all customer's sales orders            │
│  ├─ belongsTo(User, 'created_by')                                          │
│  ├─ belongsTo(User, 'updated_by')                                          │
│  └─ Accessor: getFullAddressAttribute()                                    │
│                                                                              │
│  RequestOrder Model (Updated)                                               │
│  └─ belongsTo(Customer)       → Get customer for this request              │
│                                                                              │
│  SalesOrder Model (Updated)                                                 │
│  └─ belongsTo(Customer)       → Get customer for this order                │
│                                                                              │
│  User Model (Existing)                                                      │
│  ├─ hasMany(Customer, 'created_by')                                        │
│  └─ hasMany(Customer, 'updated_by')                                        │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
                                    ↑ ↓
┌──────────────────────────────────────────────────────────────────────────────┐
│                             DATABASE LAYER                                   │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  customers Table                                                            │
│  ├── id (PK)                                                               │
│  ├── nama_customer                                                          │
│  ├── email (UNIQUE)                                                        │
│  ├── telepon                                                                │
│  ├── alamat, kota, provinsi, kode_pos                                      │
│  ├── tipe_customer (ENUM: retail/wholesale/distributor)                   │
│  ├── status (ENUM: active/inactive)                                       │
│  ├── created_by (FK → users.id)                                            │
│  ├── updated_by (FK → users.id)                                            │
│  ├── created_at, updated_at                                                │
│  └── Indexes on: email, created_by, updated_by                             │
│                                                                              │
│  Relationships:                                                             │
│  • customers.id ← request_orders.customer_id (1-to-many)                   │
│  • customers.id ← sales_orders.customer_id (1-to-many)                     │
│  • customers.created_by → users.id                                         │
│  • customers.updated_by → users.id                                         │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Request Order Creation Flow with Customer

```
START
  │
  ├─→ User clicks "Buat Request Order Baru"
  │
  ├─→ RequestOrderController@create()
  │   ├─ Load barangs
  │   ├─ Load $customers = Customer::where('status', 'active')
  │   └─ Return view('admin.sales.request-order.create', compact('barangs', 'customers'))
  │
  ├─→ Blade Renders Create Form
  │   ├─ Customer Section:
  │   │  ├─ Dropdown select #customer_id
  │   │  │  └─ @foreach($customers as $c)
  │   │  │     <option value="{{ $c->id }}" data-email data-telepon data-kota>
  │   │  │     {{ $c->nama_customer }} ({{ $c->email }})
  │   │  │
  │   │  ├─ Auto-fill Display Fields (readonly):
  │   │  │  ├─ #customer_name
  │   │  │  ├─ #customer_email
  │   │  │  ├─ #customer_telepon
  │   │  │  └─ #customer_kota
  │   │  │
  │   │  └─ "Tambah Customer Baru" button
  │   │     └─ data-bs-toggle="modal" data-bs-target="#addCustomerModal"
  │   │
  │   ├─ Other Sections (unchanged):
  │   │  ├─ Tanggal Kebutuhan, Catatan
  │   │  ├─ Detail Barang (items table)
  │   │  └─ Action Buttons
  │   │
  │   └─ Modal Dialog: #addCustomerModal
  │      └─ Form #addCustomerForm
  │         ├─ nama_customer, email, telepon
  │         ├─ tipe_customer, status
  │         ├─ alamat, kota, provinsi, kode_pos
  │         └─ Submit button
  │
  ├─→ User Interaction Path 1: Select Existing Customer
  │   │
  │   ├─ User selects customer from dropdown
  │   │
  │   ├─ onchange="populateCustomerData(this.value)" triggers
  │   │
  │   ├─ JavaScript function populateCustomerData():
  │   │  ├─ Get selected option element
  │   │  ├─ Extract data attributes (email, telepon, kota)
  │   │  ├─ Extract name from option text
  │   │  └─ Set readonly display fields
  │   │
  │   ├─ User fills remaining form (items, dates, notes)
  │   │
  │   ├─ User clicks "Buat Request Order"
  │   │
  │   ├─ Form POST to /request-order
  │   │
  │   ├─ RequestOrderController@store():
  │   │  ├─ Validate input (including customer_id)
  │   │  ├─ Create RequestOrder record with customer_id
  │   │  ├─ Create RequestOrderItem records
  │   │  └─ Redirect to show/list
  │   │
  │   └─ Success message & display
  │
  ├─→ User Interaction Path 2: Add New Customer via Modal
  │   │
  │   ├─ User clicks "Tambah Customer Baru" button
  │   │
  │   ├─ Bootstrap modal opens: #addCustomerModal
  │   │
  │   ├─ User fills customer form in modal
  │   │
  │   ├─ User clicks "Simpan Customer" button
  │   │
  │   ├─ JavaScript addEventListener('submit') on #addCustomerForm
  │   │
  │   ├─ Prevent default form submission
  │   │
  │   ├─ Create FormData from form
  │   │
  │   ├─ AJAX POST to {{ route('sales.customer.store') }}
  │   │  └─ Headers: X-Requested-With: XMLHttpRequest
  │   │
  │   ├─ CustomerController@store():
  │   │  ├─ Validate customer data
  │   │  ├─ Check if AJAX request (wantsJson)
  │   │  ├─ Create customer record with created_by = Auth::id()
  │   │  └─ Return JSON response:
  │   │     {
  │   │       "success": true,
  │   │       "customer": {
  │   │         "id": 123,
  │   │         "nama_customer": "PT. Baru",
  │   │         "email": "contact@baru.com",
  │   │         "telepon": "021-xxx",
  │   │         "kota": "Jakarta"
  │   │       }
  │   │     }
  │   │
  │   ├─ JavaScript receives response
  │   │
  │   ├─ If success:
  │   │  ├─ Create new option element with customer data
  │   │  ├─ Add option to #customer_id dropdown
  │   │  ├─ Set option as selected
  │   │  ├─ Call populateCustomerData(newCustomerId)
  │   │  ├─ Reset modal form
  │   │  ├─ Hide modal
  │   │  ├─ Show success alert
  │   │  └─ Focus on next form field
  │   │
  │   ├─ User continues filling Request Order form
  │   │
  │   ├─ User clicks "Buat Request Order"
  │   │
  │   ├─ Form POST with newly added customer
  │   │
  │   └─ Success message & display
  │
  └─→ END - Request Order created with customer data
```

---

## 🌳 Database Relationship Diagram

```
                        users (auth_users)
                       /              \
                      /                \
                    [id]               [id]
                     /                   \
                    /                     \
         ┌─────────────────┐       ┌─────────────────┐
         │   created_by    │       │   updated_by    │
         └─────────────────┘       └─────────────────┘
               ↓                         ↓
            CUSTOMERS
         ┌──────────────┐
         │ id           │ (PK)
         │ nama_customer│
         │ email        │ (UNIQUE)
         │ telepon      │
         │ alamat       │
         │ kota         │
         │ provinsi     │
         │ kode_pos     │
         │ tipe_customer│ (ENUM)
         │ status       │ (ENUM)
         │ created_by   │ (FK→users.id)
         │ updated_by   │ (FK→users.id)
         │ created_at   │
         │ updated_at   │
         └──────────────┘
            ↓          ↓
            │          └──→ [customer_id]
            │               REQUEST ORDERS
            │          ┌──────────────────┐
            │          │ id               │
            │          │ request_number   │
            │          │ customer_id      │ (FK)
            │          │ customer_name    │
            │          │ tanggal_kebutuhan│
            │          │ catatan_customer │
            │          │ status           │
            │          │ sales_id         │
            │          │ created_at       │
            │          └──────────────────┘
            │               ↓ (has many)
            │        REQUEST ORDER ITEMS
            │
            └──→ [customer_id]
                 SALES ORDERS
            ┌──────────────────┐
            │ id               │
            │ sales_order_no   │
            │ customer_id      │ (FK)
            │ customer_name    │
            │ status           │
            │ sales_id         │
            │ created_at       │
            └──────────────────┘
                 ↓ (has many)
            SALES ORDER ITEMS
```

---

## 🔐 Authorization Flow

```
REQUEST
   │
   ├─→ middleware('auth')
   │   └─→ Is user logged in?
   │       ├─ YES → Continue
   │       └─ NO → Redirect to login
   │
   ├─→ middleware('role:Sales')
   │   └─→ Does user have Sales role?
   │       ├─ YES → Continue
   │       └─ NO → Abort 403 Forbidden
   │
   ├─→ CustomerController method
   │   └─→ Check operation-specific rules
   │       ├─ delete() → Check if has orders
   │       ├─ edit() → Check if pending status
   │       └─ All others → Allow if authorized by middleware
   │
   └─→ RESPONSE
```

---

## 📊 Form Submission Flow

```
HTML Form Submit
   │
   ├─→ Client-side validation (HTML5)
   │   └─ Required fields, email format, etc.
   │
   ├─→ JavaScript validation (if any custom rules)
   │
   ├─→ POST request to backend
   │
   ├─→ Middleware checks (auth, role)
   │
   ├─→ Controller validation (Form Request or validate())
   │   ├─ nama_customer: required|string|max:255
   │   ├─ email: nullable|email|unique:customers
   │   ├─ telepon: nullable|string|max:20
   │   ├─ tipe_customer: nullable|in:retail,wholesale,distributor
   │   ├─ status: required|in:active,inactive
   │   └─ ... and more
   │
   ├─→ If validation fails
   │   ├─ Return back with errors
   │   └─ Preserve old input (old() helper)
   │
   ├─→ If validation passes
   │   ├─ Check if AJAX request (wantsJson)
   │   ├─ If AJAX: Return JSON response
   │   └─ If Form: Redirect to show page
   │
   ├─→ Create/Update/Delete model
   │   ├─ Set created_by or updated_by
   │   └─ Save to database
   │
   └─→ Success response
```

---

## 🎨 UI Component Hierarchy

```
Page Layout
├── Header
│   ├── Page Title
│   ├── Description
│   └── Back Button
│
├── Alert Messages (if any)
│   ├── Success Alert
│   ├── Error Alert
│   └── Warning Alert
│
├── Main Content
│   ├── Form/Table Card
│   │   ├── Card Header
│   │   │   ├── Title
│   │   │   ├── Icon
│   │   │   └── Action Buttons (if list view)
│   │   │
│   │   └── Card Body
│   │       ├── Form Fields (if create/edit)
│   │       │   ├── Input Group 1
│   │       │   │   ├── Label
│   │       │   │   ├── Input
│   │       │   │   ├── Help Text
│   │       │   │   └── Error Message
│   │       │   └── Input Group 2...
│   │       │
│   │       ├── Table (if list)
│   │       │   ├── Header Row
│   │       │   │   └── Column Headers
│   │       │   ├── Data Rows
│   │       │   │   ├── Columns
│   │       │   │   └── Action Buttons
│   │       │   └── Pagination Controls
│   │       │
│   │       └── Action Buttons
│   │           ├── Primary (Submit/Save)
│   │           ├── Secondary (Cancel/Back)
│   │           └── Danger (Delete)
│   │
│   └── Sidebar (if show page)
│       ├── Stats Card
│       ├── Info Card
│       └── Quick Actions
│
└── Modal Dialog (if applicable)
    ├── Modal Header
    │   ├── Title
    │   └── Close Button
    ├── Modal Body
    │   └── Form Fields
    └── Modal Footer
        ├── Cancel Button
        └── Submit Button
```

---

## 🔄 CRUD Operation Flows

### CREATE
```
GET /customer/create
   → Return form view

POST /customer
   → Validate
   → Create record with created_by
   → Return JSON (AJAX) or Redirect to show
```

### READ
```
GET /customer
   → Paginate & query database
   → Return list view with all customers

GET /customer/{id}
   → Load with relationships (createdBy, updatedBy, etc.)
   → Return detail view
```

### UPDATE
```
GET /customer/{id}/edit
   → Load record
   → Return form view with pre-populated data

PUT /customer/{id}
   → Validate
   → Update record with updated_by
   → Redirect to show with success
```

### DELETE
```
DELETE /customer/{id}
   → Check if has related orders
   → If yes: Return error
   → If no: Delete record
   → Redirect to list with success
```

---

## 🎯 Integration Points

### Point 1: Request Order Create Form
```
Request Order Form
   ↓
Customer Section
   ├─ Dropdown (populated from $customers passed by controller)
   ├─ Auto-fill fields (JavaScript driven)
   ├─ Modal button (Bootstrap)
   └─ Modal form (AJAX submission)
```

### Point 2: Request Order Edit Form
```
Request Order Edit Form
   ↓
Customer Section (same as create)
   ├─ Pre-selected customer
   ├─ Auto-populated on page load
   └─ Can change if still pending
```

### Point 3: Sidebar Navigation
```
Sidebar
   ↓
Sales Module Dropdown
   ├─ Request Order (Penawaran)
   ├─ Sales Order (Pesanan)
   └─ Customer Management ← NEW
```

---

## 📱 Data Flow Diagram

```
USER INPUT
    ↓
FORM SUBMISSION
    ├─ HTML Submit (normal form)
    └─ AJAX Submit (modal form)
    ↓
CONTROLLER
    ├─ Validation
    ├─ Business Logic
    └─ Model Interaction
    ↓
MODEL
    ├─ Eloquent ORM
    └─ Relationships
    ↓
DATABASE
    ├─ Insert/Update/Delete
    └─ Referential Integrity
    ↓
RESPONSE
    ├─ JSON (AJAX)
    ├─ HTML Redirect (Form)
    └─ Error Messages
    ↓
USER
    ├─ See success message
    ├─ See error message
    └─ View updated data
```

---

*Generated: November 13, 2025*
