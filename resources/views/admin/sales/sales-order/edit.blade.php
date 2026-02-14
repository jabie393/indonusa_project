<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Sales Order</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Ubah data sales order</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('sales.sales-order.update', $salesOrder) }}" method="POST" enctype="multipart/form-data">
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
                        </div>
                        <div class="mb-8 grid grid-cols-1 gap-6 p-5 lg:grid-cols-2">
                            <!-- To Field -->
                            <div>
                                <label for="to" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Kepada (To)</label>
                                <input type="text" id="to" name="to" value="{{ old('to', $salesOrder->to) }}" required class="@error('to') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white" placeholder="Nama customer">
                                @error('to')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Up Field -->
                            <div>
                                <label for="up" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Attn (Up)</label>
                                <select id="up" name="up" required class="@error('up') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white">
                                    <option value="">Pilih Sales</option>
                                    @foreach ($salesUsers as $name => $displayName)
                                        <option value="{{ $name }}" {{ old('up', $salesOrder->up) == $name ? 'selected' : '' }}>
                                            {{ $displayName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('up')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Subject Field -->
                            <div class="lg:col-span-2">
                                <label for="subject" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject', $salesOrder->subject) }}" required class="@error('subject') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white" placeholder="Judul sales order">
                                @error('subject')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $salesOrder->email) }}" required class="@error('email') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white" placeholder="email@example.com">
                                @error('email')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Our Ref Field (Auto-generated) -->
                            <div>
                                <label for="our_ref" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Our Ref</label>
                                <input type="text" id="our_ref" name="our_ref" value="{{ old('our_ref', $salesOrder->our_ref) }}" class="@error('our_ref') border-red-500 @enderror w-full rounded-lg bg-gray-100 px-4 py-2 text-black dark:bg-gray-600 dark:text-gray-300">
                                @error('our_ref')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Date Field -->
                            <div>
                                <label for="date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                                <input type="date" id="date" name="date" value="{{ old('date', $salesOrder->date?->format('Y-m-d')) }}" required class="@error('date') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white">
                                @error('date')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Intro Text -->
                            <div class="lg:col-span-2">
                                <label for="intro_text" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Teks Pembuka</label>
                                <textarea id="intro_text" name="intro_text" rows="4" class="@error('intro_text') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white" placeholder="Masukkan teks pembuka...">{{ old('intro_text', $salesOrder->intro_text) }}</textarea>
                                @error('intro_text')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 rounded-2xl shadow-sm">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                </svg>
                                Detail Barang
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="" class="h-full w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="min-w-[50px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">No</th>
                                        <th class="min-w-[250px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Nama Barang</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Qty</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Satuan</th>
                                        <th class="min-w-[180px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Harga (Rp)</th>
                                        <th class="min-w-[100px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Diskon (%)</th>
                                        <th class="min-w-[200px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Keterangan</th>
                                        <th class="min-w-[180px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Total Setelah Diskon (Rp)</th>
                                        <th class="min-w-[150px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Gambar</th>
                                        <th class="min-w-[80px] border border-gray-300 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:text-gray-100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    @forelse ($salesOrder->items as $item)
                                        <tr class="item-row" data-index="{{ $loop->index }}">
                                            <td class="item-no border border-gray-300 px-4 py-2 text-center text-black dark:border-gray-600 dark:text-gray-100">{{ $loop->iteration }}</td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" name="items[{{ $loop->index }}][nama_barang]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Nama barang" value="{{ $item->nama_barang }}" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="items[{{ $loop->index }}][qty]" class="item-qty form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="0" value="{{ $item->qty }}" min="1" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" name="items[{{ $loop->index }}][satuan]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Unit" value="{{ $item->satuan }}" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="items[{{ $loop->index }}][harga]" class="item-harga form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="0" value="{{ $item->harga }}" step="0.01" min="0" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="items[{{ $loop->index }}][diskon]" class="item-diskon form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="0" value="{{ $item->diskon }}" min="0" max="100" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" name="items[{{ $loop->index }}][keterangan]" class="item-keterangan form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Keterangan jika diskon > 20%" value="{{ $item->keterangan }}">
                                            </td>
                                            <td class="item-subtotal border border-gray-300 px-4 py-2 text-right font-semibold text-gray-900 dark:border-gray-600 dark:text-gray-100">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <div class="upload-btn-container relative">
                                                    <input type="file" name="items[{{ $loop->index }}][images][]" class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0" multiple accept="image/*">
                                                    <button type="button" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">
                                                        Upload Gambar
                                                    </button>
                                                </div>
                                                <div class="item-images-preview mt-2 flex flex-wrap gap-2 space-y-2">
                                                    @if ($item->images && count($item->images) > 0)
                                                        @foreach ($item->images as $image)
                                                            <div class="relative inline-block">
                                                                <img src="{{ asset('storage/' . $image) }}" class="w-20 h-20 object-cover rounded border" title="{{ $image }}">
                                                                <button type="button" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs remove-existing-image-btn" data-image="{{ $image }}">
                                                                    ✕
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <input type="hidden" name="items[{{ $loop->index }}][existing_images]" class="existing-images-input" value="{{ json_encode($item->images ?? []) }}">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <button type="button" class="btn btn-remove-item rounded-lg border-none bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
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
                                        <tr class="item-row" data-index="0">
                                            <td class="item-no border border-gray-300 px-4 py-2 text-center text-black dark:border-gray-600 dark:text-gray-100">1</td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" name="items[0][nama_barang]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Nama barang" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="items[0][qty]" class="item-qty form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="0" value="1" min="1" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" name="items[0][satuan]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Unit" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="items[0][harga]" class="item-harga form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="0" step="0.01" min="0" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="number" name="items[0][diskon]" class="item-diskon form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="0" value="0" min="0" max="100" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <input type="text" name="items[0][keterangan]" class="item-keterangan form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" placeholder="Keterangan jika diskon > 20%">
                                            </td>
                                            <td class="item-subtotal border border-gray-300 px-4 py-2 text-right font-semibold text-gray-900 dark:border-gray-600 dark:text-gray-100">0</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <div class="upload-btn-container relative">
                                                    <input type="file" name="items[0][images][]" class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0" multiple accept="image/*">
                                                    <button type="button" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">
                                                        Upload Gambar
                                                    </button>
                                                </div>
                                                <div class="item-images-preview mt-2 flex flex-wrap gap-2 space-y-2"></div>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                                <button type="button" class="btn btn-remove-item rounded-lg border-none bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
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
                            </table>
                        </div>
                        <button type="button" id="btn-add-item" class="btn m-5 bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            + Tambah Barang
                        </button>
                        @error('items')
                            <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Summary Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mt-4 rounded-2xl shadow-md">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight"><i class="fas fa-calculator"></i> Ringkasan Sales Order</h3>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <!-- Subtotal -->
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="w-full">
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Sub Total</p>
                                            <input type="text" id="subtotal-display" readonly class="mt-1 w-full border-none bg-transparent p-0 text-2xl font-bold text-gray-900 focus:ring-0 dark:text-white" value="Rp {{ number_format($salesOrder->subtotal, 0, ',', '.') }}">
                                            <input type="hidden" id="subtotal-value" name="subtotal" value="{{ $salesOrder->subtotal }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tax -->
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="w-full">
                                            <div class="mb-1 flex items-center justify-start">
                                                <div class="flex items-center gap-1 rounded border border-gray-300 bg-white px-2 py-0.5 dark:border-gray-500 dark:bg-gray-600" style="width: fit-content;">
                                                    <p class="w-fit text-sm font-medium text-gray-600 dark:text-gray-300">Pajak/PPN</p>
                                                    <input type="number" id="tax_rate" value="11" class="w-12 border-none bg-transparent p-0 text-right text-sm text-gray-900 focus:ring-0 dark:text-white" min="0" max="100">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">%</span>
                                                </div>
                                            </div>
                                            <input type="hidden" id="tax" name="tax" value="{{ $salesOrder->tax }}">
                                            <input type="text" id="tax_display" readonly class="mt-1 w-full border-none bg-transparent p-0 text-2xl font-bold text-gray-900 focus:ring-0 dark:text-white" value="Rp {{ number_format($salesOrder->tax, 0, ',', '.') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Grand Total -->
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="w-full">
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Grand Total</p>
                                            <input type="text" id="grand-total-display" readonly class="mt-1 w-full border-none bg-transparent p-0 text-2xl font-bold text-green-600 focus:ring-0 dark:text-green-400" value="Rp {{ number_format($salesOrder->grand_total, 0, ',', '.') }}">
                                            <input type="hidden" id="grand-total-value" name="grand_total" value="{{ $salesOrder->grand_total }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 mt-4">
                        <a href="{{ route('sales.sales-order.show', $salesOrder) }}" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Batal
                        </a>
                        <button type="submit" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/sales-order-edit.js') }}"></script>
</x-app-layout>
