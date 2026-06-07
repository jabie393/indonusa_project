<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 p-6">
        
        <!-- Header / Breadcrumbs -->
        <div class="flex flex-col justify-between gap-4 border-b border-gray-200 pb-4 dark:border-gray-700 md:flex-row md:items-center">
            <div>
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Procurement Details</span>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $procurement->procurement_number }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Dibuat oleh: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $procurement->generalAffair->name }}</span> pada {{ $procurement->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('general-affair.procurement.index') }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Daftar Procurement
                </a>
                
                @if($procurement->status !== 'completed')
                    <!-- Force Complete Button -->
                    <form action="{{ route('general-affair.procurement.force-complete', $procurement->id) }}" method="POST">
                        @csrf
                        <button type="button" onclick="confirmForceComplete(() => this.closest('form').submit())" class="flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Force Complete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                <span class="block text-xs font-semibold uppercase text-slate-400">Asal Custom Quotation</span>
                <a href="{{ route('sales.custom-quotation.show', $procurement->custom_quotation_id) }}" class="text-base font-bold text-[#0067B1] hover:underline mt-1 block">
                    {{ $procurement->customQuotation->quotation_number ?? '-' }}
                </a>
            </div>
            <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                <span class="block text-xs font-semibold uppercase text-slate-400">Status Procurement</span>
                @php
                    $statusLabel = [
                        'pending' => 'Pending',
                        'partial_received' => 'Parsial Diterima',
                        'completed' => 'Selesai',
                    ][$procurement->status] ?? $procurement->status;

                    $badgeClass = 'bg-gray-100 text-gray-800';
                    if ($procurement->status === 'completed') {
                        $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-950/30 dark:text-green-300';
                    } elseif ($procurement->status === 'partial_received') {
                        $badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-950/30 dark:text-blue-300';
                    } else {
                        $badgeClass = 'bg-amber-100 text-amber-800 dark:bg-amber-950/30 dark:text-amber-300';
                    }
                @endphp
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold mt-2 {{ $badgeClass }}">
                    {{ $statusLabel }}
                </span>
            </div>
            <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                <span class="block text-xs font-semibold uppercase text-slate-400">Catatan (Notes)</span>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 block mt-1">{{ $procurement->notes ?: '-' }}</span>
            </div>
        </div>

        <!-- Form Record Arrival -->
        @if($procurement->status !== 'completed')
            <div class="mt-8 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                <div class="bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 text-white">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Form Pencatatan Kedatangan Barang
                    </h3>
                </div>
                
                <form id="recordArrivalForm" action="{{ route('general-affair.procurement.record-arrival', $procurement->id) }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-slate-700 dark:text-slate-200">
                                    <th class="border border-gray-300 px-4 py-3 text-left text-xs uppercase font-bold tracking-wider dark:border-gray-600">Nama Barang</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Satuan</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Qty Dipesan</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Qty Diterima</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Qty Datang Hari Ini</th>
                                    <th class="border border-gray-300 px-4 py-3 text-right text-xs uppercase font-bold tracking-wider dark:border-gray-600">Harga Beli Final (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($procurement->items as $index => $item)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                        <input type="hidden" name="items[{{ $index }}][goods_id]" value="{{ $item->goods_id }}">
                                        <input type="hidden" name="items[{{ $index }}][procurement_item_id]" value="{{ $item->id }}">
                                        
                                        <!-- Nama Barang -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900 dark:text-white dark:border-gray-600 font-medium">
                                            {{ $item->goods->goods_name }}
                                            <span class="block text-xs font-mono text-gray-400 mt-1">{{ $item->goods->goods_code }}</span>
                                        </td>
                                        
                                        <!-- Satuan -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300 dark:border-gray-600">
                                            {{ $item->unit }}
                                        </td>
                                        
                                        <!-- Qty Ordered -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-center text-gray-900 dark:text-white dark:border-gray-600 font-semibold">
                                            {{ $item->qty_ordered }}
                                        </td>
                                        
                                        <!-- Qty Received -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-center text-green-600 dark:text-green-400 dark:border-gray-600 font-semibold">
                                            {{ $item->qty_received }}
                                        </td>
                                        
                                        <!-- Qty Arriving input -->
                                        <td class="border border-gray-300 px-4 py-3 dark:border-gray-600" style="width: 160px;">
                                            @php
                                                $remaining = max(0, $item->qty_ordered - $item->qty_received);
                                            @endphp
                                            @if($remaining > 0)
                                                <input type="number" name="items[{{ $index }}][qty_arriving]" 
                                                    value="0" min="0" max="{{ $remaining }}" required
                                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-center text-sm font-semibold focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            @else
                                                <span class="block text-center text-xs font-semibold text-green-600 uppercase py-2">Selesai</span>
                                                <input type="hidden" name="items[{{ $index }}][qty_arriving]" value="0">
                                            @endif
                                        </td>
                                        
                                        <!-- Buy Price Final input -->
                                        <td class="border border-gray-300 px-4 py-3 dark:border-gray-600" style="width: 220px;">
                                            @if($remaining > 0)
                                                <div class="relative flex items-center">
                                                    <span class="absolute left-3 text-sm text-gray-500 dark:text-gray-400 font-semibold">Rp</span>
                                                    <input type="text" name="items[{{ $index }}][buy_price]" 
                                                        value="{{ old('items.'.$index.'.buy_price', $item->buy_price) }}"
                                                        required placeholder="0"
                                                        class="buy-price-input w-full rounded-lg border border-gray-300 bg-gray-50 p-2 pl-9 text-right text-sm font-semibold focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>
                                            @else
                                                <div class="text-right text-sm font-semibold text-gray-500 py-2">
                                                    Rp {{ number_format($item->buy_price, 0, '.', ',') }}
                                                    <input type="hidden" name="items[{{ $index }}][buy_price]" value="{{ $item->buy_price }}">
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="rounded-lg bg-[#225A97] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#19426d] transition-all">
                            Simpan Penerimaan Kedatangan
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- List of Arrival Batches (GoodsReceipts) -->
        <div class="mt-8 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
            <div class="bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 text-white">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Kedatangan &amp; Status Approval
                </h3>
            </div>
            
            <div class="p-6">
                @php
                    $allReceipts = collect();
                    foreach($procurement->items as $item) {
                        foreach($item->goodsReceipts as $receipt) {
                            $receipt->goods_name = $item->goods->goods_name;
                            $receipt->goods_code = $item->goods->goods_code;
                            $allReceipts->push($receipt);
                        }
                    }
                    $allReceipts = $allReceipts->sortByDesc('created_at');
                @endphp
                
                @if($allReceipts->isEmpty())
                    <div class="flex flex-col items-center justify-center text-center p-8">
                        <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat kedatangan barang untuk procurement ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-slate-700 dark:text-slate-200">
                                    <th class="border border-gray-300 px-4 py-3 text-left text-xs uppercase font-bold tracking-wider dark:border-gray-600">Tanggal Pencatatan</th>
                                    <th class="border border-gray-300 px-4 py-3 text-left text-xs uppercase font-bold tracking-wider dark:border-gray-600">Nama Barang</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Qty Datang</th>
                                    <th class="border border-gray-300 px-4 py-3 text-right text-xs uppercase font-bold tracking-wider dark:border-gray-600">Harga Beli Final (Rp)</th>
                                    <th class="border border-gray-300 px-4 py-3 text-center text-xs uppercase font-bold tracking-wider dark:border-gray-600">Status Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allReceipts as $receipt)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                        <!-- Tanggal -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-600 dark:text-gray-300 dark:border-gray-600">
                                            {{ $receipt->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        
                                        <!-- Barang -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900 dark:text-white dark:border-gray-600 font-medium">
                                            {{ $receipt->goods_name }}
                                            <span class="block text-xs font-mono text-gray-400 mt-0.5">{{ $receipt->goods_code }}</span>
                                            @if($receipt->status === 'rejected')
                                                <div class="mt-2 text-xs text-red-600 dark:text-red-400 font-normal">
                                                    <strong>Alasan Penolakan:</strong> {{ $receipt->reject_reason }}
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <!-- Qty -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-center text-gray-900 dark:text-white dark:border-gray-600 font-semibold">
                                            {{ $receipt->quantity }}
                                        </td>
                                        
                                        <!-- Harga -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-right text-gray-900 dark:text-white dark:border-gray-600 font-semibold">
                                            Rp {{ number_format($receipt->unit_cost, 0, '.', ',') }}
                                        </td>
                                        
                                        <!-- Status -->
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-center dark:border-gray-600">
                                            @if($receipt->status === 'approved')
                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-bold text-green-800 dark:bg-green-950/30 dark:text-green-300">
                                                    Disetujui oleh {{ $receipt->approver->name ?? 'Warehouse' }}
                                                </span>
                                            @elseif($receipt->status === 'rejected')
                                                @php
                                                    $procItem = $receipt->procurementOfGoodsItem;
                                                    $otherPending = 0;
                                                    if ($procItem) {
                                                        $otherPending = \App\Models\GoodsReceipt::where('procurement_of_goods_item_id', $procItem->id)
                                                            ->where('id', '!=', $receipt->id)
                                                            ->where('status', 'pending')
                                                            ->sum('quantity');
                                                    }
                                                    $maxAllowed = $procItem ? max(0, $procItem->qty_ordered - $procItem->qty_received - $otherPending) : 999999;
                                                @endphp
                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-800 dark:bg-red-950/30 dark:text-red-300">
                                                    Ditolak
                                                </span>
                                                <div class="mt-2">
                                                    <button type="button" 
                                                        onclick="openRevisionModal({{ $receipt->id }}, {{ $receipt->quantity }}, {{ (float)$receipt->unit_cost }}, '{{ addslashes($receipt->goods_name) }}', {{ $maxAllowed }})"
                                                        class="inline-flex items-center gap-1 rounded bg-blue-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-blue-700 transition-all">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Revisi
                                                    </button>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800 dark:bg-amber-950/30 dark:text-amber-300">
                                                    Menunggu Approval
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Revision Modal -->
    <dialog id="modalRevisiReceipt" class="modal">
        <div class="modal-box w-full max-w-md overflow-hidden rounded-2xl bg-white p-0 shadow-2xl ring-1 ring-black/5 dark:bg-gray-800">
            {{-- Header --}}
            <header class="relative flex items-center justify-between px-7 py-5 text-white"
                style="background-image: linear-gradient(135deg, #225A97 0%, #0D223A 100%)">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold leading-tight">Revisi Kedatangan Barang</h3>
                        <p class="text-xs text-white/80"><span id="revisionGoodsName">&nbsp;</span></p>
                    </div>
                </div>
                <form method="dialog">
                    <button type="button" onclick="closeRevisionModal()"
                        class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </form>
            </header>

            {{-- Body --}}
            <form id="formRevisiReceipt" method="POST" action="">
                @csrf
                <div class="px-7 py-6">
                    <!-- Qty Input -->
                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                            Kuantitas Datang <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="revisionQuantity" name="quantity" required min="1"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 dark:border-gray-600 dark:bg-gray-700 dark:focus:border-blue-500">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Maksimal kuantitas yang diperbolehkan: <span id="revisionQuantityMaxLabel" class="font-bold text-[#225A97] dark:text-[#2798e6]">0</span>
                        </p>
                    </div>

                    <!-- Price Input -->
                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                            Harga Beli Final (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-sm text-gray-500 dark:text-gray-400 font-semibold">Rp</span>
                            <input type="text" id="revisionUnitCost" name="unit_cost" required
                                class="buy-price-input w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pl-9 text-sm transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 dark:border-gray-600 dark:bg-gray-700 dark:focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 px-7 py-5 dark:bg-gray-900/50">
                    <button type="button" onclick="closeRevisionModal()"
                        class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#225A97] px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-700/20 transition-all hover:bg-blue-800 hover:shadow-blue-800/30 active:scale-95">
                        Simpan Revisi
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button onclick="closeRevisionModal()">close</button>
        </form>
    </dialog>

    <!-- JS for formatting price input -->
    <script>
        function openRevisionModal(receiptId, qty, unitCost, goodsName, maxAllowed) {
            const modal = document.getElementById('modalRevisiReceipt');
            if (!modal) return;

            document.getElementById('formRevisiReceipt').action = '/procurement/receipt/' + receiptId + '/update';
            document.getElementById('revisionGoodsName').textContent = goodsName;
            
            const qtyInput = document.getElementById('revisionQuantity');
            qtyInput.value = qty;
            qtyInput.max = maxAllowed;
            document.getElementById('revisionQuantityMaxLabel').textContent = maxAllowed;
            
            const costInput = document.getElementById('revisionUnitCost');
            costInput.value = unitCost;
            
            // Format input using standard event
            const event = new Event('input', { bubbles: true });
            costInput.dispatchEvent(event);

            if (typeof modal.showModal === "function") {
                modal.showModal();
            } else {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeRevisionModal() {
            const modal = document.getElementById('modalRevisiReceipt');
            if (!modal) return;

            if (typeof modal.close === "function") {
                modal.close();
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

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

            const arrivalForm = document.getElementById('recordArrivalForm');
            if (arrivalForm) {
                arrivalForm.addEventListener('submit', function(e) {
                    document.querySelectorAll('.buy-price-input').forEach(input => {
                        input.value = input.value.replace(/,/g, '');
                    });
                });
            }

            const revisionForm = document.getElementById('formRevisiReceipt');
            if (revisionForm) {
                revisionForm.addEventListener('submit', function(e) {
                    const costInput = document.getElementById('revisionUnitCost');
                    costInput.value = costInput.value.replace(/,/g, '');
                });
            }
        });
    </script>
</x-app-layout>
