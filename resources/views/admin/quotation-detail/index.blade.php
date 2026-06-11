<x-app-layout>
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-6">
            @if (($requestOrder->customer->status ?? 'active') === 'inactive')
                <div class="mb-6 flex items-center justify-between rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-900 dark:text-red-300"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="font-bold uppercase tracking-widest">Customer Non Aktif:</span>
                        <span class="ml-2">Semua tindakan untuk order ini dinonaktifkan sementara.</span>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert mb-6 flex items-center justify-between rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900 dark:text-green-300"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button"
                        class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-green-100 p-1.5 text-green-500 hover:bg-green-200 focus:ring-2 focus:ring-green-400 dark:bg-green-800 dark:text-green-400 dark:hover:bg-green-700"
                        data-bs-dismiss="alert" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert mb-6 rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-900 dark:text-red-300" role="alert">
                    <div class="mb-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="font-medium">Terdapat kesalahan:</span>
                    </div>
                    <ul class="ml-4 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="mt-2 text-sm underline hover:no-underline" data-bs-dismiss="alert"
                        aria-label="Close">Tutup</button>
                </div>
            @endif

            @php
                $orderStatus = $requestOrder->order?->status;

                $bannerKey = $orderStatus;
                if (in_array($orderStatus, ['pending_approval', 'sent'])) {
                    $bannerKey = 'sent_to_supervisor';
                } elseif ($orderStatus === 'open' && !empty($requestOrder->order?->supervisor_id)) {
                    $bannerKey = 'approved_supervisor';
                }

                $bannerConfig = [
                    'rejected_supervisor' => [
                        'border' => 'border-rose-200 dark:border-rose-900/30',
                        'bg' => 'bg-rose-50/50 dark:bg-rose-950/20',
                        'icon_bg' => 'bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
                        'title' => 'Quotation Ditolak Supervisor',
                        'title_text' => 'text-rose-800 dark:text-rose-300',
                        'desc_text' => 'text-rose-700/80 dark:text-rose-400/80',
                    ],
                    'sent_to_supervisor' => [
                        'border' => 'border-amber-200 dark:border-amber-900/30',
                        'bg' => 'bg-[#FFFDF5] dark:bg-amber-950/20',
                        'icon_bg' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
                        'title' => 'Dalam Peninjauan Supervisor',
                        'title_text' => 'text-amber-800 dark:text-amber-300',
                        'desc_text' => 'text-amber-700/80 dark:text-amber-400/80',
                    ],
                    'approved_supervisor' => [
                        'border' => 'border-emerald-200 dark:border-emerald-900/30',
                        'bg' => 'bg-emerald-50/50 dark:bg-emerald-950/20',
                        'icon_bg' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        'title' => 'Verifikasi Disetujui',
                        'title_text' => 'text-emerald-800 dark:text-emerald-300',
                        'desc_text' => 'text-emerald-700/80 dark:text-emerald-400/80',
                    ],
                ];

                $activeConfig = $bannerConfig[$bannerKey] ?? null;
            @endphp

            @if ($activeConfig)
                <div class="mb-6 flex flex-col gap-4 rounded-2xl border {{ $activeConfig['border'] }} {{ $activeConfig['bg'] }} p-5 shadow-sm md:flex-row md:items-center">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $activeConfig['icon_bg'] }}">
                        {!! $activeConfig['icon'] !!}
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm font-bold uppercase tracking-wider {{ $activeConfig['title_text'] }}">{{ $activeConfig['title'] }}</h4>
                        
                        @if ($bannerKey === 'rejected_supervisor')
                            <div class="mt-1 space-y-1">
                                <p class="text-xs {{ $activeConfig['desc_text'] }}">
                                    <span class="mr-1 text-[10px] font-black uppercase opacity-60">Alasan:</span>
                                    {{ $requestOrder->reason ?? ($requestOrder->order?->reason ?? 'Keterangan tidak tersedia') }}
                                </p>
                                @if ($requestOrder->order?->supervisor)
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-rose-500/80">
                                        Oleh {{ $requestOrder->order->supervisor->name }}
                                        @if ($requestOrder->order->approved_at)
                                            &middot;
                                            {{ \Carbon\Carbon::parse($requestOrder->order->approved_at)->translatedFormat('d M Y, H:i') }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @elseif ($bannerKey === 'sent_to_supervisor')
                            <p class="mt-1 text-xs {{ $activeConfig['desc_text'] }}">
                                Quotation mengandung diskon besar (&gt;20%). Dokumen PDF akan terkunci hingga mendapatkan persetujuan.
                            </p>
                        @elseif ($bannerKey === 'approved_supervisor')
                            <p class="mt-1 text-xs {{ $activeConfig['desc_text'] }}">
                                Supervisor telah memberikan persetujuan. Dokumen PDF kini dapat diunduh dan diproses lebih lanjut.
                            </p>
                        @endif
                    </div>
                    
                    @if ($bannerKey === 'rejected_supervisor' && Auth::user()->role === 'Sales')
                        <div class="shrink-0 mt-3 md:mt-0">
                            <a href="{{ route('sales.quotation.edit', $requestOrder->id) }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-rose-200 transition-all hover:bg-rose-700 hover:shadow-none dark:shadow-none active:scale-[0.98]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Perbaiki & Ajukan Ulang
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if ($orderStatus === 'not_completed')
                {{-- BANNER ORANGE: Partial Delivery --}}
                <div
                    class="mb-6 overflow-hidden rounded-2xl border border-orange-200 bg-orange-50/50 shadow-sm dark:border-orange-900/30 dark:bg-orange-950/20">
                    <div class="flex items-start gap-4 p-5">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-orange-800 dark:text-orange-300">
                                Partial Delivery &mdash; Pengiriman Bertahap</h4>
                            <p class="mt-1 text-xs text-orange-700/80 dark:text-orange-400/80">
                                Sebagian barang sudah dikirim oleh warehouse. Sisa barang akan dikirim pada pengiriman
                                berikutnya.
                            </p>
                        </div>
                    </div>

                    {{-- Detail barang --}}
                    @php
                        $orderItems = $requestOrder->order->items ?? collect();
                        $sudahDikirim = $orderItems->filter(fn($i) => ($i->delivered_quantity ?? 0) > 0);
                        $belumDikirim = $orderItems->filter(fn($i) => ($i->delivered_quantity ?? 0) < $i->quantity && ($i->status_item ?? '') !== 'delivered');
                    @endphp

                    @if ($orderItems->count() > 0)
                        <div class="grid grid-cols-1 gap-0 border-t border-orange-100 dark:border-orange-900/20 md:grid-cols-2">
                            {{-- Kolom Sudah Dikirim --}}
                            <div class="border-b border-orange-100 p-5 dark:border-orange-900/20 md:border-b-0 md:border-r">
                                <h5
                                    class="mb-3 flex items-center gap-2 text-[10px] font-black uppercase tracking-wider text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Sudah Dikirim ({{ $sudahDikirim->count() }} item)
                                </h5>
                                @forelse ($sudahDikirim as $item)
                                    <div
                                        class="mb-2 flex items-center justify-between rounded-xl bg-green-50/50 px-4 py-3 dark:bg-green-950/20">
                                        <div>
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200">
                                                {{ $item->barang->goods_name ?? '-' }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $item->barang->goods_code ?? '' }}</p>
                                        </div>
                                        <div class="ml-3 shrink-0 text-right">
                                            <span
                                                class="text-xs font-bold text-green-600">{{ $item->delivered_quantity }}/{{ $item->quantity }}</span>
                                            <p class="text-[10px] text-gray-400">{{ $item->barang->unit ?? '' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs italic text-gray-400">Belum ada barang yang dikirim.</p>
                                @endforelse
                            </div>

                            {{-- Kolom Belum/Sisa Dikirim --}}
                            <div class="p-5">
                                <h5
                                    class="mb-3 flex items-center gap-2 text-[10px] font-black uppercase tracking-wider text-orange-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Menunggu Pengiriman ({{ $belumDikirim->count() }} item)
                                </h5>
                                @forelse ($belumDikirim as $item)
                                    @php $sisa = $item->quantity - ($item->delivered_quantity ?? 0); @endphp
                                    <div
                                        class="mb-2 flex items-center justify-between rounded-xl bg-orange-50/50 px-4 py-3 dark:bg-orange-950/20">
                                        <div>
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200">
                                                {{ $item->barang->goods_name ?? '-' }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $item->barang->goods_code ?? '' }}</p>
                                        </div>
                                        <div class="ml-3 shrink-0 text-right">
                                            <span class="text-xs font-bold text-orange-600">Sisa {{ $sisa }}</span>
                                            <p class="text-[10px] text-gray-400">{{ $item->barang->unit ?? '' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs italic text-gray-400">Semua barang sudah dikirim.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            {{-- ============================================================
            END BANNER STATUS SUPERVISOR
            ============================================================ --}}

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
                <!-- Left Column: Informasi Quotation -->
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="space-y-1">
                            <span
                                class="text-[11px] font-extrabold uppercase tracking-wider text-[#225A97] dark:text-[#818CF8]">Informasi
                                Quotation</span>
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-3xl font-black tracking-tight text-slate-800 dark:text-white">
                                    {{ $requestOrder->quotation_number ?? '-' }}</h1>
                                <span
                                    class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    {{ $requestOrder->request_number }}
                                </span>
                            </div>
                        </div>

                        <!-- Status Badge Below Title -->
                        @php
                            $statusText = $requestOrder->status;
                            $statusClass = 'bg-blue-50/50 text-blue-600 border border-blue-200';
                            $statusDot = 'bg-blue-500';

                            if (in_array($statusText, ['Waiting for Supervisor Approval', 'Sent to Supervisor'])) {
                                $statusClass = 'bg-[#FFFBEB] text-[#D97706] border border-[#FCD34D]';
                                $statusDot = 'bg-[#D97706]';
                            } elseif (in_array($statusText, ['Rejected by Supervisor', 'Rejected by Warehouse'])) {
                                $statusClass = 'bg-rose-50/50 text-rose-600 border border-rose-200';
                                $statusDot = 'bg-rose-500';
                            } elseif (in_array($statusText, ['Completed', 'Approved', 'Open'])) {
                                $statusClass = 'bg-emerald-50/50 text-emerald-600 border border-emerald-200';
                                $statusDot = 'bg-emerald-500';
                            } elseif ($statusText === 'Partial Delivery') {
                                $statusClass = 'bg-orange-50/50 text-orange-600 border border-orange-200';
                                $statusDot = 'bg-orange-500';
                            }
                        @endphp
                        <div class="mt-4">
                            <span
                                class="inline-flex items-center gap-2 rounded-full px-4 py-1 text-xs font-bold uppercase tracking-wider {{ $statusClass }}">
                                <span class="h-2 w-2 rounded-full {{ $statusDot }}"></span>
                                {{ $statusText }}
                            </span>
                        </div>

                        <!-- Metadata Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-y-6 gap-x-8 mt-10">
                            <!-- NAMA CUSTOMER -->
                            <div>
                                <span
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Nama
                                    Customer</span>
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">
                                    {{ $requestOrder->customer_name }}</p>
                            </div>

                            <!-- PIC (CUSTOMER) -->
                            <div>
                                <span
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">PIC
                                    (Customer)</span>
                                <div class="mt-1 flex items-center space-x-2">
                                    <div
                                        class="flex h-7 w-7 items-center justify-center text-slate-500 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-gray-200">
                                        {{ $requestOrder->pic->name ?? $requestOrder->sales->name ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- NO. PO -->
                            <div>
                                <span
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">No.
                                    PO</span>
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">
                                    {{ $requestOrder->no_po ?? '-' }}</p>
                            </div>

                            <!-- SUBJECT / KETERANGAN -->
                            <div>
                                <span
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Subject
                                    / Keterangan</span>
                                <p class="mt-1 text-sm font-semibold italic text-slate-800 dark:text-gray-200">
                                    "{{ $requestOrder->subject ?? '-' }}"</p>
                            </div>

                            <!-- KATEGORI -->
                            <div>
                                <span
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Kategori</span>
                                @php
                                    $firstItem = $requestOrder->items->first();
                                    $kategori = $firstItem->kategori_barang ?? ($firstItem->kategori ?? ($firstItem->barang->kategori ?? '-'));
                                @endphp
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">{{ $kategori }}
                                </p>
                            </div>

                            <!-- MASA BERLAKU -->
                            <div>
                                <span
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Masa
                                    Berlaku</span>
                                <div class="mt-1">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-gray-200">
                                        {{ $requestOrder->valid_date_formatted ?? '-' }}</p>
                                    @php
                                        $expiryDate = $requestOrder->expired_at ?? $requestOrder->valid_date;
                                    @endphp
                                    @if ($expiryDate)
                                        @php
                                            $isExpired = now() > $expiryDate;
                                            $daysLeft = now()->diffInDays($expiryDate, false);
                                        @endphp
                                        @if ($isExpired)
                                            <span
                                                class="mt-1 inline-block rounded bg-rose-50 border border-rose-100 px-2 py-0.5 text-[10px] font-bold text-rose-600 dark:bg-rose-950/20 dark:text-rose-400">Expired</span>
                                        @else
                                            <span
                                                class="mt-1 inline-block rounded bg-emerald-50 border border-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400">
                                                {{ floor($daysLeft) }} Hari Lagi
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- TGL QUOTATION -->
                            <div>
                                <span
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Tgl
                                    Quotation</span>
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">
                                    {{ $requestOrder->created_at->format('d M Y') }}</p>
                            </div>

                            <!-- TGL KEBUTUHAN -->
                            <div>
                                <span
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Tgl
                                    Kebutuhan</span>
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">
                                    {{ $requestOrder->required_date_formatted }}</p>
                            </div>
                        </div>

                        <!-- Catatan Customer -->
                        @if ($requestOrder->customer_notes)
                            <div
                                class="mt-8 p-5 bg-indigo-50/20 border border-indigo-100/50 rounded-2xl dark:bg-indigo-950/10 dark:border-indigo-950/30">
                                <label
                                    class="flex items-center text-[10px] font-bold uppercase tracking-wider text-[#225A97] dark:text-[#818CF8]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    Catatan Customer
                                </label>
                                <div class="mt-2 text-xs leading-relaxed italic text-slate-600 dark:text-slate-400">
                                    "{{ $requestOrder->customer_notes }}"
                                </div>
                            </div>
                        @endif

                        <!-- Catatan Penolakan Supervisor -->
                        @if (!empty($requestOrder->reason) || !empty($requestOrder->order?->reason))
                            <div
                                class="mt-4 p-5 bg-rose-50/30 border border-rose-100 rounded-2xl dark:bg-rose-950/10 dark:border-rose-950/30">
                                <label
                                    class="flex items-center text-[10px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Catatan Penolakan Supervisor
                                </label>
                                <div class="mt-2 text-xs leading-relaxed text-rose-700 dark:text-rose-300">
                                    {{ $requestOrder->reason ?? $requestOrder->order?->reason }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Supporting Images -->
                    @if ($requestOrder->supporting_images && count($requestOrder->supporting_images) > 0)
                        <div
                            class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
                            <h2
                                class="flex items-center text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Gambar Pendukung
                            </h2>
                            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach ($requestOrder->supporting_images as $image)
                                    <div
                                        class="group relative aspect-square overflow-hidden rounded-xl border border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/50">
                                        <button type="button" class="custom-quotation-thumb block h-full w-full"
                                            data-full="{{ asset('storage/' . $image) }}">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                                alt="Supporting image">
                                            <div
                                                class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                                <span
                                                    class="truncate text-[10px] font-medium text-white">{{ basename($image) }}</span>
                                            </div>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Sidebar (Ringkasan Order & Action Buttons) -->
                <div class="lg:col-span-1 space-y-6">
                    @php
                        $subtotal = $requestOrder->subtotal ?? $requestOrder->items->sum('subtotal');
                        $totalPPN = $requestOrder->tax ?? 0;
                        $grandTotal = $requestOrder->grand_total ?? round($subtotal + $totalPPN, 2);
                    @endphp

                    <!-- Ringkasan Order Card -->
                    <div
                        class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
                        <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-800 dark:text-white">
                            Ringkasan Order</h2>

                        <div class="space-y-4 mt-6">
                            <!-- Subtotal -->
                            <div class="flex items-center justify-between text-xs">
                                <span
                                    class="font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Sub-Total
                                    Barang</span>
                                <span class="font-bold text-slate-800 dark:text-white">
                                    <span
                                        class="mr-0.5 text-[10px] text-gray-400 font-medium">Rp</span>{{ number_format($subtotal, 0, '.', ',') }}
                                </span>
                            </div>

                            <!-- Pajak / PPN -->
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center space-x-1">
                                    <span
                                        class="font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pajak
                                        / PPN</span>
                                    <span
                                        class="rounded bg-slate-100 dark:bg-gray-700 px-1.5 py-0.5 text-[10px] font-bold text-slate-500 dark:text-gray-400">
                                        {{ $requestOrder->tax > 0 ? '11%' : '0%' }}
                                    </span>
                                </div>
                                <span class="font-bold text-slate-800 dark:text-white">
                                    <span
                                        class="mr-0.5 text-[10px] text-gray-400 font-medium">Rp</span>{{ number_format($totalPPN, 0, '.', ',') }}
                                </span>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-slate-100 dark:border-gray-700 my-4"></div>

                            <!-- Grand Total -->
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Total
                                    Bayar</span>
                                <div class="text-right">
                                    <span
                                        class="block text-[9px] font-extrabold text-[#225A97] dark:text-[#818CF8] uppercase tracking-wider">IDR</span>
                                    <span
                                        class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($grandTotal, 0, '.', ',') }}</span>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-slate-100 dark:border-gray-700 my-4"></div>

                            <!-- Dibuat -->
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-semibold uppercase tracking-wider text-gray-400">Dibuat</span>
                                <span
                                    class="font-bold text-gray-600 dark:text-gray-400">{{ $requestOrder->created_at->format('d M Y, H:i') }}</span>
                            </div>

                            <!-- Diperbarui -->
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-semibold uppercase tracking-wider text-gray-400">Diperbarui</span>
                                <span
                                    class="font-bold text-gray-600 dark:text-gray-400">{{ $requestOrder->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        {{-- Approval Actions for Supervisor --}}
                        @if ($orderStatus === 'sent_to_supervisor' && Auth::user()->role === 'Supervisor')
                            <div
                                class="p-5 bg-amber-50/50 border border-amber-200 rounded-3xl dark:bg-amber-950/10 dark:border-amber-900/30 space-y-3">
                                <h3 class="text-[10px] font-black uppercase tracking-wider text-amber-700">Persetujuan
                                    Supervisor</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <form action="{{ route('supervisor.quotation.approve', $requestOrder->id) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center justify-center space-x-2 rounded-2xl bg-emerald-600 py-3 text-xs font-bold text-white shadow-lg shadow-emerald-100/50 hover:bg-emerald-700 transition-all active:scale-[0.98] dark:shadow-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Approve</span>
                                        </button>
                                    </form>
                                    <button
                                        onclick="openTolakModal('request_order', '{{ $requestOrder->id }}', '{{ $requestOrder->request_number }}')"
                                        title="Tolak"
                                        class="flex w-full items-center justify-center space-x-2 rounded-2xl bg-rose-600 py-3 text-xs font-bold text-white shadow-lg shadow-rose-100/50 hover:bg-rose-700 transition-all active:scale-[0.98] dark:shadow-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span>Reject</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if (($requestOrder->customer->status ?? 'active') === 'active')
                            {{-- Edit Button (Sales Only) --}}
                            @if (Auth::user()->role === 'Sales')
                                <a href="{{ route('sales.quotation.edit', $requestOrder->id) }}"
                                    class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#225A97] py-3 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-blue-100/30 hover:bg-[#1a4675] transition-all active:scale-[0.98] dark:shadow-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>Edit Quotation</span>
                                </a>
                            @endif

                            {{-- PDF Button (Enabled / Disabled) --}}
                            @php
                                $canDownloadPdf = method_exists($requestOrder, 'canDownloadPdf') ? $requestOrder->canDownloadPdf() : true;
                                $pdfRoute = 'sales.quotation.pdf';
                            @endphp

                            @if ($canDownloadPdf && Auth::user()->role !== 'Supervisor')
                                <a href="{{ route($pdfRoute, $requestOrder->id) }}" target="_blank"
                                    class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#102A47] py-3 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-100/30 hover:bg-[#0d223a] transition-all active:scale-[0.98] dark:shadow-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Download PDF</span>
                                </a>
                            @else
                                <button disabled
                                    class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-2xl bg-[#EEF2FF] border border-slate-200 py-3 text-xs font-bold uppercase tracking-wider text-slate-400 dark:bg-gray-700/50 dark:border-gray-700 dark:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span>PDF Locked</span>
                                </button>
                            @endif

                            {{-- Sent to Warehouse (Primary Action) - Sales Only --}}
                            @if (!$requestOrder->order && Auth::user()->role === 'Sales')
                                <form action="{{ route('sales.quotation.sent-to-warehouse', $requestOrder->id) }}" method="POST"
                                    class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-indigo-600 py-3 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-[0.98] dark:shadow-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 01-1 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                        <span>Send to Warehouse</span>
                                    </button>
                                </form>
                            @endif

                            {{-- Waiting Supervisor Approval status pill --}}
                            @if (!$canDownloadPdf)
                                <div
                                    class="flex w-full items-center justify-center rounded-2xl border border-amber-200 bg-[#FFFDF5] py-3 text-xs font-extrabold uppercase tracking-wider text-[#B45309] dark:bg-amber-950/20 dark:border-amber-900/30">
                                    <span>Menunggu Persetujuan Supervisor</span>
                                </div>
                            @endif

                            {{-- Dangerous Actions: Delete (Sales Only) --}}
                            @if (Auth::user()->role === 'Sales')
                                <form id="deleteRequestOrderForm"
                                    action="{{ route('sales.quotation.destroy', $requestOrder->id) }}" method="POST"
                                    class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" id="btnDeleteRequestOrder"
                                        class="flex w-full items-center justify-center gap-2 rounded-2xl border border-rose-200 bg-white py-3 text-xs font-bold uppercase tracking-wider text-rose-600 hover:bg-rose-50 transition-all active:scale-[0.98] dark:bg-gray-800 dark:hover:bg-rose-950/20 dark:border-rose-900/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Delete Quotation</span>
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Card (Detail Barang) - Full Width Below the Grid -->
            <div
                class="mb-6 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between p-6 border-b border-slate-50 dark:border-gray-700">
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-800 dark:text-white">Detail
                        Barang</h2>
                </div>
                <div class="overflow-x-auto overflow-y-auto" style="max-height: 600px;">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-[#F8FAFC] text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:bg-gray-750 dark:text-gray-400 border-b border-slate-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4">Barang & Kategori</th>
                                <th class="px-6 py-4">Deskripsi Barang</th>
                                <th class="px-6 py-4 text-center">Diskon</th>
                                <th class="px-6 py-4 text-center">Qty</th>
                                <th class="px-6 py-4">Harga Satuan</th>
                                <th class="px-6 py-4">Subtotal</th>
                                <th class="px-6 py-4 text-center">Gambar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                            @php $total = 0; @endphp
                            @forelse($requestOrder->items as $item)
                                @php
                                    $displayHarga = $item->price ?? 0;
                                    $diskonPercent = $item->discount_percent ?? 0;
                                    $computedSubtotal = $item->subtotal ?? 0;
                                    $total += $computedSubtotal;
                                @endphp
                                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col space-y-0.5">
                                            <span class="font-bold text-slate-800 dark:text-white">
                                                {{ $item->barang->goods_name ?? ($item->custom_product_name ?? 'N/A') }}
                                            </span>
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase">
                                                    {{ $item->product_category ?? ($item->barang->category ?? 'UMUM') }}
                                                </span>
                                                <span class="text-gray-350 dark:text-gray-600">&middot;</span>
                                                <span class="font-mono text-[10px] text-gray-400">CODE:
                                                    {{ $item->barang->goods_code ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col space-y-0.5">
                                            <span>{{ $item->barang->description ?? $item->custom_product_description ?? '-' }}</span>
                                            @if ($item->notes)
                                                <span class="text-xs italic text-gray-500 dark:text-gray-400">
                                                    Note: {{ $item->notes }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @php $dk = $item->discount_percent ?? ($item->barang->discount_percent ?? 0); @endphp
                                        @if ($dk > 0)
                                            <div class="flex flex-col items-center">
                                                <span class="text-xs font-bold text-green-500">
                                                    {{ floatval($dk) }}%
                                                </span>
                                                @if ($dk > 20)
                                                    <span
                                                        class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-red-500">Approval
                                                        Required</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-slate-800 dark:text-white">{{ $item->quantity ?? $item->qty }}
                                                Unit</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-slate-600 dark:text-gray-400 text-sm">
                                        {{ number_format($displayHarga, 0, '.', ',') }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-5 font-bold text-[#225A97] dark:text-[#818CF8] text-sm">
                                        {{ number_format($computedSubtotal, 0, '.', ',') }}
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @php
                                            $rawImgs = $item->images ?? ($item->item_images ?? []);
                                            if (is_string($rawImgs)) {
                                                $itemImgs = json_decode($rawImgs, true) ?? [];
                                            } else {
                                                $itemImgs = is_array($rawImgs) ? $rawImgs : [];
                                            }
                                            $itemImgs = array_filter($itemImgs, fn($img) => !empty($img));
                                        @endphp
                                        @if (!empty($itemImgs) && count($itemImgs) > 0)
                                            <div class="flex items-center justify-center -space-x-2 overflow-hidden">
                                                @foreach (array_slice($itemImgs, 0, 3) as $image)
                                                    @php
                                                        if (is_null($image) || $image === '') {
                                                            $imgUrl = null;
                                                        } elseif (str_starts_with($image, 'http')) {
                                                            $imgUrl = $image;
                                                        } else {
                                                            $imgUrl = asset('storage/' . ltrim($image, '/'));
                                                        }
                                                    @endphp
                                                    @if ($imgUrl)
                                                        <button type="button" class="custom-quotation-thumb inline-block"
                                                            data-full="{{ $imgUrl }}">
                                                            <img class="inline-block h-8 w-8 cursor-zoom-in rounded-lg object-cover ring-2 ring-white transition-transform hover:scale-110 dark:ring-gray-800"
                                                                src="{{ $imgUrl }}" alt="Item image">
                                                        </button>
                                                    @endif
                                                @endforeach
                                                @if (count($itemImgs) > 3)
                                                    <span
                                                        class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-[10px] font-bold text-gray-500 ring-2 ring-white dark:bg-gray-700 dark:text-gray-400 dark:ring-gray-800">+{{ count($itemImgs) - 3 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <p class="italic">Belum ada item barang yang ditambahkan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-[#F8FAFC] dark:bg-gray-750">
                            <tr class="border-t border-slate-100 dark:border-gray-700">
                                <td colspan="5"
                                    class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-gray-400">
                                    Total Keseluruhan</td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 font-black text-slate-800 dark:text-white text-base">
                                    {{ number_format($total, 0, '.', ',') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>




        <!-- PDF Note Modal -->
        <div class="modal fade" id="pdfNoteModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Catatan untuk PDF</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    @php
                        $modalPdfRoute = 'sales.quotation.pdf';
                    @endphp
                    <form method="GET" action="{{ route($modalPdfRoute, $requestOrder->id) }}" target="_blank">
                        <div class="modal-body">
                            @php
                                $defaultPdfNote = "Untuk memenuhi kebutuhan..., bersama ini kami sampaikan quotation harga beserta spesifikasi produk sebagai berikut:\n\n";
                            @endphp
                            <div class="mb-3">
                                <label for="pdf_note" class="form-label">Catatan yang akan muncul di PDF</label>
                                <textarea id="pdf_note" name="pdf_note" rows="8"
                                    class="form-control">{{ old('pdf_note', $requestOrder->customer_notes ?? $defaultPdfNote) }}</textarea>
                                <small class="text-muted">Teks ini akan dimasukkan ke bagian pembuka PDF. Anda dapat
                                    mengeditnya sebelum mengunduh.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Generate &amp; Download PDF</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Image modal (lightbox) -->
        <div id="image-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-70">
            <button id="image-modal-close"
                class="absolute right-6 top-6 text-3xl leading-none text-white">&times;</button>
            <img id="image-modal-img" src="" alt="Gambar" class="max-h-[90%] max-w-[95%] rounded shadow-lg">
        </div>

    </div>


    <script>
        (function () {
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

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.custom-quotation-thumb');
                if (btn) {
                    const src = btn.getAttribute('data-full');
                    if (src) {
                        e.preventDefault();
                        openModal(src);
                    }
                }
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) closeModal();
                });
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });

            // SweetAlert for Delete
            const btnDelete = document.getElementById('btnDeleteRequestOrder');
            if (btnDelete) {
                btnDelete.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Hapus Quotation?',
                        text: "Data ini akan dihapus permanen dari sistem.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: "#225A97",
                        cancelButtonColor: "#d33",// slate-500
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('deleteRequestOrderForm').submit();
                        }
                    });
                });
            }
        })();

        function supervisorBack() {
            try {
                var backBtn = document.getElementById('backBtn');
                var fallback = backBtn ? backBtn.dataset.fallback : '/quotation-approval';

                if (document.referrer && document.referrer.indexOf('/quotation-approval') !== -1) {
                    history.back();
                    return;
                }

                if (history.length > 1) {
                    var navigated = false;
                    var onPop = function () {
                        navigated = true;
                        window.removeEventListener('popstate', onPop);
                    };
                    window.addEventListener('popstate', onPop);
                    history.back();
                    setTimeout(function () {
                        if (!navigated) {
                            window.location.href = fallback;
                        }
                    }, 250);
                    return;
                }

                window.location.href = fallback;
            } catch (e) {
                window.location.href = '{{ route('admin.quotation_approval') }}';
            }
        }
    </script>

    @include('admin.quotation-approval.partials.quotation-approval-modal-reject')



</x-app-layout>