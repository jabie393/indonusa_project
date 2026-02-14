<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Daftar Sales Order</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Kelola semua sales order Anda</p>
            </div>
            <a href="{{ route('sales.sales-order.create') }}" class="inline-block rounded-lg bg-[#225A97] px-6 py-3 text-center font-semibold text-white hover:bg-[#1c4d81]">
                + Buat Sales Order
            </a>
        </div>
    </div>

    <div class="mt-6">
        @if (session('title'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-900 dark:bg-green-900/30">
                <p class="font-semibold text-green-800 dark:text-green-300">{{ session('title') }}</p>
                @if (session('text'))
                    <p class="mt-1 text-sm text-green-700 dark:text-green-400">{{ session('text') }}</p>
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-900/30">
                <p class="font-semibold text-red-800 dark:text-red-300">Terjadi kesalahan:</p>
                <ul class="mt-2 list-inside list-disc text-sm text-red-700 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>


    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mt-6 overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                    <line x1="4" x2="4" y1="15" y2="21"></line>
                    <line x1="12" x2="12" y1="15" y2="21"></line>
                    <line x1="20" x2="20" y1="15" y2="21"></line>
                </svg>
                Daftar Sales Order
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">No SO</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Customer</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Subject</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">Grand Total (Rp)</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salesOrders as $salesOrder)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <a href="{{ route('sales.sales-order.show', $salesOrder) }}" class="font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    {{ $salesOrder->so_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $salesOrder->to }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $salesOrder->subject }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $salesOrder->date ? \Carbon\Carbon::parse($salesOrder->date)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClass =
                                        [
                                            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            'pending_approval' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'sent' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        ][$salesOrder->status] ?? 'bg-gray-100 text-gray-800';
                                    $statusLabel =
                                        [
                                            'draft' => 'Draft',
                                            'pending_approval' => 'Menunggu Persetujuan',
                                            'sent' => 'Terkirim',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                        ][$salesOrder->status] ?? $salesOrder->status;
                                @endphp
                                <span class="{{ $statusClass }} inline-block rounded-full px-3 py-1 text-xs font-semibold">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($salesOrder->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('sales.sales-order.show', $salesOrder) }}" title="Lihat" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="{{ route('sales.sales-order.edit', $salesOrder) }}" title="Edit" class="rounded-lg bg-yellow-600 px-3 py-2 text-xs font-semibold text-white hover:bg-yellow-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('sales.sales-order.destroy', $salesOrder) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sales order ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" x2="10" y1="11" y2="17"></line>
                                                <line x1="14" x2="14" y1="11" y2="17"></line>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-400 dark:text-gray-600">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                                <p class="text-lg font-semibold">Tidak ada sales order</p>
                                <p class="mt-1 text-sm">Mulai buat sales order baru dengan klik tombol di atas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($salesOrders->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-600">
                {{ $salesOrders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>