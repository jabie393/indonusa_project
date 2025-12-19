<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
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
                            <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable" value="{{ request('search') }}" class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Search" />
                        </div>
                    </form>
                </div>
                <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                    {{-- Tambah barang modal --}}
                    <a href="{{ route('sales.request-order.create') }}" class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
                        <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Request Order Baru
                    </a>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">No. Request</th>
                        <th scope="col" class="px-4 py-3">No. Penawaran</th>
                        <th scope="col" class="px-4 py-3">No. Sales Order</th>
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
                                'sent_to_warehouse' => 'bg-gray-50 text-gray-700 inset-ring inset-ring-gray-700',
                                default => 'secondary',
                            };
                            $statusLabel = match ($ro->status) {
                                'open', 'converted' => 'Open',
                                'expired' => 'Kadaluarsa',
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'sent_to_warehouse' => 'Dikirim ke Gudang',
                                default => ucfirst($ro->status),
                            };
                        @endphp
                        <tr class="max-h-16 dark:border-gray-700">
                            <td class="px-4 py-3 text-nowrap">{{ $ro->request_number }}</td>
                            <td class="px-4 py-3"><span class="badge inset-ring inset-ring-indigo-700 h-fit bg-indigo-50 text-indigo-700">{{ $ro->nomor_penawaran ?? '-' }}</span></td>
                            <td class="px-4 py-3">{{ $ro->sales_order_number ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $ro->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $ro->customer_name }}</td>
                            <td class="px-4 py-3">{{ $ro->items->count() }} item(s)</td>
                            <td class="px-4 py-3">Rp {{ number_format($total, 2, ',', '.') }}</td>
                            @php
                                // Collect discounts per item, group by percentage and count occurrences
                                $discountCounts = $ro->items
                                    ->pluck('diskon_percent')
                                    ->map(function ($d) {
                                        return $d === null ? 0 : $d;
                                    })
                                    ->groupBy(function ($d) {
                                        return $d;
                                    })
                                    ->map(function ($g, $k) {
                                        return ['percent' => $k, 'count' => $g->count()];
                                    })
                                    ->values();
                            @endphp
                            <td class="px-4 py-3">
                                @if ($discountCounts->isEmpty())
                                    <span class="badge bg-gray-50 text-gray-700">0%</span>
                                @else
                                    @php $displayed = false; @endphp
                                    @foreach ($discountCounts as $dc)
                                        @if ((float) $dc['percent'] > 0)
                                            <span class="badge mr-1 bg-green-50 text-green-700">{{ $dc['percent'] }}%{{ $dc['count'] > 1 ? ' x' . $dc['count'] : '' }}</span>
                                            @php $displayed = true; @endphp
                                        @endif
                                    @endforeach
                                    @if (!$displayed)
                                        <span class="badge bg-gray-50 text-gray-700">0%</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge text-nowrap {{ $statusClass }}">{{ $statusLabel }}</span>
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
                                                @if ($daysLeft && $daysLeft > 0)
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
                            <td class="w-fit px-4 py-3 text-right">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-32 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Detail --}}
                                        <a href="{{ route('sales.request-order.show', $ro->id) }}" class="group flex h-full items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                        </a>

                                        {{-- PDF --}}
                                        @php
                                            $canDownload = false;
                                            if (auth()->check() && in_array(auth()->user()->role, ['Supervisor', 'Admin'])) {
                                                $canDownload = true;
                                            } elseif (auth()->check() && auth()->id() === $ro->sales_id) {
                                                $canDownload = !in_array($ro->status, ['pending_approval', 'rejected']);
                                            }
                                        @endphp
                                        @if ($canDownload)
                                            <a href="{{ route('sales.request-order.pdf', $ro->id) }}" class="group flex h-full items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" title="Download PDF" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-4 w-4">
                                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                    <path d="M10 9H8"></path>
                                                    <path d="M16 13H8"></path>
                                                    <path d="M16 17H8"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">PDF</span>
                                            </a>
                                        @else
                                            <button type="button" disabled class="group flex h-full cursor-not-allowed items-center justify-center bg-gray-400 p-2 text-sm font-medium text-white" title="PDF tidak tersedia sampai Supervisor menyetujui penawaran">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-4 w-4">
                                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                    <path d="M10 9H8"></path>
                                                    <path d="M16 13H8"></path>
                                                    <path d="M16 17H8"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">PDF</span>
                                            </button>
                                        @endif

                                        {{-- Edit --}}
                                        @if ($ro->status === 'pending')
                                            <a href="{{ route('sales.request-order.edit', $ro->id) }}" class="group flex h-full items-center justify-center bg-yellow-500 p-2 text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4">
                                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                            </a>
                                        @endif

                                        {{-- Sent to Warehouse --}}
                                        @if (in_array($ro->status, ['open', 'approved']))
                                            <form action="{{ route('sales.request-order.sent-to-warehouse', $ro->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="group flex h-full items-center justify-center cursor-pointer bg-blue-600 p-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Kirim ke Warehouse">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck h-4 w-4">
                                                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                                        <path d="M15 18H9"></path>
                                                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                                        <circle cx="17" cy="18" r="2"></circle>
                                                        <circle cx="7" cy="18" r="2"></circle>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Kirim ke Warehouse</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11">Belum ada request.</td></tr>
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
