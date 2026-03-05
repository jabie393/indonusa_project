
<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-6 w-full">
            <h1 class="mb-6 text-2xl font-bold text-gray-800 dark:text-white">History Approval Supervisor</h1>
            <div class="overflow-x-auto rounded-xl">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Proses</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nomor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Customer/Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Sales/Supervisor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Grand Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Alasan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tanggal Approve/Reject</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($histories as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3">
                                @if($item['type'] === 'request_order')
                                    <span class="inline-block rounded bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-200">Sent Penawaran</span>
                                @elseif($item['type'] === 'custom_penawaran')
                                    <span class="inline-block rounded bg-cyan-100 px-2 py-1 text-xs font-semibold text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200">Custom Penawaran</span>
                                @elseif($item['type'] === 'defect_report')
                                    <span class="inline-block rounded bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Defect Report</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $item['number'] }}</td>
                            <td class="px-4 py-3">{{ $item['customer'] }}</td>
                            <td class="px-4 py-3">{{ $item['sales'] }}</td>
                            <td class="px-4 py-3">{{ $item['grand_total'] }}</td>
                            <td class="px-4 py-3">
                                @if($item['status'] === 'approved_supervisor' || $item['status'] === 'approved')
                                    <span class="inline-block rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200">Disetujui</span>
                                @elseif($item['status'] === 'rejected_supervisor' || $item['status'] === 'rejected')
                                    <span class="inline-block rounded bg-red-100 px-2 py-1 text-xs font-semibold text-red-800 dark:bg-red-900 dark:text-red-200">Ditolak</span>
                                @else
                                    <span class="inline-block rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-900 dark:text-gray-200">{{ $item['status'] }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $item['reason'] }}</td>
                            <td class="px-4 py-3">{{ $item['approved_at'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-400 dark:text-gray-500">Tidak ada data history approval.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
