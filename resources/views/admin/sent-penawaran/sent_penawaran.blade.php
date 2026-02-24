<x-app-layout>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">

        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 selectCol"></th>
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
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3">{{ $loop->iteration + ($penawarans->currentPage() - 1) * $penawarans->perPage() }}</td>
                            @if (isset($penawaran->offer_type) && $penawaran->offer_type === 'custom')
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
                                @php
                                    $status = $penawaran->order?->status ?? $penawaran->status;
                                    $label = $penawaran->status_label ?? ucfirst($status);
                                @endphp
                                <td class="px-4 py-3">
                                    @if($status === 'sent_to_supervisor')
                                        <span class="badge bg-yellow-100 text-yellow-800">{{ $label }}</span>
                                    @elseif($status === 'open')
                                        <span class="badge bg-green-100 text-green-800">{{ $label }}</span>
                                    @elseif($status === 'rejected_supervisor')
                                        <span class="badge bg-red-100 text-red-800">{{ $label }}</span>
                                    @else
                                        <span class="badge bg-blue-50 text-blue-700">{{ $label }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.request-order.show', $penawaran->id) }}" class="btn btn-info btn-sm" style="margin-right:4px;">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>

                                    {{-- Tombol Terima --}}
                                    <form action="{{ route('supervisor.request-order.approve', $penawaran->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" style="margin-right:4px;">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                    </form>

                                    <button type="button"
                                        class="btn btn-danger btn-sm btn-tolak"
                                        data-id="{{ $penawaran->id }}">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                    <form id="formTolak{{ $penawaran->id }}"
                                        action="{{ route('supervisor.request-order.reject', $penawaran->id) }}"
                                        method="POST"
                                        style="display:none">
                                        @csrf
                                        <input type="hidden" name="reason" id="reasonInput{{ $penawaran->id }}">
                                    </form>
                                </td>
                                        </div>
                                    </div>
                                </td>
                            @else
                                {{-- RequestOrder type --}}
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
                                <td class="px-4 py-3">{{ ucfirst($penawaran->status) }}</td>
                                <td>
                                    {{-- Tombol Terima --}}
                                    <form action="{{ route('supervisor.request-order.approve', $penawaran->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                    </form>

                                    {{-- Tombol Tolak --}}
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTolak{{ $penawaran->id }}">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>

                                    {{-- Modal Alasan Penolakan --}}
                                    <div class="modal fade" id="modalTolak{{ $penawaran->id }}" tabindex="-1" aria-labelledby="modalTolakLabel{{ $penawaran->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="modalTolakLabel{{ $penawaran->id }}">
                                                        Alasan Penolakan - {{ $penawaran->request_number ?? $penawaran->penawaran_number }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('supervisor.request-order.reject', $penawaran->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">
                                                                Alasan Penolakan <span class="text-danger">*</span>
                                                            </label>
                                                            <textarea name="reason" class="form-control" rows="4" required placeholder="Tulis alasan penolakan..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-times"></i> Konfirmasi Tolak
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        <!-- Form Tolak hidden sudah di atas, tidak perlu modal -->
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $penawarans->links() }}
    </div>




<script>
// Fallback: force open modal Bootstrap jika event gagal
$(document).on('click', '.btn-danger[data-bs-toggle="modal"]', function(e) {
    var target = $(this).attr('data-bs-target');
    if (target) {
        var modal = $(target);
        if (modal.length) {
            var bsModal = new bootstrap.Modal(modal[0]);
            bsModal.show();
        }
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-tolak').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var alasan = prompt('Masukkan alasan penolakan:', '');
            if (alasan !== null && alasan.trim() !== '') {
                document.getElementById('reasonInput' + id).value = alasan;
                document.getElementById('formTolak' + id).submit();
            } else if (alasan !== null) {
                alert('Alasan penolakan wajib diisi!');
            }
        });
    });
});
</script>
</x-app-layout>
