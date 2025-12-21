<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Request Order</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">No. {{ $requestOrder->request_number }}</p>
            </div>
            <div>
                <a href="{{ route('sales.request-order.show', $requestOrder->id) }}" class="flex items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-medium text-white hover:bg-[#1c4d81] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-[#225A97] dark:focus:ring-primary-800">
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
                <form method="POST" action="{{ route('sales.request-order.update', $requestOrder->id) }}" id="requestOrderForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Customer Info Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 rounded-2xl shadow-sm">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> Informasi Customer
                            </h3>
                            {{-- <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg> Tambah Customer Baru
                            </button> --}}
                        </div>

                        <div class="mb-8 grid grid-cols-1 gap-6 p-5 lg:grid-cols-2">
                            <div id="discountWarning" class="alert alert-warning col-span-2" style="display:none;">
                                Diskon lebih dari 20% pada salah satu item. Penawaran akan menunggu persetujuan Supervisor.
                            </div>

                            <div class="col-span-2 flex flex-col">
                                <label for="customer_id" class="form-label dark:text-gray-300">Pilih Customer <span class="text-danger">*</span></label>
                                <select class="@error('customer_id') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="customer_id" name="customer_id" required onchange="populateCustomerData(this.value)">
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach ($customers as $c)
                                        <option value="{{ $c->id }}" data-email="{{ $c->email }}" data-telepon="{{ $c->telepon }}" data-kota="{{ $c->kota }}" @selected(old('customer_id', $requestOrder->customer_id) == $c->id)>
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
                                <input type="text" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="customer_name" name="customer_name" value="{{ old('customer_name', $requestOrder->customer_name) }}" readonly>
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
                                        <option value="{{ $sales->id }}" @selected(old('pic_id', $requestOrder->sales_id) == $sales->id)>
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
                                <input type="text" class="@error('subject') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="subject" name="subject" value="{{ old('subject', $requestOrder->subject) }}" placeholder="Masukkan subject untuk penawaran" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted dark:text-gray-400">Subject yang akan muncul di PDF penawaran</small>
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="tanggal_kebutuhan" class="form-label dark:text-gray-300">Tanggal Kebutuhan</label>
                                <input type="date" class="@error('tanggal_kebutuhan') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="tanggal_kebutuhan" name="tanggal_kebutuhan" value="{{ old('tanggal_kebutuhan', $requestOrder->tanggal_kebutuhan) }}">
                                @error('tanggal_kebutuhan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-2 flex flex-col md:col-span-1">
                                <label for="catatan_customer" class="form-label dark:text-gray-300">Catatan</label>
                                <textarea class="@error('catatan_customer') is-invalid @enderror block min-h-[80px] w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="catatan_customer" name="catatan_customer" rows="1">{{ old('catatan_customer', $requestOrder->catatan_customer) }}</textarea>
                                @error('catatan_customer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <!-- Items Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 rounded-2xl shadow-sm" id="barangSection">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                    <path d="M12 22V12"></path>
                                    <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                                    <path d="m7.5 4.27 9 5.15"></path>
                                </svg> Detail Barang
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
                                        <th class="min-w-[200px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Nama Barang</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Diskon (%)</th>
                                        <th class="min-w-[200px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Keterangan</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Jumlah</th>
                                        <th class="min-w-[150px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Harga Satuan</th>
                                        <th class="min-w-[150px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Gambar</th>
                                        <th class="min-w-[150px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Harga Setelah Diskon</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">PPN (%)</th>
                                        <th class="min-w-[80px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    @forelse($requestOrder->items as $item)
                                        <tr class="item-row">
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <select name="kategori_barang[]" class="kategori-barang-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat }}" @selected($item->barang->kategori === $cat)>{{ $cat }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <select name="barang_id[]" class="barang-select @error('barang_id.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" required onchange="updateKategoriBarang(this)">
                                                    <option value="">-- Pilih Barang --</option>
                                                    @foreach ($barangs as $b)
                                                        <option value="{{ $b->id }}" data-kode="{{ $b->kode_barang }}" data-nama="{{ $b->nama_barang }}" data-kategori="{{ $b->kategori }}" data-stok="{{ $b->stok }}" data-harga="{{ $b->harga ?? 0 }}" data-diskon="{{ $b->diskon_percent ?? 0 }}" @selected($item->barang_id === $b->id)>
                                                            {{ $b->kode_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" class="barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" readonly value="{{ $item->barang->nama_barang ?? '' }}">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="diskon_percent[]" class="diskon-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" max="100" step="0.01" value="{{ $item->diskon_percent ?? ($item->barang->diskon_percent ?? 0) }}">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" name="keterangan[]" maxlength="255" class="keterangan-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Isi jika diskon > 20%" value="{{ $item->keterangan }}" {{ ($item->diskon_percent ?? 0) > 20 ? '' : 'disabled' }}>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="quantity[]" class="quantity-input @error('quantity.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="1" value="{{ old('quantity', $item->quantity) }}" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="harga[]" class="harga-input @error('harga.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" step="0.01" value="{{ old('harga', $item->harga) }}" readonly>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <div class="upload-btn-container relative">
                                                    <input type="file" name="item_images[{{ $loop->index }}][]" class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0" multiple accept="image/*">
                                                    <button type="button" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">
                                                        Upload
                                                    </button>
                                                </div>
                                                <div class="item-images-preview mt-2 flex flex-wrap gap-2">
                                                    @if ($item->item_images && count($item->item_images) > 0)
                                                        @foreach ($item->item_images as $img)
                                                            <div class="relative">
                                                                <img src="{{ asset('storage/' . $img) }}" class="h-20 w-20 rounded border object-cover" alt="Image">
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" class="harga-setelah-diskon-display block h-10 w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" value="{{ 'Rp ' . number_format($item->quantity * $item->harga * (1 - ($item->diskon_percent ?? 0) / 100), 2, ',', '.') }}" readonly>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="ppn_percent[]" class="ppn-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" max="100" step="0.01" value="{{ $item->ppn_percent ?? 0 }}">
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
                                    @empty
                                        <tr class="item-row">
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <select name="kategori_barang[]" class="kategori-barang-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <select name="barang_id[]" class="barang-select @error('barang_id.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" required onchange="updateKategoriBarang(this)">
                                                    <option value="">-- Pilih Barang --</option>
                                                    @foreach ($barangs as $b)
                                                        <option value="{{ $b->id }}" data-kode="{{ $b->kode_barang }}" data-nama="{{ $b->nama_barang }}" data-kategori="{{ $b->kategori }}" data-stok="{{ $b->stok }}" data-harga="{{ $b->harga ?? 0 }}" data-diskon="{{ $b->diskon_percent ?? 0 }}" style="display: none;">
                                                            {{ $b->kode_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" class="barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" readonly>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="diskon_percent[]" class="diskon-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" max="100" step="0.01" value="0">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" name="keterangan[]" maxlength="255" class="keterangan-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Isi jika diskon > 20%" disabled>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="quantity[]" class="quantity-input @error('quantity.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="1" value="1" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="harga[]" class="harga-input @error('harga.*') is-invalid @enderror block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" step="0.01" value="0" readonly>
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
                                                <input type="text" class="harga-setelah-diskon-display block h-10 w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" readonly>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="ppn_percent[]" class="ppn-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" max="100" step="0.01" value="0">
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
                                    @endforelse
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2 h-4 w-4">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg> Tambah Barang
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
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total PPN</p>
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
                    </div>



                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <button type="submit" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save mr-2 h-4 w-4">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg> Simpan Perubahan
                        </button>
                        <a href="{{ route('sales.request-order.show', $requestOrder->id) }}" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-2 h-4 w-4">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Customer Baru -->
    {{-- <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content overflow-hidden rounded-2xl border-none shadow-2xl dark:bg-gray-800">
                <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 text-white">
                    <h5 class="flex items-center gap-2 text-lg font-semibold"><i class="fas fa-user-plus"></i> Tambah Customer Baru</h5>
                    <button type="button" class="text-white hover:text-gray-200" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-6 w-6">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="addCustomerForm" method="POST">
                    @csrf
                    <div class="space-y-4 p-6">
                        <div id="addCustomerAlert"></div>

                        <!-- Nama Customer -->
                        <div>
                            <label for="modalNamaCustomer" class="form-label block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Customer <span class="text-red-500">*</span></label>
                            <input type="text" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalNamaCustomer" name="nama_customer" required>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nama lengkap pelanggan</p>
                            <div class="invalid-feedback text-xs text-red-500" id="error-nama_customer"></div>
                        </div>

                        <!-- Email & Telepon -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="modalEmail" class="form-label block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalEmail" name="email">
                                <div class="invalid-feedback text-xs text-red-500" id="error-email"></div>
                            </div>
                            <div>
                                <label for="modalTelepon" class="form-label block text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</label>
                                <input type="tel" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalTelepon" name="telepon">
                                <div class="invalid-feedback text-xs text-red-500" id="error-telepon"></div>
                            </div>
                        </div>

                        <!-- Tipe Customer & Alamat -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="modalTipeCustomer" class="form-label block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Customer <span class="text-red-500">*</span></label>
                                <select class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalTipeCustomer" name="tipe_customer" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    @foreach ($customerTypes as $type)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback text-xs text-red-500" id="error-tipe_customer"></div>
                            </div>
                            <div>
                                <label for="modalAlamat" class="form-label block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                                <textarea class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalAlamat" name="alamat" rows="2"></textarea>
                                <div class="invalid-feedback text-xs text-red-500" id="error-alamat"></div>
                            </div>
                        </div>

                        <!-- PIC & Catatan -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="modalNamaPic" class="form-label block text-sm font-medium text-gray-700 dark:text-gray-300">Nama PIC</label>
                                <input type="text" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalNamaPic" name="nama_pic">
                                <div class="invalid-feedback text-xs text-red-500" id="error-nama_pic"></div>
                            </div>
                            <div>
                                <label for="modalCatatan" class="form-label block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                                <textarea class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" id="modalCatatan" name="catatan" rows="2"></textarea>
                                <div class="invalid-feedback text-xs text-red-500" id="error-catatan"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 rounded-b-2xl bg-gray-50 p-6 dark:bg-gray-700/50">
                        <button type="button" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="rounded-lg bg-[#225A97] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-[#1c4d81] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-[#225A97] dark:hover:bg-[#1c4d81] dark:focus:ring-primary-800">Simpan Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}


    <script>
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

            // Populate customer data on page load
            const customerId = document.getElementById('customer_id').value;
            if (customerId) {
                populateCustomerData(customerId);
            }

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
            const addRowBtn = document.getElementById('addRow');
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
                });
            };


            function handleBarangChange(select) {
                const option = select.options[select.selectedIndex];
                const row = select.closest('.item-row');
                const namaDisplay = row.querySelector('.barang-nama-display');
                const diskonInput = row.querySelector('.diskon-input');
                const hargaInput = row.querySelector('.harga-input');

                if (option.value) {
                    namaDisplay.value = option.dataset.nama || '';
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

                    // Compute jual price (base + 30%) then apply diskon if any
                    const hargaJual = +(baseHarga * 1.3).toFixed(2);
                    const finalHarga = +(hargaJual * (1 - (useDiskon / 100))).toFixed(2);
                    if (hargaInput) hargaInput.value = finalHarga;
                } else {
                    namaDisplay.value = '';
                    if (diskonInput) diskonInput.value = 0;
                    if (hargaInput) hargaInput.value = 0;
                }
                calculateTotals();
            }

            function getBarangOptionsHTML() {
                const firstSelect = document.querySelector('.barang-select');
                return firstSelect.innerHTML;
            }

            window.updateKategoriBarang = function(select) {
                const row = select.closest('tr');
                const kategoriSelect = row.querySelector('.kategori-barang-select');
                const selectedOption = select.options[select.selectedIndex];
                const kategori = selectedOption.dataset.kategori;

                if (kategori && kategoriSelect) {
                    kategoriSelect.value = kategori;
                }
                handleBarangChange(select);
            };

            function handleBarangChange(select) {
                const row = select.closest('.item-row');
                const selectedOption = select.options[select.selectedIndex];

                const namaDisplay = row.querySelector('.barang-nama-display');
                const hargaInput = row.querySelector('.harga-input');
                const baseDiskonInput = row.querySelector('.diskon-input');

                if (selectedOption.value) {
                    namaDisplay.value = selectedOption.dataset.nama || '';
                    const baseHarga = parseFloat(selectedOption.dataset.harga || 0) || 0;
                    const defaultDiskon = parseFloat(selectedOption.dataset.diskon || 0) || 0;

                    // Apply markup 30%
                    const markupHarga = baseHarga * 1.3;
                    hargaInput.value = markupHarga.toFixed(2);
                    baseDiskonInput.value = defaultDiskon;
                } else {
                    namaDisplay.value = '';
                    hargaInput.value = 0;
                    baseDiskonInput.value = 0;
                }
                calculateTotals();
            }

            function calculateTotals() {
                let subtotal = 0;
                let totalPPN = 0;
                let grandTotal = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
                    const markupHarga = parseFloat(row.querySelector('.harga-input').value) || 0;
                    const diskonPercent = parseFloat(row.querySelector('.diskon-input').value) || 0;
                    const ppnPercent = parseFloat(row.querySelector('.ppn-input').value) || 0;

                    const hargaSetelahDiskon = +(markupHarga * (1 - (diskonPercent / 100))).toFixed(2);
                    const itemSubtotal = +(qty * hargaSetelahDiskon).toFixed(2);
                    const itemPPN = +(itemSubtotal * (ppnPercent / 100)).toFixed(2);

                    row.querySelector('.harga-setelah-diskon-display').value = 'Rp ' + hargaSetelahDiskon.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    subtotal += itemSubtotal;
                    totalPPN += itemPPN;
                });

                grandTotal = subtotal + totalPPN;

                document.getElementById('totalAmount').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Update summary section
                document.getElementById('summarySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID', {
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

                updateDiscountWarning();
            }

            addRowBtn.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.innerHTML = `
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <select name="kategori_barang[]" class="kategori-barang-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <select name="barang_id[]" class="barang-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" required onchange="updateKategoriBarang(this)">
                            ${getBarangOptionsHTML()}
                        </select>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <input type="text" class="barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" readonly>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <input type="number" name="diskon_percent[]" class="diskon-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" max="100" step="0.01" value="0">
                    </td>
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <input type="text" name="keterangan[]" maxlength="255" class="keterangan-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Isi jika diskon > 20%" disabled>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <input type="number" name="quantity[]" class="quantity-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="1" value="1" required>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <input type="number" name="harga[]" class="harga-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" step="0.01" value="0" readonly>
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
                        <input type="text" class="harga-setelah-diskon-display block h-10 w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" readonly>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <input type="number" name="ppn_percent[]" class="ppn-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" min="0" max="100" step="0.01" value="0">
                    </td>
                    <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                        <button type="button" class="btn remove-row rounded-lg bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 h-4 w-4">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                <line x1="14" x2="14" y1="11" y2="17"></line>
                            </svg>
                        </button>
                    </td>
                `;
                itemRows.appendChild(newRow);
                attachRowEvents(newRow);
                updateRemoveButtons();
                calculateTotals();
                reindexItemImageInputs();

                const selectedKategori = kategoriSelect ? kategoriSelect.value : '';
                if (selectedKategori) {
                    filterBarangByCategory(selectedKategori);
                }
            });

            function attachRowEvents(row) {
                const barangSelect = row.querySelector('.barang-select');
                if (barangSelect) {
                    barangSelect.addEventListener('change', function() {
                        updateKategoriBarang(this);
                    });
                }

                row.querySelector('.quantity-input').addEventListener('input', calculateTotals);
                row.querySelector('.ppn-input').addEventListener('input', calculateTotals);

                const diskonInput = row.querySelector('.diskon-input');
                const keteranganInput = row.querySelector('.keterangan-input');

                if (diskonInput) {
                    diskonInput.addEventListener('input', function() {
                        const val = parseFloat(this.value) || 0;
                        if (val > 20) {
                            keteranganInput.disabled = false;
                            keteranganInput.setAttribute('required', 'required');
                        } else {
                            keteranganInput.disabled = true;
                            keteranganInput.value = '';
                            keteranganInput.removeAttribute('required');
                        }
                        calculateTotals();
                    });
                }

                row.querySelector('.remove-row').addEventListener('click', function() {
                    row.remove();
                    updateRemoveButtons();
                    calculateTotals();
                    reindexItemImageInputs();
                });

                handleItemImagePreview(row);
            }

            function updateDiscountWarning() {
                const warning = document.getElementById('discountWarning');
                if (!warning) return;
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

            // Preview handler for per-item images
            function handleItemImagePreview(row) {
                const fileInput = row.querySelector('.item-images-input');
                const preview = row.querySelector('.item-images-preview');
                if (!fileInput || !preview) return;

                fileInput.addEventListener('change', function() {
                    // Clear existing previews
                    preview.innerHTML = '';
                    const uploadBtn = row.querySelector('.upload-btn-container');
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

            function reindexItemImageInputs() {
                document.querySelectorAll('.item-row').forEach((row, i) => {
                    const fileInput = row.querySelector('.item-images-input');
                    if (fileInput) fileInput.name = `item_images[${i}][]`;
                });
            }

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

            // Initialization on load
            const selectedKategori = kategoriSelect ? kategoriSelect.value : '';
            if (selectedKategori) {
                filterBarangByCategory(selectedKategori);
            }

            document.querySelectorAll('.item-row').forEach(row => {
                attachRowEvents(row);
                const select = row.querySelector('.barang-select');
                if (select && select.value) {
                    // Update category display for existing rows
                    const kategoriSelect = row.querySelector('.kategori-barang-select');
                    const selectedOption = select.options[select.selectedIndex];
                    if (selectedOption.dataset.kategori && kategoriSelect) {
                        kategoriSelect.value = selectedOption.dataset.kategori;
                    }

                    // Enable/disable keterangan for existing rows
                    const diskonInput = row.querySelector('.diskon-input');
                    const keteranganInput = row.querySelector('.keterangan-input');
                    if (diskonInput && keteranganInput) {
                        const val = parseFloat(diskonInput.value) || 0;
                        if (val > 20) {
                            keteranganInput.disabled = false;
                            keteranganInput.setAttribute('required', 'required');
                        }
                    }
                }
            });

            updateRemoveButtons();
            calculateTotals();
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

        .alert {
            position: relative;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
        }

        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }

        .dark .alert-info {
            background-color: #083344;
            border-color: #0e7490;
            color: #cff4fc;
        }
    </style>
</x-app-layout>
