<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detail Request Order</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">No. {{ $requestOrder->request_number }}</p>
            </div>
            <div>
                <a href="{{ route('sales.request-order.index') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center justify-between rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900 dark:text-green-300" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-green-100 p-1.5 text-green-500 hover:bg-green-200 focus:ring-2 focus:ring-green-400 dark:bg-green-800 dark:text-green-400 dark:hover:bg-green-700" data-bs-dismiss="alert" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-900 dark:text-red-300" role="alert">
                <div class="mb-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="font-medium">Terdapat kesalahan:</span>
                </div>
                <ul class="ml-4 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="mt-2 text-sm underline hover:no-underline" data-bs-dismiss="alert" aria-label="Close">Tutup</button>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Main Details Card -->
                <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                    <div class="flex items-center justify-between bg-[#225A97] p-4 text-white">
                        <h2 class="flex items-center gap-2 text-lg font-semibold">
                            <i class="fas fa-file-alt"></i> Informasi Request Order
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">No. Request</label>
                                <p class="text-gray-900 dark:text-white">{{ $requestOrder->request_number }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">No. Penawaran</label>
                                <p><span class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 dark:bg-blue-200 dark:text-blue-800">{{ $requestOrder->nomor_penawaran ?? '-' }}</span></p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Dibuat</label>
                                <p class="text-gray-900 dark:text-white">{{ $requestOrder->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Kategori Barang</label>
                                <p><span class="rounded bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ $requestOrder->kategori_barang ?? '-' }}</span></p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Customer</label>
                                <p class="text-gray-900 dark:text-white">{{ $requestOrder->customer_name }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">ID Customer</label>
                                <p class="text-gray-900 dark:text-white">{{ $requestOrder->customer_id ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Kebutuhan</label>
                                <p class="text-gray-900 dark:text-white">{{ $requestOrder->tanggal_kebutuhan_formatted }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                                <div>
                                    @php
                                        $statusClass = match ($requestOrder->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'converted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            'expired' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }} rounded px-2.5 py-0.5 text-xs font-semibold">
                                        {{ ucfirst($requestOrder->status) }}
                                    </span>
                                </div>
                            </div>
                            <!-- Validity Period -->
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Masa Berlaku Mulai</label>
                                <p class="text-gray-900 dark:text-white">{{ $requestOrder->tanggal_berlaku_formatted }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Masa Berlaku Berakhir</label>
                                <div>
                                    @if ($requestOrder->expired_at)
                                        <span class="text-gray-900 dark:text-white">{{ $requestOrder->expired_at_formatted }}</span>
                                        <div class="mt-1">
                                            @if ($requestOrder->isExpired())
                                                <span class="rounded bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800 dark:bg-red-900 dark:text-red-300">KADALUARSA</span>
                                            @else
                                                <span class="rounded bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    @if (is_string($requestOrder->expired_at))
                                                        {{ \Carbon\Carbon::parse($requestOrder->expired_at)->diffForHumans() }}
                                                    @else
                                                        {{ $requestOrder->expired_at->diffForHumans() }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </div>
                            </div>

                            @if ($requestOrder->catatan_customer)
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Catatan Customer</label>
                                    <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ $requestOrder->catatan_customer }}
                                    </div>
                                </div>
                            @endif

                            @if ($requestOrder->reason)
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-sm font-semibold text-red-600 dark:text-red-400">Alasan Penolakan</label>
                                    <div class="rounded-lg bg-red-50 p-3 text-sm text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                        {{ $requestOrder->reason }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Supporting Images Card -->
                @if ($requestOrder->supporting_images && count($requestOrder->supporting_images) > 0)
                    <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="flex items-center justify-between bg-green-600 p-4 text-white">
                            <h2 class="flex items-center gap-2 text-lg font-semibold">
                                <i class="fas fa-images"></i> Gambar Pendukung
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                                @foreach ($requestOrder->supporting_images as $image)
                                    <div class="group relative overflow-hidden rounded-lg border border-gray-200 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-700">
                                        <a href="{{ asset('storage/' . $image) }}" target="_blank" class="block aspect-square overflow-hidden bg-gray-100 dark:bg-gray-600">
                                            <img src="{{ asset('storage/' . $image) }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" alt="Supporting image">
                                        </a>
                                        <div class="truncate p-2 text-center text-xs text-gray-500 dark:text-gray-400">
                                            {{ basename($image) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Items Card -->
                <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                    <div class="flex items-center justify-between bg-[#225A97] p-4 text-white">
                        <h2 class="flex items-center gap-2 text-lg font-semibold">
                            <i class="fas fa-box"></i> Detail Barang
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Kategori Barang</th>
                                    <th scope="col" class="px-6 py-3">Barang</th>
                                    <th scope="col" class="px-6 py-3">Diskon (%)</th>
                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                    <th scope="col" class="px-6 py-3">Harga Satuan</th>
                                    <th scope="col" class="px-6 py-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 border-b dark:divide-gray-700 dark:border-gray-700">
                                @php $total = 0; @endphp
                                @forelse($requestOrder->items as $item)
                                    @php
                                        // Show harga satuan directly from barang table (fallback to item's harga if barang missing)
                                        $displayHarga = optional($item->barang)->harga ?? ($item->harga ?? 0);
                                        $computedSubtotal = $displayHarga * $item->quantity;
                                        $total += $computedSubtotal;
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <span class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                {{ $item->kategori_barang ?? ($item->barang->kategori ?? '-') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $item->barang->nama_barang ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Kode: {{ $item->barang->kode_barang ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">{{ $item->diskon_percent ?? ($item->barang->diskon_percent ?? 0) }}%</td>
                                        <td class="px-6 py-4">{{ $item->quantity }} {{ $item->barang->satuan ?? 'pcs' }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($displayHarga, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">Rp {{ number_format($computedSubtotal, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">Tidak ada item</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 font-semibold text-gray-900 dark:bg-gray-700 dark:text-white">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-right">TOTAL:</td>
                                    <td class="px-6 py-4">Rp {{ number_format($total, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Status Card -->
                <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                    <div class="flex items-center justify-between bg-cyan-600 p-4 text-white">
                        <h2 class="flex items-center gap-2 text-lg font-semibold">
                            <i class="fas fa-info-circle"></i> Info Status
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <span class="{{ $statusClass }} rounded px-2.5 py-1 text-sm font-semibold">
                                {{ ucfirst($requestOrder->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            @if ($requestOrder->status === 'pending')
                                Request Order menunggu untuk disetujui.
                            @elseif($requestOrder->status === 'approved')
                                Request Order telah disetujui dan siap untuk dikonversi.
                            @elseif($requestOrder->status === 'rejected')
                                Request Order ditolak oleh supervisor.
                            @elseif($requestOrder->status === 'converted')
                                Request Order telah dikonversi menjadi Sales Order.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                    <div class="flex items-center justify-between bg-emerald-600 p-4 text-white">
                        <h2 class="flex items-center gap-2 text-lg font-semibold">
                            <i class="fas fa-cogs"></i> Aksi
                        </h2>
                    </div>
                    <div class="flex flex-col gap-3 p-6">
                        @if ($requestOrder->status === 'pending')
                            <a href="{{ route('sales.request-order.edit', $requestOrder->id) }}" class="inline-flex w-full items-center justify-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-900">
                                <i class="fas fa-edit mr-2"></i> Edit Request Order
                            </a>
                        @endif

                        <a href="{{ route('sales.request-order.pdf', $requestOrder->id) }}" class="inline-flex w-full items-center justify-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" target="_blank">
                            <i class="fas fa-download mr-2"></i> Download PDF
                        </a>

                        @if ($requestOrder->status === 'approved' && !$requestOrder->salesOrder)
                            <form method="POST" action="{{ route('sales.request-order.convert', $requestOrder->id) }}" class="block w-full">
                                @csrf
                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800" onclick="return confirm('Konversi Request Order ke Sales Order?')">
                                    <i class="fas fa-arrow-right mr-2"></i> Konversi ke Sales Order
                                </button>
                            </form>
                        @endif

                        @if ($requestOrder->salesOrder)
                            <div class="rounded-lg bg-blue-50 p-4 text-sm text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                <div class="mb-1 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Sudah dikonversi
                                </div>
                                <div>
                                    Sales Order: <a href="{{ route('sales.sales-order.show', $requestOrder->salesOrder->id) }}" class="font-bold underline hover:no-underline">
                                        {{ $requestOrder->salesOrder->sales_order_number }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Supervisor actions for pending approvals --}}
                        @if (auth()->check() && auth()->user()->role === 'Supervisor' && $requestOrder->status === 'pending_approval')
                            <form method="POST" action="{{ route('supervisor.request-order.approve', $requestOrder->id) }}" class="block w-full">
                                @csrf
                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800" onclick="return confirm('Setujui Request Order ini?')">
                                    <i class="fas fa-check mr-2"></i> Setujui Penawaran
                                </button>
                            </form>

                            <!-- Reject with reason -->
                            <button type="button" class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-ban mr-2"></i> Tolak Penawaran
                            </button>
                        @endif

                        <a href="{{ route('sales.request-order.index') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-gray-500 px-4 py-2 text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            <i class="fas fa-list mr-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    @if (auth()->check() && auth()->user()->role === 'Supervisor' && $requestOrder->status === 'pending_approval')
        <div class="modal fade fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                <div class="modal-content pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-gray-700">
                    <div class="modal-header flex flex-shrink-0 items-center justify-between rounded-t-md border-b-0 bg-red-600 p-4 text-white">
                        <h5 class="text-xl font-medium leading-normal" id="rejectModalLabel">Tolak Request Order</h5>
                        <button type="button" class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('supervisor.request-order.reject', $requestOrder->id) }}">
                        @csrf
                        <div class="modal-body relative p-4">
                            <div class="mb-3">
                                <label for="reason" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Alasan Penolakan <span class="text-red-500">*</span></label>
                                <textarea name="reason" id="reason" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:border-red-500 dark:focus:ring-red-500" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-0 p-4">
                            <button type="button" class="inline-block rounded bg-gray-200 px-6 py-2.5 text-xs font-medium uppercase leading-tight text-gray-700 shadow-md transition duration-150 ease-in-out hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="ml-1 inline-block rounded bg-red-600 px-6 py-2.5 text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg">Kirim Penolakan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
