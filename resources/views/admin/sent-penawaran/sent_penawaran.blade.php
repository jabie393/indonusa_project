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
                        <th class="px-4 py-3"></th>
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
                                <td class="w-fit px-4 py-3 text-right">
                                    <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                        <div class="pointer-events-none invisible h-9 w-32 opacity-0">Placeholder</div>
                                        <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                            @if (auth()->user()->role === 'Supervisor')
                                                <a href="{{ route('admin.custom-penawaran.show', $penawaran->id) }}" class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Lihat</span>
                                                </a>
                                            @else
                                                <a href="{{ route('sales.custom-penawaran.show', $penawaran->id) }}" class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Lihat</span>
                                                </a>
                                            @endif

                                            @if (auth()->user()->role === 'Supervisor' && $penawaran->status === 'sent')
                                                <form action="{{ route('admin.custom-penawaran.approval', $penawaran) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button class="group flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" title="Setujui">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-4 w-4">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                        <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Setujui</span>
                                                    </button>
                                                </form>
                                                <button type="button" class="group flex h-full cursor-pointer items-center justify-center bg-red-600 p-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="submitReject('{{ route('admin.custom-penawaran.approval', $penawaran) }}', { action: 'reject' })" title="Tolak">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle h-4 w-4">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <path d="m15 9-6 6"></path>
                                                        <path d="m9 9 6 6"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Tolak</span>
                                                </button>
                                            @endif
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
                                <td class="w-fit px-4 py-3 text-right">
                                    <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                        <div class="pointer-events-none invisible h-9 w-32 opacity-0">Placeholder</div>
                                        <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                            @if (auth()->user()->role === 'Supervisor')
                                                <a href="{{ route('admin.request-order.show', $penawaran->id) }}" class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Lihat</span>
                                                </a>
                                            @else
                                                <a href="{{ route('sales.request-order.show', $penawaran->id) }}" class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Lihat</span>
                                                </a>
                                            @endif

                                            @if (auth()->user()->role === 'Supervisor' && $penawaran->status === 'pending_approval')
                                                <form action="{{ route('supervisor.request-order.approve', $penawaran->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="group flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" title="Setujui">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-4 w-4">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                        <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Setujui</span>
                                                    </button>
                                                </form>
                                                <button type="button" class="group flex h-full cursor-pointer items-center justify-center bg-red-600 p-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="submitReject('{{ route('supervisor.request-order.reject', $penawaran->id) }}')" title="Tolak">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle h-4 w-4">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <path d="m15 9-6 6"></path>
                                                        <path d="m9 9 6 6"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Tolak</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
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
