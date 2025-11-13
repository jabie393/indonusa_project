<x-app-layout>
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">History Orders</h1>

            @foreach($orders as $order)
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-4">
                    <p><strong>{{ $order->order_number }}</strong></p>
                    <p>Status: {{ $order->status }}</p>
                    <p>Reason: {{ $order->reason ?? '-' }}</p>
                </div>
            @endforeach

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
