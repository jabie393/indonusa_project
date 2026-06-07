<!-- Header / Breadcrumbs -->
<header class="relative flex items-center justify-between px-7 py-5 text-white shrink-0"
    style="background-image: linear-gradient(135deg, #225A97 0%, #0D223A 100%)">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold leading-tight">Detail Procurement: {{ $procurement->procurement_number }}</h3>
            <p class="text-xs text-white/80">Dibuat oleh: {{ $procurement->generalAffair->name }} pada {{ $procurement->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        @if($procurement->status !== 'completed')
            <!-- Force Complete Button -->
            <form action="{{ route('general-affair.procurement.force-complete', $procurement->id) }}" method="POST">
                @csrf
                <button type="button" onclick="confirmForceComplete(() => this.closest('form').submit())" class="flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Force Complete
                </button>
            </form>
        @endif
        <button type="button" class="js-procurement-modal-close ml-2 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
            <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</header>

<!-- Scrollable Body -->
<div class="overflow-y-auto p-6 flex-1 space-y-6">
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold shadow-sm">
            <div class="flex items-start gap-2.5">
                <svg class="h-5 w-5 text-rose-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <span class="font-bold">Gagal memproses tindakan:</span>
                    <ul class="list-disc list-inside mt-1 space-y-1 font-normal">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Details Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <span class="block text-xs font-semibold uppercase text-slate-400">Asal Custom Quotation</span>
            <a href="{{ route('sales.custom-quotation.show', $procurement->custom_quotation_id) }}" target="_blank" class="text-sm font-bold text-[#0067B1] hover:underline mt-1 block">
                {{ $procurement->customQuotation->quotation_number ?? '-' }}
            </a>
        </div>
        <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <span class="block text-xs font-semibold uppercase text-slate-400">Status Procurement</span>
            @php
                $statusLabel = [
                    'pending' => 'Pending',
                    'partial_received' => 'Partial Received',
                    'completed' => 'Completed',
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
            <div>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold mt-2 {{ $badgeClass }}">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <span class="block text-xs font-semibold uppercase text-slate-400">Catatan (Notes)</span>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 block mt-1">{{ $procurement->notes ?: '-' }}</span>
        </div>
    </div>

    <!-- Form Record Arrival -->
    @if($procurement->status !== 'completed')
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
            <div class="bg-gray-100 dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-white font-bold text-sm">
                Form Pencatatan Kedatangan Barang
            </div>
            
            <form id="recordArrivalForm" action="{{ route('general-affair.procurement.record-arrival', $procurement->id) }}" method="POST" class="p-4 space-y-4">
                @csrf
                
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full border-collapse text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50 text-slate-700 dark:text-slate-200 text-xs font-bold uppercase">
                                <th class="px-4 py-3">Nama Barang</th>
                                <th class="px-4 py-3 text-center">Satuan</th>
                                <th class="px-4 py-3 text-center">Qty Dipesan</th>
                                <th class="px-4 py-3 text-center">Qty Diterima</th>
                                <th class="px-4 py-3 text-center w-36">Qty Datang</th>
                                <th class="px-4 py-3 text-right w-52">Harga Beli Final (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($procurement->items as $index => $item)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                    <input type="hidden" name="items[{{ $index }}][goods_id]" value="{{ $item->goods_id }}">
                                    <input type="hidden" name="items[{{ $index }}][procurement_item_id]" value="{{ $item->id }}">
                                    
                                    <!-- Nama Barang -->
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $item->goods->goods_name }}
                                        <span class="block text-xs font-mono text-gray-400 mt-1">{{ $item->goods->goods_code }}</span>
                                    </td>
                                    
                                    <!-- Satuan -->
                                    <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">
                                        {{ $item->unit }}
                                    </td>
                                    
                                    <!-- Qty Ordered -->
                                    <td class="px-4 py-3 text-center text-gray-900 dark:text-white font-semibold">
                                        {{ $item->qty_ordered }}
                                    </td>
                                    
                                    <!-- Qty Received -->
                                    <td class="px-4 py-3 text-center text-green-600 dark:text-green-400 font-semibold">
                                        {{ $item->qty_received }}
                                    </td>
                                    
                                    <!-- Qty Arriving input -->
                                    <td class="px-4 py-2">
                                        @php
                                            $alreadyPending = \App\Models\GoodsReceipt::where('procurement_of_goods_item_id', $item->id)
                                                ->where('status', 'pending')
                                                ->sum('quantity');
                                            $remaining = max(0, $item->qty_ordered - $item->qty_received);
                                            $remainingAllowed = max(0, $remaining - $alreadyPending);
                                        @endphp
                                        @if($remainingAllowed > 0)
                                            <input type="number" name="items[{{ $index }}][qty_arriving]" 
                                                value="0" min="0" max="{{ $remainingAllowed }}" required
                                                class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-center text-sm font-semibold focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            @if($alreadyPending > 0)
                                                <span class="block text-center text-[10px] text-amber-600 font-semibold mt-1">Pending: {{ $alreadyPending }}</span>
                                            @endif
                                        @elseif($remaining > 0 && $remainingAllowed <= 0)
                                            <span class="block text-center text-xs font-semibold text-amber-600 uppercase py-2">Pending ({{ $alreadyPending }})</span>
                                            <input type="hidden" name="items[{{ $index }}][qty_arriving]" value="0">
                                        @else
                                            <span class="block text-center text-xs font-semibold text-green-600 uppercase py-2">Selesai</span>
                                            <input type="hidden" name="items[{{ $index }}][qty_arriving]" value="0">
                                        @endif
                                    </td>
                                    
                                    <!-- Buy Price Final input -->
                                    <td class="px-4 py-2">
                                        @if($remainingAllowed > 0)
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
                
                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-[#225A97] px-6 py-2 text-sm font-bold text-white hover:bg-blue-800 transition-all">
                        Simpan Penerimaan Kedatangan
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- List of Arrival Batches (GoodsReceipts) -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="bg-gray-100 dark:bg-gray-700 px-4 py-3 text-gray-800 dark:text-white font-bold text-sm">
            Riwayat Kedatangan &amp; Status Approval
        </div>
        
        <div class="p-4">
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
                <div class="flex flex-col items-center justify-center text-center p-6">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada riwayat kedatangan barang untuk procurement ini.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full border-collapse text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50 text-slate-700 dark:text-slate-200 text-xs font-bold uppercase">
                                <th class="px-4 py-3">Tanggal Pencatatan</th>
                                <th class="px-4 py-3">Nama Barang</th>
                                <th class="px-4 py-3 text-center">Qty Datang</th>
                                <th class="px-4 py-3 text-right">Harga Beli Final (Rp)</th>
                                <th class="px-4 py-3 text-center">Status Approval</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($allReceipts as $receipt)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                    <!-- Tanggal -->
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ $receipt->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    
                                    <!-- Barang -->
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $receipt->goods_name }}
                                        <span class="block text-xs font-mono text-gray-400 mt-0.5">{{ $receipt->goods_code }}</span>
                                        @if($receipt->status === 'rejected')
                                            <div class="mt-2 text-xs text-red-600 dark:text-red-400 font-normal">
                                                <strong>Rejection Reason:</strong> {{ $receipt->reject_reason }}
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Qty -->
                                    <td class="px-4 py-3 text-center text-gray-900 dark:text-white font-semibold">
                                        {{ $receipt->quantity }}
                                    </td>
                                    
                                    <!-- Harga -->
                                    <td class="px-4 py-3 text-right text-gray-900 dark:text-white font-semibold">
                                        Rp {{ number_format($receipt->unit_cost, 0, '.', ',') }}
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="px-4 py-3 text-center">
                                        @if($receipt->status === 'approved')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-bold text-green-800 dark:bg-green-950/30 dark:text-green-300">
                                                Approved
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
                                                Rejected
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
                                                Pending
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

<footer class="flex items-center justify-end border-t border-gray-200 px-7 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 shrink-0 animate-fade-in">
    <button type="button" class="js-procurement-modal-close px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700">
        Tutup
    </button>
</footer>
