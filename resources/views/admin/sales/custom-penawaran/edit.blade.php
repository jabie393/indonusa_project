<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Penawaran Kustom</h1>
                <p class="mt-2 text-gray-600">Ubah detail penawaran</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('sales.custom-penawaran.update', $customPenawaran->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card bg-light bg-card mb-4 rounded-2xl border shadow-sm">
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
                                <label for="to" class="mb-2 block text-sm font-medium text-gray-700">Kepada (To)</label>
                                <input type="text" id="to" name="to" value="{{ old('to', $customPenawaran->to) }}" required
                                    class="@error('to') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                    placeholder="Nama customer">
                                @error('to')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Up Field -->
                            <div>
                                <label for="up" class="mb-2 block text-sm font-medium text-gray-700">Attn (Up)</label>
                                <input type="text" id="up" name="up" value="{{ old('up', $customPenawaran->up) }}"
                                    class="@error('up') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                    placeholder="Nama PIC">
                                @error('up')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Subject Field -->
                            <div class="lg:col-span-2">
                                <label for="subject" class="mb-2 block text-sm font-medium text-gray-700">Subject</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject', $customPenawaran->subject) }}" required
                                    class="@error('subject') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                    placeholder="Judul penawaran">
                                @error('subject')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $customPenawaran->email) }}" required
                                    class="@error('email') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                    placeholder="email@example.com">
                                @error('email')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Our Ref Field -->
                            <div>
                                <label for="our_ref" class="mb-2 block text-sm font-medium text-gray-700">Our Ref</label>
                                <input type="text" id="our_ref" name="our_ref" value="{{ old('our_ref', $customPenawaran->our_ref) }}" readonly
                                    class="@error('our_ref') border-red-500 @enderror w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2">
                                @error('our_ref')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Date Field -->
                            <div>
                                <label for="date" class="mb-2 block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" id="date" name="date" value="{{ old('date', $customPenawaran->date) }}" required
                                    class="@error('date') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500">
                                @error('date')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Intro Text -->
                            <div class="lg:col-span-2">
                                <label for="intro_text" class="mb-2 block text-sm font-medium text-gray-700">Teks Pembuka</label>
                                <textarea id="intro_text" name="intro_text" rows="4"
                                    class="@error('intro_text') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                    placeholder="Masukkan teks pembuka penawaran...">{{ old('intro_text', $customPenawaran->intro_text) }}</textarea>
                                @error('intro_text')
                                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card bg-light bg-card mb-4 rounded-2xl border shadow-sm">
                        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
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
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">No</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Nama Barang</th>
                                        <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Qty</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Satuan</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right text-sm font-semibold">Harga (Rp)</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right text-sm font-semibold">Total (Rp)</th>
                                        <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Gambar</th>
                                        <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    @forelse($customPenawaran->items as $index => $item)
                                        <tr class="item-row" data-index="{{ $index }}">
                                            <td class="item-no border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="text" name="items[{{ $index }}][nama_barang]"
                                                    class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                    placeholder="Nama barang" value="{{ $item->nama_barang }}" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="number" name="items[{{ $index }}][qty]"
                                                    class="item-qty form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                    placeholder="0" value="{{ $item->qty }}" min="1" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="text" name="items[{{ $index }}][satuan]"
                                                    class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                    placeholder="Unit" value="{{ $item->satuan }}" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="number" name="items[{{ $index }}][harga]"
                                                    class="item-harga form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                    placeholder="0" value="{{ $item->harga }}" step="0.01" min="0" required>
                                            </td>
                                            <td class="item-subtotal border border-gray-300 px-4 py-2 text-right font-semibold">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <div class="upload-btn-container relative">
                                                    <input type="file" name="items[{{ $index }}][images][]"
                                                        class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0" multiple accept="image/*">
                                                    <button type="button" class="upload-button rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600"
                                                        style="{{ $item->images && count($item->images) > 0 ? 'display: none;' : '' }}">
                                                        Upload Gambar
                                                    </button>
                                                </div>
                                                <!-- Hidden inputs to preserve existing images -->
                                                @if ($item->images && count($item->images) > 0)
                                                    @foreach ($item->images as $image)
                                                        <input type="hidden" name="items[{{ $index }}][existing_images][]" value="{{ $image }}">
                                                    @endforeach
                                                @endif
                                                <div class="item-images-preview mt-2 flex flex-wrap gap-2">
                                                    @if ($item->images && count($item->images) > 0)
                                                        @foreach ($item->images as $image)
                                                            <div class="relative inline-block">
                                                                <img src="{{ Storage::url($image) }}" class="h-20 w-20 rounded border object-cover" title="{{ $image }}">
                                                                <button type="button"
                                                                    class="remove-existing-image absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs text-white hover:bg-red-600"
                                                                    data-image="{{ $image }}">
                                                                    ✕
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <button type="button" class="btn btn-remove-item rounded-lg bg-red-500 text-white hover:bg-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 h-4 w-4">
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
                                            <td class="item-no border border-gray-300 px-4 py-2 text-center">1</td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="text" name="items[0][nama_barang]" class="w-full rounded border border-gray-300 px-2 py-1" placeholder="Nama barang" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="number" name="items[0][qty]" class="item-qty w-full rounded border border-gray-300 px-2 py-1" placeholder="0" value="1"
                                                    min="1" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="text" name="items[0][satuan]" class="w-full rounded border border-gray-300 px-2 py-1" placeholder="Unit" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="number" name="items[0][harga]" class="item-harga w-full rounded border border-gray-300 px-2 py-1" placeholder="0" step="0.01"
                                                    min="0" required>
                                            </td>
                                            <td class="item-subtotal border border-gray-300 px-4 py-2 text-right font-semibold">0</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="file" name="items[0][images][]" class="item-images-input w-full text-xs" multiple accept="image/*">
                                                <div class="item-images-preview mt-2 space-y-1" style="max-height: 100px; overflow-y: auto;"></div>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <button type="button" class="btn-remove-item rounded bg-red-500 px-3 py-1 text-sm text-white hover:bg-red-600">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <button type="button" id="btn-add-item" class="btn bg-[#225A97] text-white hover:bg-[#1c4d81] m-5">
                            + Tambah Barang
                        </button>
                    </div>

                    <!-- Summary Section -->
                    <div class="mb-8 rounded-lg bg-gray-50 p-6">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <!-- Subtotal -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Sub Total (Rp)</label>
                                <input type="text" id="subtotal-display" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2 text-right font-semibold" value="0">
                                <input type="hidden" id="subtotal-value" name="subtotal" value="{{ $customPenawaran->subtotal }}">
                            </div>

                            <!-- Tax -->
                            <div>
                                <label for="tax" class="mb-2 block text-sm font-medium text-gray-700">Pajak/PPN (Rp)</label>
                                <input type="number" id="tax" name="tax" value="{{ old('tax', $customPenawaran->tax) }}" step="0.01" min="0"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500" placeholder="0">
                            </div>

                            <!-- Grand Total -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Grand Total (Rp)</label>
                                <input type="text" id="grand-total-display" readonly
                                    class="w-full rounded-lg border border-gray-300 bg-blue-50 px-4 py-2 text-right text-lg font-bold text-blue-600" value="0">
                                <input type="hidden" id="grand-total-value" name="grand_total" value="{{ $customPenawaran->grand_total }}">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('sales.custom-penawaran.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = {{ count($customPenawaran->items) }};

            // Format currency
            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);
            }

            // Calculate subtotal for a single item
            function calculateItemSubtotal(row) {
                const qtyInput = row.querySelector('.item-qty');
                const hargaInput = row.querySelector('.item-harga');
                const subtotalDisplay = row.querySelector('.item-subtotal');

                const qty = parseInt(qtyInput.value) || 0;
                const harga = parseFloat(hargaInput.value) || 0;
                const subtotal = qty * harga;

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
                const fileInput = row.querySelector('.item-images-input');
                const preview = row.querySelector('.item-images-preview');
                const uploadBtn = row.querySelector('.upload-button');

                // Handle removing existing images
                const removeExistingBtns = row.querySelectorAll('.remove-existing-image');
                removeExistingBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const imagePath = this.dataset.image;

                        // Remove the image div
                        this.closest('div.relative').remove();

                        // Remove the corresponding hidden input
                        const hiddenInputs = row.querySelectorAll('input[type="hidden"][name*="existing_images"]');
                        hiddenInputs.forEach(input => {
                            if (input.value === imagePath) {
                                input.remove();
                            }
                        });

                        // Show upload button if no more images
                        if (preview.querySelectorAll('img').length === 0 && fileInput.files.length === 0) {
                            uploadBtn.style.display = 'block';
                        }
                    });
                });

                fileInput.addEventListener('change', function() {
                    // Clear only newly added file previews, not existing ones
                    const newlyAddedPreviews = preview.querySelectorAll('div:has(.remove-image-btn)');
                    newlyAddedPreviews.forEach(div => div.remove());

                    if (this.files.length > 0) {
                        uploadBtn.style.display = 'none';
                    } else if (preview.querySelectorAll('img').length === 0) {
                        uploadBtn.style.display = 'block';
                    }

                    Array.from(this.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'relative inline-block';
                            imgContainer.innerHTML = `
                                <img src="${e.target.result}" class="w-20 h-20 object-cover rounded border" title="${file.name}">
                                <button type="button" class="remove-image-btn absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs" data-index="${index}">
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
            <td class="item-no border border-gray-300 px-4 py-2 text-cente">${itemCount + 1}</td>
            <td class="border border-gray-300 px-4 py-2">
                <input type="text" name="items[${itemCount}][nama_barang]" class="form-control  block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="Nama barang" required>
            </td>
            <td class="border border-gray-300 px-4 py-2">
                <input type="number" name="items[${itemCount}][qty]" class="item-qty form-control  block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="0" value="1" min="1" required>
            </td>
            <td class="border border-gray-300 px-4 py-2">
                <input type="text" name="items[${itemCount}][satuan]" class="form-control  block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="Unit" required>
            </td>
            <td class="border border-gray-300 px-4 py-2">
                <input type="number" name="items[${itemCount}][harga]" class="item-harga form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    placeholder="0" step="0.01" min="0" required>
            </td>
            <td class="border border-gray-300 px-4 py-2 text-right item-subtotal font-semibold">0</td>
            <td class="border border-gray-300 px-4 py-2 text-center">
                <div class="relative upload-btn-container">
                    <input type="file" name="items[${itemCount}][images][]" class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0" 
                        multiple accept="image/*">
                    <button type="button" class="upload-button rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">
                        Upload Gambar
                    </button>
                </div>
                <div class="item-images-preview mt-2 flex flex-wrap gap-2"></div>
            </td>
            <td class="border border-gray-300 px-4 py-2">
                <button type="button" class="btn btn-remove-item rounded-lg bg-red-500 text-white hover:bg-red-600">
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
                const removeBtn = newRow.querySelector('.btn-remove-item');

                qtyInput.addEventListener('change', calculateTotals);
                hargaInput.addEventListener('change', calculateTotals);
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
                const removeBtn = row.querySelector('.btn-remove-item');

                qtyInput.addEventListener('change', calculateTotals);
                hargaInput.addEventListener('change', calculateTotals);
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
