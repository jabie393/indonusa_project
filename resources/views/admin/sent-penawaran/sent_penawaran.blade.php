<x-app-layout>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">

        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <h2 class="font-semibold text-white">Penawaran Menunggu Persetujuan Supervisor</h2>
            @if (session('success'))
                <div class="rounded bg-green-100 px-4 py-2 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded bg-red-100 px-4 py-2 text-sm text-red-800">{{ session('error') }}</div>
            @endif
        </div>

        {{-- Tampilkan error validasi jika ada --}}
        @if ($errors->any())
            <div class="m-4 rounded bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 selectCol">
                            <input type="checkbox" id="select-all-checkbox" />
                        </th>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">No. Penawaran</th>
                        <th class="px-4 py-3">Sales</th>
                        <th class="px-4 py-3">To</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Diskon (%)</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Sent At</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penawarans as $penawaran)
                        <tr>
                            <td class="px-4 py-3">
                                <input type="checkbox" class="row-checkbox" value="{{ $penawaran->id }}" />
                            </td>
                            <td class="px-4 py-3">{{ $loop->iteration + ($penawarans->currentPage() - 1) * $penawarans->perPage() }}</td>

                            @if (isset($penawaran->offer_type) && $penawaran->offer_type === 'custom')
                                {{-- ===== CUSTOM PENAWARAN ===== --}}
                                <td class="px-4 py-3">{{ $penawaran->penawaran_number }}</td>
                                <td class="px-4 py-3">{{ optional($penawaran->sales)->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $penawaran->to }}</td>
                                <td class="px-4 py-3">{{ $penawaran->items->count() }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $maxDiskon = $penawaran->items->max('diskon') ?? 0;
                                        $itemsWithHighDiscount = $penawaran->items->where('diskon', '>', 20);
                                    @endphp
                                    {{ $maxDiskon }}%
                                    @if ($maxDiskon > 20)
                                        <span class="ml-2 rounded bg-red-100 px-2 py-1 text-xs text-red-800">Perlu Approval</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($itemsWithHighDiscount->isNotEmpty())
                                        <div class="space-y-1 text-xs">
                                            @foreach ($itemsWithHighDiscount as $item)
                                                <div class="rounded border border-yellow-200 bg-yellow-50 p-2">
                                                    <p class="font-semibold text-gray-800">{{ $item->nama_barang }}</p>
                                                    <p class="text-gray-700">Diskon: {{ $item->diskon }}%</p>
                                                    @if (!empty($item->keterangan))
                                                        <p class="mt-1 text-gray-600">{{ $item->keterangan }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $penawaran->created_at ? $penawaran->created_at->format('Y-m-d H:i') : '-' }}</td>
                                <td class="px-4 py-3">{{ ucfirst($penawaran->status) }}</td>
                                <td class="px-4 py-3">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.custom-penawaran.show', $penawaran->id) }}"
                                       class="mb-1 inline-flex items-center gap-1 rounded bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    {{-- Tombol Terima --}}
                                    <form action="{{ route('supervisor.request-order.approve', $penawaran->id) }}" method="POST" class="mb-1 inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded bg-green-600 px-3 py-1.5 text-xs text-white hover:bg-green-700">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                    </form>
                                    {{-- Tombol Tolak (trigger modal) --}}
                                    <button type="button"
                                        class="inline-flex items-center gap-1 rounded bg-red-600 px-3 py-1.5 text-xs text-white hover:bg-red-700"
                                        onclick="openTolakModal('custom', '{{ $penawaran->id }}', '{{ $penawaran->penawaran_number ?? $penawaran->request_number }}')">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </td>

                            @else
                                {{-- ===== REQUEST ORDER ===== --}}
                                <td class="px-4 py-3">{{ $penawaran->request_number }}</td>
                                <td class="px-4 py-3">{{ optional($penawaran->sales)->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $penawaran->customer_name }}</td>
                                <td class="px-4 py-3">{{ $penawaran->items->count() }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $maxDiskon = $penawaran->items->max('diskon_percent') ?? 0;
                                        $itemsWithHighDiscount = $penawaran->items->where('diskon_percent', '>', 20);
                                    @endphp
                                    {{ $maxDiskon }}%
                                    @if ($maxDiskon > 20)
                                        <span class="ml-2 rounded bg-red-100 px-2 py-1 text-xs text-red-800">Perlu Approval</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($itemsWithHighDiscount->isNotEmpty())
                                        <div class="space-y-1 text-xs">
                                            @foreach ($itemsWithHighDiscount as $item)
                                                <div class="rounded border border-yellow-200 bg-yellow-50 p-2">
                                                    <p class="font-semibold text-gray-800">{{ optional($item->barang)->nama_barang ?? 'N/A' }}</p>
                                                    <p class="text-gray-700">Diskon: {{ $item->diskon_percent }}%</p>
                                                    @if (!empty($item->keterangan))
                                                        <p class="mt-1 text-gray-600">{{ $item->keterangan }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $penawaran->created_at ? $penawaran->created_at->format('Y-m-d H:i') : '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $status = $penawaran->order?->status ?? $penawaran->status;
                                        $badgeClass = match($status) {
                                            'sent_to_supervisor'   => 'bg-yellow-100 text-yellow-800',
                                            'open'                 => 'bg-green-100 text-green-800',
                                            'approved_supervisor'  => 'bg-green-100 text-green-800',
                                            'rejected_supervisor'  => 'bg-red-100 text-red-800',
                                            default                => 'bg-blue-50 text-blue-700',
                                        };
                                    @endphp
                                    <span class="rounded px-2 py-0.5 text-xs {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    {{-- Tombol Terima --}}
                                    <form action="{{ route('supervisor.request-order.approve', $penawaran->id) }}" method="POST" class="mb-1 inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded bg-green-600 px-3 py-1.5 text-xs text-white hover:bg-green-700">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                    </form>
                                    {{-- Tombol Tolak (trigger modal) --}}
                                    <button type="button"
                                        class="inline-flex items-center gap-1 rounded bg-red-600 px-3 py-1.5 text-xs text-white hover:bg-red-700"
                                        onclick="openTolakModal('request_order', '{{ $penawaran->id }}', '{{ $penawaran->request_number }}')">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3 text-center text-gray-400">Tidak ada penawaran yang perlu disetujui.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $penawarans->links() }}
    </div>

    {{-- ===================================================
         MODAL TOLAK - Satu modal dipakai untuk semua baris
         ===================================================  --}}
    <div id="modalTolakGlobal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50"
         role="dialog" aria-modal="true" aria-labelledby="modalTolakTitle">
        <div class="w-full max-w-md rounded-xl bg-white shadow-2xl dark:bg-gray-800">
            {{-- Header --}}
            <div class="flex items-center justify-between rounded-t-xl bg-red-600 px-5 py-3">
                <h5 id="modalTolakTitle" class="font-semibold text-white">
                    Tolak Penawaran — <span id="modalTolakNomor" class="font-normal"></span>
                </h5>
                <button type="button" onclick="closeTolakModal()"
                    class="text-white opacity-80 hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
            {{-- Body --}}
            <form id="formTolakGlobal" method="POST" action="">
                @csrf
                <div class="px-5 py-4">
                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="modalTolakReason"
                        name="reason"
                        rows="4"
                        required
                        minlength="5"
                        placeholder="Tulis alasan penolakan yang jelas agar sales dapat memahami dan memperbaiki..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                    <p id="modalTolakError" class="mt-1 hidden text-xs text-red-600">Alasan penolakan wajib diisi minimal 5 karakter.</p>
                </div>
                <div class="flex justify-end gap-2 rounded-b-xl bg-gray-50 px-5 py-3 dark:bg-gray-700">
                    <button type="button" onclick="closeTolakModal()"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="button" onclick="submitTolakModal()"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        <i class="fas fa-times"></i> Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
/**
 * Buka modal tolak.
 * @param {string} type  - 'request_order' atau 'custom'
 * @param {string} id    - ID record
 * @param {string} nomor - Nomor penawaran / request number (untuk label)
 */
function openTolakModal(type, id, nomor) {
    // Tentukan action URL berdasarkan tipe
    let actionUrl = '';
    if (type === 'request_order') {
        // route: POST /request-order/{id}/reject  → supervisor.request-order.reject
        actionUrl = '/request-order/' + id + '/reject';
    } else if (type === 'custom') {
        // route: POST /supervisor/custom-penawaran/{id}/approval → admin.custom-penawaran.approval
        // (kirim via hidden field atau sesuaikan dengan route yang ada)
        actionUrl = '/request-order/' + id + '/reject';
    }

    document.getElementById('formTolakGlobal').action = actionUrl;
    document.getElementById('modalTolakNomor').textContent = nomor;
    document.getElementById('modalTolakReason').value = '';
    document.getElementById('modalTolakError').classList.add('hidden');
    document.getElementById('modalTolakGlobal').classList.remove('hidden');
    document.getElementById('modalTolakGlobal').classList.add('flex');
    document.getElementById('modalTolakReason').focus();
}

function closeTolakModal() {
    document.getElementById('modalTolakGlobal').classList.add('hidden');
    document.getElementById('modalTolakGlobal').classList.remove('flex');
}

function submitTolakModal() {
    const reason = document.getElementById('modalTolakReason').value.trim();
    if (reason.length < 5) {
        document.getElementById('modalTolakError').classList.remove('hidden');
        document.getElementById('modalTolakReason').focus();
        return;
    }
    document.getElementById('modalTolakError').classList.add('hidden');
    document.getElementById('formTolakGlobal').submit();
}

// Tutup modal jika klik di luar area modal
document.getElementById('modalTolakGlobal').addEventListener('click', function(e) {
    if (e.target === this) closeTolakModal();
});

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeTolakModal();
});
</script>

</x-app-layout>