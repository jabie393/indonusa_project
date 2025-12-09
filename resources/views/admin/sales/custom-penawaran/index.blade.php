<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">

            <div>
                <h2 class="mr-3 font-semibold text-white">Daftar Penawaran Kustom</h2>
            </div>
            <a href="{{ route('sales.custom-penawaran.create') }}" class="rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
                + Buat Penawaran Baru
            </a>

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 ">No Penawaran</th>
                        <th class="px-4 py-3 ">Kepada</th>
                        <th class="px-4 py-3 ">Subject</th>
                        <th class="px-4 py-3 ">Our Ref</th>
                        <th class="px-4 py-3 ">Total</th>
                        <th class="px-4 py-3 ">Status</th>
                        <th class="px-4 py-3 ">Tanggal</th>
                        <th class="px-4 py-3 ">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customPenawarans as $penawaran)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-900">{{ $penawaran->penawaran_number }}</span>
                            </td>
                            <td class="px-4 py-4 text-gray-700">{{ $penawaran->to }}</td>
                            <td class="px-4 py-4 text-gray-700">{{ Str::limit($penawaran->subject, 30) }}</td>
                            <td class="px-4 py-4 text-gray-700">{{ $penawaran->our_ref }}</td>
                            <td class="px-4 py-4 text-right font-semibold text-gray-900">
                                Rp {{ number_format($penawaran->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $hasHighDiscountStatus = $penawaran->items->where('diskon', '>', 20)->isNotEmpty();
                                    $statusClass =
                                        [
                                            'draft' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                            'sent' => 'bg-indigo-50 text-indigo-700 inset-ring inset-ring-indigo-700',
                                            'approved' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                            'rejected' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-700',
                                        ][$penawaran->status] ?? 'bbg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600';
                                    $statusLabel =
                                        [
                                            'draft' => 'Draft',
                                            'sent' => 'Terkirim',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                        ][$penawaran->status] ?? $penawaran->status;
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    <span class="{{ $statusClass }} badge">
                                        {{ $statusLabel }}
                                    </span>
                                    @if($penawaran->status === 'sent' && $hasHighDiscountStatus)
                                        <span class="px-2 py-1 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded">Menunggu Approval</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center text-gray-700">
                                {{ \Carbon\Carbon::parse($penawaran->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('sales.custom-penawaran.show', $penawaran->id) }}"
                                        class="btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        title="Lihat">
                                        Detail
                                    </a>
                                    <a href="{{ route('sales.custom-penawaran.edit', $penawaran->id) }}"
                                        class="btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        title="Edit">
                                        Edit
                                    </a>
                                    @php
                                        // apakah ada item dengan diskon > 20%
                                        $hasHighDiscount = $penawaran->items->where('diskon', '>', 20)->isNotEmpty();
                                    @endphp
                                    @if(!$hasHighDiscount || $penawaran->status === 'approved')
                                        <a href="{{ route('sales.custom-penawaran.pdf', $penawaran->id) }}" target="_blank"
                                            class="btn mb-2 me-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                            title="Download PDF">
                                            PDF
                                        </a>
                                    @else
                                        <button type="button" title="Menunggu persetujuan Supervisor" disabled
                                            class="btn mb-2 me-2 rounded-lg bg-gray-300 px-5 py-2.5 text-sm font-medium text-white cursor-not-allowed"
                                            aria-disabled="true">
                                            PDF
                                        </button>
                                        <span class="sr-only">Menunggu persetujuan Supervisor</span>
                                    @endif
                                    <form action="{{ route('sales.custom-penawaran.destroy', $penawaran->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn mb-2 me-2 rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                            title="Hapus">
                                            <svg class="inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H4v2h16V7h-3z">
                                                </path>
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
        @if ($customPenawarans->hasPages())
            <div class="mt-6">
                {{ $customPenawarans->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</x-app-layout>
