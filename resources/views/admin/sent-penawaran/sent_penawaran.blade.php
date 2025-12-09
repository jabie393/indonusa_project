<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Sent Penawaran (Waiting Approval)</h2>
            </div>

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
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">No. Penawaran</th>
                        <th class="px-4 py-3">Sales</th>
                        <th class="px-4 py-3">To</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Max Diskon (%)</th>
                        <th class="px-4 py-3">Sent At</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penawarans as $penawaran)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration + ($penawarans->currentPage() - 1) * $penawarans->perPage() }}</td>
                            <td class="px-4 py-3">{{ $penawaran->penawaran_number }}</td>
                            <td class="px-4 py-3">{{ optional($penawaran->sales)->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $penawaran->to }}</td>
                            <td class="px-4 py-3">{{ $penawaran->items->count() }}</td>
                            <td class="px-4 py-3">{{ $penawaran->items->max('diskon') ?? 0 }}%</td>
                            <td class="px-4 py-3">{{ $penawaran->created_at ? $penawaran->created_at->format('Y-m-d H:i') : '-' }}</td>
                            <td class="px-4 py-3">{{ ucfirst($penawaran->status) }}</td>
                            <td class="px-4 py-3">
                                @if(auth()->user()->role === 'Supervisor')
                                    <a href="{{ route('admin.custom-penawaran.show', $penawaran->id) }}" class="px-2 py-1 bg-gray-200 rounded mr-2">Lihat</a>
                                @else
                                    <a href="{{ route('sales.custom-penawaran.show', $penawaran->id) }}" class="px-2 py-1 bg-gray-200 rounded mr-2">Lihat</a>
                                @endif

                                @if(auth()->user()->role === 'Supervisor' && $penawaran->status === 'sent')
                                    <form action="{{ url('/custom-penawaran/' . $penawaran->id . '/approval') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button class="px-2 py-1 bg-green-600 text-white rounded mr-1">Setujui</button>
                                    </form>
                                    <form action="{{ url('/custom-penawaran/' . $penawaran->id . '/approval') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button class="px-2 py-1 bg-red-600 text-white rounded">Tolak</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center px-4 py-3">Tidak ada penawaran yang menunggu persetujuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $penawarans->links() }}
    </div>
</x-app-layout>