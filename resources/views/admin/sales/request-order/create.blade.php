<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Buat Request Order</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Buat penawaran awal kepada pelanggan</p>
            </div>
            <div>
                <a href="{{ route('sales.request-order.index') }}" class="flex items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-medium text-white hover:bg-[#1c4d81] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-[#225A97] dark:focus:ring-primary-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left h-4 w-4">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg> Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <strong>Gagal:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('sales.request-order.store') }}" id="requestOrderForm" enctype="multipart/form-data">
                    @csrf

                    <!-- Customer Info Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 rounded-2xl shadow-sm">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> Informasi Customer
                            </h3>
                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg> Tambah Customer Baru
                            </button>
                        </div>

                        <div class="mb-8 grid grid-cols-1 gap-6 p-5 lg:grid-cols-2">

                            <div class="col-span-2 flex flex-col">
                                <label for="customer_id" class="form-label dark:text-gray-300">Pilih Customer <span class="text-danger">*</span></label>
                                <select class="@error('customer_id') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="customer_id" name="customer_id" required onchange="populateCustomerData(this.value)">
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach ($customers as $c)
                                        <option value="{{ $c->id }}" data-email="{{ $c->email }}" data-telepon="{{ $c->telepon }}" data-kota="{{ $c->kota }}" @selected(old('customer_id') == $c->id)>
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
                                <small class="text-muted mt-1 dark:text-gray-400">Pilih dari daftar customer yang sudah terdaftar</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_name" class="form-label dark:text-gray-300">Nama Customer</label>
                                <input type="text" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" readonly>
                                <small class="text-muted dark:text-gray-400">Auto-filled dari customer yang dipilih</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_email" class="form-label dark:text-gray-300">Email</label>
                                <input type="email" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="customer_email" readonly>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_telepon" class="form-label dark:text-gray-300">Telepon</label>
                                <input type="text" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="customer_telepon" readonly>
                            </div>
                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="customer_kota" class="form-label dark:text-gray-300">Kota</label>
                                <input type="text" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="customer_kota" readonly>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="pic_id" class="form-label dark:text-gray-300">PIC (Sales) <span class="text-danger">*</span></label>
                                <select class="@error('pic_id') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="pic_id" name="pic_id" required>
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
                                <small class="text-muted dark:text-gray-400">Pilih sales yang menangani request order ini</small>
                            </div>

                            <div class="col-span-2 flex flex-col">
                                <label for="subject" class="form-label dark:text-gray-300">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="@error('subject') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Masukkan subject untuk penawaran" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted dark:text-gray-400">Subject yang akan muncul di PDF penawaran</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="tanggal_kebutuhan" class="form-label dark:text-gray-300">Tanggal Kebutuhan</label>
                                <input type="date" class="@error('tanggal_kebutuhan') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="tanggal_kebutuhan" name="tanggal_kebutuhan" value="{{ old('tanggal_kebutuhan') }}">
                                @error('tanggal_kebutuhan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="catatan_customer" class="form-label dark:text-gray-300">Catatan</label>
                                @php
                                    $pdfDefault = "Untuk memenuhi kebutuhan..., bersama ini kami sampaikan penawaran harga beserta spesifikasi produk sebagai berikut:\n\n";
                                @endphp
                                <textarea class="@error('catatan_customer') is-invalid @enderror block min-h-[80px] w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="catatan_customer" name="catatan_customer" rows="4">{{ old('catatan_customer', $pdfDefault) }}</textarea>
                                @error('catatan_customer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <!-- Items Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 rounded-2xl shadow-sm" id="barangSection" style="display: flex;">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                    <path d="M12 22V12"></path>
                                    <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                                    <path d="m7.5 4.27 9 5.15"></path>
                                </svg>
                                Detail Barang
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <div id="discountWarning" class="alert alert-warning m-4" style="display:none;">
                                Diskon lebih dari 20% pada salah satu item. Penawaran akan menunggu persetujuan Supervisor.
                            </div>
                            <table class="h-full w-full border-collapse" id="itemsTable">
                                <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="min-w-[150px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Kategori Barang</th>
                                        <th class="min-w-[150px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Kode Barang</th>
                                        <th class="min-w-[250px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Nama Barang</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Diskon (%)</th>
                                        <th class="min-w-[200px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Keterangan</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Jumlah</th>
                                        <th class="min-w-[180px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Harga Satuan</th>
                                        <th class="min-w-[150px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Gambar</th>
                                        <th class="min-w-[180px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Harga Setelah Diskon</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">PPN (%)</th>
                                        <th class="min-w-[80px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    <tr class="item-row">
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <select name="kategori_barang[]" class="form-control kategori-barang-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <select name="barang_id[]" class="form-control barang-select @error('barang_id.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" required onchange="updateKategoriBarang(this)">
                                                <option value="">Pilih Barang</option>
                                                @foreach ($barangs as $b)
                                                    <option value="{{ $b->id }}" data-kode="{{ $b->kode_barang }}" data-nama="{{ $b->nama_barang }}" data-kategori="{{ $b->kategori }}" data-stok="{{ $b->stok }}" data-harga="{{ $b->harga ?? 0 }}" data-diskon="{{ $b->diskon_percent ?? 0 }}">
                                                        {{ $b->kode_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text" class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" readonly>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="diskon_percent[]" class="form-control diskon-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" max="100" step="0.01" value="0">
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text" name="keterangan[]" maxlength="255" class="form-control keterangan-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Isi jika diskon > 20%" disabled>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="quantity[]" class="form-control quantity-input @error('quantity.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="1" value="1" required>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="harga[]" class="form-control harga-input @error('harga.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" step="0.01" value="">
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <div class="upload-btn-container relative">
                                                <input type="file" name="item_images[0][]" class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0" multiple accept="image/*">
                                                <button type="button" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">
                                                    Upload
                                                </button>
                                            </div>
                                            <div class="item-images-preview mt-2 flex flex-wrap gap-2"></div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text" class="form-control harga-setelah-diskon-display @error('harga.*') is-invalid @enderror block h-10 w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" style="min-width: 100px; font-size: 1rem; font-weight: 500;" readonly>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="ppn_percent[]" class="form-control ppn-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" max="100" step="0.01" value="">
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <button type="button" class="btn remove-row rounded-lg bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700" style="display: none;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 h-4 w-4">
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
                                        <th colspan="8" class="border border-gray-300 px-4 py-2 text-end text-black dark:border-gray-600 dark:text-gray-300">TOTAL:</th>
                                        <th class="border border-gray-300 px-4 py-2 text-black dark:border-gray-600 dark:text-gray-300">
                                            <strong id="totalAmount">Rp 0</strong>
                                        </th>
                                        <th class="border border-gray-300 px-4 py-2 dark:border-gray-600"></th>
                                        <th class="border border-gray-300 px-4 py-2 dark:border-gray-600"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <button type="button" id="addRow" class="btn inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm m-5 border-none bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Tambah Barang
                        </button>


                        <!-- Summary Section -->
                        <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mt-4 rounded-2xl shadow-md">
                            <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                                <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight"><i class="fas fa-calculator"></i> Ringkasan Penawaran</h3>
                            </div>
                            <div class="p-5">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Sub Total</p>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-white" id="summarySubtotal">Rp 0</p>
                                            </div>
                                            <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">PPN (11%)</p>
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
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Grand Total</p>
                                                <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="summaryGrandTotal">Rp 0</p>
                                            </div>
                                            <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
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
                                <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight"><i class="fas fa-images"></i> Gambar Pendukung Penawaran</h3>
                            </div>
                            <div class="p-5">
                                <div class="mb-3">
                                    <label for="supporting_images" class="form-label dark:text-gray-300">Unggah Gambar <span class="text-muted dark:text-gray-400">(Foto barang, contoh produk, desain,
                                            dll)</span></label>
                                    <div class="input-group">
                                        <input type="file" class="form-control barang-nama-display @error('supporting_images.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="supporting_images" name="supporting_images[]" multiple accept="image/*">
                                        <small class="text-muted d-block mt-2 dark:text-gray-400">Format: JPG, PNG, GIF | Ukuran maksimal: 5MB per gambar</small>
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

                    </div>
                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('sales.request-order.index') }}" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Batal
                        </a>
                        <button type="submit" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]" id="submitBtn" disabled>
                            Buat Request Order
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Filter barang berdasarkan kategori yang dipilih
            function filterBarangByKategori(selectKategori) {
                var tr = selectKategori.closest('tr');
                var kategori = selectKategori.value;
                var barangSelect = tr.querySelector('.barang-select');

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

        <!-- Modal Tambah Customer Baru -->
        <div class="modal fade" id="addCustomerModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-user-plus"></i> Tambah Customer Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addCustomerForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Nama Customer -->
                            <div class="mb-3">
                                <label for="modalNamaCustomer" class="form-label dark:text-gray-300">Nama Customer <span class="text-danger">*</span></label>
                                <input type="text" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalNamaCustomer" name="nama_customer" required>
                                <small class="text-muted dark:text-gray-400">Nama lengkap pelanggan</small>
                                <div class="invalid-feedback" id="error-nama_customer"></div>
                            </div>

                            <!-- Email & Telepon -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="modalEmail" class="form-label dark:text-gray-300">Email</label>
                                        <input type="email" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalEmail" name="email">
                                        <div class="invalid-feedback" id="error-email"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="modalTelepon" class="form-label dark:text-gray-300">Telepon</label>
                                        <input type="tel" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalTelepon" name="telepon">
                                        <div class="invalid-feedback" id="error-telepon"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tipe Customer -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="modalTipeCustomer" class="form-label dark:text-gray-300">Tipe Customer <span class="text-danger">*</span></label>
                                        <select class="form-select dark:border-gray-500 dark:bg-gray-600 dark:text-white" id="modalTipeCustomer" name="tipe_customer" required>
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="retail">Retail</option>
                                            <option value="wholesale">Wholesale</option>
                                            <option value="distributor">Distributor</option>
                                        </select>
                                        <div class="invalid-feedback" id="error-tipe_customer"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="modalStatus" class="form-label dark:text-gray-300">Status <span class="text-danger">*</span></label>
                                        <select class="form-select dark:border-gray-500 dark:bg-gray-600 dark:text-white" id="modalStatus" name="status" required>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Nonaktif</option>
                                        </select>
                                        <div class="invalid-feedback" id="error-status"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label for="modalAlamat" class="form-label dark:text-gray-300">Alamat</label>
                                <textarea class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalAlamat" name="alamat" rows="2"></textarea>
                                <div class="invalid-feedback" id="error-alamat"></div>
                            </div>

                            <!-- Kota, Provinsi, Kode Pos -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="modalKota" class="form-label dark:text-gray-300">Kota</label>
                                        <input type="text" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalKota" name="kota">
                                        <div class="invalid-feedback" id="error-kota"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="modalProvinsi" class="form-label dark:text-gray-300">Provinsi</label>
                                        <input type="text" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalProvinsi" name="provinsi">
                                        <div class="invalid-feedback" id="error-provinsi"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="modalKodePos" class="form-label dark:text-gray-300">Kode Pos</label>
                                        <input type="text" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalKodePos" name="kode_pos">
                                        <div class="invalid-feedback" id="error-kode_pos"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                                <i class="fas fa-save"></i> Simpan Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
                        const response = await fetch('{{ route('sales.customer.store') }}', {
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
                            newOption.textContent = data.customer.nama_customer + (data.customer.email ? ' (' + data.customer.email + ')' : '');
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
                                const inputElement = document.getElementById('modal' + capitalizeFirst(field));

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
                    return str.charAt(0).toUpperCase() + str.slice(1).replace(/_(.)/g, (match, letter) => letter.toUpperCase());
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
                        return Array.from(firstSelect.options).some(opt => opt.value === '' || opt.style.display !== 'none');
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
                    const anyBarangSelected = Array.from(document.querySelectorAll('.barang-select')).some(s => s.value && s.value !== '');
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
                        if (hargaInput) hargaInput.value = hargaJual;

                        // Hitung harga setelah diskon otomatis (qty * harga satuan * (1 - diskon/100))
                        const qty = parseInt(quantityInput.value) || 1;
                        const hargaSetelahDiskon = qty * hargaJual * (1 - (useDiskon / 100));
                        if (hargaSetelahDiskonDisplay) {
                            hargaSetelahDiskonDisplay.value = hargaSetelahDiskon > 0 ?
                                'Rp ' + hargaSetelahDiskon.toLocaleString('id-ID', {
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
                        const hargaSatuan = parseFloat(row.querySelector('.harga-input').value) || 0;
                        const diskon = parseFloat(row.querySelector('.diskon-input').value) || 0;
                        const ppnPercent = parseFloat(row.querySelector('.ppn-input').value) || 0;

                        // Harga setelah diskon
                        const hargaSetelahDiskon = +(qty * hargaSatuan * (1 - (diskon / 100))).toFixed(2);

                        // Hitung PPN untuk item ini
                        const ppnAmount = +(hargaSetelahDiskon * (ppnPercent / 100)).toFixed(2);

                        // Update display harga setelah diskon
                        const hargaSetelahDiskonDisplay = row.querySelector('.harga-setelah-diskon-display');
                        if (hargaSetelahDiskonDisplay) {
                            hargaSetelahDiskonDisplay.value = hargaSetelahDiskon > 0 ?
                                'Rp ' + hargaSetelahDiskon.toLocaleString('id-ID', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) :
                                '0';
                        }

                        subTotal += hargaSetelahDiskon;
                        totalPPN += ppnAmount;
                    });

                    grandTotal = subTotal + totalPPN;

                    // Update table total (harga setelah diskon total)
                    document.getElementById('totalAmount').textContent = 'Rp ' + subTotal.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    // Update summary section
                    document.getElementById('summarySubtotal').textContent = 'Rp ' + subTotal.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    document.getElementById('summaryPPN').textContent = 'Rp ' + totalPPN.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    document.getElementById('summaryGrandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
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
                                    ✕
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

                    // Set default PPN to empty for new rows
                    const ppnNew = newRow.querySelector('.ppn-input');
                    if (ppnNew) {
                        ppnNew.value = '';
                    }

                    newRow.querySelectorAll('select').forEach(sel => {
                        sel.selectedIndex = 0;
                    });

                    // Hapus preview gambar
                    const preview = newRow.querySelector('.item-images-preview');
                    if (preview) preview.innerHTML = '';

                    // Reset file input
                    const fileInput = newRow.querySelector('.item-images-input');
                    if (fileInput) fileInput.value = '';

                    // Update index file input name
                    const idx = document.querySelectorAll('.item-row').length;
                    if (fileInput) fileInput.name = `item_images[${idx}][]`;

                    tbody.appendChild(newRow);

                    // Attach events ke baris baru
                    attachRowEvents(newRow);
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
                    });

                    // Event untuk quantity input - hitung harga setelah diskon saat quantity berubah
                    const quantityInput = row.querySelector('.quantity-input');
                    if (quantityInput) {
                        quantityInput.addEventListener('change', function() {
                            const qty = parseInt(this.value) || 1;
                            const hargaInput = row.querySelector('.harga-input');
                            const hargaSetelahDiskonDisplay = row.querySelector('.harga-setelah-diskon-display');

                            if (hargaInput && hargaSetelahDiskonDisplay) {
                                const hargaSatuan = parseFloat(hargaInput.value) || 0;
                                const diskonInput = row.querySelector('.diskon-input');
                                const diskon = parseFloat(diskonInput.value) || 0;
                                const hargaSetelahDiskon = qty * hargaSatuan * (1 - (diskon / 100));
                                hargaSetelahDiskonDisplay.value = hargaSetelahDiskon > 0 ?
                                    'Rp ' + hargaSetelahDiskon.toLocaleString('id-ID', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }) :
                                    '0';
                            }
                            calculateTotals();
                        });
                    }

                    // Event untuk PPN input
                    const ppnInput = row.querySelector('.ppn-input');
                    if (ppnInput) {
                        ppnInput.addEventListener('change', calculateTotals);
                    }

                    row.querySelector('.harga-input').addEventListener('change', calculateTotals);
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
                                const hargaSatuan = parseFloat(hargaInput.value) || 0;
                                const d = parseFloat(this.value) || 0;
                                const hargaSetelahDiskon = qty * hargaSatuan * (1 - (d / 100));
                                hargaSetelahDiskonDisplay.value = hargaSetelahDiskon > 0 ?
                                    'Rp ' + hargaSetelahDiskon.toLocaleString('id-ID', {
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

                // Update remove buttons visibility - always show delete button
                function updateRemoveButtons() {
                    const rows = document.querySelectorAll('.item-row');
                    rows.forEach((row, index) => {
                        const btn = row.querySelector('.remove-row');
                        if (index === 0) {
                            btn.style.display = 'none';
                        } else {
                            btn.style.display = 'inline-block';
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

                // Initialize
                document.querySelectorAll('.item-row').forEach(row => attachRowEvents(row));
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
                updateRemoveButtons();
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
