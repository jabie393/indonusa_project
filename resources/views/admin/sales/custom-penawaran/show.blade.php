<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="space-y-3 p-6 md:space-x-4 md:space-y-0">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="col-span-2 mb-6 flex items-center">
                        <h1 class="text-3xl font-bold text-black dark:text-white">Detail Penawaran</h1>
                    </div>
                    <!-- Header Info -->
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-6 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <h2 class="mr-3 font-semibold text-white">Detail Penawaran</h2>
                        </div>
                        <div class="p-6">
                            <div class="mb-6 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">No Penawaran</label>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $customPenawaran->penawaran_number }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Our Ref</label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $customPenawaran->our_ref }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal</label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($customPenawaran->date)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                                    @php
                                        $statusClass =
                                            [
                                                'draft' => 'bg-gray-100 text-gray-800',
                                                'sent' => 'bg-blue-100 text-blue-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ][$customPenawaran->status] ?? 'bg-gray-100 text-gray-800';
                                        $statusLabel =
                                            [
                                                'draft' => 'Draft',
                                                'sent' => 'Terkirim',
                                                'approved' => 'Disetujui',
                                                'rejected' => 'Ditolak',
                                            ][$customPenawaran->status] ?? $customPenawaran->status;
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
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $customPenawaran->to }}</p>
                                </div>
                                <div>
                                    <label class="mb-2 text-sm text-gray-600 dark:text-gray-400">Attn (Up)</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $customPenawaran->up ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="mb-2 text-sm text-gray-600 dark:text-gray-400">Email</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $customPenawaran->email }}</p>
                                </div>
                                <div>
                                    <label class="mb-2 text-sm text-gray-600 dark:text-gray-400">Subject</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $customPenawaran->subject }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Intro Text -->
                    @if ($customPenawaran->intro_text)
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-6 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                                <div>
                                    <h2 class="mr-3 font-semibold text-white">Teks Pembuka</h2>
                                </div>
                            </div>
                            <p class="p-6 text-gray-700 dark:text-gray-300">
                                {{ $customPenawaran->intro_text }}</p>
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
                                    @forelse($customPenawaran->items as $index => $item)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $item->nama_barang }}</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->qty }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->satuan }}</td>
                                            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp
                                                {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->diskon ?? 0 }}%</td>
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->keterangan ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                                @php
                                                    $diskonPercent = $item->diskon ?? 0;
                                                    $totalSetelahDiskon = $item->qty * $item->harga * (1 - $diskonPercent / 100);
                                                @endphp
                                                Rp {{ number_format($totalSetelahDiskon, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($item->images && count($item->images) > 0)
                                                    <div class="flex flex-wrap justify-center gap-3">
                                                        @foreach ($item->images as $image)
                                                            @php
                                                                if (is_null($image) || $image === '') {
                                                                    $imgUrl = null;
                                                                } elseif (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                                                                    $imgUrl = $image;
                                                                } elseif (str_starts_with($image, 'public/')) {
                                                                    $imgUrl = asset('storage/' . ltrim(substr($image, 7), '/'));
                                                                } else {
                                                                    $imgUrl = asset('storage/' . ltrim($image, '/'));
                                                                }
                                                            @endphp

                                                            @if ($imgUrl)
                                                                <button type="button" class="custom-penawaran-thumb inline-block" data-full="{{ $imgUrl }}" aria-label="Lihat gambar">
                                                                    <img src="{{ $imgUrl }}" alt="Gambar" class="h-20 w-20 rounded border border-gray-300 object-cover transition hover:shadow-lg sm:h-28 sm:w-28">
                                                                </button>
                                                            @else
                                                                <span class="text-sm text-gray-400">-</span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-4 py-6 text-center text-gray-500">Tidak ada
                                                barang</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Summary -->
                <div class="gap-2 lg:col-span-1">
                    <div class="sticky top-10">

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('sales.custom-penawaran.edit', $customPenawaran->id) }}" class="btn rounded-lg border-0 bg-[#225A97] px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800">
                                Edit
                            </a>
                            @if ($customPenawaran->status === 'approved')
                                <a href="{{ route('sales.custom-penawaran.pdf', $customPenawaran->id) }}" target="_blank" class="rounded-lg bg-green-500 px-4 py-2 font-semibold text-white hover:bg-green-600">
                                    Generate PDF
                                </a>
                            @endif
                            @if ($customPenawaran->status === 'sent' && auth()->user()->role === 'Supervisor')
                                <form action="{{ route('admin.custom-penawaran.approval', $customPenawaran) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" name="action" value="approve" class="rounded-lg bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700">Approve</button>
                                    <button type="submit" name="action" value="reject" class="ml-2 rounded-lg bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">Reject</button>
                                </form>
                            @endif
                            <a href="{{ route('sales.custom-penawaran.index') }}" class="flex items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-medium text-white hover:bg-[#1c4d81] focus:outline-none focus:ring-4 focus:ring-primary-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left h-4 w-4">
                                    <path d="m12 19-7-7 7-7"></path>
                                    <path d="M19 12H5"></path>
                                </svg> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm sticky top-24 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <div>
                                <h2 class="mr-3 font-semibold text-white">Ringkasan Penawaranan Kustom</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Sub Total</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($customPenawaran->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Pajak/PPN</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($customPenawaran->tax, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-4 dark:border-gray-700">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">Grand Total</span>
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">Rp
                                        {{ number_format($customPenawaran->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-gray-700">

                            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                <div>
                                    <p class="mb-1 font-semibold text-gray-700 dark:text-gray-300">Dibuat</p>
                                    <p>{{ $customPenawaran->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="mb-1 font-semibold text-gray-700 dark:text-gray-300">Diperbarui</p>
                                    <p>{{ $customPenawaran->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Image modal (lightbox) -->
    <div id="image-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-70">
        <button id="image-modal-close" class="absolute right-6 top-6 text-3xl leading-none text-white">&times;</button>
        <img id="image-modal-img" src="" alt="Gambar" class="max-h-[90%] max-w-[95%] rounded shadow-lg">
    </div>

    <script>
        (function() {
            const modal = document.getElementById('image-modal');
            const modalImg = document.getElementById('image-modal-img');
            const closeBtn = document.getElementById('image-modal-close');

            function openModal(src) {
                modalImg.src = src;
                modal.classList.remove('hidden');
            }

            function closeModal() {
                modalImg.src = '';
                modal.classList.add('hidden');
            }

            document.querySelectorAll('.custom-penawaran-thumb').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const src = this.getAttribute('data-full');
                    if (src) openModal(src);
                });
            });

            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });
        })();
    </script>
</x-app-layout>
