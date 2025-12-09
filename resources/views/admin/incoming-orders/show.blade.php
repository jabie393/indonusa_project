<x-app-layout>
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                    Order {{ $order->order_number }}
                </h1>

                <p><strong>Status:</strong> {{ $order->status }}</p>
                <p><strong>Reason:</strong> {{ $order->reason ?? '-' }}</p>

                <h3 class="mt-6 font-semibold">Items</h3>
                <ul class="list-disc pl-6">
                    @foreach($order->items as $it)
                        <li>
                            {{ $it->barang->nama_barang ?? 'Barang ID '.$it->barang_id }}
                            — qty: {{ $it->quantity }}
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6">
                    <a href="{{ route('orders.history') }}"
                       class="px-3 py-1 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
