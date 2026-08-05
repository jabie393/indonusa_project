<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-6">
            @php
                $orderStatus = $customQuotation->status;

                $bannerKey = $orderStatus;
                if (in_array($orderStatus, ['pending_approval', 'sent'])) {
                    $bannerKey = 'sent_to_supervisor';
                }

                $bannerConfig = [
                    'rejected_supervisor' => [
                        'border' => 'border-rose-200 dark:border-rose-900/30',
                        'bg' => 'bg-rose-50/50 dark:bg-rose-950/20',
                        'icon_bg' => 'bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400',
                        'icon' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
                        'title' => 'Quotation Ditolak Supervisor',
                        'title_text' => 'text-rose-800 dark:text-rose-300',
                        'desc_text' => 'text-rose-700/80 dark:text-rose-400/80',
                    ],
                    'sent_to_supervisor' => [
                        'border' => 'border-amber-200 dark:border-amber-900/30',
                        'bg' => 'bg-[#FFFDF5] dark:bg-amber-950/20',
                        'icon_bg' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                        'icon' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
                        'title' => 'Dalam Peninjauan Supervisor',
                        'title_text' => 'text-amber-800 dark:text-amber-300',
                        'desc_text' => 'text-amber-700/80 dark:text-amber-400/80',
                    ],
                    'approved_supervisor' => [
                        'border' => 'border-emerald-200 dark:border-emerald-900/30',
                        'bg' => 'bg-emerald-50/50 dark:bg-emerald-950/20',
                        'icon_bg' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'icon' =>
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        'title' => 'Verifikasi Disetujui',
                        'title_text' => 'text-emerald-800 dark:text-emerald-300',
                        'desc_text' => 'text-emerald-700/80 dark:text-emerald-400/80',
                    ],
                ];

                $activeConfig = $bannerConfig[$bannerKey] ?? null;
            @endphp

            @if ($activeConfig)
                <div class="{{ $activeConfig['border'] }} {{ $activeConfig['bg'] }} mb-6 flex flex-col gap-4 rounded-2xl border p-5 shadow-sm md:flex-row md:items-center">
                    <div class="{{ $activeConfig['icon_bg'] }} flex h-12 w-12 shrink-0 items-center justify-center rounded-xl">
                        {!! $activeConfig['icon'] !!}
                    </div>
                    <div class="flex-grow">
                        <h4 class="{{ $activeConfig['title_text'] }} text-sm font-bold uppercase tracking-wider">{{ $activeConfig['title'] }}</h4>

                        @if ($bannerKey === 'rejected_supervisor')
                            <div class="mt-1 space-y-1">
                                <p class="{{ $activeConfig['desc_text'] }} text-xs">
                                    <span class="mr-1 text-[10px] font-black uppercase opacity-60">Alasan:</span>
                                    {{ $customQuotation->reason ?? 'Keterangan tidak tersedia' }}
                                </p>
                                @if ($customQuotation->supervisor)
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-rose-500/80">
                                        Oleh {{ $customQuotation->supervisor->name }}
                                        @if ($customQuotation->approved_at)
                                            &middot;
                                            {{ \Carbon\Carbon::parse($customQuotation->approved_at)->translatedFormat('d M Y, H:i') }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @elseif ($bannerKey === 'sent_to_supervisor')
                            <p class="{{ $activeConfig['desc_text'] }} mt-1 text-xs">
                                Quotation mengandung diskon besar (&gt;20%). Dokumen PDF akan terkunci hingga mendapatkan persetujuan.
                            </p>
                        @elseif ($bannerKey === 'approved_supervisor')
                            <p class="{{ $activeConfig['desc_text'] }} mt-1 text-xs">
                                Supervisor telah memberikan persetujuan. Dokumen PDF kini dapat diunduh dan diproses lebih lanjut.
                            </p>
                        @endif
                    </div>
                    </div>
                </div>
            @endif

            <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Left Column: Informasi Quotation -->
                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="space-y-1">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#225A97] dark:text-[#818CF8]">Informasi Quotation Custom</span>
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-3xl font-black tracking-tight text-slate-800 dark:text-white">
                                    {{ $customQuotation->quotation_number ?? '-' }}</h1>
                                @if ($customQuotation->our_ref)
                                    <span
                                        class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        {{ $customQuotation->our_ref }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Status Badge Below Title -->
                        @php
                            $statusText = $customQuotation->status;
                            $statusClass = 'bg-blue-50/50 text-blue-600 border border-blue-200';
                            $statusDot = 'bg-blue-500';

                            if (in_array($statusText, ['pending_approval', 'sent'])) {
                                $statusClass = 'bg-[#FFFBEB] text-[#D97706] border border-[#FCD34D]';
                                $statusDot = 'bg-[#D97706]';
                                $statusText = 'Waiting for Supervisor Approval';
                            } elseif ($statusText === 'rejected_supervisor') {
                                $statusClass = 'bg-rose-50/50 text-rose-600 border border-rose-200';
                                $statusDot = 'bg-rose-500';
                                $statusText = 'Rejected by Supervisor';
                            } elseif (in_array($statusText, ['approved_supervisor', 'approved', 'open'])) {
                                $statusClass = 'bg-emerald-50/50 text-emerald-600 border border-emerald-200';
                                $statusDot = 'bg-emerald-500';
                                $statusText = 'Approved';
                            } elseif ($statusText === 'sent_to_quotation') {
                                $statusClass = 'bg-indigo-50/50 text-indigo-600 border border-indigo-200';
                                $statusDot = 'bg-indigo-500';
                                $statusText = 'Sent to Quotation';
                            } elseif ($statusText === 'sent_to_warehouse') {
                                $statusClass = 'bg-purple-50/50 text-purple-600 border border-purple-200';
                                $statusDot = 'bg-purple-500';
                                $statusText = 'Sent to Warehouse';
                            } elseif ($statusText === 'ready_for_delivery') {
                                $statusClass = 'bg-teal-50/50 text-teal-600 border border-teal-200';
                                $statusDot = 'bg-teal-500';
                                $statusText = 'Ready for Delivery';
                            } elseif ($statusText === 'completed') {
                                $statusClass = 'bg-emerald-50/50 text-emerald-600 border border-emerald-200';
                                $statusDot = 'bg-emerald-500';
                                $statusText = 'Completed';
                            }
                        @endphp
                        <div class="mt-4">
                            <span class="{{ $statusClass }} inline-flex items-center gap-2 rounded-full px-4 py-1 text-xs font-bold uppercase tracking-wider">
                                <span class="{{ $statusDot }} h-2 w-2 rounded-full"></span>
                                {{ $statusText }}
                            </span>
                        </div>

                        <!-- Metadata Grid -->
                        <div class="mt-10 grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-3">
                            <!-- KEPADA (TO) -->
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Kepada (To)</span>
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">
                                    {{ $customQuotation->to }}</p>
                            </div>

                            <!-- PIC (SALES) -->
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">PIC (Sales)</span>
                                <div class="mt-1 flex items-center space-x-2">
                                    <div class="flex h-7 w-7 items-center justify-center text-slate-500 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-gray-200">
                                        {{ $customQuotation->sales->name ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- EMAIL -->
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Email</span>
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">
                                    {{ $customQuotation->email ?? '-' }}</p>
                            </div>

                            <!-- SUBJECT -->
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Subject</span>
                                <p class="mt-1 text-sm font-semibold italic text-slate-800 dark:text-gray-200">
                                    "{{ $customQuotation->subject ?? '-' }}"</p>
                            </div>

                            <!-- ATTENTION (UP) -->
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Attention (Up)</span>
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">
                                    {{ $customQuotation->up ?? '-' }}</p>
                            </div>

                            <!-- MASA BERLAKU -->
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Masa Berlaku</span>
                                <div class="mt-1">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-gray-200">
                                        {{ $customQuotation->expired_at ? $customQuotation->expired_at->format('d M Y') : '-' }}</p>
                                    @if ($customQuotation->expired_at)
                                        @php
                                            $isExpired = now() > $customQuotation->expired_at;
                                            $daysLeft = now()->diffInDays($customQuotation->expired_at, false);
                                        @endphp
                                        @if ($isExpired)
                                            <span
                                                class="mt-1 inline-block rounded border border-rose-100 bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-600 dark:bg-rose-950/20 dark:text-rose-400">Expired</span>
                                        @else
                                            <span
                                                class="mt-1 inline-block rounded border border-emerald-100 bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400">
                                                {{ floor($daysLeft) }} Hari Lagi
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- TGL QUOTATION -->
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Tgl Quotation</span>
                                <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($customQuotation->date)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <!-- Teks Pembuka (Intro Text) -->
                        @if ($customQuotation->intro_text)
                            <div class="mt-8 rounded-2xl border border-indigo-100/50 bg-indigo-50/20 p-5 dark:border-indigo-950/30 dark:bg-indigo-950/10">
                                <label class="flex items-center text-[10px] font-bold uppercase tracking-wider text-[#225A97] dark:text-[#818CF8]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    Teks Pembuka
                                </label>
                                <div class="mt-2 text-xs italic leading-relaxed text-slate-600 dark:text-slate-400">
                                    {!! nl2br(e($customQuotation->intro_text)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Catatan Penolakan Supervisor -->
                        @if ($customQuotation->status === 'rejected_supervisor' || !empty($customQuotation->reason))
                            <div class="mt-4 rounded-2xl border border-rose-100 bg-rose-50/30 p-5 dark:border-rose-950/30 dark:bg-rose-950/10">
                                <label class="flex items-center text-[10px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Catatan Penolakan Supervisor
                                </label>
                                <div class="mt-2 text-xs leading-relaxed text-rose-700 dark:text-rose-300">
                                    {{ $customQuotation->reason ?? 'Keterangan tidak tersedia' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Sidebar (Ringkasan Quotation & Action Buttons) -->
                <div class="space-y-6 lg:col-span-1">
                    <!-- Ringkasan Quotation Card -->
                    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-800 dark:text-white">
                            Ringkasan Quotation</h2>

                        <div class="mt-6 space-y-4">
                            <!-- Subtotal -->
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Sub-Total Barang</span>
                                <span class="font-bold text-slate-800 dark:text-white">
                                    <span class="mr-0.5 text-[10px] font-medium text-gray-400">Rp</span>{{ number_format($customQuotation->subtotal, 0, '.', ',') }}
                                </span>
                            </div>

                            <!-- Pajak / PPN -->
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center space-x-1">
                                    <span class="font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pajak / PPN</span>
                                    <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-bold text-slate-500 dark:bg-gray-700 dark:text-gray-400">
                                        {{ $customQuotation->tax > 0 ? 'ESTIMATED' : '0%' }}
                                    </span>
                                </div>
                                <span class="font-bold text-slate-800 dark:text-white">
                                    <span class="mr-0.5 text-[10px] font-medium text-gray-400">Rp</span>{{ number_format($customQuotation->tax, 0, '.', ',') }}
                                </span>
                            </div>

                            <!-- Divider -->
                            <div class="my-4 border-t border-slate-100 dark:border-gray-700"></div>

                            <!-- Grand Total -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Total Bayar</span>
                                <div class="text-right">
                                    <span class="block text-[9px] font-extrabold uppercase tracking-wider text-[#225A97] dark:text-[#818CF8]">IDR</span>
                                    <span class="text-2xl font-black tracking-tight text-slate-800 dark:text-white">{{ number_format($customQuotation->grand_total, 0, '.', ',') }}</span>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="my-4 border-t border-slate-100 dark:border-gray-700"></div>

                            <!-- Dibuat -->
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-semibold uppercase tracking-wider text-gray-400">Dibuat</span>
                                <span class="font-bold text-gray-600 dark:text-gray-400">{{ $customQuotation->created_at->format('d M Y, H:i') }}</span>
                            </div>

                            <!-- Diperbarui -->
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-semibold uppercase tracking-wider text-gray-400">Diperbarui</span>
                                <span class="font-bold text-gray-600 dark:text-gray-400">{{ $customQuotation->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        {{-- Approval Actions for Supervisor --}}
                        @if (in_array($customQuotation->status, ['sent', 'pending_approval']) && auth()->user()->role === 'Supervisor')
                            <div class="space-y-3 rounded-3xl border border-amber-200 bg-amber-50/50 p-5 dark:border-amber-900/30 dark:bg-amber-950/10">
                                <h3 class="text-[10px] font-black uppercase tracking-wider text-amber-700">Persetujuan Supervisor</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <form action="{{ route('admin.custom-quotation-approval.approval', $customQuotation) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" name="action" value="approve"
                                            class="flex w-full items-center justify-center space-x-2 rounded-2xl bg-emerald-600 py-3 text-xs font-bold text-white shadow-lg shadow-emerald-100/50 transition-all hover:bg-emerald-700 active:scale-[0.98] dark:shadow-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Approve</span>
                                        </button>
                                    </form>
                                    <button onclick="openTolakModal('custom', '{{ $customQuotation->id }}', '{{ $customQuotation->quotation_number }}')" title="Tolak"
                                        class="flex w-full items-center justify-center space-x-2 rounded-2xl bg-rose-600 py-3 text-xs font-bold text-white shadow-lg shadow-rose-100/50 transition-all hover:bg-rose-700 active:scale-[0.98] dark:shadow-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span>Reject</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @php
                            $isCqProcessed = in_array($customQuotation->status, ['sent_to_warehouse', 'sent_to_quotation']) || 
                                             ($customQuotation->order && in_array($customQuotation->order->status, ['under_procurement', 'sent_to_warehouse', 'approved_warehouse', 'rejected_warehouse', 'completed', 'not_completed']));
                        @endphp

                        {{-- Edit Button (Sales Only) --}}
                        @if (Auth::user()->role === 'Sales' && !$isCqProcessed)
                            <a href="{{ route('sales.custom-quotation.edit', $customQuotation->id) }}"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#225A97] py-3 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-blue-100/30 transition-all hover:bg-[#1a4675] active:scale-[0.98] dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span>{{ $customQuotation->status === 'rejected_supervisor' ? 'Revisi & Ajukan Ulang' : 'Edit Custom Quotation' }}</span>
                            </a>
                        @endif

                        {{-- PDF Button (Enabled / Disabled) --}}
                        @php
                            $isExpired = $customQuotation->isExpired();
                            $canDownload = !in_array($customQuotation->status, ['pending_approval', 'rejected_supervisor']) && !$isExpired;
                            $pdfRoute = Auth::user()->role === 'Sales' ? 'sales.custom-quotation.pdf' : 'admin.custom-quotation-approval.pdf';
                        @endphp

                        @if ($canDownload && Auth::user()->role !== 'Supervisor')
                            <a href="{{ route($pdfRoute, $customQuotation->id) }}" target="_blank"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#102A47] py-3 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-100/30 transition-all hover:bg-[#0d223a] active:scale-[0.98] dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Download PDF</span>
                            </a>
                        @else
                            <button disabled
                                class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-[#EEF2FF] py-3 text-xs font-bold uppercase tracking-wider text-slate-400 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-500"
                                title="{{ $isExpired ? 'Quotation sudah kadaluarsa' : ($customQuotation->status === 'rejected_supervisor' ? 'Quotation ditolak oleh Supervisor' : 'Menunggu persetujuan Supervisor') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>PDF Locked</span>
                            </button>
                        @endif

                        {{-- Sent to Warehouse (Primary Action) - Sales Only --}}
                        @if (!$customQuotation->order && Auth::user()->role === 'Sales' && $customQuotation->status === 'ready_for_delivery')
                            <form action="{{ route('sales.custom-quotation.sent-to-warehouse', $customQuotation->id) }}" method="POST"
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

                        {{-- Dangerous Actions: Delete (Sales Only) --}}
                        @if (Auth::user()->role === 'Sales' && !$isCqProcessed)
                            <form id="deleteCustomQuotationForm" action="{{ route('sales.custom-quotation.destroy', $customQuotation->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="button" id="btnDeleteCustomQuotation"
                                    class="flex w-full items-center justify-center gap-2 rounded-2xl border border-rose-200 bg-white py-3 text-xs font-bold uppercase tracking-wider text-rose-600 transition-all hover:bg-rose-50 active:scale-[0.98] dark:border-rose-900/30 dark:bg-gray-800 dark:hover:bg-rose-950/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Delete Custom Quotation</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Card (Detail Barang) - Full Width Below the Grid -->
            <div class="mb-6 overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between border-b border-slate-50 p-6 dark:border-gray-700">
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-800 dark:text-white">Detail Barang</h2>
                </div>
                <div class="overflow-x-auto overflow-y-auto" style="max-height: 600px;">
                    <table class="w-full text-left text-sm">
                        <thead class="dark:bg-gray-750 border-b border-slate-100 bg-[#F8FAFC] text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:border-gray-700 dark:text-gray-400">
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
                            @forelse($customQuotation->items as $index => $item)
                                @php $total += $item->subtotal ?? 0; @endphp
                                <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col space-y-0.5">
                                            <span class="font-bold text-slate-800 dark:text-white">
                                                {{ $item->product_name }}
                                            </span>
                                            @if ($item->category)
                                                <span class="text-[10px] font-semibold uppercase text-gray-400 dark:text-gray-500">
                                                    {{ $item->category }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col space-y-0.5">
                                            <span>{{ $item->description ?? '-' }}</span>
                                            @if ($item->notes)
                                                <span class="text-xs italic text-gray-500 dark:text-gray-400">
                                                    Note: {{ $item->notes }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @php $dk = $item->discount ?? 0; @endphp
                                        @if ($dk > 0)
                                            <div class="flex flex-col items-center">
                                                <span class="text-xs font-bold text-green-500">
                                                    {{ floatval($dk) }}%
                                                </span>
                                                @if ($dk > 20)
                                                    <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-red-500">Approval Required</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 dark:text-white">{{ $item->qty }} {{ $item->unit }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-600 dark:text-gray-400">
                                        {{ number_format($item->price, 0, '.', ',') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-5 text-sm font-bold text-[#225A97] dark:text-[#818CF8]">
                                        @php
                                            $totalSetelahDiskon = $item->qty * $item->price * (1 - ($item->discount ?? 0) / 100);
                                        @endphp
                                        {{ number_format($totalSetelahDiskon, 0, '.', ',') }}
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @php
                                            $images = $item->images;
                                            if (is_string($images)) {
                                                $decoded = json_decode($images, true);
                                                $images = is_array($decoded) ? $decoded : [];
                                            }
                                            $images = is_array($images) ? array_filter($images, fn($img) => !empty($img)) : [];
                                        @endphp
                                        @if ($images && count($images) > 0)
                                            <div class="flex items-center justify-center -space-x-2 overflow-hidden">
                                                @foreach (array_slice($images, 0, 3) as $image)
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
                                                        <button type="button" class="custom-quotation-thumb inline-block transition-transform hover:scale-110 active:scale-95"
                                                            data-full="{{ $imgUrl }}">
                                                            <img class="inline-block h-8 w-8 cursor-zoom-in rounded-lg object-cover ring-2 ring-white transition-transform hover:scale-110 dark:ring-gray-800"
                                                                src="{{ $imgUrl }}" alt="Item image">
                                                        </button>
                                                    @endif
                                                @endforeach
                                                @if (count($images) > 3)
                                                    <span
                                                        class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-[10px] font-bold text-gray-500 ring-2 ring-white dark:bg-gray-700 dark:text-gray-400 dark:ring-gray-800">+{{ count($images) - 3 }}</span>
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
                modal.classList.add('flex');
            }

            function closeModal() {
                modalImg.src = '';
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.addEventListener('click', function(e) {
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
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) closeModal();
                });
            }
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });

            // SweetAlert for Delete
            const btnDelete = document.getElementById('btnDeleteCustomQuotation');
            if (btnDelete) {
                btnDelete.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Hapus Custom Quotation?',
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
                            document.getElementById('deleteCustomQuotationForm').submit();
                        }
                    });
                });
            }
        })();
    </script>
    @include('admin.quotation-approval.partials.quotation-approval-modal-reject')
</x-app-layout>
