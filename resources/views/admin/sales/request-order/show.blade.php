<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        @if (session('success'))
            <div class="mb-6 flex items-center justify-between rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900 dark:text-green-300" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-green-100 p-1.5 text-green-500 hover:bg-green-200 focus:ring-2 focus:ring-green-400 dark:bg-green-800 dark:text-green-400 dark:hover:bg-green-700" data-bs-dismiss="alert" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-900 dark:text-red-300" role="alert">
                <div class="mb-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="font-medium">Terdapat kesalahan:</span>
                </div>
                <ul class="ml-4 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="mt-2 text-sm underline hover:no-underline" data-bs-dismiss="alert" aria-label="Close">Tutup</button>
            </div>
        @endif

        {{-- ============================================================
                    {{-- Info asal custom penawaran --}}
        @if ($requestOrder->customPenawaran)
            <div class="mx-6 mb-2 mt-4 flex items-center gap-2 rounded bg-indigo-50 p-3 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">
                <span class="font-semibold">Dari Custom Penawaran:</span>
                <a href="{{ route('sales.custom-penawaran.show', $requestOrder->customPenawaran->id) }}" class="underline hover:text-indigo-600">
                    {{ $requestOrder->customPenawaran->penawaran_number }}
                </a>
            </div>
        @endif

        @php $orderStatus = $requestOrder->order?->status; @endphp

        @if ($orderStatus === 'rejected_supervisor')
            {{-- BANNER MERAH: Ditolak --}}
            <div class="overflow-hidden rounded-2xl border border-rose-100 bg-rose-50 shadow-sm dark:border-rose-900/30 dark:bg-rose-900/10">
                <div class="flex flex-col items-center gap-6 p-5 md:flex-row">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm dark:bg-rose-900/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-grow text-center md:text-left">
                        <h4 class="text-sm font-black uppercase tracking-[0.2em] text-rose-700">Penawaran Ditolak Supervisor</h4>
                        <div class="mt-2 space-y-1">
                            <p class="text-sm font-medium text-rose-600 dark:text-rose-300">
                                <span class="mr-1 text-[10px] font-black uppercase opacity-50">Alasan:</span>
                                {{ $requestOrder->reason ?? ($requestOrder->order?->reason ?? 'Keterangan tidak tersedia') }}
                            </p>
                            @if ($requestOrder->order?->supervisor)
                                <p class="text-[10px] font-bold uppercase tracking-tighter text-rose-400">
                                    Oleh {{ $requestOrder->order->supervisor->name }}
                                    @if ($requestOrder->order->approved_at)
                                        &middot; {{ \Carbon\Carbon::parse($requestOrder->order->approved_at)->translatedFormat('d M Y, H:i') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('sales.request-order.edit', $requestOrder->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-xl shadow-rose-200 transition-all hover:bg-rose-700 hover:shadow-none active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Perbaiki & Ajukan Ulang
                        </a>
                    </div>
                </div>
            </div>
        @elseif ($orderStatus === 'sent_to_supervisor')
            {{-- BANNER KUNING: Menunggu persetujuan --}}
            <div class="overflow-hidden rounded-2xl border border-amber-100 bg-amber-50 shadow-sm dark:border-amber-900/30 dark:bg-amber-900/10">
                <div class="flex items-center gap-5 p-5">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm dark:bg-amber-900/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 animate-pulse text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-[0.2em] text-amber-700">Dalam Peninjauan Supervisor</h4>
                        <p class="mt-1 text-sm font-medium text-amber-600 dark:text-amber-300">
                            Penawaran mengandung diskon besar (>20%). Dokumen PDF akan terkunci hingga mendapatkan persetujuan.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($orderStatus === 'approved_supervisor')
            {{-- BANNER HIJAU: Disetujui --}}
            <div class="overflow-hidden rounded-2xl border border-emerald-100 bg-emerald-50 shadow-sm dark:border-emerald-900/30 dark:bg-emerald-900/10">
                <div class="flex items-center gap-5 p-5">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm dark:bg-emerald-900/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-[0.2em] text-emerald-700">Verifikasi Disetujui</h4>
                        <p class="mt-1 text-sm font-bold text-emerald-600 dark:text-emerald-300">
                            Supervisor telah memberikan persetujuan. Dokumen PDF kini dapat diunduh dan diproses lebih lanjut.
                        </p>
                    </div>
                </div>
            </div>
        @endif
        {{-- ============================================================
             END BANNER STATUS SUPERVISOR
             ============================================================ --}}

        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Left Column -->
                <div class="lg:col-span-2">
                    <!-- Main Details Card -->
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                            <h2 class="flex items-center font-semibold text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Penawaran
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-x-12 gap-y-8 md:grid-cols-2">
                                <!-- Group: Order Metadata -->
                                <div class="space-y-5">
                                    <h3 class="flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                                        <span class="mr-2 h-[1px] w-8 bg-gray-200 dark:bg-gray-700"></span>
                                        Detail Request
                                    </h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">No. Request</label>
                                            <p class="text-sm font-black text-[#225A97] dark:text-blue-400">{{ $requestOrder->request_number }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">No. Penawaran</label>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $requestOrder->nomor_penawaran ?? '-' }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Status Sistem</label>
                                            @php
                                                $statusClass =
                                                    [
                                                        'Pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                                        'Open' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                                        'Disetujui Supervisor' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                                        'Ditolak Supervisor' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                                        'Dikirim ke Gudang' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
                                                        'Dikirim ke Supervisor' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
                                                    ][$requestOrder->status] ?? 'bg-gray-50 text-gray-600 ring-gray-600/20';
                                            @endphp
                                            <div>
                                                <span class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-tighter ring-1 ring-inset">
                                                    {{ $requestOrder->status }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Kategori</label>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                @php
                                                    $firstItem = $requestOrder->items->first();
                                                    $kategori = $firstItem->kategori_barang ?? ($firstItem->kategori ?? ($firstItem->barang->kategori ?? '-'));
                                                @endphp
                                                {{ $kategori }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Group: Customer & Sales -->
                                <div class="space-y-5">
                                    <h3 class="flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                                        <span class="mr-2 h-[1px] w-8 bg-gray-200 dark:bg-gray-700"></span>
                                        Customer & PIC
                                    </h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Nama Customer</label>
                                            <p class="text-sm font-bold leading-tight text-gray-900 dark:text-white">{{ $requestOrder->customer_name }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">PIC (Sales)</label>
                                            <div class="flex items-center space-x-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ substr($requestOrder->sales->name ?? 'S', 0, 1) }}
                                                </div>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $requestOrder->sales->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">No. PO</label>
                                            <p class="font-mono text-sm text-gray-600 dark:text-gray-400">{{ $requestOrder->no_po ?? '-' }}</p>
                                        </div>
                                        <div class="space-y-1 md:col-span-2">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Subject / Keterangan</label>
                                            <p class="text-sm font-medium italic text-gray-600 dark:text-gray-400">"{{ $requestOrder->subject ?? '-' }}"</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Group: Timeline -->
                                <div class="space-y-5 border-t border-gray-50 pt-4 dark:border-gray-700/50 md:col-span-2">
                                    <h3 class="flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                                        <span class="mr-2 h-[1px] w-8 bg-gray-200 dark:bg-gray-700"></span>
                                        Jadwal & Validitas
                                    </h3>
                                    <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Tgl Penawaran</label>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $requestOrder->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Tgl Kebutuhan</label>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $requestOrder->tanggal_kebutuhan_formatted }}</p>
                                        </div>
                                        <div class="space-y-1 md:col-span-2">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Masa Berlaku</label>
                                            <div class="flex items-center space-x-3">
                                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $requestOrder->tanggal_berlaku_formatted ?? '-' }}</p>
                                                @if ($requestOrder->expired_at)
                                                    @php
                                                        try {
                                                            $expiry = is_string($requestOrder->expired_at) ? \Carbon\Carbon::parse($requestOrder->expired_at) : $requestOrder->expired_at;
                                                            $isExpired = now() > $expiry;
                                                            $daysLeft = $expiry->diffInDays(now());
                                                        } catch (\Throwable $e) {
                                                            $isExpired = false;
                                                            $daysLeft = null;
                                                        }
                                                    @endphp
                                                    @if ($isExpired)
                                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[9px] font-bold uppercase text-red-700">Expired</span>
                                                    @else
                                                        <span class="{{ $daysLeft <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }} inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold uppercase">
                                                            {{ $daysLeft }} Hari Lagi
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($requestOrder->catatan_customer)
                            <div class="border-t border-gray-100 p-6 pt-6 dark:border-gray-700">
                                <label class="mb-3 flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    Catatan Customer
                                </label>
                                <div class="space-y-1">
                                    "{{ $requestOrder->catatan_customer }}"
                                </div>
                            </div>
                        @endif

                        {{-- Alasan penolakan di dalam card (backup tampilan selain banner atas) --}}
                        @if (!empty($requestOrder->reason) || !empty($requestOrder->order?->reason))
                            <div class="mt-8 border-t border-red-50 pt-6 dark:border-red-900/10">
                                <label class="mb-3 flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-rose-500 dark:text-rose-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Catatan Penolakan Supervisor
                                </label>
                                <div class="rounded-xl border-l-4 border-rose-500 bg-rose-50 p-4 text-sm font-medium leading-relaxed text-rose-800 dark:bg-rose-900/20 dark:text-rose-300">
                                    {{ $requestOrder->reason ?? $requestOrder->order?->reason }}
                                </div>
                            </div>
                        @endif
                    </div>




                    <!-- Supporting Images Card -->
                    @if ($requestOrder->supporting_images && count($requestOrder->supporting_images) > 0)
                        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                <h2 class="flex items-center font-semibold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Gambar Pendukung
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                    @foreach ($requestOrder->supporting_images as $image)
                                        <div class="group relative aspect-square overflow-hidden rounded-xl border border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/50">
                                            <button type="button" class="custom-penawaran-thumb block h-full w-full" data-full="{{ asset('storage/' . $image) }}">
                                                <img src="{{ asset('storage/' . $image) }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" alt="Supporting image">
                                                <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent p-3 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                                    <span class="truncate text-[10px] font-medium text-white">{{ basename($image) }}</span>
                                                </div>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Items Card -->
                    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                            <h2 class="flex items-center font-semibold text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Detail Barang
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500 dark:bg-gray-700/50 dark:text-gray-400">
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <th class="px-6 py-4">Barang & Kategori</th>
                                        <th class="px-6 py-4 text-center">Diskon</th>
                                        <th class="px-6 py-4 text-center">Qty</th>
                                        <th class="px-6 py-4">Harga Satuan</th>
                                        <th class="px-6 py-4">Subtotal</th>
                                        <th class="px-6 py-4 text-center">Gambar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @php $total = 0; @endphp
                                    @forelse($requestOrder->items as $item)
                                        @php
                                            $displayHarga = $item->harga ?? 0;
                                            $diskonPercent = $item->diskon_percent ?? 0;
                                            $computedSubtotal = $item->subtotal ?? 0;
                                            $total += $computedSubtotal;
                                        @endphp
                                        <tr class="group transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                            <td class="px-6 py-5">
                                                <div class="flex flex-col space-y-1">
                                                    <span class="font-bold leading-tight text-gray-900 dark:text-white">
                                                        {{ $item->barang->nama_barang ?? ($item->nama_barang_custom ?? 'N/A') }}
                                                    </span>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-tighter text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">
                                                            {{ $item->kategori_barang ?? ($item->barang->kategori ?? 'UMUM') }}
                                                        </span>
                                                        <span class="font-mono text-[10px] text-gray-400">CODE: {{ $item->barang->kode_barang ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                @php $dk = $item->diskon_percent ?? ($item->barang->diskon_percent ?? 0); @endphp
                                                @if ($dk > 0)
                                                    <div class="flex flex-col items-center">
                                                        <span class="{{ $dk > 20 ? 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20' : 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' }} inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold">
                                                            {{ $dk }}%
                                                        </span>
                                                        @if ($dk > 20)
                                                            <span class="mt-1 text-[9px] font-bold uppercase italic tracking-tighter text-red-500">Approval Required</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-gray-900 dark:text-white">{{ $item->quantity ?? $item->qty }}</span>
                                                    <span class="text-[10px] font-bold uppercase tracking-tighter text-gray-400">{{ $item->kategori_barang ?? ($item->satuan ?? '-') }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-5 text-gray-600 dark:text-gray-400">
                                                <span class="text-xs">Rp</span> {{ number_format($displayHarga, 0, ',', '.') }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-5 font-bold text-gray-900 dark:text-white">
                                                <span class="text-xs">Rp</span> {{ number_format($computedSubtotal, 0, ',', '.') }}
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
                                                                <button type="button" class="custom-penawaran-thumb inline-block" data-full="{{ $imgUrl }}">
                                                                    <img class="inline-block h-10 w-10 cursor-zoom-in rounded-lg object-cover ring-2 ring-white transition-transform hover:scale-110 dark:ring-gray-800" src="{{ $imgUrl }}" alt="Item image">
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                        @if (count($itemImgs) > 3)
                                                            <span class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-[10px] font-bold text-gray-500 ring-2 ring-white dark:bg-gray-700 dark:text-gray-400 dark:ring-gray-800">+{{ count($itemImgs) - 3 }}</span>
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
                                <tfoot class="bg-gray-50/50 dark:bg-gray-700/30">
                                    <tr class="border-t border-gray-100 dark:border-gray-700">
                                        <td colspan="4" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Keseluruhan</td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span class="text-xl font-black text-[#225A97] dark:text-blue-400">
                                                <span class="mt-1 align-top text-xs font-bold">Rp</span> {{ number_format($total, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="gap-2 lg:col-span-1">
                    <div class="sticky top-5 space-y-6">
                        <!-- Actions Card -->
                        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                <h2 class="flex items-center font-semibold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    Panel Aksi
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="flex flex-col gap-3">
                                    {{-- Top Row: Edit & PDF --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('sales.request-order.edit', $requestOrder->id) }}" class="flex items-center justify-center space-x-2 rounded-xl bg-[#F59E0B] py-2.5 text-xs font-bold text-white shadow-lg shadow-amber-200 transition-all hover:bg-amber-600 hover:shadow-none dark:shadow-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        @php
                                            $canDownloadPdf = method_exists($requestOrder, 'canDownloadPdf') ? $requestOrder->canDownloadPdf() : true;
                                        @endphp

                                        @if ($canDownloadPdf)
                                            {{-- PDF Button (Enabled) --}}
                                            <a href="{{ route('sales.request-order.pdf', $requestOrder->id) }}" target="_blank" class="flex items-center justify-center space-x-2 rounded-xl bg-[#10B981] py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-200 transition-all hover:bg-emerald-600 hover:shadow-none dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span>Dokumen PDF</span>
                                            </a>
                                        @else
                                            {{-- PDF Button (Disabled) --}}
                                            <button disabled class="flex cursor-not-allowed items-center justify-center space-x-2 rounded-xl bg-gray-200 py-2.5 text-xs font-bold text-gray-400 opacity-60 dark:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                <span>PDF Locked</span>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Sent to Warehouse (Primary Action) --}}
                                    @if (!$requestOrder->order)
                                        <form action="{{ route('sales.request-order.sent-to-warehouse', $requestOrder->id) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center justify-center space-x-3 rounded-xl bg-[#225A97] py-3 text-sm font-black text-white shadow-xl shadow-blue-200 transition-all hover:bg-[#1a4675] hover:shadow-none active:scale-[0.98] dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                                </svg>
                                                <span class="uppercase tracking-widest">Kirim ke Warehouse</span>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Warning Text if Locked --}}
                                    @if (!$canDownloadPdf)
                                        <div class="mt-2 flex items-center justify-center space-x-1.5 rounded-lg bg-amber-50 px-3 py-2 dark:bg-amber-900/10">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span class="text-[10px] font-bold uppercase tracking-tighter text-amber-700">Menunggu Persetujuan Supervisor</span>
                                        </div>
                                    @endif

                                    {{-- Dangerous Actions --}}
                                    <div class="mt-2 border-t border-gray-50 pt-4 dark:border-gray-700/50">
                                        <form id="deleteRequestOrderForm" action="{{ route('sales.request-order.destroy', $requestOrder->id) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" id="btnDeleteRequestOrder" class="flex w-full items-center justify-center space-x-2 rounded-xl bg-rose-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-rose-200 transition-all hover:bg-rose-700 hover:shadow-none active:scale-[0.98] dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span class="uppercase tracking-widest">Hapus Request Order</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $subtotal = $requestOrder->subtotal ?? $requestOrder->items->sum('subtotal');
                            $totalPPN = $requestOrder->tax ?? 0;
                            $grandTotal = $requestOrder->grand_total ?? round($subtotal + $totalPPN, 2);
                            $ppnRate = $subtotal > 0 ? round(($totalPPN / $subtotal) * 100, 2) : 0;

                            $getStorageImageBase64 = function ($imagePath) {
                                try {
                                    if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                                        return $imagePath;
                                    }
                                    $fullPath = str_starts_with($imagePath, 'public/') ? storage_path('app/public/' . ltrim(substr($imagePath, 7), '/')) : storage_path('app/public/' . ltrim($imagePath, '/'));
                                    if (file_exists($fullPath) && is_readable($fullPath)) {
                                        $mime = mime_content_type($fullPath);
                                        $data = base64_encode(file_get_contents($fullPath));
                                        return 'data:' . $mime . ';base64,' . $data;
                                    }
                                } catch (\Exception $e) {
                                }
                                return '';
                            };
                        @endphp

                        <!-- Order Summary Card -->
                        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                <h2 class="flex items-center font-semibold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Ringkasan Order
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Subtotal -->
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium text-gray-500 dark:text-gray-400">Sub-Total Barang</span>
                                        <span class="font-bold text-gray-900 dark:text-white">
                                            <span class="mr-0.5 text-[10px] text-gray-400">Rp</span>{{ number_format($requestOrder->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Tax/PPN -->
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center space-x-1">
                                            <span class="font-medium text-gray-500 dark:text-gray-400">Pajak / PPN</span>
                                            <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-bold text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                                {{ $requestOrder->tax > 0 ? '11%' : '0%' }}
                                            </span>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white">
                                            <span class="mr-0.5 text-[10px] text-gray-400">Rp</span>{{ number_format($requestOrder->tax, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Divider -->
                                    <div class="my-2 border-t border-dashed border-gray-200 dark:border-gray-700"></div>

                                    <!-- Grand Total -->
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Bayar</span>
                                        <div class="flex items-baseline justify-between">
                                            <span class="align-top text-xs font-bold text-[#225A97] dark:text-blue-400">Rp</span>
                                            <span class="text-3xl font-black tracking-tighter text-[#225A97] dark:text-blue-400">
                                                {{ number_format($requestOrder->grand_total, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Metadata Footer -->
                                <div class="mt-8 space-y-3 border-t border-gray-100 pt-6 dark:border-gray-700">
                                    <div class="flex items-center text-[11px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <span class="mr-auto font-medium uppercase tracking-tighter text-gray-400">Dibuat</span>
                                        <span class="font-bold text-gray-600 dark:text-gray-400">{{ $requestOrder->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center text-[11px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span class="mr-auto font-medium uppercase tracking-tighter text-gray-400">Diperbarui</span>
                                        <span class="font-bold text-gray-600 dark:text-gray-400">{{ $requestOrder->updated_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <form method="GET" action="{{ route('sales.request-order.pdf', $requestOrder->id) }}" target="_blank">
                        <div class="modal-body">
                            @php
                                $defaultPdfNote = "Untuk memenuhi kebutuhan..., bersama ini kami sampaikan penawaran harga beserta spesifikasi produk sebagai berikut:\n\n";
                            @endphp
                            <div class="mb-3">
                                <label for="pdf_note" class="form-label">Catatan yang akan muncul di PDF</label>
                                <textarea id="pdf_note" name="pdf_note" rows="8" class="form-control">{{ old('pdf_note', $requestOrder->catatan_customer ?? $defaultPdfNote) }}</textarea>
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

                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('.custom-penawaran-thumb');
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
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) closeModal();
                    });
                }
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeModal();
                });

                // SweetAlert for Delete
                const btnDelete = document.getElementById('btnDeleteRequestOrder');
                if (btnDelete) {
                    btnDelete.addEventListener('click', function() {
                        Swal.fire({
                            title: 'Hapus Request Order?',
                            text: "Data ini akan dihapus permanen dari sistem.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#e11d48', // rose-600
                            cancelButtonColor: '#64748b', // slate-500
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-2xl border-none shadow-2xl',
                                confirmButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest',
                                cancelButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest'
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
                    var fallback = backBtn ? backBtn.dataset.fallback : '/sent-penawaran';

                    if (document.referrer && document.referrer.indexOf('/sent-penawaran') !== -1) {
                        history.back();
                        return;
                    }

                    if (history.length > 1) {
                        var navigated = false;
                        var onPop = function() {
                            navigated = true;
                            window.removeEventListener('popstate', onPop);
                        };
                        window.addEventListener('popstate', onPop);
                        history.back();
                        setTimeout(function() {
                            if (!navigated) {
                                window.location.href = fallback;
                            }
                        }, 250);
                        return;
                    }

                    window.location.href = fallback;
                } catch (e) {
                    window.location.href = '{{ route('admin.sent_penawaran') }}';
                }
            }
        </script>

</x-app-layout>
