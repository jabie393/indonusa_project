<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Custom Penawaran - Menunggu Approval</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if($customPenawarans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No Penawaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sales</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kepada</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Dibuat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($customPenawarans as $penawaran)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $penawaran->penawaran_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $penawaran->sales->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $penawaran->to }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            {{ Str::limit($penawaran->subject, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $penawaran->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.custom-penawaran.show', $penawaran->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Lihat Detail</a>
                                            <form method="POST" action="{{ route('admin.custom-penawaran.approval', $penawaran->id) }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-3" onclick="return confirm('Apakah Anda yakin ingin menyetujui penawaran ini?')">Setujui</button>
                                            </form>
                                            <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="rejectPenawaran({{ $penawaran->id }})">Tolak</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $customPenawarans->links() }}
                    </div>
                @else
                    <p class="text-center">Tidak ada penawaran yang menunggu approval.</p>
                @endif
            </div>
        </div>

        <!-- Modal for Rejection Reason -->
        <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="my-modal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tolak Penawaran</h3>
                    <form id="rejectForm" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <textarea name="reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Alasan penolakan..." required></textarea>
                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeRejectModal()" class="mr-2 px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            let currentPenawaranId = null;

            function rejectPenawaran(id) {
                currentPenawaranId = id;
                document.getElementById('rejectModal').classList.remove('hidden');
                document.getElementById('rejectForm').action = `{{ url('/supervisor/custom-penawaran') }}/${id}/approval`;
            }

            function closeRejectModal() {
                document.getElementById('rejectModal').classList.add('hidden');
                currentPenawaranId = null;
            }
        </script>
    </div>
</x-app-layout>