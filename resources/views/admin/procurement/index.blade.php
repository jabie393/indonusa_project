<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        
        <!-- Header Section -->
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 shrink-0 p-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Procurement / Pengadaan Barang</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-300">Kelola pengadaan barang kustom dari Custom Quotation yang disetujui.</p>
            </div>
            <div>
                <a href="{{ route('goods-in.index') }}" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Goods In
                </a>
            </div>
        </div>

        <!-- Two Column Content Grid -->
        <div class="grow min-h-0 overflow-hidden mb-2">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">
                
                <!-- Left Column: Menunggu Pengadaan (4/12 width) -->
                <div class="lg:col-span-4 flex flex-col h-full rounded-2xl bg-white shadow-md dark:bg-gray-800 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#225A97] to-[#1e5087] p-4 text-white shrink-0">
                        <h3 class="text-md font-bold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Menunggu Pengadaan (Custom)
                        </h3>
                    </div>
                    <div class="grow overflow-y-auto p-4 space-y-4">
                        @if($pendingQuotations->isEmpty())
                            <div class="flex h-full flex-col items-center justify-center text-center py-12 px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Tidak ada barang kustom menunggu pengadaan.</p>
                            </div>
                        @else
                            @foreach($pendingQuotations as $pending)
                                <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30 hover:border-blue-300 dark:hover:border-blue-700 transition-all">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400">
                                                {{ $pending->quotation_number }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($pending->date)->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                                {{ $pending->to }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                                Sub: {{ $pending->subject }}
                                            </p>
                                        </div>
                                        <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                            <span class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                                {{ $pending->items->count() }} Item Kustom
                                            </span>
                                            <a href="{{ route('general-affair.procurement.create', $pending->id) }}" class="inline-flex items-center gap-1 rounded bg-[#0067B1] hover:bg-[#005a9b] px-3 py-1.5 text-xs font-semibold text-white transition-all">
                                                Proses Pengadaan
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Right Column: Daftar Pengadaan Aktif (8/12 width) -->
                <div class="lg:col-span-8 flex flex-col h-full rounded-2xl bg-white shadow-md dark:bg-gray-800 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 text-white shrink-0">
                        <h3 class="text-md font-bold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Daftar Pengadaan Aktif (Procurement)
                        </h3>
                    </div>
                    <div class="grow overflow-y-auto p-4 space-y-4">
                        @if($procurements->isEmpty())
                            <div class="flex h-full flex-col items-center justify-center text-center py-16 px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Belum ada pengadaan barang yang terdaftar.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($procurements as $procurement)
                                    <div class="rounded-xl border border-gray-200 p-4 transition-all hover:shadow-md dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('general-affair.procurement.show', $procurement->id) }}" class="text-base font-bold text-[#0067B1] hover:underline">
                                                        {{ $procurement->procurement_number }}
                                                    </a>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Asal CQ: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $procurement->customQuotation->quotation_number ?? '-' }}</span></p>
                                                <p class="text-[10px] text-gray-400 mt-1">Dibuat oleh: {{ $procurement->generalAffair->name }} pada {{ $procurement->created_at->format('Y-m-d H:i') }}</p>
                                                
                                                <div class="mt-4 flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-800 dark:bg-gray-800 dark:text-gray-300">
                                                        {{ $procurement->items->count() }} Jenis Barang
                                                    </span>
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
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $badgeClass }}">
                                                        {{ $statusLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                            <a href="{{ route('general-affair.procurement.show', $procurement->id) }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white transition-all whitespace-nowrap">
                                                Detail & Datang
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
