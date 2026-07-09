<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('sales.custom-quotation.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 rounded-2xl shadow-sm">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12"
                                            cy="7"
                                            r="4"></circle>
                                </svg> Informasi Customer
                            </h3>
                        </div>
                        <div class="mb-8 grid grid-cols-1 gap-6 p-5 lg:grid-cols-2">
                            <!-- To Field -->
                            <div>
                                <label for="to"
                                       class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Kepada (To)</label>
                                <input type="text"
                                       id="to"
                                       name="to"
                                       value="{{ old('to') }}"
                                       required
                                       class="@error('to') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                       placeholder="Nama customer">
                                @error('to')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Up Field -->
                            <div>
                                <label for="up"
                                       class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Attn (Up)</label>
                                <input type="text"
                                       id="up"
                                       name="up"
                                       value="{{ old('up') }}"
                                       required
                                       class="@error('up') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                       placeholder="Nama PIC Customer">
                                @error('up')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Subject Field -->
                            <div class="lg:col-span-2">
                                <label for="subject"
                                       class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                                <input type="text"
                                       id="subject"
                                       name="subject"
                                       value="{{ old('subject') }}"
                                       required
                                       class="@error('subject') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                       placeholder="Judul quotation">
                                @error('subject')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email"
                                       class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       class="@error('email') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                       placeholder="email@example.com">
                                @error('email')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Our Ref Field (Auto-generated) -->
                            <div>
                                <label for="our_ref"
                                       class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Our Ref (Auto)</label>
                                <input type="text"
                                       id="our_ref"
                                       name="our_ref"
                                       value="{{ old('our_ref') }}"
                                       class="@error('our_ref') border-red-500 @enderror w-full rounded-lg bg-gray-100 px-4 py-2 text-black dark:bg-gray-600 dark:text-gray-300"
                                       placeholder="Auto-generated">
                                @error('our_ref')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Date Field -->
                            <div>
                                <label for="date"
                                       class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                                <input type="date"
                                       id="date"
                                       name="date"
                                       value="{{ old('date', date('Y-m-d')) }}"
                                       required
                                       class="@error('date') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white">
                                @error('date')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Intro Text -->
                            <div class="lg:col-span-2">
                                <label for="intro_text"
                                       class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Teks Pembuka</label>
                                <textarea id="intro_text"
                                          name="intro_text"
                                          rows="4"
                                          class="@error('intro_text') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                          placeholder="Masukkan teks pembuka quotation...">{{ old('intro_text', '') }}</textarea>
                                @error('intro_text')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-4 max-h-[80vh] overflow-y-auto rounded-2xl shadow-sm">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                    <path d="M12 22V12"></path>
                                    <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                                    <path d="m7.5 4.27 9 5.15"></path>
                                </svg>
                                Detail Barang
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table id=""
                                   class="h-full w-full border-collapse">
                                <thead>
                                    <tr class="">
                                        <th class="sticky top-0 z-20 min-w-[250px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">Nama, Kategori, & Deskripsi</th>
                                        <th class="sticky top-0 z-20 min-w-[200px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">Qty, Satuan, & Harga</th>
                                        <th class="sticky top-0 z-20 min-w-[180px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">Diskon & Ket.</th>
                                        <th class="sticky top-0 z-20 min-w-[100px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">Gambar</th>
                                        <th class="sticky top-0 z-20 min-w-[150px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">Total</th>
                                        <th class="sticky top-0 z-20 min-w-[60px] border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-semibold text-black dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    <tr class="item-row"
                                        data-index="0">
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <div class="flex flex-col gap-2">
                                                <input type="text"
                                                       name="items[0][nama_barang]"
                                                       class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                       placeholder="Nama barang"
                                                       value="{{ old('items.0.nama_barang') }}"
                                                       required>
                                                @error('items.0.nama_barang')
                                                    <span class="text-xs text-red-500 block">{{ $message }}</span>
                                                @enderror

                                                <select name="items[0][category]"
                                                        required
                                                        class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white">
                                                    <option value="" disabled selected>Pilih Kategori</option>
                                                    @foreach(\App\Models\Goods::KATEGORI as $kategori)
                                                        <option value="{{ $kategori }}" {{ old('items.0.category') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                    @endforeach
                                                </select>
                                                @error('items.0.category')
                                                    <span class="text-xs text-red-500 block">{{ $message }}</span>
                                                @enderror

                                                <input type="text"
                                                       name="items[0][description]"
                                                       class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                       placeholder="Deskripsi barang"
                                                       value="{{ old('items.0.description') }}"
                                                       required>
                                                @error('items.0.description')
                                                    <span class="text-xs text-red-500 block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <div class="flex flex-col gap-2">
                                                <!-- Qty Field -->
                                                <div class="flex items-center gap-2 w-full">
                                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 min-w-[24px]">Qty</span>
                                                    <input type="number"
                                                           name="items[0][qty]"
                                                           class="item-qty form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                           placeholder="0"
                                                           value="{{ old('items.0.qty', 1) }}"
                                                           min="1"
                                                           required>
                                                </div>
                                                @error('items.0.qty')
                                                    <span class="text-xs text-red-500 block">{{ $message }}</span>
                                                @enderror

                                                <!-- Satuan Field -->
                                                <div class="flex items-center gap-2 w-full">
                                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 min-w-[24px]">Sat</span>
                                                    <input type="text"
                                                           name="items[0][satuan]"
                                                           class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                           placeholder="Unit"
                                                           value="{{ old('items.0.satuan') }}"
                                                           required>
                                                </div>
                                                @error('items.0.satuan')
                                                    <span class="text-xs text-red-500 block">{{ $message }}</span>
                                                @enderror

                                                <!-- Harga Field -->
                                                <div class="flex items-center gap-2 w-full">
                                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 min-w-[24px]">Rp</span>
                                                    <input type="text"
                                                           name="items[0][harga]"
                                                           class="item-harga form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                           placeholder="0"
                                                           value="{{ old('items.0.harga') }}"
                                                           required>
                                                </div>
                                                @error('items.0.harga')
                                                    <span class="text-xs text-red-500 block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <div class="flex flex-col gap-2">
                                                <div class="relative flex items-center w-full">
                                                    <input type="number"
                                                           name="items[0][diskon]"
                                                           class="item-diskon form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pr-8 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                           placeholder="0"
                                                           value="{{ old('items.0.diskon', 0) }}"
                                                           min="0"
                                                           max="100"
                                                           required>
                                                    <span class="absolute right-3 text-sm text-gray-500 dark:text-gray-400">%</span>
                                                </div>
                                                @error('items.0.diskon')
                                                    <span class="text-xs text-red-500 block">{{ $message }}</span>
                                                @enderror

                                                <input type="text"
                                                       name="items[0][keterangan]"
                                                       class="item-keterangan form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                       placeholder="Jika diskon > 20%"
                                                       value="{{ old('items.0.keterangan') }}">
                                                @error('items.0.keterangan')
                                                    <span class="text-xs text-red-500 block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 text-center dark:border-gray-600">
                                            <div class="upload-btn-container relative flex justify-center">
                                                <input type="file"
                                                       name="items[0][images][]"
                                                       class="item-images-input absolute inset-0 h-11 w-11 cursor-pointer opacity-0 z-10"
                                                       multiple
                                                       accept="image/*">
                                                <button type="button"
                                                        class="flex h-11 w-11 items-center justify-center rounded-xl border-2 border-dashed border-blue-300 bg-blue-50/50 text-blue-600 transition-all hover:border-blue-400 hover:bg-blue-50 dark:border-blue-900/40 dark:bg-blue-950/20 dark:text-blue-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="item-images-preview flex flex-wrap justify-center gap-2 space-y-2 mt-2"></div>
                                        </td>
                                        <td class="item-subtotal border border-gray-300 px-4 py-2 text-center font-bold text-blue-600 dark:text-blue-400 dark:border-gray-600 text-nowrap" style="font-size: 0.875rem;">Rp 0</td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600 text-center">
                                            <button type="button"
                                                    class="btn btn-remove-item p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-950/30 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="20"
                                                     height="20"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round"
                                                     class="lucide lucide-trash2">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                    <line x1="10"
                                                          x2="10"
                                                          y1="11"
                                                          y2="17"></line>
                                                    <line x1="14"
                                                          x2="14"
                                                          y1="11"
                                                          y2="17"></line>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Total Amount Summary Bar (Non-scrolling/Fixed-width) -->
                        <div class="flex items-center justify-between border-t border-b border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/40 px-6 py-4">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total</span>
                            <span id="totalAmount" class="text-xl font-extrabold text-blue-600 dark:text-blue-400">Rp 0</span>
                        </div>
                        <button type="button"
                                id="btn-add-item"
                                class="btn m-5 bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            + Tambah Barang
                        </button>
                        @error('items')
                            <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Summary Section -->
                    <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mt-4 rounded-2xl shadow-md">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight"><i class="fas fa-calculator"></i> Ringkasan Quotation</h3>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <!-- Subtotal -->
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="w-full">
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Sub Total</p>
                                            <input type="text"
                                                   id="subtotal-display"
                                                   readonly
                                                   class="mt-1 w-full border-none bg-transparent p-0 text-2xl font-bold text-gray-900 focus:ring-0 dark:text-white"
                                                   value="Rp 0">
                                            <input type="hidden"
                                                   id="subtotal-value"
                                                   name="subtotal"
                                                   value="0">
                                        </div>
                                        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-300"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tax -->
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="w-full">
                                            <div class="mb-1 flex items-center justify-start">
                                                <div class="flex items-center gap-1 rounded border border-gray-300 bg-white px-2 py-0.5 dark:border-gray-500 dark:bg-gray-600"
                                                     style="width: fit-content;">
                                                    <p class="w-fit text-sm font-medium text-gray-600 dark:text-gray-300">Pajak/PPN</p>
                                                    <input type="number"
                                                           id="tax_rate"
                                                           value="11"
                                                           class="w-12 border-none bg-transparent p-0 text-right text-sm text-gray-900 focus:ring-0 dark:text-white"
                                                           min="0"
                                                           max="100">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">%</span>
                                                </div>
                                            </div>
                                            <input type="hidden"
                                                   id="tax"
                                                   name="tax"
                                                   value="{{ old('tax', 0) }}">
                                            <input type="text"
                                                   id="tax_display"
                                                   readonly
                                                   class="mt-1 w-full border-none bg-transparent p-0 text-2xl font-bold text-gray-900 focus:ring-0 dark:text-white"
                                                   value="Rp 0">
                                        </div>
                                        <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                                            <svg fill="currentColor" class="h-6 w-6 text-green-600 dark:text-green-300" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M427.023,0H84.977C71.126,0,59.858,11.268,59.858,25.119v461.762c0,13.851,11.268,25.119,25.119,25.119h265.086 c2.126,0,4.166-0.844,5.668-2.348l94.063-94.063c1.504-1.503,2.348-3.542,2.348-5.668V25.119C452.142,11.268,440.874,0,427.023,0z M358.079,484.63v-57.607c0-5.01,4.076-9.086,9.086-9.086h57.607L358.079,484.63z M436.109,401.904h-68.944 c-13.851,0-25.119,11.268-25.119,25.119v68.944H84.977c-5.01,0-9.086-4.076-9.086-9.086V25.119c0-5.01,4.076-9.086,9.086-9.086 h342.046c5.01,0,9.086,4.076,9.086,9.086V401.904z"></path> </g> </g> <g> <g> <path d="M204.693,68.409h-68.409c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h26.188v77.495 c0,4.427,3.589,8.017,8.017,8.017s8.017-3.589,8.017-8.017V84.443h26.188c4.427,0,8.017-3.589,8.017-8.017 S209.12,68.409,204.693,68.409z"></path> </g> </g> <g> <g> <path d="M289.332,159.634l-23.826-79.418c-2.119-7.062-8.496-11.807-15.869-11.807h-4.378c-7.373,0-13.75,4.745-15.869,11.807 l-23.826,79.418c-1.272,4.241,1.134,8.71,5.375,9.982c4.241,1.275,8.71-1.134,9.982-5.374l3.416-11.39h46.219l3.417,11.389 c1.042,3.473,4.227,5.715,7.676,5.715c0.762,0,1.538-0.11,2.307-0.34C288.198,168.344,290.605,163.875,289.332,159.634z M229.149,136.818l15.598-51.995c0.067-0.224,0.278-0.381,0.512-0.381h4.378c0.234,0,0.445,0.156,0.512,0.381l15.598,51.995 H229.149z"></path> </g> </g> <g> <g> <path d="M373.732,157.34l-26.712-38.158l26.712-38.158c2.539-3.627,1.657-8.626-1.97-11.165c-3.627-2.539-8.626-1.658-11.165,1.97 l-23.362,33.374l-23.362-33.374c-2.539-3.628-7.539-4.51-11.165-1.97c-3.628,2.539-4.51,7.538-1.97,11.165l26.712,38.158 L300.74,157.34c-2.539,3.627-1.657,8.626,1.97,11.165c1.399,0.98,3.003,1.449,4.59,1.449c2.527,0,5.015-1.192,6.574-3.42 l23.362-33.374l23.362,33.374c1.56,2.228,4.047,3.42,6.574,3.42c1.587,0,3.192-0.47,4.59-1.449 C375.389,165.966,376.272,160.967,373.732,157.34z"></path> </g> </g> <g> <g> <path d="M136.284,213.779h-17.102c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h17.102 c4.427,0,8.017-3.589,8.017-8.017S140.711,213.779,136.284,213.779z"></path> </g> </g> <g> <g> <path d="M136.284,247.983h-17.102c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h17.102 c4.427,0,8.017-3.589,8.017-8.017S140.711,247.983,136.284,247.983z"></path> </g> </g> <g> <g> <path d="M136.284,282.188h-17.102c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h17.102 c4.427,0,8.017-3.589,8.017-8.017S140.711,282.188,136.284,282.188z"></path> </g> </g> <g> <g> <path d="M392.818,213.779h-17.102c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h17.102 c4.427,0,8.017-3.589,8.017-8.017S397.246,213.779,392.818,213.779z"></path> </g> </g> <g> <g> <path d="M392.818,247.983h-17.102c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h17.102 c4.427,0,8.017-3.589,8.017-8.017S397.246,247.983,392.818,247.983z"></path> </g> </g> <g> <g> <path d="M392.818,282.188h-17.102c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h17.102 c4.427,0,8.017-3.589,8.017-8.017S397.246,282.188,392.818,282.188z"></path> </g> </g> <g> <g> <path d="M273.102,213.779H170.489c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h102.614 c4.427,0,8.017-3.589,8.017-8.017S277.53,213.779,273.102,213.779z"></path> </g> </g> <g> <g> <path d="M238.898,247.983h-68.409c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h68.409 c4.427,0,8.017-3.589,8.017-8.017S243.325,247.983,238.898,247.983z"></path> </g> </g> <g> <g> <path d="M273.102,282.188H170.489c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h102.614 c4.427,0,8.017-3.589,8.017-8.017S277.53,282.188,273.102,282.188z"></path> </g> </g> <g> <g> <path d="M152.852,387.18v-27.859c5.907,1.936,9.62,5.833,9.62,9.447c0,4.427,3.589,8.017,8.017,8.017s8.017-3.589,8.017-8.017 c0-12.784-10.768-23.198-25.653-25.984v-1.273c0-4.427-3.589-8.017-8.017-8.017s-8.017,3.589-8.017,8.017v1.273 c-14.885,2.786-25.653,13.2-25.653,25.984c0,17.862,14.265,25.369,25.653,29.69v27.859c-5.907-1.936-9.62-5.834-9.62-9.447 c0-4.427-3.589-8.017-8.017-8.017s-8.017,3.589-8.017,8.017c0,12.784,10.768,23.198,25.653,25.984v1.273 c0,4.427,3.589,8.017,8.017,8.017s8.017-3.589,8.017-8.017v-1.273c14.885-2.786,25.653-13.2,25.653-25.984 C178.505,399.007,164.24,391.5,152.852,387.18z M136.818,380.966c-7.992-3.916-9.62-7.337-9.62-12.198 c0-3.614,3.713-7.511,9.62-9.447V380.966z M152.852,426.315V404.67c7.992,3.916,9.62,7.337,9.62,12.198 C162.472,420.482,158.758,424.38,152.852,426.315z"></path> </g> </g> <g> <g> <path d="M264.551,350.597h-59.858c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h59.858 c4.427,0,8.017-3.589,8.017-8.017S268.979,350.597,264.551,350.597z"></path> </g> </g> <g> <g> <path d="M238.898,419.006h-34.205c-4.427,0-8.017,3.589-8.017,8.017c0,4.427,3.589,8.017,8.017,8.017h34.205 c4.427,0,8.017-3.589,8.017-8.017C246.914,422.596,243.325,419.006,238.898,419.006z"></path> </g> </g> <g> <g> <path d="M290.205,419.006h-17.102c-4.427,0-8.017,3.589-8.017,8.017c0,4.427,3.589,8.017,8.017,8.017h17.102 c4.427,0,8.017-3.589,8.017-8.017C298.221,422.596,294.632,419.006,290.205,419.006z"></path> </g> </g> <g> <g> <path d="M315.858,384.802H204.693c-4.427,0-8.017,3.589-8.017,8.017c0,4.427,3.589,8.017,8.017,8.017h111.165 c4.427,0,8.017-3.589,8.017-8.017C323.875,388.391,320.285,384.802,315.858,384.802z"></path> </g> </g> <g> <g> <circle cx="273.102" cy="256" r="8.017"></circle> </g> </g> <g> <g> <circle cx="307.307" cy="256" r="8.017"></circle> </g> </g> <g> <g> <circle cx="341.511" cy="256" r="8.017"></circle> </g> </g> <g> <g> <circle cx="307.307" cy="221.795" r="8.017"></circle> </g> </g> <g> <g> <circle cx="341.511" cy="221.795" r="8.017"></circle> </g> </g> <g> <g> <circle cx="307.307" cy="290.205" r="8.017"></circle> </g> </g> <g> <g> <circle cx="341.511" cy="290.205" r="8.017"></circle> </g> </g> </g></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Grand Total -->
                                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div class="w-full">
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Grand Total</p>
                                            <input type="text"
                                                   id="grand-total-display"
                                                   readonly
                                                   class="mt-1 w-full border-none bg-transparent p-0 text-2xl font-bold text-green-600 focus:ring-0 dark:text-green-400"
                                                   value="Rp 0">
                                            <input type="hidden"
                                                   id="grand-total-value"
                                                   name="grand_total"
                                                   value="0">
                                        </div>
                                        <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-300"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="mt-4 flex justify-end gap-4">
                        <a href="{{ route('sales.custom-quotation.index') }}"
                           class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Batal
                        </a>
                        <button type="submit"
                                class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Simpan Quotation
                        </button>
                    </div>
                    <script>
                        (function() {
                            function handleDiskonChange(el) {
                                const row = el.closest('tr');
                                const diskonVal = parseFloat(el.value) || 0;
                                const keterangan = row.querySelector('.item-keterangan');
                                if (!keterangan) return;
                                if (diskonVal > 20) {
                                    keterangan.required = true;
                                    keterangan.classList.add('border-red-500');
                                } else {
                                    keterangan.required = false;
                                    keterangan.classList.remove('border-red-500');
                                }
                            }

                            // Attach to existing rows
                            document.querySelectorAll('.item-diskon').forEach(function(d) {
                                d.addEventListener('input', function() {
                                    handleDiskonChange(d);
                                });
                                // initial state
                                handleDiskonChange(d);
                            });

                            // When adding new rows, make sure handlers attach (if your add-row script triggers an event, adapt accordingly)
                            document.getElementById('btn-add-item')?.addEventListener('click', function() {
                                setTimeout(function() {
                                    document.querySelectorAll('.item-diskon').forEach(function(d) {
                                        if (!d.dataset._hasListener) {
                                            d.addEventListener('input', function() {
                                                handleDiskonChange(d);
                                            });
                                            d.dataset._hasListener = '1';
                                            handleDiskonChange(d);
                                        }
                                    });
                                }, 50);
                            });
                        })();
                    </script>
            </div>


            </form>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = 1;

            // Generate unique reference
            function generateUniqueRef() {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let result = 'REF-';
                for (let i = 0; i < 8; i++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return result;
            }

            // Set our_ref value on page load
            const ourRefInput = document.getElementById('our_ref');
            if (ourRefInput && !ourRefInput.value) {
                ourRefInput.value = generateUniqueRef();
            }

            // Format currency
            function formatCurrency(value) {
                return 'Rp ' + new Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }).format(value);
            }

            // Parse currency
            function parseCurrency(value) {
                return parseInt(value.replace(/\D/g, '')) || 0;
            }

            function formatInputPrice(input) {
                let selectionStart = input.selectionStart;
                let oldLength = input.value.length;
                let value = input.value;
                let cleanValue = value.replace(/[^0-9.]/g, '');

                const dotIndex = cleanValue.indexOf('.');
                if (dotIndex !== -1) {
                    cleanValue = cleanValue.substring(0, dotIndex + 1) + cleanValue.substring(dotIndex + 1).replace(/\./g, '');
                }

                let parts = cleanValue.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                let formattedValue = parts.join('.');
                input.value = formattedValue;

                let newLength = formattedValue.length;
                let newStart = selectionStart + (newLength - oldLength);
                input.setSelectionRange(newStart, newStart);
            }

            function formatNumberWithCommas(value) {
                if (!value) return '';
                let cleanValue = value.toString().replace(/[^0-9.]/g, '');
                const dotIndex = cleanValue.indexOf('.');
                if (dotIndex !== -1) {
                    cleanValue = cleanValue.substring(0, dotIndex + 1) + cleanValue.substring(dotIndex + 1).replace(/\./g, '');
                }
                let parts = cleanValue.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                return parts.join('.');
            }

            // Calculate subtotal for a single item
            function calculateItemSubtotal(row) {
                const qtyInput = row.querySelector('.item-qty');
                const hargaInput = row.querySelector('.item-harga');
                const diskonInput = row.querySelector('.item-diskon');
                const subtotalDisplay = row.querySelector('.item-subtotal');

                const qty = parseInt(qtyInput.value) || 0;
                const harga = parseFloat(hargaInput.value.replace(/,/g, '')) || 0;
                const diskonPercent = parseFloat(diskonInput.value) || 0;

                // Hitung subtotal dengan diskon: (qty * harga) * (1 - diskon%)
                const subtotal = (qty * harga) * (1 - diskonPercent / 100);

                subtotalDisplay.textContent = formatCurrency(subtotal);
                return subtotal;
            }

            // Calculate total and grand total
            function calculateTotals() {
                let subtotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    subtotal += calculateItemSubtotal(row);
                });

                const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
                const tax = Math.round(subtotal * (taxRate / 100));

                // Update tax inputs (hidden value and display text)
                document.getElementById('tax').value = tax;
                const taxDisplay = document.getElementById('tax_display');
                if (taxDisplay) taxDisplay.value = formatCurrency(tax);

                const grandTotal = subtotal + tax;

                // Update non-scrolling total banner
                const totalAmountEl = document.getElementById('totalAmount');
                if (totalAmountEl) {
                    totalAmountEl.textContent = formatCurrency(subtotal);
                }

                document.getElementById('subtotal-display').value = formatCurrency(subtotal);
                document.getElementById('subtotal-value').value = subtotal;
                document.getElementById('grand-total-display').value = formatCurrency(grandTotal);
                document.getElementById('grand-total-value').value = grandTotal;
            }

            // Listen for tax rate changes
            document.getElementById('tax_rate').addEventListener('input', calculateTotals);

            // Handle image preview
            function handleImagePreview(row) {
                const fileInput = row.querySelector('input[type="file"]');
                const preview = row.querySelector('.item-images-preview');
                const uploadBtn = row.querySelector('.upload-btn-container');

                fileInput.addEventListener('change', function() {
                    preview.innerHTML = '';
                    if (this.files.length > 0) {
                        uploadBtn.style.display = 'none';
                    } else {
                        uploadBtn.style.display = 'block';
                    }

                    Array.from(this.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'group relative inline-block';
                            imgContainer.innerHTML = `
                                <a href="${e.target.result}" target="_blank">
                                    <img src="${e.target.result}" class="w-20 h-20 object-cover rounded border transition-transform hover:scale-105" title="${file.name}">
                                </a>
                                <button type="button" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs remove-image-btn opacity-0 group-hover:opacity-100 transition-opacity shadow-lg" data-index="${index}">
                                    \u2715
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

            // Add item row
            document.getElementById('btn-add-item').addEventListener('click', function() {
                const container = document.getElementById('items-container');
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.index = itemCount;
                newRow.innerHTML = `
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <div class="flex flex-col gap-2">
                    <input type="text" name="items[${itemCount}][nama_barang]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        placeholder="Nama barang" required>
                    <select name="items[${itemCount}][category]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach(\App\Models\Goods::KATEGORI as $kategori)
                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="items[${itemCount}][description]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        placeholder="Deskripsi barang" required>
                </div>
            </td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2 w-full">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 min-w-[24px]">Qty</span>
                        <input type="number" name="items[${itemCount}][qty]" class="item-qty form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="0" value="1" min="1" required>
                    </div>
                    <div class="flex items-center gap-2 w-full">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 min-w-[24px]">Sat</span>
                        <input type="text" name="items[${itemCount}][satuan]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="Unit" required>
                    </div>
                    <div class="flex items-center gap-2 w-full">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 min-w-[24px]">Rp</span>
                        <input type="text" name="items[${itemCount}][harga]" class="item-harga form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="0" required>
                    </div>
                </div>
            </td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <div class="flex flex-col gap-2">
                    <div class="relative flex items-center w-full">
                        <input type="number" name="items[${itemCount}][diskon]" class="item-diskon form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pr-8 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="0" min="0" max="100" value="0" required>
                        <span class="absolute right-3 text-sm text-gray-500 dark:text-gray-400">%</span>
                    </div>
                    <input type="text" name="items[${itemCount}][keterangan]" class="item-keterangan form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        placeholder="Jika diskon > 20%">
                </div>
            </td>
            <td class="border border-gray-300 px-4 py-2 text-center dark:border-gray-600">
                <div class="relative upload-btn-container flex justify-center">
                    <input type="file" name="items[${itemCount}][images][]" class="item-images-input absolute inset-0 w-11 h-11 opacity-0 cursor-pointer z-10" 
                        multiple accept="image/*">
                    <button type="button" class="flex h-11 w-11 items-center justify-center rounded-xl border-2 border-dashed border-blue-300 bg-blue-50/50 text-blue-600 transition-all hover:border-blue-400 hover:bg-blue-50 dark:border-blue-900/40 dark:bg-blue-950/20 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
                <div class="item-images-preview flex flex-wrap gap-2 justify-center mt-2"></div>
            </td>
            <td class="item-subtotal border border-gray-300 px-4 py-2 text-center font-bold text-blue-600 dark:text-blue-400 dark:border-gray-600 text-nowrap" style="font-size: 0.875rem;">Rp 0</td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600 text-center">
                <button type="button" class="btn btn-remove-item p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-950/30 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2">
                        <path d="M3 6h18"></path>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                        <line x1="10" x2="10" y1="11" y2="17"></line>
                        <line x1="14" x2="14" y1="11" y2="17"></line>
                    </svg>
                </button>
            </td>
        `;

                container.appendChild(newRow);

                // Attach event listeners to new row
                const qtyInput = newRow.querySelector('.item-qty');
                const hargaInput = newRow.querySelector('.item-harga');
                const diskonInput = newRow.querySelector('.item-diskon');
                const keteranganInput = newRow.querySelector('.item-keterangan');
                const removeBtn = newRow.querySelector('.btn-remove-item');

                qtyInput.addEventListener('input', calculateTotals);
                hargaInput.addEventListener('input', function() {
                    formatInputPrice(this);
                    calculateTotals();
                });
                diskonInput.addEventListener('input', function() {
                    const diskonVal = parseFloat(diskonInput.value) || 0;
                    if (diskonVal > 20) {
                        keteranganInput.required = true;
                        keteranganInput.classList.add('border-red-500');
                    } else {
                        keteranganInput.required = false;
                        keteranganInput.classList.remove('border-red-500');
                    }
                    calculateTotals();
                });
                removeBtn.addEventListener('click', function() {
                    newRow.remove();
                    reindexItems();
                    calculateTotals();
                });

                handleImagePreview(newRow);
                itemCount++;
                reindexItems();
                calculateTotals();
            });

            // Remove item row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-item')) {
                    const row = e.target.closest('.item-row');
                    if (document.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        reindexItems();
                        calculateTotals();
                    } else {
                        alert('Minimal harus ada 1 barang');
                    }
                }
            });

            // Reindex items
            function reindexItems() {
                document.querySelectorAll('.item-row').forEach((row, index) => {
                    row.dataset.index = index;
                    const itemNoEl = row.querySelector('.item-no');
                    if (itemNoEl) {
                        itemNoEl.textContent = index + 1;
                    }

                    // Update all input names
                    row.querySelectorAll('input, select, textarea').forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/\[\d+\]/, `[${index}]`);
                            input.setAttribute('name', newName);
                        }
                    });
                });
            }

            // Format and attach event listeners for existing items
            document.querySelectorAll('.item-row').forEach(row => {
                const qtyInput = row.querySelector('.item-qty');
                const hargaInput = row.querySelector('.item-harga');
                const diskonInput = row.querySelector('.item-diskon');
                const keteranganInput = row.querySelector('.item-keterangan');
                const removeBtn = row.querySelector('.btn-remove-item');

                if (hargaInput.value) {
                    hargaInput.value = formatNumberWithCommas(hargaInput.value);
                }

                qtyInput.addEventListener('input', calculateTotals);
                hargaInput.addEventListener('input', function() {
                    formatInputPrice(this);
                    calculateTotals();
                });
                diskonInput.addEventListener('input', function() {
                    const diskonVal = parseFloat(diskonInput.value) || 0;
                    if (diskonVal > 20) {
                        keteranganInput.required = true;
                        keteranganInput.classList.add('border-red-500');
                    } else {
                        keteranganInput.required = false;
                        keteranganInput.classList.remove('border-red-500');
                    }
                    calculateTotals();
                });
                removeBtn.addEventListener('click', function() {
                    if (document.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        reindexItems();
                        calculateTotals();
                    } else {
                        alert('Minimal harus ada 1 barang');
                    }
                });

                handleImagePreview(row);
            });

            // Tax input change
            document.getElementById('tax').addEventListener('change', calculateTotals);

            // Clean up comma formatting before submitting to Laravel validation
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form) {
                    form.querySelectorAll('.item-harga').forEach(input => {
                        input.value = input.value.replace(/,/g, '');
                    });
                }
            });

            // Initial calculation
            calculateTotals();
        });
    </script>
</x-app-layout>

