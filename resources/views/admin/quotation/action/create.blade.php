<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">


        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: `<div class="text-center text-md">@foreach ($errors->all() as $error)<span>{{ $error }}</span><br>@endforeach</div>`,
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'rounded-2xl!'
                        }
                    });
                });
            </script>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('sales.quotation.store') }}" id="requestOrderForm" enctype="multipart/form-data">
                    @csrf

                    <!-- Customer Info Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 rounded-2xl shadow-sm">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> Informasi Customer
                            </h3>
                        </div>

                        <div class="mb-8 grid grid-cols-1 gap-6 p-5 lg:grid-cols-2">

                            <div class="col-span-2 flex flex-col">
                                <label for="customer_id" class="form-label text-gray-700 dark:text-gray-300">Pilih
                                    Customer <span class="text-danger">*</span></label>
                                <select
                                    class="@error('customer_id') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="customer_id" name="customer_id" required onchange="populateCustomerData(this.value)">
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach ($customers as $c)
                                        <option value="{{ $c->id }}" data-email="{{ $c->email }}" data-telepon="{{ $c->telepon }}" data-kota="{{ $c->kota }}"
                                            @selected(old('customer_id') == $c->id)>
                                            {{ $c->nama_customer }}
                                            @if ($c->email)
                                                ({{ $c->email }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 dark:text-gray-400">Pilih dari daftar customer yang sudah
                                    terdaftar</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_name" class="form-label text-gray-700 dark:text-gray-300">Nama
                                    Customer</label>
                                <input type="text"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="customer_name" name="customer_name" value="{{ old('customer_name') }}" readonly>
                                <small class="text-muted dark:text-gray-400">Auto-filled dari customer yang
                                    dipilih</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_email" class="form-label text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="customer_email" readonly>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_telepon" class="form-label text-gray-700 dark:text-gray-300">Telepon</label>
                                <input type="text"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="customer_telepon" readonly>
                            </div>
                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_kota" class="form-label text-gray-700 dark:text-gray-300">Kota</label>
                                <input type="text"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="customer_kota" readonly>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="pic_id" class="form-label text-gray-700 dark:text-gray-300">PIC (Sales)
                                    <span class="text-danger">*</span></label>
                                <select
                                    class="@error('pic_id') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="pic_id" name="pic_id" required>
                                    <option value="">-- Pilih PIC Sales --</option>
                                    @foreach ($salesUsers as $sales)
                                        <option value="{{ $sales->id }}" @selected(old('pic_id', Auth::id()) == $sales->id)>
                                            {{ $sales->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pic_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted dark:text-gray-400">Pilih sales yang menangani Quotation
                                    ini</small>
                            </div>

                            <div class="col-span-2 flex flex-col">
                                <label for="subject" class="form-label text-gray-700 dark:text-gray-300">Subject <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="@error('subject') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="subject" name="subject" value="{{ old('subject') }}" placeholder="Masukkan subject untuk penawaran" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted dark:text-gray-400">Subject yang akan muncul di PDF
                                    penawaran</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="no_po" class="form-label text-gray-700 dark:text-gray-300">No.
                                    PO</label>
                                <input type="text"
                                    class="@error('no_po') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="no_po" name="no_po" value="{{ old('no_po') }}" placeholder="Masukkan No. PO">
                                @error('no_po')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted dark:text-gray-400">Nomor Purchase Order. Harus unik, tidak boleh sama dengan penawaran lain.</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="sales_order_number" class="form-label text-gray-700 dark:text-gray-300">No. SO</label>
                                <input type="text"
                                    class="@error('sales_order_number') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="sales_order_number" name="sales_order_number" value="{{ old('sales_order_number', App\Models\Quotation::generateSalesOrderNumber()) }}"
                                    placeholder="No. SO akan diisi otomatis" readonly>
                                @error('sales_order_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted dark:text-gray-400">Nomor Sales Order (Otomatis, tidak bisa diubah)</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="required_date" class="form-label text-gray-700 dark:text-gray-300">Tanggal
                                    Kebutuhan</label>
                                <input type="date"
                                    class="@error('required_date') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="required_date" name="required_date" value="{{ old('required_date') }}">
                                @error('required_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_notes" class="form-label text-gray-700 dark:text-gray-300">Catatan</label>
                                <textarea
                                    class="@error('customer_notes') is-invalid @enderror block min-h-[80px] w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                    id="customer_notes" name="customer_notes" rows="4">{{ old('customer_notes', "Syarat dan Ketentuan:\n1. Harga Franko On Site\n2. Harga Sudah Include PPN 11%\n3. Penawaran berlaku 2 Minggu") }}</textarea>
                                @error('customer_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <!-- Items Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 max-h-[80vh] rounded-2xl shadow-sm" id="barangSection"
                        style="display: flex;">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                                    </path>
                                    <path d="M12 22V12"></path>
                                    <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                                    <path d="m7.5 4.27 9 5.15"></path>
                                </svg>
                                Detail Barang
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <div id="discountWarning" class="alert alert-warning m-4" style="display:none;">
                                Diskon lebih dari 20% pada salah satu item. Penawaran akan menunggu persetujuan
                                Supervisor.
                            </div>
                            <table class="h-full w-full border-collapse" id="itemsTable">
                                <thead>
                                    <tr class="">
                                        <th
                                            class="sticky top-0 z-20 min-w-[150px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Kategori Barang</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[150px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Kode Barang</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[250px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Nama Barang</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[100px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Diskon (%)</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[200px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Keterangan</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[100px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Jumlah</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[180px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Harga Satuan</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[150px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Gambar</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[180px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Harga Setelah Diskon</th>
                                        <th
                                            class="sticky top-0 z-20 min-w-[80px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    <tr class="item-row">
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <select name="product_category[]"
                                                class="form-control kategori-barang-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <div class="barang-dropdown-container relative">
                                                <!-- Trigger Button -->
                                                <button type="button"
                                                    class="border-subtle bg-surface text-body-sm text-on-surface-variant hover:border-primary dropdown-toggle-btn flex w-full items-center justify-between rounded-lg border px-3 py-2 transition-all">
                                                    <span class="flex gap-2">
                                                        <span class="selected-barang-label text-nowrap">Pilih Barang</span>
                                                    </span>
                                                    <span class="h-[16px] w-[16px] text-[4px]">
                                                        <svg class="text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                            <g id="SVGRepo_iconCarrier">
                                                                <path
                                                                    d="M5.70711 9.71069C5.31658 10.1012 5.31658 10.7344 5.70711 11.1249L10.5993 16.0123C11.3805 16.7927 12.6463 16.7924 13.4271 16.0117L18.3174 11.1213C18.708 10.7308 18.708 10.0976 18.3174 9.70708C17.9269 9.31655 17.2937 9.31655 16.9032 9.70708L12.7176 13.8927C12.3271 14.2833 11.6939 14.2832 11.3034 13.8927L7.12132 9.71069C6.7308 9.32016 6.09763 9.32016 5.70711 9.71069Z"
                                                                    fill="currentColor"></path>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </button>

                                                <!-- Hidden Select (maintains compatibility with existing JS / validations) -->
                                                <select name="product_id[]" class="form-control barang-select @error('barang_id.*') is-invalid @enderror hidden" required
                                                    onchange="updateKategoriBarang(this)">
                                                    <option value="">Pilih Barang</option>
                                                    @foreach ($goods as $b)
                                                        <option value="{{ $b->id }}" data-kode="{{ $b->goods_code }}" data-nama="{{ $b->goods_name }}"
                                                            data-kategori="{{ $b->category }}" data-stok="{{ $b->stock }}" data-satuan="{{ $b->unit ?? '' }}"
                                                            data-harga="{{ $b->selling_price ?? 0 }}" data-diskon="{{ $b->discount_percent ?? 0 }}">
                                                            {{ $b->goods_code }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <!-- Dropdown Menu -->
                                                <div class="border-subtle dropdown-menu-container fixed z-[9999] hidden w-[600px] overflow-hidden rounded-xl border bg-white shadow-2xl">
                                                    <!-- Search Header -->
                                                    <div class="border-subtle bg-surface-container-low border-b p-3">
                                                        <div class="relative">
                                                            <span class="text-on-surface-variant absolute left-3 top-1/2 -translate-y-1/2 text-[18px]"><svg class="h-5 w-5" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                                    <g id="SVGRepo_iconCarrier">
                                                                        <path
                                                                            d="M14.9536 14.9458L21 21M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    </g>
                                                                </svg></span>
                                                            <input
                                                                class="border-subtle text-body-sm focus:ring-primary focus:border-primary search-barang-input w-full rounded-lg border bg-white py-2 pl-10 pr-4 outline-none focus:ring-1"
                                                                placeholder="Cari kode atau nama barang..." type="text">
                                                        </div>
                                                    </div>
                                                    <!-- Dropdown Table -->
                                                    <div class="max-h-[300px] overflow-y-auto">
                                                        <table class="w-full text-left">
                                                            <thead class="border-subtle sticky top-0 border-b bg-white">
                                                                <tr>
                                                                    <th class="font-table-header text-on-surface-variant px-4 py-2 text-[11px] uppercase tracking-wider">Kode Barang</th>
                                                                    <th class="font-table-header text-on-surface-variant px-4 py-2 text-[11px] uppercase tracking-wider">Nama Barang</th>
                                                                    <th class="font-table-header text-on-surface-variant px-4 py-2 text-[11px] uppercase tracking-wider">Description</th>
                                                                    <th class="w-8"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-subtle/30 barang-options-body divide-y">
                                                                @foreach ($goods as $b)
                                                                    <tr class="hover:bg-surface-container-high barang-option-row cursor-pointer" data-id="{{ $b->id }}"
                                                                        data-kode="{{ $b->goods_code }}" data-nama="{{ $b->goods_name }}" data-kategori="{{ $b->category }}"
                                                                        data-deskripsi="{{ $b->description ?? '' }}">
                                                                        <td class="text-body-sm text-nowrap px-4 py-3 font-semibold">{{ $b->goods_code }}</td>
                                                                        <td class="text-body-sm text-nowrap px-4 py-3">{{ $b->goods_name }}</td>
                                                                        <td class="text-on-surface-variant px-4 py-3 text-[12px]">{{ $b->description ?? '-' }}</td>
                                                                        <td class="text-primary select-check-icon pr-4 text-right">
                                                                            <span class="material-symbols-outlined checked-icon hidden text-[18px]"><svg class="h-5 w-5" viewBox="0 0 24 24"
                                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC"
                                                                                        stroke-width="0.048"></g>
                                                                                    <g id="SVGRepo_iconCarrier">
                                                                                        <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                                                                            stroke-linejoin="round"></path>
                                                                                    </g>
                                                                                </svg></span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text"
                                                class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                readonly>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="discount_percent[]"
                                                class="form-control diskon-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                min="0" max="100" step="0.01" value="0">
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text" name="keterangan[]" maxlength="255"
                                                class="form-control keterangan-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="Isi jika diskon > 20%" disabled>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="quantity[]"
                                                class="form-control quantity-input @error('quantity.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                min="1" value="1" required>
                                            <div class="stok-info mt-1 hidden text-xs">
                                                <span class="stok-ok hidden font-medium text-green-600"></span>
                                                <span class="stok-warn hidden font-semibold text-red-500">âš  Stok kurang! Tersedia: <span class="stok-angka font-bold"></span></span>
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <div class="relative flex items-center">
                                                <span class="absolute left-3 text-sm text-gray-500 dark:text-gray-400">Rp</span>
                                                <input type="text" name="price[]"
                                                    class="form-control harga-input @error('harga.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-9 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                    value="" placeholder="0">
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 text-center dark:border-gray-600">
                                            <div class="upload-btn-container relative">
                                                <input type="file" name="item_images[0][]" class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0" multiple
                                                    accept="image/*">
                                                <button type="button" class="rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1c4d81]">
                                                    Upload
                                                </button>
                                            </div>
                                            <div class="item-images-preview flex flex-wrap justify-center gap-2 space-y-2"></div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text"
                                                class="form-control harga-setelah-diskon-display @error('harga.*') is-invalid @enderror block h-10 w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                style="min-width: 100px; font-size: 1rem; font-weight: 500;" readonly>
                                        </td>

                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <button type="button" class="btn remove-row rounded-lg bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                                                style="display: none;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 h-4 w-4">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="8" class="border border-gray-300 px-4 py-2 text-end text-black dark:border-gray-600 dark:text-gray-300">
                                            TOTAL:</th>
                                        <th class="border border-gray-300 px-4 py-2 text-black dark:border-gray-600 dark:text-gray-300">
                                            <strong id="totalAmount">Rp 0</strong>
                                        </th>
                                        <th class="border border-gray-300 px-4 py-2 dark:border-gray-600"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <button type="button" id="addRow"
                            class="btn inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm m-5 border-none bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Tambah Barang
                        </button>

                    </div>

                    <!-- Summary Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mt-4 rounded-2xl shadow-md">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <i class="fas fa-calculator"></i> Ringkasan Penawaran
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Sub
                                                Total</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white" id="summarySubtotal">Rp 0</p>
                                        </div>
                                        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="mb-1 flex items-center justify-start">
                                                <div class="flex items-center gap-1 rounded border border-gray-300 bg-white px-2 py-0.5 dark:border-gray-500 dark:bg-gray-600"
                                                    style="width: fit-content;">
                                                    <p class="w-fit text-sm font-medium text-gray-600 dark:text-gray-300">
                                                        Pajak/PPN</p>
                                                    <input type="number" id="tax_rate" name="tax_rate" value="11"
                                                        class="w-12 border-none bg-transparent p-0 text-right text-sm text-gray-900 focus:ring-0 dark:text-white" min="0" max="100">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">%</span>
                                                </div>
                                            </div>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white" id="summaryPPN">Rp 0</p>
                                        </div>
                                        <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                                            <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-6 0l6 6m-6-6v12"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Grand
                                                Total</p>
                                            <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="summaryGrandTotal">Rp 0</p>
                                        </div>
                                        <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supporting Images Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 rounded-2xl shadow-md" id="imagesSection" style="display: none;">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#1E9722] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <i class="fas fa-images"></i> Gambar Pendukung Penawaran
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="mb-3">
                                <label for="supporting_images" class="form-label text-gray-700 dark:text-gray-300">Unggah Gambar
                                    <span class="text-muted dark:text-gray-400">(Foto barang, contoh produk,
                                        desain,
                                        dll)</span></label>
                                <div class="input-group">
                                    <input type="file"
                                        class="form-control barang-nama-display @error('supporting_images.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        id="supporting_images" name="supporting_images[]" multiple accept="image/*">
                                    <small class="text-muted d-block mt-2 dark:text-gray-400">Format: JPG, PNG, GIF
                                        | Ukuran maksimal: 5MB per gambar</small>
                                </div>
                                @error('supporting_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="imagePreview" class="row g-2">
                                <!-- Preview images will be displayed here -->
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-4">
                        <a href="{{ route('sales.quotation.index') }}" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-2 h-4 w-4">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg> Batal
                        </a>
                        <button type="submit" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]" id="submitBtn" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save mr-2 h-4 w-4">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg> Buat Penawaran
                        </button>
                    </div>

            </div>
            <!-- Hidden Financial Totals -->
            <input type="hidden" name="subtotal" id="hiddenSubtotal" value="0">
            <input type="hidden" name="tax" id="hiddenTax" value="0">
            <input type="hidden" name="grand_total" id="hiddenGrandTotal" value="0">


            </form>
        </div>
    </div>

    <script>
        // Filter barang berdasarkan kategori yang dipilih
        function filterBarangByKategori(selectKategori) {
            var tr = selectKategori.closest('tr');
            var kategori = selectKategori.value;
            var barangSelect = tr.querySelector('.barang-select');

            if (barangSelect) {
                Array.from(barangSelect.options).forEach(function(opt) {
                    if (opt.value === '') {
                        // Selalu tampilkan placeholder
                        opt.style.display = '';
                    } else if (kategori && opt.getAttribute('data-kategori') === kategori) {
                        // Tampilkan hanya barang yang sesuai kategori
                        opt.style.display = '';
                    } else if (!kategori) {
                        // Jika tidak ada kategori dipilih, tampilkan semua
                        opt.style.display = '';
                    } else {
                        // Sembunyikan yang tidak sesuai
                        opt.style.display = 'none';
                    }
                });
                barangSelect.selectedIndex = 0;
                barangSelect.dispatchEvent(new Event('change'));
            }

            // Sync custom dropdown options visibility
            var dropdownRows = tr.querySelectorAll('.barang-option-row');
            dropdownRows.forEach(function(row) {
                var rowKategori = row.getAttribute('data-kategori');
                if (!kategori || rowKategori === kategori) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Clear search input
            var searchInput = tr.querySelector('.search-barang-input');
            if (searchInput) {
                searchInput.value = '';
            }

            // Reset label button text
            var labelSpan = tr.querySelector('.selected-barang-label');
            if (labelSpan) {
                labelSpan.textContent = 'Pilih Barang';
            }

            // Hide check icons
            var checkIcons = tr.querySelectorAll('.checked-icon');
            checkIcons.forEach(icon => icon.classList.add('hidden'));

            // Remove selected row backgrounds
            var optionRows = tr.querySelectorAll('.barang-option-row');
            optionRows.forEach(row => {
                row.classList.remove('bg-secondary-container/10', 'hover:bg-secondary-container/20');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Attach event listener ke semua kategori select yang sudah ada
            document.querySelectorAll('.kategori-barang-select').forEach(function(sel) {
                sel.addEventListener('change', function() {
                    filterBarangByKategori(this);
                });
            });
        });

        // Observer untuk kategori select baru yang ditambahkan
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            const kategoriSelect = node.querySelector('.kategori-barang-select');
                            if (kategoriSelect) {
                                kategoriSelect.addEventListener('change', function() {
                                    filterBarangByKategori(this);
                                });
                            }
                        }
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('itemRows');
            if (tbody) {
                observer.observe(tbody, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>


    <script>
        // Update kategori barang otomatis saat barang dipilih
        function updateKategoriBarang(select) {
            var kategoriInput = select.closest('tr').querySelector('.kategori-barang-display');
            var selectedOption = select.options[select.selectedIndex];
            kategoriInput.value = selectedOption.getAttribute('data-kategori') || '';
        }
        // Populate customer data from select dropdown
        function populateCustomerData(customerId) {
            const customerSelect = document.getElementById('customer_id');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];

            if (!customerId) {
                document.getElementById('customer_name').value = '';
                document.getElementById('customer_email').value = '';
                document.getElementById('customer_telepon').value = '';
                document.getElementById('customer_kota').value = '';
                return;
            }

            document.getElementById('customer_name').value = selectedOption.textContent.split('(')[0].trim();
            document.getElementById('customer_email').value = selectedOption.dataset.email || '';
            document.getElementById('customer_telepon').value = selectedOption.dataset.telepon || '';
            document.getElementById('customer_kota').value = selectedOption.dataset.kota || '';
        }

        // Handle Add Customer Form Submission
        document.addEventListener('DOMContentLoaded', function() {
            const addCustomerForm = document.getElementById('addCustomerForm');
            const addCustomerModal = new bootstrap.Modal(document.getElementById('addCustomerModal'));

            addCustomerForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Clear previous errors
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                    el.previousElementSibling.classList.remove('is-invalid');
                });

                const formData = new FormData(this);

                try {
                    const response = await fetch('{{ route('customer.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Add new customer to dropdown
                        const customerSelect = document.getElementById('customer_id');
                        const newOption = document.createElement('option');
                        newOption.value = data.customer.id;
                        newOption.textContent = data.customer.nama_customer + (data.customer.email ?
                            ' (' + data.customer.email + ')' : '');
                        newOption.dataset.email = data.customer.email || '';
                        newOption.dataset.telepon = data.customer.telepon || '';
                        newOption.dataset.kota = data.customer.kota || '';
                        newOption.selected = true;
                        customerSelect.appendChild(newOption);

                        // Populate fields with new customer data
                        populateCustomerData(data.customer.id);

                        // Reset form and close modal
                        addCustomerForm.reset();
                        addCustomerModal.hide();

                        // Show success message
                        showAlert('success', 'Customer berhasil ditambahkan!');
                    } else {
                        showAlert('danger', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                } catch (error) {
                    if (error.response) {
                        // Handle validation errors
                        const errors = error.response.data.errors || {};
                        Object.keys(errors).forEach(field => {
                            const errorElement = document.getElementById('error-' + field);
                            const inputElement = document.getElementById('modal' +
                                capitalizeFirst(field));

                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                                if (inputElement) {
                                    inputElement.classList.add('is-invalid');
                                }
                            }
                        });
                    } else {
                        showAlert('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                    }
                }
            });

            // Helper function to capitalize field names
            function capitalizeFirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1).replace(/_(.)/g, (match, letter) => letter
                    .toUpperCase());
            }

            // Helper function to show alert
            function showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Function to initialize thousand separator
            function initThousandSeparator(input) {
                if (!input) return;

                // Format initial value
                if (input.value) {
                    let value = input.value.replace(/[^0-9.]/g, '');
                    if (value) {
                        let parts = value.split('.');
                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        input.value = parts.join('.');
                    }
                }

                input.addEventListener('input', function(e) {
                    // Save cursor position
                    let cursorPosition = this.selectionStart;
                    let originalLength = this.value.length;

                    let value = this.value.replace(/[^0-9.]/g, '');
                    if (value) {
                        let parts = value.split('.');
                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        this.value = parts.join('.');
                    }

                    // Adjust cursor position
                    let newLength = this.value.length;
                    cursorPosition = cursorPosition + (newLength - originalLength);
                    this.setSelectionRange(cursorPosition, cursorPosition);
                });
            }

            const kategoriSelect = document.getElementById('kategori_barang');
            const barangSection = document.getElementById('barangSection');
            const imagesSection = document.getElementById('imagesSection');
            const addRowBtn = document.getElementById('addRow');
            const submitBtn = document.getElementById('submitBtn');
            const itemRows = document.getElementById('itemRows');
            const supportingImagesInput = document.getElementById('supporting_images');
            const imagePreview = document.getElementById('imagePreview');

            // Filter barang by selected kategori
            window.filterBarangByCategory = function(kategoriValue) {
                const barangSelects = document.querySelectorAll('.barang-select');
                let hasVisibleOptions = false;

                barangSelects.forEach(select => {
                    const options = select.querySelectorAll('option');
                    options.forEach(option => {
                        if (option.value === '') {
                            option.style.display = 'block'; // Always show placeholder
                        } else if (option.dataset.kategori === kategoriValue) {
                            option.style.display = 'block';
                            hasVisibleOptions = true;
                        } else {
                            option.style.display = 'none';
                        }
                    });
                    select.value = ''; // Reset selection
                });

                // Show/hide sections based on kategori selection
                // Determine visibility of addRowBtn based on visible options
                function anyBarangOptionVisible() {
                    const firstSelect = document.querySelector('.barang-select');
                    if (!firstSelect) return false;
                    return Array.from(firstSelect.options).some(opt => opt.value === '' || opt.style.display !==
                        'none');
                }

                if (kategoriValue) {
                    barangSection.style.display = 'block';
                    imagesSection.style.display = 'block';
                } else {
                    // if there are any visible barang options (e.g., no kategori list), keep sections visible
                    const visible = anyBarangOptionVisible();
                    barangSection.style.display = visible ? 'block' : 'none';
                    imagesSection.style.display = visible ? 'block' : 'none';
                }

                addRowBtn.style.display = anyBarangOptionVisible() ? 'inline-block' : 'none';

                updateSubmitState();
            };

            // Update submit button state depending on kategori selection or any selected barang
            function updateSubmitState() {
                const hasKategori = kategoriSelect && kategoriSelect.value;
                const anyBarangSelected = Array.from(document.querySelectorAll('.barang-select')).some(s => s
                    .value && s.value !== '');
                submitBtn.disabled = !(hasKategori || anyBarangSelected);
            }

            // Handle barang selection change
            function handleBarangChange(select) {
                const option = select.options[select.selectedIndex];
                const row = select.closest('.item-row');
                const namaDisplay = row.querySelector('.barang-nama-display');
                const quantityInput = row.querySelector('.quantity-input');
                const diskonInput = row.querySelector('.diskon-input');
                const hargaInput = row.querySelector('.harga-input');
                const hargaSetelahDiskonDisplay = row.querySelector('.harga-setelah-diskon-display');

                if (option.value) {
                    namaDisplay.value = option.dataset.nama || '';

                    // Set quantity to 1 otomatis
                    if (quantityInput) quantityInput.value = 1;

                    // Base price from barang
                    const baseHarga = parseFloat(option.dataset.harga || 0) || 0;
                    const defaultDiskon = parseFloat(option.dataset.diskon || '0') || 0;

                    // Determine which diskon to use: existing input value (if non-zero) or default from barang
                    let useDiskon = defaultDiskon;
                    if (diskonInput) {
                        const currentVal = parseFloat(diskonInput.value);
                        if (!isNaN(currentVal) && currentVal !== 0) {
                            useDiskon = currentVal;
                        } else {
                            diskonInput.value = defaultDiskon;
                        }
                    }

                    // Compute jual price (base + 30%)
                    const hargaJual = +(baseHarga * 1.3).toFixed(2);
                    // Harga satuan tetap tanpa diskon
                    if (hargaInput) {
                        hargaInput.value = hargaJual.toLocaleString('en-US');
                        // Trigger input event to format if needed
                        hargaInput.dispatchEvent(new Event('input'));
                    }

                    // Hitung harga setelah diskon otomatis (qty * harga satuan * (1 - diskon/100))
                    const qty = parseInt(quantityInput.value) || 1;
                    const hargaSetelahDiskon = qty * hargaJual * (1 - (useDiskon / 100));
                    if (hargaSetelahDiskonDisplay) {
                        hargaSetelahDiskonDisplay.value = hargaSetelahDiskon > 0 ?
                            'Rp ' + hargaSetelahDiskon.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) :
                            '0';
                    }
                } else {
                    namaDisplay.value = '';
                    if (quantityInput) quantityInput.value = 1;
                    if (diskonInput) diskonInput.value = 0;
                    if (hargaInput) hargaInput.value = 0;
                    if (hargaSetelahDiskonDisplay) hargaSetelahDiskonDisplay.value = '0';
                }
                updateKeteranganState(select.closest('tr'));
                calculateTotals();
            }

            // Get barang options HTML
            function getBarangOptionsHTML() {
                const firstSelect = document.querySelector('.barang-select');
                return firstSelect.innerHTML;
            }

            // Calculate harga setelah diskon, PPN, and totals
            function calculateTotals() {
                let subTotal = 0;
                let totalPPN = 0;
                let grandTotal = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
                    const hargaSatuan = parseFloat(row.querySelector('.harga-input').value.replace(/,/g, '')) || 0;
                    const diskon = parseFloat(row.querySelector('.diskon-input').value) || 0;

                    // Harga setelah diskon
                    const hargaSetelahDiskon = +(qty * hargaSatuan * (1 - (diskon / 100))).toFixed(2);

                    // Update display harga setelah diskon
                    const hargaSetelahDiskonDisplay = row.querySelector('.harga-setelah-diskon-display');
                    if (hargaSetelahDiskonDisplay) {
                        hargaSetelahDiskonDisplay.value = hargaSetelahDiskon > 0 ?
                            'Rp ' + hargaSetelahDiskon.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) :
                            '0';
                    }

                    // Add to subtotal
                    subTotal += hargaSetelahDiskon;

                });

                const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
                totalPPN = +(subTotal * (taxRate / 100)).toFixed(2);
                grandTotal = subTotal + totalPPN;

                // Update table total (harga setelah diskon total)
                document.getElementById('totalAmount').textContent = 'Rp ' + subTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Update summary section
                document.getElementById('summarySubtotal').textContent = 'Rp ' + subTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                document.getElementById('summaryPPN').textContent = 'Rp ' + totalPPN.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                document.getElementById('summaryGrandTotal').textContent = 'Rp ' + grandTotal.toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                // Update hidden inputs for submission
                document.getElementById('hiddenSubtotal').value = subTotal.toFixed(2);
                document.getElementById('hiddenTax').value = totalPPN.toFixed(2);
                document.getElementById('hiddenGrandTotal').value = grandTotal.toFixed(2);
            }

            // =============================================
            // VALIDASI STOK REAL-TIME
            // =============================================
            function updateStokInfo(row) {
                const barangSelect = row.querySelector('.barang-select');
                const qtyInput = row.querySelector('.quantity-input');
                const stokInfo = row.querySelector('.stok-info');
                if (!barangSelect || !qtyInput || !stokInfo) return;

                const stokOk = stokInfo.querySelector('.stok-ok');
                const stokWarn = stokInfo.querySelector('.stok-warn');
                const stokAngka = stokInfo.querySelector('.stok-angka');
                const selectedOption = barangSelect.options[barangSelect.selectedIndex];

                if (!selectedOption || !selectedOption.value) {
                    stokInfo.classList.add('hidden');
                    qtyInput.classList.remove('border-red-500', 'border-green-500');
                    return;
                }

                const stokTersedia = parseInt(selectedOption.getAttribute('data-stok') ?? '0') || 0;
                const satuan = selectedOption.getAttribute('data-satuan') || '';
                const qty = parseInt(qtyInput.value) || 0;

                stokInfo.classList.remove('hidden');

                if (qty > stokTersedia) {
                    if (stokOk) {
                        stokOk.classList.add('hidden');
                        stokOk.textContent = '';
                    }
                    if (stokWarn) stokWarn.classList.remove('hidden');
                    if (stokAngka) stokAngka.textContent = stokTersedia + (satuan ? ' ' + satuan : '');
                    qtyInput.classList.add('border-red-500');
                    qtyInput.classList.remove('border-green-500');
                } else if (qty > 0) {
                    if (stokOk) {
                        stokOk.textContent = 'Stok tersedia: ' + stokTersedia + (satuan ? ' ' + satuan : '');
                        stokOk.classList.remove('hidden');
                    }
                    if (stokWarn) stokWarn.classList.add('hidden');
                    qtyInput.classList.remove('border-red-500');
                    qtyInput.classList.add('border-green-500');
                } else {
                    if (stokOk) {
                        stokOk.textContent = 'Stok tersedia: ' + stokTersedia + (satuan ? ' ' + satuan : '');
                        stokOk.classList.remove('hidden');
                    }
                    if (stokWarn) stokWarn.classList.add('hidden');
                    qtyInput.classList.remove('border-red-500', 'border-green-500');
                }
            }

            // Helper: preview for item images
            function handleItemImagePreview(row) {
                const fileInput = row.querySelector('.item-images-input');
                const preview = row.querySelector('.item-images-preview');
                const uploadBtn = row.querySelector('.upload-btn-container');
                if (!fileInput || !preview) return;


                fileInput.addEventListener('change', function() {
                    // Clear existing previews
                    preview.innerHTML = '';
                    if (this.files.length > 0) {
                        uploadBtn.style.display = 'none';
                    } else {
                        uploadBtn.style.display = 'block';
                    }

                    const files = Array.from(this.files || []);
                    if (files.length === 0) return;

                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'relative inline-block';
                            imgContainer.innerHTML = `
                                <img src="${e.target.result}" class="w-20 h-20 object-cover rounded border" title="${file.name}">
                                <button type="button" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs remove-image-btn" data-index="${index}">
                                    âœ•
                                </button>
                            `;
                            preview.appendChild(imgContainer);

                            // Add click handler to remove button
                            const removeBtn = imgContainer.querySelector('.remove-image-btn');
                            removeBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                const removeIndex = parseInt(this.dataset.index);
                                const dataTransfer = new DataTransfer();

                                Array.from(fileInput.files).forEach((file, i) => {
                                    if (i !== removeIndex) {
                                        dataTransfer.items.add(file);
                                    }
                                });

                                fileInput.files = dataTransfer.files;
                                fileInput.dispatchEvent(new Event('change', {
                                    bubbles: true
                                }));
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }

            // Add row
            addRowBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const tbody = document.getElementById('itemRows');
                const firstRow = tbody.querySelector('tr');
                const newRow = firstRow.cloneNode(true);

                // Reset stok info di row baru
                const stokInfoNew = newRow.querySelector('.stok-info');
                if (stokInfoNew) {
                    stokInfoNew.classList.add('hidden');
                    const stokOkNew = stokInfoNew.querySelector('.stok-ok');
                    const stokWarnNew = stokInfoNew.querySelector('.stok-warn');
                    if (stokOkNew) {
                        stokOkNew.textContent = '';
                        stokOkNew.classList.add('hidden');
                    }
                    if (stokWarnNew) stokWarnNew.classList.add('hidden');
                }
                const newQtyInput = newRow.querySelector('.quantity-input');
                if (newQtyInput) newQtyInput.classList.remove('border-red-500', 'border-green-500');

                // Reset all inputs dan selects di baris baru
                newRow.querySelectorAll('input[type="text"], input[type="number"]').forEach(inp => {
                    inp.value = '';
                });
                // Ensure keterangan input is cleared and disabled by default
                const keteranganNew = newRow.querySelector('.keterangan-input');
                if (keteranganNew) {
                    keteranganNew.value = '';
                    keteranganNew.disabled = true;
                    keteranganNew.required = false;
                }

                // Set default PPN to 0 for new rows
                const ppnNew = newRow.querySelector('.ppn-input');
                if (ppnNew) {
                    ppnNew.value = '0';
                }

                newRow.querySelectorAll('select').forEach(sel => {
                    sel.selectedIndex = 0;
                });

                // Reset custom dropdown state for the cloned row
                const selectedLabel = newRow.querySelector('.selected-barang-label');
                if (selectedLabel) selectedLabel.textContent = 'Pilih Barang';

                const dropdownMenu = newRow.querySelector('.dropdown-menu-container');
                if (dropdownMenu) dropdownMenu.classList.add('hidden');

                newRow.querySelectorAll('.barang-option-row').forEach(row => {
                    row.classList.remove('bg-secondary-container/10', 'hover:bg-secondary-container/20');
                    row.style.display = '';
                });

                newRow.querySelectorAll('.checked-icon').forEach(icon => {
                    icon.classList.add('hidden');
                });

                // Hapus preview gambar
                const preview = newRow.querySelector('.item-images-preview');
                if (preview) preview.innerHTML = '';

                // Reset file input
                const fileInput = newRow.querySelector('.item-images-input');
                if (fileInput) fileInput.value = '';

                // Reset upload button visibility di baris baru
                const uploadBtnContainer = newRow.querySelector('.upload-btn-container');
                if (uploadBtnContainer) uploadBtnContainer.style.display = 'block';

                // Hapus preview gambar yang ter-clone
                const clonedPreview = newRow.querySelector('.item-images-preview');
                if (clonedPreview) clonedPreview.innerHTML = '';

                // Update index file input name
                const idx = document.querySelectorAll('.item-row').length;
                if (fileInput) fileInput.name = `item_images[${idx}][]`;

                tbody.appendChild(newRow);

                // Attach events ke baris baru
                attachRowEvents(newRow);
                attachCustomDropdownEvents(newRow);
                handleItemImagePreview(newRow);
                updateRemoveButtons();
                calculateTotals();
            });

            // Attach events to row
            function attachRowEvents(row) {
                const barangSelect = row.querySelector('.barang-select');
                barangSelect.addEventListener('change', function() {
                    handleBarangChange(this);
                    updateSubmitState();
                    updateStokInfo(row);
                });

                // Event untuk quantity input - hitung harga setelah diskon saat quantity berubah
                const quantityInput = row.querySelector('.quantity-input');
                if (quantityInput) {
                    quantityInput.addEventListener('change', function() {
                        const qty = parseInt(this.value) || 1;
                        const hargaInput = row.querySelector('.harga-input');
                        const hargaSetelahDiskonDisplay = row.querySelector(
                            '.harga-setelah-diskon-display');

                        if (hargaInput && hargaSetelahDiskonDisplay) {
                            const hargaSatuan = parseFloat(hargaInput.value.replace(/,/g, '')) || 0;
                            const diskonInput = row.querySelector('.diskon-input');
                            const diskon = parseFloat(diskonInput.value) || 0;
                            const hargaSetelahDiskon = qty * hargaSatuan * (1 - (diskon / 100));
                            hargaSetelahDiskonDisplay.value = hargaSetelahDiskon > 0 ?
                                'Rp ' + hargaSetelahDiskon.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) :
                                '0';
                        }
                        calculateTotals();
                        updateStokInfo(row);
                    });
                    quantityInput.addEventListener('input', function() {
                        updateStokInfo(row);
                    });
                }

                // Event untuk PPN input
                const ppnInput = row.querySelector('.ppn-input');
                if (ppnInput) {
                    ppnInput.addEventListener('change', calculateTotals);
                }

                row.querySelector('.harga-input').addEventListener('change', calculateTotals);
                initThousandSeparator(row.querySelector('.harga-input'));
                row.querySelector('.quantity-input').addEventListener('change', calculateTotals);
                const diskonInput = row.querySelector('.diskon-input');
                if (diskonInput) {
                    const updateHargaFromDiskon = function() {
                        // Update harga setelah diskon display, harga satuan tetap
                        const select = row.querySelector('.barang-select');
                        const quantityInput = row.querySelector('.quantity-input');
                        const hargaInput = row.querySelector('.harga-input');
                        const hargaSetelahDiskonDisplay = row.querySelector('.harga-setelah-diskon-display');
                        if (select && select.value && hargaInput && hargaSetelahDiskonDisplay) {
                            const qty = parseInt(quantityInput.value) || 1;
                            const hargaSatuan = parseFloat(hargaInput.value.replace(/,/g, '')) || 0;
                            const d = parseFloat(this.value) || 0;
                            const hargaSetelahDiskon = qty * hargaSatuan * (1 - (d / 100));
                            hargaSetelahDiskonDisplay.value = hargaSetelahDiskon > 0 ?
                                'Rp ' + hargaSetelahDiskon.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) :
                                '0';
                        }
                        calculateTotals();
                        updateDiscountWarning();
                        updateKeteranganState(row);
                    };
                    diskonInput.addEventListener('change', updateHargaFromDiskon);
                    diskonInput.addEventListener('input', updateHargaFromDiskon);
                }
                row.querySelector('.remove-row').addEventListener('click', function() {
                    row.remove();
                    updateRemoveButtons();
                    calculateTotals();
                    // reindex file input names after removal
                    document.querySelectorAll('.item-row').forEach((r, i) => {
                        const fi = r.querySelector('.item-images-input');
                        if (fi) fi.name = `item_images[${i}][]`;
                    });
                    updateSubmitState();
                });
            }

            function updateDiscountWarning() {
                const warning = document.getElementById('discountWarning');
                const anyHigh = Array.from(document.querySelectorAll('.diskon-input')).some(inp => {
                    const v = parseFloat(inp.value) || 0;
                    return v > 20;
                });
                if (anyHigh) {
                    warning.style.display = 'block';
                } else {
                    warning.style.display = 'none';
                }
            }
            // Enable/disable and require keterangan input depending on diskon value for a specific row
            function updateKeteranganState(row) {
                if (!row) return;
                const disk = row.querySelector('.diskon-input');
                const ket = row.querySelector('.keterangan-input');
                if (!disk || !ket) return;
                const val = parseFloat(disk.value) || 0;
                if (val > 20) {
                    ket.disabled = false;
                    ket.required = true;
                } else {
                    ket.disabled = true;
                    ket.required = false;
                    ket.value = '';
                }
            }

            // Disable remove button when only one row remains
            function updateRemoveButtons() {
                const rows = document.querySelectorAll('.item-row');
                const isSingleRow = rows.length <= 1;
                rows.forEach((row) => {
                    const btn = row.querySelector('.remove-row');
                    if (!btn) return;
                    btn.style.display = 'inline-block';
                    btn.disabled = isSingleRow;
                    if (isSingleRow) {
                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
            }

            // Handle supporting images upload and preview
            supportingImagesInput.addEventListener('change', function() {
                imagePreview.innerHTML = ''; // Clear previous previews
                const files = this.files;

                if (files.length === 0) {
                    return;
                }

                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" alt="Preview ${index + 1}" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="text-truncate d-block">${file.name}</small>
                                    <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                                </div>
                            </div>
                        `;
                        imagePreview.appendChild(col);
                    };

                    reader.readAsDataURL(file);
                });
            });

            // Custom Dropdown Event Handlers
            function attachCustomDropdownEvents(row) {
                const container = row.querySelector('.barang-dropdown-container');
                if (!container) return;

                const toggleBtn = container.querySelector('.dropdown-toggle-btn');
                const menu = container.querySelector('.dropdown-menu-container');
                const searchInput = container.querySelector('.search-barang-input');
                const optionRows = container.querySelectorAll('.barang-option-row');
                const backingSelect = row.querySelector('.barang-select');

                // Open/close menu on button click
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Close all other dropdown menus
                    document.querySelectorAll('.dropdown-menu-container').forEach(m => {
                        if (m !== menu) {
                            m.classList.add('hidden');
                        }
                    });
                    menu.classList.toggle('hidden');
                    if (!menu.classList.contains('hidden')) {
                        const rect = toggleBtn.getBoundingClientRect();
                        const menuWidth = Math.max(240, Math.min(600, window.innerWidth - 24));
                        const left = Math.min(rect.left, window.innerWidth - menuWidth - 12);
                        menu.style.width = menuWidth + 'px';
                        menu.style.top = (rect.bottom + 4) + 'px';
                        menu.style.left = Math.max(12, left) + 'px';
                        searchInput.value = '';
                        searchInput.dispatchEvent(new Event('input'));
                        searchInput.focus();
                    }
                });

                // Handle search input filtering
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    const kategoriSelect = row.querySelector('.kategori-barang-select');
                    const kategori = kategoriSelect ? kategoriSelect.value : '';

                    optionRows.forEach(optRow => {
                        const kode = (optRow.getAttribute('data-kode') || '').toLowerCase();
                        const nama = (optRow.getAttribute('data-nama') || '').toLowerCase();
                        const deskripsi = (optRow.getAttribute('data-deskripsi') || '').toLowerCase();
                        const optKategori = optRow.getAttribute('data-kategori');

                        const matchesKategori = !kategori || optKategori === kategori;
                        const matchesQuery = !query || kode.includes(query) || nama.includes(query) || deskripsi.includes(query);

                        if (matchesKategori && matchesQuery) {
                            optRow.style.display = '';
                        } else {
                            optRow.style.display = 'none';
                        }
                    });
                });

                // Prevent click on search input from closing menu
                searchInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Option row selection click handler
                optionRows.forEach(optRow => {
                    optRow.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const id = this.getAttribute('data-id');
                        const kode = this.getAttribute('data-kode');
                        const selectedKategori = this.getAttribute('data-kategori') || '';
                        const kategoriSelect = row.querySelector('.kategori-barang-select');

                        if (kategoriSelect && !kategoriSelect.value && selectedKategori) {
                            kategoriSelect.value = selectedKategori;
                        }

                        // Set backing select value and trigger change event
                        backingSelect.value = id;
                        backingSelect.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));

                        // Update toggle button text/label
                        const labelSpan = container.querySelector('.selected-barang-label');
                        if (labelSpan) {
                            labelSpan.textContent = kode;
                        }

                        // Remove active class and hide check icons for all other rows
                        optionRows.forEach(r => {
                            r.classList.remove('bg-secondary-container/10', 'hover:bg-secondary-container/20');
                            const icon = r.querySelector('.checked-icon');
                            if (icon) icon.classList.add('hidden');
                        });

                        // Set active styling and show check icon for selected row
                        this.classList.add('bg-secondary-container/10', 'hover:bg-secondary-container/20');
                        const checkIcon = this.querySelector('.checked-icon');
                        if (checkIcon) checkIcon.classList.remove('hidden');

                        // Hide dropdown menu
                        menu.classList.add('hidden');
                    });
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.barang-dropdown-container')) {
                    document.querySelectorAll('.dropdown-menu-container').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });

            // Close dropdowns when scrolling outside of the dropdown/menu area
            document.addEventListener('scroll', function(e) {
                const target = e.target;
                const isInsideDropdown =
                    target instanceof Element &&
                    (target.closest('.dropdown-menu-container') || target.closest('.barang-dropdown-container'));

                if (!isInsideDropdown) {
                    document.querySelectorAll('.dropdown-menu-container').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            }, true);

            // Initialize
            document.querySelectorAll('.item-row').forEach(row => {
                attachRowEvents(row);
                attachCustomDropdownEvents(row);
            });
            // Initialize thousand separator for existing inputs
            document.querySelectorAll('.harga-input').forEach(input => initThousandSeparator(input));

            // Form submission sanitization
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form) {
                    form.querySelectorAll('.harga-input').forEach(input => {
                        input.value = input.value.replace(/,/g, '');
                    });
                }
            });

            // Initialize item image previews for existing rows
            document.querySelectorAll('.item-row').forEach(row => handleItemImagePreview(row));
            // If any rows already have a selected barang, set harga to barang.harga * 1.3
            document.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('.barang-select');
                if (select && select.value) {
                    handleBarangChange(select);
                }
            });
            // Ensure keterangan inputs reflect current diskon state on page load
            document.querySelectorAll('.item-row').forEach(row => updateKeteranganState(row));
            document.querySelectorAll('.item-row').forEach(row => updateStokInfo(row));
            updateRemoveButtons();
            document.getElementById('tax_rate').addEventListener('input', calculateTotals);
            calculateTotals();
            updateSubmitState();
            updateDiscountWarning();
        });
    </script>

    <style>
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .dark .form-label {
            color: #d1d5db;
        }


        .card-header {
            padding: 1rem;
        }
    </style>
</x-app-layout>

