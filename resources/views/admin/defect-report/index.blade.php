<x-app-layout>
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white p-4 shadow-md dark:bg-gray-800">
        <h2 class="text-2xl font-bold dark:text-white inline-flex items-center">
            <svg class="w-6 h-6 mr-2 text-[#225A97] dark:text-white" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                </path>
            </svg>
            Defect Report Tinjauan
        </h2>

        <form action="{{ route('supervisor.defect-report.index') }}" method="GET" class="relative">
            <div class="rtl:inset-r-0 pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                class="block w-80 rounded-lg border border-gray-300 bg-gray-50 ps-10 pt-2 text-sm text-gray-900 focus:border-[#225A97] focus:ring-[#225A97] dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-[#225A97] dark:focus:ring-[#225A97]"
                placeholder="Cari barang...">
        </form>
    </div>

    <!-- Table -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead
                class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400 font-bold border-b dark:border-gray-600">
                <tr>
                    <th scope="col" class="px-6 py-4">Nama Barang</th>
                    <th scope="col" class="px-6 py-4">Kode</th>
                    <th scope="col" class="px-6 py-4">Stok Defect</th>
                    <th scope="col" class="px-6 py-4">Harga Diajukan</th>
                    <th scope="col" class="px-6 py-4">Alasan</th>
                    <th scope="col" class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($goods as $barang)
                    <tr
                        class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                        <th scope="row"
                            class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $barang->nama_barang }}
                        </th>
                        <td class="px-6 py-4">{{ $barang->kode_barang }}</td>
                        <td class="px-6 py-4">{{ $barang->stok }} {{ $barang->satuan }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-xs">{{ $barang->alasan_pengajuan }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button onclick="confirmApprove({{ $barang->id }})"
                                    class="rounded-lg bg-green-700 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 transition-colors">
                                    Setujui
                                </button>
                                <button onclick="openRejectModal({{ $barang->id }})"
                                    class="rounded-lg bg-red-700 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 transition-colors">
                                    Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                            Tidak ada pengajuan defect item yang perlu ditinjau.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $goods->links() }}
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" tabindex="-1" aria-hidden="true"
        class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0 bg-black/50 items-center justify-center">
        <div class="relative max-h-full w-full max-w-md">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                <button type="button" onclick="closeRejectModal()"
                    class="absolute end-2.5 top-3 ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white transition-colors">
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5">
                    <h3 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Tolak Pengajuan Defect</h3>
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="mb-4 text-sm text-gray-500 dark:text-gray-400 italic">
                            Penolakan akan mengubah status pengajuan menjadi 'Ditolak Supervisor' agar dapat diperbaiki
                            dan diajukan ulang oleh Warehouse.
                        </div>
                        <div class="mb-4 text-left">
                            <label for="reason"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Alasan
                                Penolakan</label>
                            <textarea id="reason" name="reason" rows="4" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                placeholder="Tuliskan alasan penolakan..."></textarea>
                        </div>
                        <button type="submit"
                            class="w-full rounded-lg bg-red-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 transition-colors">
                            Konfirmasi Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmApprove(id) {
            Swal.fire({
                title: 'Setujui Pengajuan?',
                text: "Stok akan dipindahkan secara resmi ke kategori defect.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#057A55',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/supervisor/defect-report/${id}/approve`;
                    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        }

        function openRejectModal(id) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            form.action = `/supervisor/defect-report/${id}/reject`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>
