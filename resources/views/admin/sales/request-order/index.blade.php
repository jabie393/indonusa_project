<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
            <div>
                <h2 class="mr-3 font-semibold text-white">Request Order</h2>
            </div>
            <div class="flex w-full flex-col py-5 md:w-auto md:flex-row md:py-0">
                <div class="mr-5 flex max-w-full shrink-0 flex-col items-stretch justify-end space-y-2 py-5 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0 md:py-0">
                    {{-- Search --}}
                    <form action="{{ route('sales.request-order.index') }}" method="GET" class="block pl-2">
                        <label for="topbar-search" class="sr-only">Search</label>
                        <div class="relative md:w-64 md:w-96">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable" value="{{ request('search') }}"
                                class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                placeholder="Search" />
                        </div>
                    </form>
                </div>
                <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                    {{-- Tambah barang modal --}}
                    <a href="{{ route('sales.request-order.create') }}"
                        class="rounded-lg flex flex-row items-center justify-center bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
                        <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Request Order Baru
                    </a>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">No. Request</th>
                        <th scope="col" class="px-4 py-3">No. Penawaran</th>
                        <th scope="col" class="px-4 py-3">Tanggal</th>
                        <th scope="col" class="px-4 py-3">Nama Customer</th>
                        <th scope="col" class="px-4 py-3">Jumlah Item</th>
                        <th scope="col" class="px-4 py-3">Total</th>
                                <th scope="col" class="px-4 py-3">Diskon</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3">Berlaku Sampai</th>
                        <th scope="col" class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requestOrders as $ro)
                        @php
                            $total = $ro->items->sum('subtotal');
                            $statusClass = match ($ro->status) {
                                'open', 'converted' => 'bg-blue-50 text-blue-700 inset-ring inset-ring-blue-700',
                                'pending' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                'approved' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                'rejected' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-700',
                                'expired' => 'bg-gray-50 text-gray-700 inset-ring inset-ring-gray-700',
                                default => 'secondary',
                            };
                            $statusLabel = match ($ro->status) {
                                'open', 'converted' => 'Open',
                                'expired' => 'Kadaluarsa',
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                default => ucfirst($ro->status),
                            };
                        @endphp
                        <tr class="max-h-16 dark:border-gray-700">
                            <td class="px-4 py-3">{{ $ro->request_number }}</td>
                            <td class="px-4 py-3"><span class="badge inset-ring inset-ring-indigo-700 h-fit bg-indigo-50 text-indigo-700">{{ $ro->nomor_penawaran ?? '-' }}</span></td>
                            <td class="px-4 py-3">{{ $ro->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $ro->customer_name }}</td>
                            <td class="px-4 py-3">{{ $ro->items->count() }} item(s)</td>
                            <td class="px-4 py-3">Rp {{ number_format($total, 2, ',', '.') }}</td>
                            @php
                                // Collect discounts per item, group by percentage and count occurrences
                                $discountCounts = $ro->items->pluck('diskon_percent')->map(function($d){ return $d === null ? 0 : $d; })->groupBy(function($d){ return $d; })->map(function($g, $k){ return ['percent' => $k, 'count' => $g->count()]; })->values();
                            @endphp
                            <td class="px-4 py-3">
                                @if($discountCounts->isEmpty())
                                    <span class="badge bg-gray-50 text-gray-700">0%</span>
                                @else
                                    @php $displayed = false; @endphp
                                    @foreach($discountCounts as $dc)
                                        @if((float)$dc['percent'] > 0)
                                            <span class="badge bg-green-50 text-green-700 mr-1">{{ $dc['percent'] }}%{{ $dc['count'] > 1 ? ' x'.$dc['count'] : '' }}</span>
                                            @php $displayed = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(!$displayed)
                                        <span class="badge bg-gray-50 text-gray-700">0%</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($ro->expired_at)
                                    {{ $ro->expired_at_formatted }}
                                    <br>
                                    <small>
                                        @if ($ro->isExpired())
                                            <span class="badge bg-danger">KADALUARSA</span>
                                        @else
                                            @php
                                                try {
                                                    $expiry = is_string($ro->expired_at) ? \Carbon\Carbon::parse($ro->expired_at) : $ro->expired_at;
                                                    $daysLeft = $expiry->diffInDays(now());
                                                } catch (\Throwable $e) {
                                                    $daysLeft = null;
                                                }
                                            @endphp
                                            <span class="text-success">
                                                @if($daysLeft && $daysLeft > 0)
                                                    {{ $daysLeft }} hari dari sekarang
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        @endif
                                    </small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="flex h-full items-center gap-2 px-4 py-3">
                                    <a href="{{ route('sales.request-order.show', $ro->id) }}"
                                        class="btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        title="Lihat Detail">
                                        Detail
                                    </a>
                                    @php
                                        $canDownload = false;
                                        if (auth()->check() && in_array(auth()->user()->role, ['Supervisor', 'Admin'])) {
                                            $canDownload = true;
                                        } elseif (auth()->check() && auth()->id() === $ro->sales_id) {
                                            $canDownload = !in_array($ro->status, ['pending_approval', 'rejected']);
                                        }
                                    @endphp
                                    @if($canDownload)
                                        <a href="{{ route('sales.request-order.pdf', $ro->id) }}"
                                            class="btn mb-2 me-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                            title="Download PDF" target="_blank">
                                            PDF
                                        </a>
                                    @else
                                        <button type="button" class="btn mb-2 me-2 rounded-lg bg-gray-400 px-5 py-2.5 text-sm font-medium text-white" disabled title="PDF tidak tersedia sampai Supervisor menyetujui penawaran">
                                            PDF
                                        </button>
                                    @endif
                                    @if ($ro->status === 'pending')
                                        <a href="{{ route('sales.request-order.edit', $ro->id) }}"
                                            class="btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            title="Edit">
                                            Edit
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $requestOrders->firstItem() ?? 0 }}-{{ $requestOrders->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $requestOrders->total() ?? $requestOrders->count() }}</span>
                </span>
                <form method="GET" action="{{ route('sales.request-order.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()" class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
            </div>
            <div>
                {{ $requestOrders->links() }}
            </div>
        </nav>
    </div>
</x-app-layout>
