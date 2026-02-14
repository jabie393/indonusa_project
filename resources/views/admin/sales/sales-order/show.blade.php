<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="space-y-3 p-6 md:space-x-4 md:space-y-0">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="col-span-2 mb-6 flex items-center">
                        <h1 class="text-3xl font-bold text-black dark:text-white">Detail Sales Order</h1>
                    </div>
                    <!-- Header Info -->
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-6 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <h2 class="mr-3 font-semibold text-white">Detail Sales Order</h2>
                        </div>
                        <div class="p-6">
                            <div class="mb-6 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">No Sales Order</label>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $salesOrder->so_number }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Our Ref</label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $salesOrder->our_ref }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal</label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($salesOrder->date)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                                    @php
                                        $statusClass =
                                            [
                                                'draft' => 'bg-gray-100 text-gray-800',
                                                'pending_approval' => 'bg-yellow-100 text-yellow-800',
                                                'sent' => 'bg-blue-100 text-blue-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ][$salesOrder->status] ?? 'bg-gray-100 text-gray-800';
                                        $statusLabel =
                                            [
                                                'draft' => 'Draft',
                                                'pending_approval' => 'Menunggu Persetujuan',
                                                'sent' => 'Terkirim',
                                                'approved' => 'Disetujui',
                                                'rejected' => 'Ditolak',
                                            ][$salesOrder->status] ?? $salesOrder->status;
                                    @endphp
                                    <span class="{{ $statusClass }} mt-1 inline-block rounded-full px-3 py-1 text-sm font-semibold">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-gray-700">

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 text-sm text-gray-600 dark:text-gray-400">Kepada (To)</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $salesOrder->to }}</p>
                                </div>
                                <div>
                                    <label class="mb-2 text-sm text-gray-600 dark:text-gray-400">Attn (Up)</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $salesOrder->up ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="mb-2 text-sm text-gray-600 dark:text-gray-400">Email</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $salesOrder->email }}</p>
                                </div>
                                <div>
                                    <label class="mb-2 text-sm text-gray-600 dark:text-gray-400">Subject</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $salesOrder->subject }}
                                    </p>
                                </div>
                            </div>

                            @if ($salesOrder->customPenawaran)
                                <hr class="my-6 border-gray-200 dark:border-gray-700">
                                <div>
                                    <label class="mb-2 text-sm text-gray-600 dark:text-gray-400">Asal Penawaran</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $salesOrder->customPenawaran->penawaran_number }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Intro Text -->
                    @if ($salesOrder->intro_text)
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-6 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                                <div>
                                    <h2 class="mr-3 font-semibold text-white">Teks Pembuka</h2>
                                </div>
                            </div>
                            <p class="p-6 text-gray-700 dark:text-gray-300">
                                {{ $salesOrder->intro_text }}</p>
                        </div>
                    @endif

                    <!-- Items Table -->
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <div>
                                <h2 class="mr-3 font-semibold text-white">Detail Barang</h2>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="border-b border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            No</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Nama Barang
                                        </th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Qty</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Satuan</th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Harga (Rp)
                                        </th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Diskon (%)
                                        </th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Keterangan
                                        </th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Total
                                            Setelah Diskon (Rp)</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Gambar
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($salesOrder->items as $item)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $item->nama_barang }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->qty }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->satuan }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">
                                                {{ number_format($item->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->diskon }}%
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->keterangan ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($item->images && count($item->images) > 0)
                                                    <div class="flex flex-wrap gap-2 justify-center">
                                                        @foreach ($item->images as $image)
                                                            <a href="{{ asset('storage/' . $image) }}" target="_blank" class="inline-block">
                                                                <img src="{{ asset('storage/' . $image) }}" alt="Gambar barang" class="w-12 h-12 object-cover rounded border border-gray-300 hover:border-blue-500 cursor-pointer">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-sm">Tidak ada gambar</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                                Tidak ada barang dalam sales order ini
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mt-6 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <div>
                                <h2 class="mr-3 font-semibold text-white">Ringkasan</h2>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-3">
                            <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sub Total</p>
                                <p class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($salesOrder->subtotal, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pajak/PPN</p>
                                <p class="mt-2 text-2xl font-bold text-green-600 dark:text-green-400">
                                    Rp {{ number_format($salesOrder->tax, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-lg bg-purple-50 p-4 dark:bg-purple-900/20">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Grand Total</p>
                                <p class="mt-2 text-2xl font-bold text-purple-600 dark:text-purple-400">
                                    Rp {{ number_format($salesOrder->grand_total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <!-- Sales Info -->
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <div>
                                <h3 class="font-semibold text-white">Informasi Sales</h3>
                            </div>
                        </div>
                        <div class="p-4">
                            @if ($salesOrder->sales)
                                <div class="mb-4">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400">SALES</p>
                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $salesOrder->sales->name }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400">DIBUAT</p>
                                <p class="text-gray-900 dark:text-white font-semibold">
                                    {{ $salesOrder->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <div>
                                <h3 class="font-semibold text-white">Aksi</h3>
                            </div>
                        </div>
                        <div class="space-y-2 p-4">
                            <a href="{{ route('sales.sales-order.edit', $salesOrder) }}" class="inline-block w-full rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700">
                                Edit
                            </a>
                            <form action="{{ route('sales.sales-order.destroy', $salesOrder) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sales order ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-block w-full rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                            <a href="{{ route('sales.sales-order.index') }}" class="inline-block w-full rounded-lg bg-gray-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-gray-700">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
