<x-app-layout>
    <div class="w-full py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-gray-100">Incoming Orders</h1>
            @if ($orders->count())
                @foreach ($orders as $order)
                    <div class="mb-6 rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $order->order_number }}</h3>
                                <p class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">

                                    <svg class="mx-1" width="10" height="10" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6.62502 7.19999H1.37527C1.09271 7.19999 0.884061 6.92119 0.990632 6.66479C1.48511 5.47919 2.64677 4.79999 3.99994 4.79999C5.35352 4.79999 6.51518 5.47919 7.00966 6.66479C7.11623 6.92119 6.90758 7.19999 6.62502 7.19999ZM2.36667 2.39999C2.36667 1.51759 3.0996 0.799994 3.99994 0.799994C4.90069 0.799994 5.63321 1.51759 5.63321 2.39999C5.63321 3.28239 4.90069 3.99999 3.99994 3.99999C3.0996 3.99999 2.36667 3.28239 2.36667 2.39999ZM7.98227 7.05439C7.68542 5.71079 6.75691 4.71918 5.53481 4.26918C6.1824 3.75838 6.5601 2.93237 6.42127 2.02797C6.26039 0.978773 5.36944 0.139191 4.29393 0.0167907C2.80929 -0.152409 1.55003 0.979594 1.55003 2.39999C1.55003 3.15599 1.90772 3.82958 2.46548 4.26918C1.24297 4.71918 0.314865 5.71079 0.0176096 7.05439C-0.0901865 7.54279 0.311599 7.99999 0.821589 7.99999H7.17829C7.68869 7.99999 8.09047 7.54279 7.98227 7.05439Z"
                                            fill="black" />
                                    </svg>

                                    Dari: {{ $order->sales->display_name ?? 'Sales' }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button data-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}" data-status="{{ $order->status }}" data-reason="{{ $order->reason }}"
                                    data-items='@json(
                                        $order->items->map(function ($it) {
                                            return ['kode' => optional($it->barang)->kode_barang ?? null, 'nama' => optional($it->barang)->nama_barang ?? "Barang ID $it->barang_id", 'quantity' => $it->quantity];
                                        }))' class="open-order-detail cursor-pointer rounded bg-blue-500 px-3 py-1 text-white">Detail</button>
                                <form action="{{ route('orders.approve', $order->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button class="flex cursor-pointer items-center rounded bg-green-600 px-3 py-1 text-white">
                                        <svg class="pr-1" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="8.5" cy="8.5" r="8.5" fill="white" />
                                            <ellipse cx="8.5" cy="8.5" rx="7.5" ry="7.4375" fill="#37AF2F" />
                                            <path d="M12 5.43569L11.1306 4.25L7.41613 9.3142L5.86935 7.20652L5 8.39221L7.41613 11.6875L12 5.43569Z" fill="white" />
                                        </svg>

                                        Approve
                                    </button>
                                </form>
                                <button data-id="{{ $order->id }}" class="inline-flex cursor-pointer items-center rounded bg-red-600 px-3 py-1 text-white" onclick="reject.showModal()">
                                    <svg class="pr-1" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="8.5" cy="9" rx="8.5" ry="8" fill="white" />
                                        <ellipse cx="8.5" cy="9" rx="7.5" ry="7" fill="#FC0000" />
                                        <path d="M9.993 12L8.563 9.756L7.188 12H6.143L8.09 9.008L6.143 5.972H7.276L8.706 8.205L10.07 5.972H11.115L9.179 8.953L11.126 12H9.993Z" fill="white" />
                                    </svg>
                                    Reject
                                </button>

                            </div>
                        </div>

                    </div>
                @endforeach
            @else
                <div class="mb-6 rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                    <x-app-layout>
                        <div class="w-full py-12">
                            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                                <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-gray-100">Halaman Incoming Orders Telah Dihapus</h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Halaman daftar Incoming Orders untuk role Supervisor telah dihapus. Gunakan menu lain untuk melihat daftar order.</p>
                            </div>
                        </div>
                    </x-app-layout>
                            <!-- if there is a button in form, it will close the modal -->
