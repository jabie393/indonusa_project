<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white"> Sent Penawaran (Waiting Approval)</h2>
            </div>

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
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">No. Penawaran</th>
                        <th class="px-4 py-3">Sales</th>
                        <th class="px-4 py-3">To</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Diskon (%)</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Sent At</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penawarans as $penawaran)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration + ($penawarans->currentPage() - 1) * $penawarans->perPage() }}</td>
                            @if(isset($penawaran->offer_type) && $penawaran->offer_type === 'custom')
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
                                    @if($maxDiskon > 20)
                                        <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Perlu Approval</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($itemsWithHighDiscount->isNotEmpty())
                                        <div class="text-xs space-y-1">
                                            @foreach($itemsWithHighDiscount as $item)
                                                <div class="bg-yellow-50 border border-yellow-200 rounded p-2">
                                                    <p class="font-semibold text-gray-800">{{ $item->nama_barang }}</p>
                                                    <p class="text-gray-700">Diskon: {{ $item->diskon }}%</p>
                                                    @if(!empty($item->keterangan))
                                                        <p class="text-gray-600 mt-1">{{ $item->keterangan }}</p>
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
                                    @if(auth()->user()->role === 'Supervisor')
                                        <a href="{{ route('admin.custom-penawaran.show', $penawaran->id) }}" class="px-2 py-1 bg-gray-200 rounded mr-2">Lihat</a>
                                    @else
                                        <a href="{{ route('sales.custom-penawaran.show', $penawaran->id) }}" class="px-2 py-1 bg-gray-200 rounded mr-2">Lihat</a>
                                    @endif

                                    @if(auth()->user()->role === 'Supervisor' && $penawaran->status === 'sent')
                                        <form action="{{ route('admin.custom-penawaran.approval', $penawaran) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button class="px-2 py-1 bg-green-600 text-white rounded mr-1">Setujui</button>
                                        </form>
                                        <button type="button" class="px-2 py-1 bg-red-600 text-white rounded" onclick="submitReject('{{ route('admin.custom-penawaran.approval', $penawaran) }}', { action: 'reject' })">Tolak</button>
                                    @endif
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
                                    @if($maxDiskon > 20)
                                        <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Perlu Approval</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($itemsWithHighDiscount->isNotEmpty())
                                        <div class="text-xs space-y-1">
                                            @foreach($itemsWithHighDiscount as $item)
                                                <div class="bg-yellow-50 border border-yellow-200 rounded p-2">
                                                    <p class="font-semibold text-gray-800">{{ optional($item->barang)->nama_barang ?? 'N/A' }}</p>
                                                    <p class="text-gray-700">Diskon: {{ $item->diskon_percent }}%</p>
                                                    @if(!empty($item->keterangan))
                                                        <p class="text-gray-600 mt-1">{{ $item->keterangan }}</p>
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
                                    @if(auth()->user()->role === 'Supervisor')
                                        <a href="{{ route('admin.request-order.show', $penawaran->id) }}" class="px-2 py-1 bg-gray-200 rounded mr-2">Lihat</a>
                                    @else
                                        <a href="{{ route('sales.request-order.show', $penawaran->id) }}" class="px-2 py-1 bg-gray-200 rounded mr-2">Lihat</a>
                                    @endif

                                    @if(auth()->user()->role === 'Supervisor' && $penawaran->status === 'pending_approval')
                                        <form action="{{ route('supervisor.request-order.approve', $penawaran->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="px-2 py-1 bg-green-600 text-white rounded mr-1">Setujui</button>
                                        </form>
                                        <button type="button" class="px-2 py-1 bg-red-600 text-white rounded" onclick="submitReject('{{ route('supervisor.request-order.reject', $penawaran->id) }}')">Tolak</button>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $penawarans->links() }}
    </div>

    <script>
        function submitReject(actionUrl, extra = {}) {
            try {
                var reason = prompt('Masukkan alasan penolakan:');
                if (reason === null) return; // cancelled
                reason = reason.trim();
                if (!reason) {
                    alert('Alasan penolakan diperlukan.');
                    return;
                }

                // create form dynamically and submit
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                form.style.display = 'none';

                // CSRF token from meta tag (Laravel includes this in layout)
                var token = document.querySelector('meta[name="csrf-token"]');
                if (token) {
                    var inputToken = document.createElement('input');
                    inputToken.type = 'hidden';
                    inputToken.name = '_token';
                    inputToken.value = token.getAttribute('content');
                    form.appendChild(inputToken);
                }

                // reason
                var inReason = document.createElement('input');
                inReason.type = 'hidden';
                inReason.name = 'reason';
                inReason.value = reason;
                form.appendChild(inReason);

                // extra fields (like action=reject)
                for (var k in extra) {
                    if (!extra.hasOwnProperty(k)) continue;
                    var ei = document.createElement('input');
                    ei.type = 'hidden';
                    ei.name = k;
                    ei.value = extra[k];
                    form.appendChild(ei);
                }

                document.body.appendChild(form);
                form.submit();
            } catch (e) {
                console.error('submitReject error', e);
                alert('Terjadi kesalahan saat mengirim penolakan.');
            }
        }
    </script>

</x-app-layout>