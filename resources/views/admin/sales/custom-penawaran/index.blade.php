<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">

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
                        <th class="px-4 py-3">No Penawaran</th>
                        <th class="px-4 py-3">Kepada</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">Our Ref</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customPenawarans as $penawaran)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <span class="font-semibold">{{ $penawaran->penawaran_number }}</span>
                            </td>
                            <td class="px-4 py-4 text-nowrap">{{ $penawaran->to }}</td>
                            <td class="px-4 py-4">{{ Str::limit($penawaran->subject, 30) }}</td>
                            <td class="px-4 py-4 text-nowrap">{{ $penawaran->our_ref }}</td>
                            <td class="px-4 py-4 text-right font-semibold text-nowrap">
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
                                    @if ($penawaran->status === 'sent' && $hasHighDiscountStatus)
                                        <span class="rounded bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-800">Menunggu Approval</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                {{ \Carbon\Carbon::parse($penawaran->date)->format('d/m/Y') }}
                            </td>
                            <td class="w-fit px-4 py-3 text-right">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-32 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Detail --}}
                                        <a href="{{ route('sales.custom-penawaran.show', $penawaran->id) }}" class="group flex h-full items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('sales.custom-penawaran.edit', $penawaran->id) }}" class="group flex h-full items-center justify-center bg-yellow-500 p-2 text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4">
                                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                        </a>

                                        {{-- PDF --}}
                                        @php
                                            // apakah ada item dengan diskon > 20%
                                            $hasHighDiscount = $penawaran->items->where('diskon', '>', 20)->isNotEmpty();
                                        @endphp
                                        @if (!$hasHighDiscount || $penawaran->status === 'approved')
                                            <a href="{{ route('sales.custom-penawaran.pdf', $penawaran->id) }}" target="_blank" class="group flex h-full items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" title="Download PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-4 w-4">
                                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                    <path d="M10 9H8"></path>
                                                    <path d="M16 13H8"></path>
                                                    <path d="M16 17H8"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">PDF</span>
                                            </a>
                                        @else
                                            <button type="button" disabled class="group flex h-full cursor-not-allowed items-center justify-center bg-gray-300 p-2 text-sm font-medium text-white" title="Menunggu persetujuan Supervisor">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-4 w-4">
                                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                    <path d="M10 9H8"></path>
                                                    <path d="M16 13H8"></path>
                                                    <path d="M16 17H8"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">PDF</span>
                                            </button>
                                        @endif

                                        {{-- Delete --}}
                                        <form action="{{ route('sales.custom-penawaran.destroy', $penawaran->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="group flex h-full items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
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
