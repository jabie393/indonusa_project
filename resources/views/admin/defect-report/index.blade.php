<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            <form action="{{ route('supervisor.defect-report.index') }}" method="GET" class="relative">
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center ps-3">
                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" name="search" value="{{ request('search') }}" class="block block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Cari barang...">
                </div>
            </form>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="selectCol px-4 py-3"></th>
                        <th scope="col" class="px-4 py-3">Nama Barang</th>
                        <th scope="col" class="px-4 py-3">Kode</th>
                        <th scope="col" class="px-4 py-3">Stok Defect</th>
                        <th scope="col" class="px-4 py-3">Harga Diajukan</th>
                        <th scope="col" class="px-4 py-3">Alasan</th>
                        <th scope="col" class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($goods as $barang)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                            <td class="px-4 py-3"></td>
                            <th scope="row" class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $barang->nama_barang }}
                            </th>
                            <td class="px-4 py-3">{{ $barang->kode_barang }}</td>
                            <td class="px-4 py-3">{{ $barang->stok }} {{ $barang->satuan }}</td>
                            <td class="px-4 py-3">Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs">{{ $barang->alasan_pengajuan }}</td>
                            <td class="w-fit px-4 py-3">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-20 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">

                                        <button onclick="confirmApprove({{ $barang->id }})" class="group flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-4 w-4">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Setujui</span>
                                        </button>
                                        <button onclick="openTolakModal('defect', {{ $barang->id }}, '{{ $barang->nama_barang }}')" title="Tolak" class="group flex h-full cursor-pointer items-center justify-center bg-red-600 p-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle h-4 w-4">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="m15 9-6 6"></path>
                                                <path d="m9 9 6 6"></path>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Tolak</span>
                                        </button>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $goods->links() }}
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
        </script>

        @include('admin.defect-report.partials.modal_tolak')
</x-app-layout>
