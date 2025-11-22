<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Penawaran Kustom</h1>
            <p class="text-gray-600 mt-2">Ubah detail penawaran</p>
        </div>

        <form action="{{ route('sales.custom-penawaran.update', $customPenawaran->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- To Field -->
                <div>
                    <label for="to" class="block text-sm font-medium text-gray-700 mb-2">Kepada (To)</label>
                    <input type="text" id="to" name="to" value="{{ old('to', $customPenawaran->to) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('to') border-red-500 @enderror"
                        placeholder="Nama customer">
                    @error('to')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Up Field -->
                <div>
                    <label for="up" class="block text-sm font-medium text-gray-700 mb-2">Attn (Up)</label>
                    <input type="text" id="up" name="up" value="{{ old('up', $customPenawaran->up) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('up') border-red-500 @enderror"
                        placeholder="Nama PIC">
                    @error('up')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Subject Field -->
                <div class="lg:col-span-2">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject', $customPenawaran->subject) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                        placeholder="Judul penawaran">
                    @error('subject')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customPenawaran->email) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="email@example.com">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Our Ref Field -->
                <div>
                    <label for="our_ref" class="block text-sm font-medium text-gray-700 mb-2">Our Ref</label>
                    <input type="text" id="our_ref" name="our_ref" value="{{ old('our_ref', $customPenawaran->our_ref) }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 @error('our_ref') border-red-500 @enderror">
                    @error('our_ref')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date Field -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ old('date', $customPenawaran->date) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror">
                    @error('date')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Intro Text -->
            <div class="mb-8">
                <label for="intro_text" class="block text-sm font-medium text-gray-700 mb-2">Teks Pembuka</label>
                <textarea id="intro_text" name="intro_text" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('intro_text') border-red-500 @enderror"
                    placeholder="Masukkan teks pembuka penawaran...">{{ old('intro_text', $customPenawaran->intro_text) }}</textarea>
                @error('intro_text')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Barang</h2>
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
                                    <td class="border border-gray-300 px-4 py-2 text-center item-no">{{ $index + 1 }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" name="items[{{ $index }}][nama_barang]" class="w-full px-2 py-1 border border-gray-300 rounded"
                                            placeholder="Nama barang" value="{{ $item->nama_barang }}" required>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="number" name="items[{{ $index }}][qty]" class="w-full px-2 py-1 border border-gray-300 rounded item-qty"
                                            placeholder="0" value="{{ $item->qty }}" min="1" required>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" name="items[{{ $index }}][satuan]" class="w-full px-2 py-1 border border-gray-300 rounded"
                                            placeholder="Unit" value="{{ $item->satuan }}" required>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="number" name="items[{{ $index }}][harga]" class="w-full px-2 py-1 border border-gray-300 rounded item-harga"
                                            placeholder="0" value="{{ $item->harga }}" step="0.01" min="0" required>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 text-right item-subtotal font-semibold">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <input type="file" name="items[{{ $index }}][images][]" class="item-images-input w-full text-xs" 
                                            multiple accept="image/*">
                                        <small class="text-gray-500 block mt-1">Format: JPG, PNG, GIF</small>
                                        <div class="item-images-preview mt-2 space-y-1" style="max-height: 100px; overflow-y: auto;">
                                            @if($item->images && count($item->images) > 0)
                                                @foreach($item->images as $image)
                                                    <img src="{{ Storage::url($image) }}" class="w-12 h-12 object-cover rounded border" title="{{ $image }}">
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <button type="button" class="btn-remove-item bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="item-row" data-index="0">
                                    <td class="border border-gray-300 px-4 py-2 text-center item-no">1</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" name="items[0][nama_barang]" class="w-full px-2 py-1 border border-gray-300 rounded"
                                            placeholder="Nama barang" required>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="number" name="items[0][qty]" class="w-full px-2 py-1 border border-gray-300 rounded item-qty"
                                            placeholder="0" value="1" min="1" required>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="text" name="items[0][satuan]" class="w-full px-2 py-1 border border-gray-300 rounded"
                                            placeholder="Unit" required>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <input type="number" name="items[0][harga]" class="w-full px-2 py-1 border border-gray-300 rounded item-harga"
                                            placeholder="0" step="0.01" min="0" required>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 text-right item-subtotal font-semibold">0</td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <input type="file" name="items[0][images][]" class="item-images-input w-full text-xs" 
                                            multiple accept="image/*">
                                        <small class="text-gray-500 block mt-1">Format: JPG, PNG, GIF</small>
                                        <div class="item-images-preview mt-2 space-y-1" style="max-height: 100px; overflow-y: auto;"></div>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <button type="button" class="btn-remove-item bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" id="btn-add-item" class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded font-semibold">
                    + Tambah Barang
                </button>
            </div>

            <!-- Summary Section -->
            <div class="bg-gray-50 p-6 rounded-lg mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Subtotal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sub Total (Rp)</label>
                        <input type="text" id="subtotal-display" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 font-semibold text-right"
                            value="0">
                        <input type="hidden" id="subtotal-value" name="subtotal" value="{{ $customPenawaran->subtotal }}">
                    </div>

                    <!-- Tax -->
                    <div>
                        <label for="tax" class="block text-sm font-medium text-gray-700 mb-2">Pajak/PPN (Rp)</label>
                        <input type="number" id="tax" name="tax" value="{{ old('tax', $customPenawaran->tax) }}" step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="0">
                    </div>

                    <!-- Grand Total -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grand Total (Rp)</label>
                        <input type="text" id="grand-total-display" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-blue-50 font-bold text-right text-lg text-blue-600"
                            value="0">
                        <input type="hidden" id="grand-total-value" name="grand_total" value="{{ $customPenawaran->grand_total }}">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('sales.custom-penawaran.index') }}" 
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
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

        fileInput.addEventListener('change', function() {
            // Don't clear existing images on edit, just add new ones
            const newPreviewContainer = document.createElement('div');
            newPreviewContainer.className = 'space-y-1';

            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-12 h-12 object-cover rounded border';
                    img.title = file.name;
                    newPreviewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });

            if (this.files.length > 0) {
                preview.appendChild(newPreviewContainer);
            }
        });
    }

    // Add item row
    document.getElementById('btn-add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.dataset.index = itemCount;
        newRow.innerHTML = `
            <td class="border border-gray-300 px-4 py-2 text-center item-no">${itemCount + 1}</td>
            <td class="border border-gray-300 px-4 py-2">
                <input type="text" name="items[${itemCount}][nama_barang]" class="w-full px-2 py-1 border border-gray-300 rounded"
                    placeholder="Nama barang" required>
            </td>
            <td class="border border-gray-300 px-4 py-2">
                <input type="number" name="items[${itemCount}][qty]" class="w-full px-2 py-1 border border-gray-300 rounded item-qty"
                    placeholder="0" value="1" min="1" required>
            </td>
            <td class="border border-gray-300 px-4 py-2">
                <input type="text" name="items[${itemCount}][satuan]" class="w-full px-2 py-1 border border-gray-300 rounded"
                    placeholder="Unit" required>
            </td>
            <td class="border border-gray-300 px-4 py-2">
                <input type="number" name="items[${itemCount}][harga]" class="w-full px-2 py-1 border border-gray-300 rounded item-harga"
                    placeholder="0" step="0.01" min="0" required>
            </td>
            <td class="border border-gray-300 px-4 py-2 text-right item-subtotal font-semibold">0</td>
            <td class="border border-gray-300 px-4 py-2 text-center">
                <input type="file" name="items[${itemCount}][images][]" class="item-images-input w-full text-xs" 
                    multiple accept="image/*">
                <small class="text-gray-500 block mt-1">Format: JPG, PNG, GIF</small>
                <div class="item-images-preview mt-2 space-y-1" style="max-height: 100px; overflow-y: auto;"></div>
            </td>
            <td class="border border-gray-300 px-4 py-2 text-center">
                <button type="button" class="btn-remove-item bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Hapus
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
