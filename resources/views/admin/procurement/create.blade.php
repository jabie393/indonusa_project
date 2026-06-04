<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Buat Procurement Baru</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Buat pesanan pengadaan berdasarkan Custom Quotation #{{ $customQuotation->quotation_number }}</p>
            </div>
        </div>

        <div class="card p-6">
            <form id="procurementCreateForm" action="{{ route('general-affair.procurement.store') }}" method="POST">
                @csrf
                <input type="hidden" name="custom_quotation_id" value="{{ $customQuotation->id }}">

                <!-- Customer and Info Summary Card -->
                <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                        <h3 class="flex items-center gap-2 text-lg font-semibold leading-none tracking-tight">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Custom Quotation Asal
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 gap-6 p-5 lg:grid-cols-3">
                        <div>
                            <span class="block text-xs font-semibold uppercase text-slate-400">Kepada / Customer</span>
                            <span class="text-base font-bold text-slate-800 dark:text-white">{{ $customQuotation->to }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase text-slate-400">Subject</span>
                            <span class="text-base font-bold text-slate-800 dark:text-white">{{ $customQuotation->subject }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase text-slate-400">Tanggal Target Delivery</span>
                            <span class="text-base font-bold text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($customQuotation->date)->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="mb-6">
                    <label for="notes" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Pengadaan (Notes)</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                        placeholder="Masukkan catatan pembelian atau nama supplier..."></textarea>
                </div>

                <!-- Item list table -->
                <div class="card bg-light bg-card inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
                        <h3 class="flex items-center gap-2 text-lg font-semibold leading-none tracking-tight font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Barang Kustom Untuk Dipesan
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-slate-700 dark:text-slate-200">
                                    <th class="border border-gray-300 px-4 py-3 text-left text-xs uppercase font-bold tracking-wider dark:border-gray-600">Nama Barang</th>
                                    <th class="border border-gray-300 px-4 py-3 text-left text-xs uppercase font-bold tracking-wider dark:border-gray-600">Kategori</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Satuan</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Qty Permintaan</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Qty Dipesan (GA)</th>
                                    <th class="border border-gray-300 px-4 py-3 text-right text-xs uppercase font-bold tracking-wider dark:border-gray-600">Harga Beli Satuan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customQuotation->items as $index => $item)
                                    @php
                                        // Pastikan goods_id ada
                                        $goodsId = $item->goods_id;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                        <input type="hidden" name="items[{{ $index }}][goods_id]" value="{{ $goodsId }}">
                                        <input type="hidden" name="items[{{ $index }}][qty_requested]" value="{{ $item->qty }}">
                                        
                                        <!-- Nama Barang -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900 dark:text-white dark:border-gray-600 font-medium">
                                            {{ $item->product_name }}
                                            @if($item->description)
                                                <span class="block text-xs text-gray-400 dark:text-gray-500 font-normal mt-1">{{ $item->description }}</span>
                                            @endif
                                        </td>
                                        
                                        <!-- Kategori -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-600 dark:text-gray-300 dark:border-gray-600">
                                            {{ $item->category ?: 'OTHER CATEGORIES' }}
                                        </td>
                                        
                                        <!-- Satuan -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300 dark:border-gray-600">
                                            {{ $item->unit }}
                                        </td>
                                        
                                        <!-- Qty Requested -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-center text-gray-900 dark:text-white dark:border-gray-600 font-semibold">
                                            {{ $item->qty }}
                                        </td>
                                        
                                        <!-- Qty Ordered (Editable, default to Qty Requested) -->
                                        <td class="border border-gray-300 px-4 py-3 dark:border-gray-600" style="width: 150px;">
                                            <input type="number" name="items[{{ $index }}][qty_ordered]" 
                                                value="{{ old('items.'.$index.'.qty_ordered', $item->qty) }}"
                                                min="{{ $item->qty }}" required
                                                class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-center text-sm font-semibold focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                                title="GA bisa memesan stok lebih dari permintaan quotation">
                                            @error('items.'.$index.'.qty_ordered')
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        
                                        <!-- Buy Price (Input by GA) -->
                                        <td class="border border-gray-300 px-4 py-3 dark:border-gray-600" style="width: 220px;">
                                            <div class="relative flex items-center">
                                                <span class="absolute left-3 text-sm text-gray-500 dark:text-gray-400 font-semibold">Rp</span>
                                                <input type="text" name="items[{{ $index }}][buy_price]" 
                                                    value="{{ old('items.'.$index.'.buy_price', '') }}"
                                                    required placeholder="0"
                                                    class="buy-price-input w-full rounded-lg border border-gray-300 bg-gray-50 p-2 pl-9 text-right text-sm font-semibold focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>
                                            @error('items.'.$index.'.buy_price')
                                                <span class="text-xs text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('general-affair.procurement.index') }}" 
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                        class="rounded-lg bg-[#225A97] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#19426d] transition-all">
                        Simpan &amp; Buat Pengadaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS for formatting price input -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            document.querySelectorAll('.buy-price-input').forEach(input => {
                if (input.value) {
                    input.value = formatNumberWithCommas(input.value);
                }
                input.addEventListener('input', function() {
                    formatInputPrice(this);
                });
            });

            const createForm = document.getElementById('procurementCreateForm');
            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    document.querySelectorAll('.buy-price-input').forEach(input => {
                        input.value = input.value.replace(/,/g, '');
                    });
                });
            }
        });
    </script>
</x-app-layout>
