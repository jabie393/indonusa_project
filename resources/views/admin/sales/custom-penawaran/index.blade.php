<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Daftar Penawaran Kustom</h1>
                <p class="text-gray-600 mt-2">Kelola penawaran kustom Anda</p>
            </div>
            <a href="{{ route('sales.custom-penawaran.create') }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                + Buat Penawaran Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($customPenawarans->isEmpty())
            <div class="bg-gray-50 rounded-lg p-12 text-center">
                <p class="text-gray-500 text-lg">Belum ada penawaran kustom. <a href="{{ route('sales.custom-penawaran.create') }}" class="text-blue-600 hover:underline">Buat sekarang</a></p>
            </div>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No Penawaran</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kepada</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Subject</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Our Ref</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customPenawarans as $penawaran)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">{{ $penawaran->penawaran_number }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $penawaran->to }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ Str::limit($penawaran->subject, 30) }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $penawaran->our_ref }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($penawaran->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'sent' => 'bg-blue-100 text-blue-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800'
                                        ][$penawaran->status] ?? 'bg-gray-100 text-gray-800';
                                        $statusLabel = [
                                            'draft' => 'Draft',
                                            'sent' => 'Terkirim',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak'
                                        ][$penawaran->status] ?? $penawaran->status;
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700">
                                    {{ \Carbon\Carbon::parse($penawaran->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('sales.custom-penawaran.show', $penawaran->id) }}" 
                                            class="text-blue-600 hover:text-blue-800 font-semibold text-sm"
                                            title="Lihat">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('sales.custom-penawaran.edit', $penawaran->id) }}" 
                                            class="text-yellow-600 hover:text-yellow-800 font-semibold text-sm"
                                            title="Edit">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('sales.custom-penawaran.pdf', $penawaran->id) }}" 
                                            target="_blank"
                                            class="text-green-600 hover:text-green-800 font-semibold text-sm"
                                            title="Download PDF">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('sales.custom-penawaran.destroy', $penawaran->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm" title="Hapus">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H4v2h16V7h-3z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($customPenawarans->hasPages())
                <div class="mt-6">
                    {{ $customPenawarans->links('pagination::tailwind') }}
                </div>
            @endif
        @endif
    </div>
</div>
</x-app-layout>
