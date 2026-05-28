<x-app-layout>
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex items-center h-16 justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('delivery-orders.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-64 md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable"
                        value="{{ request('search') }}"
                        class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder="Search" />
                </div>
            </form>
        </div>
    </div>

    <div
        class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div
            class="shrink-0 flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            @php
                $supplyOrderCount = \App\Models\Barang::where('goods_status', 'ditinjau')->count();
                $deliveryOrderCount = \App\Models\Order::where('status', 'sent_to_warehouse')->count();
            @endphp
            <div class="flex items-center space-x-2">
                <a href="{{ route('warehouse.index', ['status' => 'masuk']) }}"
                    class="text-white hover:bg-white/10 rounded-lg px-4 py-2 text-sm font-medium transition-all">
                    Semua Barang
                </a>
                <a href="{{ route('supply-orders.index') }}"
                    class="text-white hover:bg-white/10 flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-all">
                    Supply Orders
                    @if ($supplyOrderCount > 0)
                        <span
                            class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $supplyOrderCount }}</span>
                    @endif
                </a>
                <a href="{{ route('delivery-orders.index') }}"
                    class="bg-white text-[#225A97] flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-all">
                    Delivery Orders
                    @if ($deliveryOrderCount > 0)
                        <span
                            class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $deliveryOrderCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400" id="">
                <thead
                    class="sticky top-0 z-30 bg-gray-50 text-nowrap text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="text-nowrap px-4 py-3">No. Dokumen</th>
                        <th class="text-nowrap px-4 py-3">Customer</th>
                        <th class="text-nowrap px-4 py-3">Sales</th>
                        <th class="flex justify-center text-nowrap px-4 py-3 select-none">Status</th>
                        <th class="text-nowrap px-4 py-3">Dibuat</th>
                        <th class="flex justify-center text-nowrap px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @foreach ($orders as $order)
                        <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-slate-50/80 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                                <div>
                                    <a href="javascript:void(0)"
                                        class="js-show-order text-[#225A97] dark:text-blue-400 font-bold hover:underline"
                                        data-order-id="{{ $order->id }}"
                                        data-order-number="{{ $order->do_number ?? $order->order_number }}"
                                        data-reason="{{ $order->reason }}"
                                        data-items='@json($order->items)'>
                                        {{ $order->do_number ?? $order->order_number }}
                                    </a>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-normal flex items-center">
                                    <span class="text-gray-400 dark:text-gray-500 w-6">SO</span>
                                    <span class="text-gray-300 dark:text-gray-600 font-bold mx-1.5">·</span>
                                    <span>{{ $order->requestOrder?->sales_order_number ?? $order->no_so }}</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-normal flex items-center">
                                    <span class="text-gray-400 dark:text-gray-500 w-6">PO</span>
                                    <span class="text-gray-300 dark:text-gray-600 font-bold mx-1.5">·</span>
                                    <span>{{ $order->requestOrder?->no_po ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                                <div class="font-bold text-gray-900 dark:text-white text-[14px]">
                                    {{ $order->customer?->nama_customer ?? $order->customer_name }}
                                </div>
                                @php
                                    $firstPic = $order->customer?->pics?->first();
                                @endphp
                                @if ($firstPic)
                                    <div class="flex items-center text-[12px] text-gray-500 dark:text-gray-400 mt-1 font-normal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user text-gray-400 dark:text-gray-500 mr-1.5 shrink-0">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        <span class="truncate">{{ $firstPic->name }}</span>
                                        @if ($firstPic->position)
                                            <span class="text-gray-300 dark:text-gray-600 font-bold mx-1.5">·</span>
                                            <span class="text-gray-400 dark:text-gray-500 truncate">{{ $firstPic->position }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white font-semibold">
                                {{ $order->requestOrder?->sales?->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-center align-middle">
                                @php
                                    $status = $order->status;
                                    $badgeBg = '';
                                    $badgeText = '';
                                    $badgeBorder = '';
                                    $badgeLabel = '';
                                    $iconSvg = '';

                                    switch ($status) {
                                        case 'sent_to_warehouse':
                                            $badgeBg = 'bg-blue-50 dark:bg-blue-950/30';
                                            $badgeText = 'text-blue-700 dark:text-blue-300';
                                            $badgeBorder = 'border border-blue-200 dark:border-blue-800/50';
                                            $badgeLabel = 'Pending';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                            break;
                                        case 'completed':
                                            $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                            $badgeText = 'text-green-700 dark:text-green-300';
                                            $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                            $badgeLabel = 'Completed';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                            break;
                                        case 'not_completed':
                                            $badgeBg = 'bg-amber-50 dark:bg-amber-950/30';
                                            $badgeText = 'text-amber-800 dark:text-amber-300';
                                            $badgeBorder = 'border border-amber-200 dark:border-amber-800/50';
                                            $badgeLabel = 'Partial Delivery';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                            break;
                                        case 'rejected_warehouse':
                                            $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                            $badgeText = 'text-red-700 dark:text-red-300';
                                            $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                            $badgeLabel = 'Rejected';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                            break;
                                        default:
                                            $badgeBg = 'bg-gray-50 dark:bg-gray-900';
                                            $badgeText = 'text-gray-700 dark:text-gray-300';
                                            $badgeBorder = 'border border-gray-200 dark:border-gray-800';
                                            $badgeLabel = $status;
                                            $iconSvg = '';
                                            break;
                                    }
                                @endphp
                                <span class="flex justify-center items-center rounded-full px-2 py-1 text-xs font-semibold {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }}">
                                    {!! $iconSvg !!}{{ $badgeLabel }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                                <div class="font-bold text-gray-900 dark:text-white text-[14px]">
                                    {{ optional($order->created_at)->format('Y-m-d') }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1 font-normal">
                                    {{ optional($order->created_at)->format('H:i') }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right align-middle">
                                <div class="flex justify-center">
                                    <div
                                        class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out divide-x divide-gray-200 dark:divide-gray-600 dark:border-gray-600 dark:bg-gray-700">
                                        <button type="button"
                                            class="js-show-order group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            data-order-id="{{ $order->id }}"
                                            data-order-number="{{ $order->do_number ?? $order->order_number }}"
                                            data-reason="{{ $order->reason }}" data-items='@json($order->items)'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-eye h-4 w-4">
                                                <path
                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                                </path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span
                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Show</span>
                                        </button>
                                        @if (in_array($order->status, ['sent_to_warehouse', 'not_completed']))
                                            {{-- Approve --}}
                                            <button type="button"
                                                class="js-approve-order group flex h-full cursor-pointer items-center justify-center bg-green-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                                data-id="{{ $order->id }}"
                                                data-order-number="{{ $order->do_number ?? $order->order_number }}"
                                                data-approve-url="{{ route('delivery-orders.approve', $order->id) }}"
                                                data-delivery-options="{{ $order->delivery_options }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Approve</span>
                                            </button>
                                            {{-- Reject barang --}}
                                            @php
                                                $hasDeliveries = $order->items->sum('delivered_quantity') > 0;
                                                $btnLabel = $hasDeliveries ? 'Cancel' : 'Reject';
                                            @endphp
                                            <button type="button"
                                                class="reject-btn group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                onclick='openTolakModal("delivery_order", "{{ $order->id }}", "{{ $order->do_number ?? $order->order_number }}", @json($order->items))'>
                                                @if ($hasDeliveries)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M18.36 6.64a9 9 0 11-12.73 12.73 9 9 0 0112.73-12.73zM6.34 17.66l11.32-11.32" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                @endif
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">{{ $btnLabel }}</span>
                                            </button>
                                        @endif
                                        @if ($order->status === 'completed')
                                            <a href="{{ route('delivery-orders.pdf', $order->id) }}" target="_blank"
                                                class="group flex h-full cursor-pointer items-center justify-center bg-green-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                                data-id="{{ $order->id }}"
                                                data-order-number="{{ $order->do_number ?? $order->order_number }}"
                                                data-items='@json($order->items)'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-file-text h-4 w-4">
                                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z">
                                                    </path>
                                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                    <path d="M10 9H8"></path>
                                                    <path d="M16 13H8"></path>
                                                    <path d="M16 17H8"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">DO</span>
                                            </a>
                                        @endif
                                        @if (in_array($order->status, ['completed', 'not_completed']) && $order->delivery_options === 'partial')
                                            <button type="button"
                                                class="js-history-order group flex h-full cursor-pointer items-center justify-center bg-cyan-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-300 dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800"
                                                data-id="{{ $order->id }}"
                                                data-order-number="{{ $order->do_number ?? $order->order_number }}"
                                                data-history-url="{{ route('delivery-orders.history', $order->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-history h-4 w-4">
                                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8">
                                                    </path>
                                                    <path d="M3 3v5h5"></path>
                                                    <path d="M12 7v5l3 3"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">PDO</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav id="pagination-nav"
            class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }}</span>
                    of
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $orders->total() ?? $orders->count() }}</span>
                </span>
                <form method="GET" action="{{ route('delivery-orders.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()"
                        class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
            </div>
            <div>
                {{ $orders->links() }}
            </div>
        </nav>
    </div>

    <!-- Modals -->
    @include('admin.delivery-orders.partials.delivery-orders-modal-show')
    @include('admin.delivery-orders.partials.delivery-orders-detail-modal-show')
    @include('admin.delivery-orders.partials.delivery-orders-approve-modal')
    @include('admin.delivery-orders.partials.delivery-orders-history-modal')
    @include('admin.delivery-orders.partials.delivery-orders-modal-reject')

    @vite(['resources/js/delivery-orders.js', 'resources/js/table-sort.js'])

</x-app-layout>