<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Approved Orders (Sent to Warehouse)</h2>
            </div>

            {{-- Notifikasi pesan sukses --}}
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
                        <th>#</th>
                        <th>Order Number</th>
                        <th>Kode Barang</th>
                        <th>Sales</th>
                        <th>Customer</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Sent At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @forelse($orders as $order)
                        @if ($order->items && $order->items->count())
                            @foreach ($order->items as $item)
                                <tr>
                                    @if ($loop->first)
                                        <td rowspan="{{ $order->items->count() }}">{{ $loop->parent->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                        <td rowspan="{{ $order->items->count() }}">{{ $order->order_number }}</td>
                                        <td rowspan="{{ $order->items->count() }}">
                                            @if (optional($item->barang)->kode_barang)
                                                {{ optional($item->barang)->kode_barang }}@else-
                                            @endif
                                        </td>
                                        <td rowspan="{{ $order->items->count() }}">{{ optional($order->sales)->name ?? 'N/A' }}</td>
                                        <td rowspan="{{ $order->items->count() }}">{{ $order->customer_name ?? '-' }}</td>
                                    @endif

                                    <td>{{ optional($item->barang)->nama_barang ?? 'Barang #' . $item->barang_id }}</td>
                                    <td>{{ $item->quantity }}</td>

                                    @if ($loop->first)
                                        <td rowspan="{{ $order->items->count() }}">{{ $order->updated_at ? $order->updated_at->format('Y-m-d H:i') : '-' }}</td>
                                        <td rowspan="{{ $order->items->count() }}">{{ ucfirst($order->status) }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                <td>{{ $order->order_number }}</td>
                                <td>-</td>
                                <td>{{ optional($order->sales)->name ?? 'N/A' }}</td>
                                <td>{{ $order->customer_name ?? '-' }}</td>
                                <td>-</td>
                                <td>-</td>
                                <td>{{ $order->updated_at ? $order->updated_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>{{ ucfirst($order->status) }}</td>
                            </tr>
                        @endif
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
</x-app-layout>
