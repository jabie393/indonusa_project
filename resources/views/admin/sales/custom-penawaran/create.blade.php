<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Buat Penawaran Kustom</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Buat penawaran kustom untuk customer</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('sales.custom-penawaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card bg-light bg-card mb-4 rounded-2xl inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm shadow-sm">
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
                            <!-- To Field -->
                            <div>
                                <label for="to" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Kepada (To)</label>
                                <input type="text" id="to" name="to" value="{{ old('to') }}" required
                                    class="@error('to') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                    placeholder="Nama customer">
                                @error('to')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Up Field -->
                            <div>
                                <label for="up" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Attn (Up)</label>
                                <input type="text" id="up" name="up" value="{{ old('up') }}"
                                    class="@error('up') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                    placeholder="Nama PIC">
                                @error('up')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Subject Field -->
                            <div class="lg:col-span-2">
                                <label for="subject" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                    class="@error('subject') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                    placeholder="Judul penawaran">
                                @error('subject')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="@error('email') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                    placeholder="email@example.com">
                                @error('email')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Our Ref Field (Auto-generated) -->
                            <div>
                                <label for="our_ref" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Our Ref (Auto)</label>
                                <input type="text" id="our_ref" name="our_ref" value="{{ old('our_ref') }}"
                                    class="@error('our_ref') border-red-500 @enderror w-full rounded-lg bg-gray-100 px-4 py-2 dark:bg-gray-600 dark:text-gray-300" placeholder="Auto-generated">
                                @error('our_ref')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Date Field -->
                            <div>
                                <label for="date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                                <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                    class="@error('date') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white">
                                @error('date')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Intro Text -->
                            <div class="lg:col-span-2">
                                <label for="intro_text" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Teks Pembuka</label>
                                <textarea id="intro_text" name="intro_text" rows="4"
                                    class="@error('intro_text') border-red-500 @else border-gray-300 dark:border-gray-500 @enderror w-full rounded-lg border bg-gray-50 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                    placeholder="Masukkan teks pembuka penawaran...">{{ old('intro_text') }}</textarea>
                                @error('intro_text')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card bg-light bg-card mb-4 rounded-2xl inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm shadow-sm">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
                            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
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
                            <table id="" class="h-full w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">No</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Nama Barang</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Qty</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Satuan</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Harga (Rp)</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Diskon (%)</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Keterangan</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Total Setelah Diskon (Rp)</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Gambar</th>
                                        <th class="border text-black border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600 dark:text-gray-100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    <tr class="item-row" data-index="0">
                                        <td class="item-no border text-black border-gray-300 px-4 py-2 text-center dark:border-gray-600 dark:text-gray-100">1</td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text" name="items[0][nama_barang]"
                                                class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="Nama barang" value="{{ old('items.0.nama_barang') }}" required>
                                            @error('items.0.nama_barang')
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="items[0][qty]"
                                                class="item-qty form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="0" value="{{ old('items.0.qty', 1) }}" min="1" required>
                                            @error('items.0.qty')
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text" name="items[0][satuan]"
                                                class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="Unit" value="{{ old('items.0.satuan') }}" required>
                                            @error('items.0.satuan')
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="items[0][harga]"
                                                class="item-harga form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="0" value="{{ old('items.0.harga') }}" step="0.01" min="0" required>
                                            @error('items.0.harga')
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="number" name="items[0][diskon]"
                                                class="item-diskon form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="0" value="{{ old('items.0.diskon', 0) }}" min="0" max="100" required>
                                            @error('items.0.diskon')
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <input type="text" name="items[0][keterangan]"
                                                class="item-keterangan form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="Keterangan jika diskon > 20%" value="{{ old('items.0.keterangan') }}">
                                            @error('items.0.keterangan')
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 item-subtotal text-right font-semibold dark:border-gray-600 dark:text-gray-100">0</td>
                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                            <div class="upload-btn-container relative">
                                                <input type="file" name="items[0][images][]" class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0" multiple
                                                    accept="image/*">
                                                <button type="button" class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">
                                                    Upload Gambar
                                                </button>
                                            </div>
                                            <div class="item-images-preview mt-2 flex flex-wrap gap-2 space-y-2"></div>
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                                            <button type="button" class="btn btn-remove-item border-none rounded-lg bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
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
                    <div class="mb-8 rounded-lg bg-gray-50 p-6 dark:bg-gray-700 shadow-md inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <!-- Subtotal -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sub Total (Rp)</label>
                                <input type="text" id="subtotal-display" readonly class="w-full text-black rounded-lg bg-gray-100 px-4 py-2 text-right font-semibold dark:bg-gray-600 dark:text-gray-100"
                                    value="0">
                                <input type="hidden" id="subtotal-value" name="subtotal" value="0">
                            </div>

                            <!-- Tax -->
                            <div>
                                <label for="tax" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Pajak/PPN (Rp)</label>
                                <input type="number" id="tax" name="tax" value="{{ old('tax', 0) }}" step="0.01" min="0"
                                    class="w-full rounded-lg border text-black border-gray-300 bg-gray-50 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-600 dark:text-white"
                                    placeholder="0">
        
                            </div>

                            <!-- Grand Total -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Grand Total (Rp)</label>
                                <input type="text" id="grand-total-display" readonly
                                    class="w-full rounded-lg bg-blue-50 px-4 py-2 text-right text-lg font-bold text-blue-600 dark:bg-blue-900 dark:text-blue-200" value="0">
                                <input type="hidden" id="grand-total-value" name="grand_total" value="0">
                            </div>
                        </div>
                        <script>
                            (function(){
                                function handleDiskonChange(el){
                                    const row = el.closest('tr');
                                    const diskonVal = parseFloat(el.value) || 0;
                                    const keterangan = row.querySelector('.item-keterangan');
                                    if(!keterangan) return;
                                    if(diskonVal > 20){
                                        keterangan.required = true;
                                        keterangan.classList.add('border-red-500');
                                    } else {
                                        keterangan.required = false;
                                        keterangan.classList.remove('border-red-500');
                                    }
                                }

                                // Attach to existing rows
                                document.querySelectorAll('.item-diskon').forEach(function(d){
                                    d.addEventListener('input', function(){ handleDiskonChange(d); });
                                    // initial state
                                    handleDiskonChange(d);
                                });

                                // When adding new rows, make sure handlers attach (if your add-row script triggers an event, adapt accordingly)
                                document.getElementById('btn-add-item')?.addEventListener('click', function(){
                                    setTimeout(function(){
                                        document.querySelectorAll('.item-diskon').forEach(function(d){
                                            if(!d.dataset._hasListener){
                                                d.addEventListener('input', function(){ handleDiskonChange(d); });
                                                d.dataset._hasListener = '1';
                                                handleDiskonChange(d);
                                            }
                                        });
                                    }, 50);
                                });
                            })();
                        </script>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('sales.custom-penawaran.index') }}" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Batal
                        </a>
                        <button type="submit" class="btn rounded-lg bg-[#225A97] text-white hover:bg-[#1c4d81]">
                            Simpan Penawaran
                        </button>
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
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);
            }

            // Parse currency
            function parseCurrency(value) {
                return parseInt(value.replace(/\D/g, '')) || 0;
            }

            // Calculate subtotal for a single item
            function calculateItemSubtotal(row) {
                const qtyInput = row.querySelector('.item-qty');
                const hargaInput = row.querySelector('.item-harga');
                const diskonInput = row.querySelector('.item-diskon');
                const subtotalDisplay = row.querySelector('.item-subtotal');

                const qty = parseInt(qtyInput.value) || 0;
                const harga = parseFloat(hargaInput.value) || 0;
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

                const tax = parseFloat(document.getElementById('tax').value) || 0;
                const grandTotal = subtotal + tax;

                document.getElementById('subtotal-display').value = formatCurrency(subtotal);
                document.getElementById('subtotal-value').value = subtotal;
                document.getElementById('grand-total-display').value = formatCurrency(grandTotal);
                document.getElementById('grand-total-value').value = grandTotal;
            }

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

            // Add item row
            document.getElementById('btn-add-item').addEventListener('click', function() {
                const container = document.getElementById('items-container');
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.dataset.index = itemCount;
                newRow.innerHTML = `
            <td class="border border-gray-300 px-4 py-2 text-center item-no dark:border-gray-600 text-black dark:text-gray-100">${itemCount + 1}</td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <input type="text" name="items[${itemCount}][nama_barang]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="Nama barang" required>
            </td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <input type="number" name="items[${itemCount}][qty]" class="item-qty form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="0" value="1" min="1" required>
            </td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <input type="text" name="items[${itemCount}][satuan]" class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="Unit" required>
            </td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <input type="number" name="items[${itemCount}][harga]" class="item-harga form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="0" step="0.01" min="0" required>
            </td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <input type="number" name="items[${itemCount}][diskon]" class="item-diskon form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="0" min="0" max="100" value="0" required>
            </td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <input type="text" name="items[${itemCount}][keterangan]" class="item-keterangan form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="Keterangan jika diskon > 20%">
            </td>
            <td class="border border-gray-300 px-4 py-2 text-black text-right item-subtotal font-semibold dark:border-gray-600 dark:text-gray-100">0</td>
            <td class="border border-gray-300 px-4 py-2 text-center dark:border-gray-600">
                <div class="relative upload-btn-container">
                    <input type="file" name="items[${itemCount}][images][]" class="item-images-input absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                        multiple accept="image/*">
                    <button type="button" class="upload-button bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold dark:bg-blue-600 dark:hover:bg-blue-700">
                        Upload Gambar
                    </button>
                </div>
                <div class="item-images-preview mt-2 flex flex-wrap gap-2"></div>
            </td>
            <td class="border border-gray-300 px-4 py-2 dark:border-gray-600">
                <button type="button" class="btn btn-remove-item rounded-lg bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
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
        `;

                container.appendChild(newRow);

                // Attach event listeners to new row
                const qtyInput = newRow.querySelector('.item-qty');
                const hargaInput = newRow.querySelector('.item-harga');
                const diskonInput = newRow.querySelector('.item-diskon');
                const keteranganInput = newRow.querySelector('.item-keterangan');
                const removeBtn = newRow.querySelector('.btn-remove-item');

                qtyInput.addEventListener('change', calculateTotals);
                hargaInput.addEventListener('change', calculateTotals);
                diskonInput.addEventListener('change', function() {
                    const diskonVal = parseFloat(diskonInput.value) || 0;
                    if(diskonVal > 20) {
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
                    row.querySelector('.item-no').textContent = index + 1;

                    // Update all input names
                    row.querySelectorAll('input, textarea').forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/\[\d+\]/, `[${index}]`);
                            input.setAttribute('name', newName);
                        }
                    });
                });
            }

            // Event listeners for existing items
            document.querySelectorAll('.item-row').forEach(row => {
                const qtyInput = row.querySelector('.item-qty');
                const hargaInput = row.querySelector('.item-harga');
                const diskonInput = row.querySelector('.item-diskon');
                const keteranganInput = row.querySelector('.item-keterangan');
                const removeBtn = row.querySelector('.btn-remove-item');

                qtyInput.addEventListener('change', calculateTotals);
                hargaInput.addEventListener('change', calculateTotals);
                diskonInput.addEventListener('change', function() {
                    const diskonVal = parseFloat(diskonInput.value) || 0;
                    if(diskonVal > 20) {
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

            // Initial calculation
            calculateTotals();
        });
    </script>
</x-app-layout>
