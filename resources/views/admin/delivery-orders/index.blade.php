<x-app-layout>
    <div class="p-6">
        <h2 class="text-lg font-semibold">Delivery Orders</h2>

        @if(isset($orders) && $orders->isEmpty())
            <div class="mt-4 text-sm text-gray-500">Belum ada delivery order dengan status <strong>sent_to_warehouse</strong>.</div>
        @else
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No. Order</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Supervisor</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dibuat</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $order->order_number }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ optional($order->supervisor)->name ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $order->status }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <button type="button" class="text-indigo-600 hover:text-indigo-900 js-show-order" data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}" data-items='@json($order->items)'>Show</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modals -->
    @include('components.delivery-orders-modal-show')
    @vite(['resources/js/delivery-orders.js'])

</x-app-layout>
