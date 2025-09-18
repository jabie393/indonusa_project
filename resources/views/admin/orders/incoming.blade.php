
<x-app-layout>
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Incoming Orders</h1>
            <a href="{{ url()->previous() }}" class="mb-4 inline-block px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded">Kembali</a>

            @if($orders->count())
                @foreach($orders as $order)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-lg">{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Dari: {{ $order->sales->name ?? 'Sales' }}
                                </p>
                            </div>
                            <div class="space-x-2">
                                <a href="{{ route('admin.orders.show',$order->id) }}"
                                   class="px-3 py-1 bg-blue-500 text-white rounded">Detail</a>
                                <form action="{{ route('admin.orders.approve',$order->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('admin.orders.reject',$order->id) }}" method="POST">
                                @csrf
                                <input type="text" name="reason" placeholder="Alasan penolakan"
                                       class="border rounded p-2 w-2/3 dark:bg-gray-700 dark:text-gray-200" required>
                                <button class="px-3 py-1 bg-red-500 text-white rounded">Reject</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
                    <p>Tidak ada order masuk.</p>
                    <!-- Show approve/reject form even if no orders, for demonstration -->
                    <div class="mt-4">
                        <form action="#" method="POST">
                            @csrf
                            <input type="text" name="reason" placeholder="Alasan penolakan"
                                   class="border rounded p-2 w-2/3 dark:bg-gray-700 dark:text-gray-200" required>
                            <button class="px-3 py-1 bg-red-500 text-white rounded">Reject</button>
                        </form>
                        <form action="#" method="POST" class="inline-block mt-2">
                            @csrf
                            <button class="px-3 py-1 bg-green-600 text-white rounded">Approve</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
