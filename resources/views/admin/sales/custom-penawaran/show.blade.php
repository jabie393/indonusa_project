<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="space-y-3 p-6 md:space-x-4 md:space-y-0">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Header Info -->
                    <!-- Header Info -->
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                            <h2 class="flex items-center font-semibold text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Detail Penawaran Kustom
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-x-12 gap-y-8 md:grid-cols-2">
                                <!-- Group: Order Metadata -->
                                <div class="space-y-5">
                                    <h3 class="flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                                        <span class="mr-2 h-[1px] w-8 bg-gray-200 dark:bg-gray-700"></span>
                                        Metadata Penawaran
                                    </h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">No. Penawaran</label>
                                            <p class="text-sm font-black text-[#225A97] dark:text-blue-400">{{ $customPenawaran->penawaran_number }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Our Ref</label>
                                            <p class="font-mono text-[11px] text-sm font-bold text-gray-700 dark:text-gray-300">{{ $customPenawaran->our_ref ?? '-' }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Status</label>
                                            @php
                                                $statusClass =
                                                    [
                                                        'pending_approval' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                                        'draft' => 'bg-gray-50 text-gray-600 ring-gray-600/20',
                                                        'sent' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
                                                        'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                                        'rejected' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                                    ][$customPenawaran->status] ?? 'bg-gray-50 text-gray-600 ring-gray-600/20';
                                                $statusLabel =
                                                    [
                                                        'pending_approval' => 'Pending Approval',
                                                        'draft' => 'Draft',
                                                        'sent' => 'Terkirim',
                                                        'approved' => 'Disetujui',
                                                        'rejected' => 'Ditolak',
                                                    ][$customPenawaran->status] ?? strtoupper($customPenawaran->status);
                                            @endphp
                                            <div>
                                                <span class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-tighter ring-1 ring-inset">
                                                    {{ $statusLabel }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Sales PIC</label>
                                            <div class="flex items-center space-x-2">
                                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-100 text-[9px] font-bold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ substr($customPenawaran->sales->name ?? 'S', 0, 1) }}
                                                </div>
                                                <p class="text-[11px] font-medium text-gray-700 dark:text-gray-300">{{ $customPenawaran->sales->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Group: Customer info -->
                                <div class="space-y-5">
                                    <h3 class="flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                                        <span class="mr-2 h-[1px] w-8 bg-gray-200 dark:bg-gray-700"></span>
                                        Informasi Customer
                                    </h3>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Kepada (To)</label>
                                            <p class="text-sm font-bold leading-tight text-gray-900 dark:text-white">{{ $customPenawaran->to }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Email</label>
                                            <p class="truncate text-sm font-medium tracking-tighter text-gray-600 dark:text-gray-400" title="{{ $customPenawaran->email }}">{{ $customPenawaran->email }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Attention (Up)</label>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $customPenawaran->up ?? '-' }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Subject</label>
                                            <p class="text-sm font-medium italic leading-tight text-gray-600 dark:text-gray-400">"{{ $customPenawaran->subject }}"</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Group: Timeline -->
                                <div class="space-y-5 border-t border-gray-50 pt-4 dark:border-gray-700/50 md:col-span-2">
                                    <h3 class="flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                                        <span class="mr-2 h-[1px] w-8 bg-gray-200 dark:bg-gray-700"></span>
                                        Jadwal & Deadline
                                    </h3>
                                    <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Tgl Penawaran</label>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($customPenawaran->date)->format('d M Y') }}</p>
                                        </div>
                                        <div class="space-y-1 md:col-span-2">
                                            <label class="text-[10px] font-bold uppercase text-gray-400">Berlaku Hingga</label>
                                            <div class="flex items-center space-x-3">
                                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $customPenawaran->expired_at ? $customPenawaran->expired_at->format('d M Y') : '-' }}</p>
                                                @if ($customPenawaran->expired_at)
                                                    @php
                                                        $isExpired = now() > $customPenawaran->expired_at;
                                                        $daysLeft = now()->diffInDays($customPenawaran->expired_at, false);
                                                    @endphp
                                                    @if ($isExpired)
                                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[9px] font-bold uppercase text-red-700">Expired</span>
                                                    @else
                                                        <span class="{{ $daysLeft <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }} inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold uppercase">
                                                            {{ floor($daysLeft) }} Hari Lagi
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Intro Text -->
                    @if ($customPenawaran->intro_text)
                        <div class="mb-6 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                <h2 class="flex items-center font-semibold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    Teks Pembuka
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl border-l-4 border-blue-400 bg-gray-50 p-4 text-sm leading-relaxed text-gray-700 dark:bg-gray-900/50 dark:text-gray-300">
                                    {!! nl2br(e($customPenawaran->intro_text)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Rejection reason (visible when rejected and high discount items present) -->
                    @php
                        $hasHighDiscount = $customPenawaran->items->where('diskon', '>', 20)->isNotEmpty();
                    @endphp
                    @if ($customPenawaran->status === 'rejected' && $hasHighDiscount)
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-6 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-red-600 p-4 md:flex-row md:space-x-4 md:space-y-0">
                                <div>
                                    <h2 class="mr-3 font-semibold text-white">Alasan Penolakan</h2>
                                </div>
                            </div>
                            <div class="p-6 text-red-700 dark:text-red-300">
                                @if ($customPenawaran->reason)
                                    {!! nl2br(e($customPenawaran->reason)) !!}
                                @else
                                    <em>Alasan penolakan belum dicatat.</em>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Items Table -->
                    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                            <h2 class="flex items-center text-sm font-semibold text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Detail Produk & Penawaran
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50/50 text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 dark:bg-gray-700/30 dark:text-gray-500">
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <th class="px-6 py-4">Nama Barang & Detail</th>
                                        <th class="px-6 py-4 text-center">Diskon</th>
                                        <th class="px-6 py-4 text-center">Qty</th>
                                        <th class="px-6 py-4">Harga Satuan</th>
                                        <th class="px-6 py-4">Subtotal</th>
                                        <th class="px-6 py-4 text-center">Gambar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($customPenawaran->items as $index => $item)
                                        <tr class="group transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                            <td class="px-6 py-5">
                                                <div class="flex flex-col space-y-1">
                                                    <span class="font-bold leading-tight text-gray-900 transition-colors group-hover:text-[#225A97] dark:text-white">
                                                        {{ $item->nama_barang }}
                                                    </span>
                                                    @if ($item->keterangan)
                                                        <span class="text-[10px] font-medium italic text-gray-500">Note: {{ $item->keterangan }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                @php $dk = $item->diskon ?? 0; @endphp
                                                @if ($dk > 0)
                                                    <div class="flex flex-col items-center">
                                                        <span class="{{ $dk > 20 ? 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20' : 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' }} inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-tighter">
                                                            {{ $dk }}%
                                                        </span>
                                                        @if ($dk > 20)
                                                            <span class="mt-1 text-[8px] font-black uppercase italic tracking-[0.1em] text-rose-500">Need Approval</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-gray-900 dark:text-white">{{ $item->qty }}</span>
                                                    <span class="text-[10px] font-bold uppercase tracking-tighter text-gray-400">{{ $item->satuan }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-5">
                                                <div class="flex items-baseline font-medium text-gray-700 dark:text-gray-300">
                                                    <span class="mr-1 text-[10px] font-bold text-gray-400">Rp</span>
                                                    <span class="text-sm">{{ number_format($item->harga, 0, ',', '.') }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-5">
                                                @php
                                                    $totalSetelahDiskon = $item->qty * $item->harga * (1 - ($item->diskon ?? 0) / 100);
                                                @endphp
                                                <div class="flex items-baseline font-black text-[#225A97] dark:text-blue-400">
                                                    <span class="mr-1 text-[10px] font-bold opacity-60">Rp</span>
                                                    <span class="text-sm tracking-tight">{{ number_format($totalSetelahDiskon, 0, ',', '.') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                @if ($item->images && count($item->images) > 0)
                                                    <div class="flex items-center justify-center -space-x-2 overflow-hidden">
                                                        @foreach (array_slice($item->images, 0, 3) as $image)
                                                            @php
                                                                if (is_null($image) || $image === '') {
                                                                    $imgUrl = null;
                                                                } elseif (str_starts_with($image, 'http')) {
                                                                    $imgUrl = $image;
                                                                } else {
                                                                    $imgUrl = asset('storage/' . ltrim($image, 'public/'));
                                                                }
                                                            @endphp
                                                            @if ($imgUrl)
                                                                <button type="button" class="custom-penawaran-thumb inline-block transition-transform hover:scale-110 active:scale-95" data-full="{{ $imgUrl }}">
                                                                    <img class="inline-block h-10 w-10 cursor-zoom-in rounded-lg object-cover shadow-sm ring-2 ring-white dark:ring-gray-800" src="{{ $imgUrl }}" alt="Item image">
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                        @if (count($item->images) > 3)
                                                            <span class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-[10px] font-black text-gray-500 ring-2 ring-white dark:bg-gray-700 dark:text-gray-400 dark:ring-gray-800">+{{ count($item->images) - 3 }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-[10px] font-bold uppercase text-gray-300 dark:text-gray-600">No Image</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-16 text-center">
                                                <div class="flex flex-col items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-12 w-12 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                    <p class="text-sm font-medium italic text-gray-400">Belum ada item barang yang ditambahkan.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-50/80 dark:bg-gray-700/50">
                                    <tr class="border-t border-gray-100 dark:border-gray-700">
                                        <td colspan="4" class="px-6 py-5 text-right">
                                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Total Produk Sub-Total</span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-5">
                                            <div class="flex items-baseline font-black text-[#225A97] dark:text-blue-400">
                                                <span class="mr-1 text-xs opacity-70">Rp</span>
                                                <span class="text-xl tracking-tighter">{{ number_format($customPenawaran->subtotal, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Summary -->
                <div class="gap-2 lg:col-span-1">
                    <div class="sticky top-5 space-y-6">
                        <!-- Action Card -->
                        <!-- Panel Aksi -->
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
                                <div class="flex flex-col gap-4">
                                    {{-- Approval Actions for Supervisor --}}
                                    @if (in_array($customPenawaran->status, ['sent', 'pending_approval']) && auth()->user()->role === 'Supervisor')
                                        <div class="space-y-3 border-b border-gray-100 pb-4 dark:border-gray-700">
                                            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400">Persetujuan Supervisor</h3>
                                            <div class="grid grid-cols-2 gap-3">
                                                <form action="{{ route('admin.custom-penawaran.approval', $customPenawaran) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" name="action" value="approve" class="flex w-full items-center justify-center space-x-2 rounded-xl bg-emerald-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-100 transition-all hover:bg-emerald-700 hover:shadow-none dark:shadow-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Approve</span>
                                                    </button>
                                                </form>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#rejectReasonModal" class="flex w-full items-center justify-center space-x-2 rounded-xl bg-rose-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-rose-100 transition-all hover:bg-rose-700 hover:shadow-none dark:shadow-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span>Reject</span>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Reject Reason Modal --}}
                                        <div class="modal fade" id="rejectReasonModal" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content overflow-hidden rounded-2xl border-none">
                                                    <form action="{{ route('admin.custom-penawaran.approval', $customPenawaran) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="action" value="reject">
                                                        <div class="bg-gradient-to-r from-rose-600 to-rose-800 p-4 text-white">
                                                            <h5 class="flex items-center font-bold">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                                Konfirmasi Penolakan
                                                            </h5>
                                                        </div>
                                                        <div class="bg-white p-6 dark:bg-gray-800">
                                                            <div class="space-y-4">
                                                                <label for="reason" class="text-xs font-bold uppercase text-gray-400">Alasan Penolakan (WAJIB)</label>
                                                                <textarea name="reason" id="reason" rows="4" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" placeholder="Berikan alasan mengapa penawaran ini ditolak..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-end space-x-3 bg-gray-50 p-4 dark:bg-gray-900/50">
                                                            <button type="button" class="px-4 py-2 text-xs font-bold uppercase text-gray-500 hover:text-gray-700" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="rounded-xl bg-rose-600 px-6 py-2 text-xs font-bold text-white shadow-lg shadow-rose-100 transition-all hover:bg-rose-700">Tolak Penawaran</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Standard Actions --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        {{-- Edit --}}
                                        <a href="{{ route('sales.custom-penawaran.edit', $customPenawaran->id) }}" class="flex items-center justify-center space-x-2 rounded-xl bg-[#F59E0B] py-2.5 text-xs font-bold text-white shadow-lg shadow-amber-100 transition-all hover:bg-amber-600 hover:shadow-none dark:shadow-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        {{-- PDF --}}
                                        @php
                                            $isExpired = $customPenawaran->isExpired();
                                            $canDownload = in_array($customPenawaran->status, ['open', 'approved']) && !$isExpired;
                                        @endphp
                                        @if ($canDownload)
                                            <a href="{{ route('sales.custom-penawaran.pdf', $customPenawaran->id) }}" target="_blank" class="flex items-center justify-center space-x-2 rounded-xl bg-[#10B981] py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-100 transition-all hover:bg-emerald-600 hover:shadow-none dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span>Dokumen PDF</span>
                                            </a>
                                        @else
                                            <button disabled class="flex cursor-not-allowed items-center justify-center space-x-2 rounded-xl bg-gray-200 py-2.5 text-xs font-bold text-gray-400 opacity-60 dark:bg-gray-700" title="{{ $isExpired ? 'Penawaran sudah kadaluarsa' : 'Menunggu persetujuan Supervisor' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                <span>PDF Locked</span>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Primary Warehouse Action --}}
                                    @if (in_array($customPenawaran->status, ['open', 'approved']))
                                        <form action="{{ route('sales.custom-penawaran.sent-to-warehouse', $customPenawaran->id) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center justify-center space-x-3 rounded-xl bg-[#225A97] py-3 text-sm font-black text-white shadow-xl shadow-blue-100 transition-all hover:bg-[#1a4675] hover:shadow-none active:scale-[0.98] dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                                </svg>
                                                <span class="uppercase tracking-widest">Kirim ke Warehouse</span>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Dangerous Actions --}}
                                    <div class="mt-2 border-t border-gray-50 pt-4 dark:border-gray-700/50">
                                        <form id="deleteCustomPenawaranForm" action="{{ route('sales.custom-penawaran.destroy', $customPenawaran->id) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" id="btnDeleteCustomPenawaran" class="flex w-full items-center justify-center space-x-2 rounded-xl bg-rose-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-rose-200 transition-all hover:bg-rose-700 hover:shadow-none active:scale-[0.98] dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span class="uppercase tracking-widest">Hapus Penawaran Kustom</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                <h2 class="flex items-center font-semibold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Ringkasan Penawaran
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Subtotal -->
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium text-gray-500 dark:text-gray-400">Sub-Total Penawaran</span>
                                        <span class="font-bold text-gray-900 dark:text-white">
                                            <span class="mr-0.5 text-[10px] text-gray-400">Rp</span>{{ number_format($customPenawaran->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Tax/PPN -->
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center space-x-1">
                                            <span class="font-medium text-gray-500 dark:text-gray-400">Pajak / PPN</span>
                                            <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-bold text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                                {{ $customPenawaran->tax > 0 ? 'ESTIMATED' : '0%' }}
                                            </span>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white">
                                            <span class="mr-0.5 text-[10px] text-gray-400">Rp</span>{{ number_format($customPenawaran->tax, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Divider -->
                                    <div class="my-2 border-t border-dashed border-gray-200 dark:border-gray-700"></div>

                                    <!-- Grand Total -->
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Keseluruhan</span>
                                        <div class="flex items-baseline justify-between">
                                            <span class="align-top text-xs font-bold text-[#225A97] dark:text-blue-400">Rp</span>
                                            <span class="text-3xl font-black tracking-tighter text-[#225A97] dark:text-blue-400">
                                                {{ number_format($customPenawaran->grand_total, 0, ',', '.') }}
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
                                        <span class="font-bold text-gray-600 dark:text-gray-400">{{ $customPenawaran->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center text-[11px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span class="mr-auto font-medium uppercase tracking-tighter text-gray-400">Diperbarui</span>
                                        <span class="font-bold text-gray-600 dark:text-gray-400">{{ $customPenawaran->updated_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
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

            // SweetAlert for Delete
            const btnDelete = document.getElementById('btnDeleteCustomPenawaran');
            if (btnDelete) {
                btnDelete.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Hapus Penawaran Kustom?',
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
                            document.getElementById('deleteCustomPenawaranForm').submit();
                        }
                    });
                });
            }
        })();
    </script>
</x-app-layout>
