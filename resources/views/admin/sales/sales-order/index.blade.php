<x-app-layout>
    <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">

        @if (session('title'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: "{{ session('title') }}",
                        text: "{{ session('text') }}",
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                });
            </script>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Daftar Sales Order</h2>
            </div>
            <div class="flex w-full flex-col py-5 md:w-auto md:flex-row md:py-0">
                <div class="mr-5 flex max-w-full shrink-0 flex-col items-stretch justify-end space-y-2 py-5 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0 md:py-0">
                    {{-- Search --}}
                    <form action="{{ route('sales.sales-order.index') }}" method="GET" class="block pl-2">
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
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="table-hover mb-0 table">
                <thead class="table-light">
                    <tr>
                        <th>No. Sales Order</th>
                        <th>Request Order</th>
                        <th>Tanggal</th>
                        <th>Nama Customer</th>
                        <th>Jumlah Item</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesOrders as $so)
                        @php
                            $statusClass = match ($so->status) {
                                'pending' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                'in_process' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                'shipped' => 'bg-indigo-50 text-indigo-700 inset-ring inset-ring-indigo-700',
                                'completed' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-700',
                                'cancelled' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-700',
                                default => 'secondary',
                            };
                            $statusLabel = match ($so->status) {
                                'in_process' => 'Dalam Proses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default => ucfirst(str_replace('_', ' ', $so->status)),
                            };
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $so->sales_order_number }}</strong>
                            </td>
                            <td>
                                @if ($so->requestOrder)
                                    <a href="{{ route('sales.request-order.show', $so->requestOrder->id) }}" class="text-decoration-none">
                                        {{ $so->requestOrder->request_number }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $so->created_at->format('d M Y') }}</td>
                            <td>{{ $so->customer_name }}</td>
                            <td>{{ $so->items->count() }} item(s)</td>
                            <td>
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <div class="flex h-full items-center gap-2 px-4 py-3">
                                    <a href="{{ route('sales.sales-order.show', $so->id) }}"
                                        class="btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        title="Lihat Detail" title="Lihat Detail">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $salesOrders->links() }}
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</x-app-layout>
