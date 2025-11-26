<x-app-layout>
    <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
        <div
            class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Kelola Pics</h2>
            </div>
            <div class="flex w-full flex-col md:w-auto md:flex-row md:py-0">
                <div
                    class="mr-5 flex max-w-full shrink-0 flex-col items-stretch justify-end space-y-2 py-5 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0 md:py-0">
                    {{-- Search --}}
                    <form action="" method="GET" class="block pl-2">
                        <label for="topbar-search" class="sr-only">Search</label>
                        <div class="relative md:w-64 md:w-96">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="search" name="search" id="topbar-search dt-search-0"
                                aria-controls="warehouseTable" value="{{ request('search') }}"
                                class="dt-input block w-full rounded-lg bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                placeholder="Search" />
                        </div>
                    </form>
                </div>

                <div class="p-4">
                    <button onclick="createPicsModal.showModal()"
                        class="flex items-center justify-center rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Tambah Pics
                    </button>
                </div>
            </div>
        </div>


        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">No. HP</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @foreach ($pics as $pic)
                        <tr class="dark:border-gray-700">
                            <td class="px-4 py-2">{{ $pic->name }}</td>
                            <td class="px-4 py-2">{{ $pic->phone }}</td>
                            <td class="px-4 py-2">{{ $pic->email }}</td>
                            <td class="px-4 py-2">{{ $pic->position }}</td>
                            <td class="px-4 py-2">
                                <button
                                    class="editPicsButton mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    data-id="{{ $pic->id }}" data-name="{{ $pic->name }}" data-phone="{{ $pic->phone }}"
                                    data-email="{{ $pic->email }}" data-position="{{ $pic->position }}">
                                    Edit
                                </button>
                                <form action="{{ route('pics.destroy', $pic->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="mb-2 me-2 rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                        onclick="confirmDelete(() => this.closest('form').submit())">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                {{-- <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $salesUsers->firstItem() ?? 0 }}-{{ $salesUsers->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $salesUsers->total() ?? $salesUsers->count() }}</span>
                </span>
                <form method="GET" action="{{ route('akun-sales.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()" class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span> --}}
            </div>
            <div>
                {{-- {{ $salesUsers->links() }} --}}
            </div>
        </nav> -->

    </div>

    <!-- Modals -->
    @include('admin.pics.partials.pics-modal')

    <!-- JS -->
    @vite(['resources/js/pics.js'])

</x-app-layout>
