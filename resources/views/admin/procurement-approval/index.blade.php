<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <!-- Top Search Bar -->
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-end overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <form action="{{ route('supervisor.procurement-approval.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-80">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search" value="{{ request('search') }}"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                        placeholder="Cari pengadaan, barang, vendor..." />
                </div>
            </form>
        </div>

        <!-- Main Card Container -->
        <div class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="flex shrink-0 items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] px-5 py-3.5">
                <div class="flex items-center gap-2 text-white">
                    <span class="text-sm font-bold uppercase tracking-wider">Daftar Menunggu Persetujuan Supervisor</span>
                    <span class="rounded-full bg-amber-400 px-2.5 py-0.5 text-xs font-bold text-slate-900">
                        {{ $requests->total() }} Antrean
                    </span>
                </div>
            </div>

            <div class="grow overflow-x-auto overflow-y-auto">
                <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Pengadaan &amp; Vendor</th>
                            <th class="px-4 py-3">Barang</th>
                            <th scope="col" class="text-nowrap px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3 text-center">Qty Datang</th>
                            <th class="px-4 py-3 text-right">Harga &amp; Total</th>
                            <th scope="col" class="flex justify-end text-nowrap px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($requests as $item)
                            @php
                                $procurement = $item->procurementOfGoodsItem?->procurementOfGoods;
                                $subtotal = $item->quantity * $item->unit_cost;
                            @endphp
                            <tr class="border-b transition-colors duration-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                                <!-- Pengadaan & Vendor -->
                                <td class="px-4 py-3.5 align-top">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="font-bold text-[#225A97] dark:text-blue-400 text-sm">
                                             {{ $procurement->procurement_number ?? '-' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">
                                            <svg class="h-3 w-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            {{ $procurement->vendor_name ?: 'Vendor tidak disebutkan' }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5 flex-wrap">
                                        <span>{{ $item->received_at ? $item->received_at->format('Y-m-d H:i') : $item->created_at->format('Y-m-d H:i') }}</span>
                                        <span>•</span>
                                        <span class="font-medium text-slate-600 dark:text-slate-300">
                                            @if($procurement?->order)
                                                SO: {{ $procurement->order->order_number }}
                                            @elseif($procurement?->customQuotation)
                                                Quotation: {{ $procurement->customQuotation->quotation_number }}
                                            @else
                                                Pengadaan Stok Gudang
                                            @endif
                                        </span>
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        <span>Oleh: <strong class="font-semibold text-gray-700 dark:text-gray-300">{{ $procurement->generalAffair->name ?? 'General Affair' }}</strong></span>
                                        @if(!empty($procurement->notes))
                                            <span class="italic text-gray-400"> - "{{ $procurement->notes }}"</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Barang -->
                                <td class="px-4 py-3.5 align-top">
                                    <div class="text-[11px] font-semibold uppercase text-gray-400 tracking-wider">
                                        {{ $item->good->category ?? '-' }}
                                    </div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $item->good->goods_name ?? '-' }}
                                    </div>
                                    <div class="font-mono text-xs text-blue-600 dark:text-blue-400">
                                        {{ $item->good->goods_code ?? '-' }}
                                    </div>
                                </td>

                                <!-- Deskripsi (Warehouse Style) -->
                                <td class="max-w-xs px-4 align-middle">
                                    <div class="line-clamp-3 max-w-[250px] break-words text-xs text-gray-600 dark:text-gray-300">
                                        {{ $item->good->description ?: '-' }}
                                    </div>
                                </td>

                                <!-- Qty Datang -->
                                <td class="px-4 py-3.5 text-center align-middle whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">
                                        {{ $item->quantity }} {{ $item->good->unit ?? 'Unit' }}
                                    </span>
                                </td>

                                <!-- Harga & Total -->
                                <td class="px-4 py-3.5 text-right align-middle whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @ Rp {{ number_format($item->unit_cost, 0, ',', '.') }} / {{ $item->good->unit ?? 'Unit' }}
                                    </div>
                                </td>

                                <!-- Aksi (Exact Match to Warehouse Action Button Style) -->
                                <td class="px-4 py-3.5 text-right align-middle whitespace-nowrap">
                                    <div class="flex justify-end">
                                        <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                            <!-- Tombol Detail (Warehouse style) -->
                                            <button type="button"
                                                class="view-detail-btn group flex h-full cursor-pointer items-center justify-center border-r border-yellow-700 bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:border-yellow-500 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                title="Detail Barang"
                                                data-id="{{ $item->good->id ?? '' }}"
                                                data-nama="{{ $item->good->goods_name ?? '' }}"
                                                data-kode="{{ $item->good->goods_code ?? '' }}"
                                                data-kategori="{{ $item->good->category ?? '' }}"
                                                data-status="{{ $item->good->status_listing ?? '' }}"
                                                data-stok="{{ $item->good->stock ?? 0 }}"
                                                data-satuan="{{ $item->good->unit ?? '' }}"
                                                data-lokasi="{{ $item->good->location ?? '-' }}"
                                                data-harga="{{ $item->good->selling_price ?? 0 }}"
                                                data-deskripsi="{{ $item->good->description ?? '' }}"
                                                data-gambar="{{ $item->good->image ?? '' }}">
                                                <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                            </button>

                                            <!-- Form Approve -->
                                            <form action="{{ route('supervisor.procurement-approval.approve', $item->id) }}" method="POST" class="inline-flex approve-form" data-confirm-text="{{ 'Apakah Anda yakin ingin menyetujui kedatangan ' . ($item->good->goods_name ?? 'Barang') . ' (' . $item->quantity . ' ' . ($item->good->unit ?? 'PCS') . ') untuk pengadaan ' . ($procurement->procurement_number ?? '') . '?' }}" data-confirm-button-text="Ya, Setujui">
                                                @csrf
                                                <button type="submit" 
                                                    class="group flex h-full cursor-pointer items-center justify-center border-r border-green-700 bg-green-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 dark:border-green-500 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                                    title="Setujui Kedatangan">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Setujui</span>
                                                </button>
                                            </form>

                                            <!-- Tombol Tolak (Open Modal) -->
                                            <button type="button" 
                                                onclick="openSpvRejectModal({{ $item->id }}, {{ json_encode($item->good->goods_name ?? 'Barang') }}, {{ json_encode($procurement->procurement_number ?? '') }})"
                                                class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                title="Tolak Kedatangan">
                                                <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Tolak</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm font-semibold">Tidak ada antrean kedatangan barang pengadaan yang menunggu persetujuan.</p>
                                        <p class="text-xs text-gray-400 mt-1">Semua kedatangan barang yang dicatat GA sudah diproses.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="border-t border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Reject Supervisor -->
    <dialog id="spvRejectModal" class="modal">
        <div class="modal-box w-full max-w-md overflow-hidden rounded-2xl bg-white p-0 shadow-2xl ring-1 ring-black/5 dark:bg-gray-800">
            {{-- Header --}}
            <div class="relative bg-gradient-to-r from-[#225A97] to-[#0D223A] px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-lg font-bold tracking-tight text-white">
                            Penolakan Kedatangan Barang
                        </h5>
                        <p class="mt-1 text-xs font-medium text-blue-100/80">Number: <span id="rejectProcNumber"></span></p>
                    </div>
                    <form method="dialog">
                        <button class="rounded-lg bg-white/10 p-2 text-white transition-colors hover:bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Body --}}
            <form id="spvRejectForm" method="POST" action="">
                @csrf
                <div class="px-7 py-6">
                    <div class="mb-5 rounded-lg border border-amber-100 bg-amber-50 p-3">
                        <div class="flex space-x-2">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-[11px] font-medium leading-relaxed text-amber-800">
                                Harap sertakan alasan yang jelas dan konstruktif untuk <strong id="rejectGoodsName" class="font-bold text-amber-900"></strong> agar tim dapat merevisi atau menindaklanjuti dengan tepat.
                            </p>
                        </div>
                    </div>

                    <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                        REJECTION REASON <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="rejectReason" name="reason" rows="4" required minlength="5" placeholder="Contoh: Harga beli tidak sesuai kesepakatan, vendor salah kirim spesifikasi..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm transition-all focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-rose-500/10 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-rose-500"></textarea>

                    <div id="rejectReasonError" class="mt-2 hidden items-center text-xs font-semibold text-rose-600">
                        <svg class="mr-1 h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Alasan penolakan minimal 5 karakter.
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 px-7 py-5 dark:bg-gray-900/50">
                    <button type="button" onclick="closeSpvRejectModal()" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-500 transition-all hover:bg-gray-100 hover:text-gray-700 active:scale-95 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="button" onclick="submitSpvRejectModal()" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-600/20 transition-all hover:bg-rose-700 hover:shadow-rose-600/30 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Confirm Reject
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Modal Detail Barang -->
    @include('admin.procurement-approval.partials.procurement-approval-modal-detail')

    <script>
        const spvRejectRouteTemplate = "{{ route('supervisor.procurement-approval.reject', ':id') }}";

        function openSpvRejectModal(receiptId, goodsName, procNumber) {
            const form = document.getElementById('spvRejectForm');
            form.action = spvRejectRouteTemplate.replace(':id', receiptId);
            document.getElementById('rejectGoodsName').textContent = goodsName;
            document.getElementById('rejectProcNumber').textContent = procNumber;
            document.getElementById('rejectReason').value = '';
            document.getElementById('rejectReasonError')?.classList.add('hidden');
            
            const modal = document.getElementById('spvRejectModal');
            if (modal) {
                modal.showModal();
                document.getElementById('rejectReason').focus();
            }
        }

        function closeSpvRejectModal() {
            const modal = document.getElementById('spvRejectModal');
            if (modal) modal.close();
        }

        function submitSpvRejectModal() {
            const reason = document.getElementById('rejectReason').value.trim();
            if (reason.length < 5) {
                document.getElementById('rejectReasonError')?.classList.remove('hidden');
                document.getElementById('rejectReason').focus();
                return;
            }
            document.getElementById('rejectReasonError')?.classList.add('hidden');
            document.getElementById('spvRejectForm').submit();
        }
    </script>
    @vite(['resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
</x-app-layout>
