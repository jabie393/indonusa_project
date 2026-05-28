<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white p-4 shadow-md dark:bg-gray-800">
        {{-- Search --}}
        <form action="{{ route('supervisor.custom-penawaran.index') }}"
            method="GET"
            class="block pl-2">
            <label for="topbar-search"
                class="sr-only">Search</label>
            <div class="relative md:w-96">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400"
                        fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                        </path>
                    </svg>
                </div>
                <input type="search"
                    name="search"
                    id="topbar-search"
                    value="{{ request('search') }}"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                    placeholder="Search" />
            </div>
        </form>
    </div>

    <div class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="shrink-0 flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table id=""
                class="sortable w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="text-nowrap px-4 py-3">No Penawaran</th>
                        <th class="text-nowrap px-4 py-3">Sales</th>
                        <th class="text-nowrap px-4 py-3">Kepada</th>
                        <th class="text-nowrap px-4 py-3">Subject</th>
                        <th class="text-nowrap px-4 py-3">Tanggal Dibuat</th>
                        <th class="flex justify-center text-nowrap px-4 py-3 no-sort text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-nowrap">
                    @forelse($customPenawarans as $penawaran)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            {{ $penawaran->penawaran_number }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $penawaran->sales->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $penawaran->to }}
                        </td>
                        <td class="px-4 py-3">
                            {{ Str::limit($penawaran->subject, 50) }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $penawaran->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right align-middle">
                            <div class="flex justify-center">
                                <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out divide-x divide-gray-200 dark:divide-gray-600 dark:border-gray-600 dark:bg-gray-700">
                                    <!-- View Detail -->
                                    <a href="{{ route('admin.custom-penawaran.show', $penawaran->id) }}"
                                        class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-eye h-4 w-4">
                                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                            <circle cx="12"
                                                cy="12"
                                                r="3"></circle>
                                        </svg>
                                        <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                    </a>

                                    <!-- Approve -->
                                    <form method="POST"
                                        action="{{ route('admin.custom-penawaran.approval', $penawaran->id) }}"
                                        class="approve-form m-0 p-0"
                                        data-confirm-text="Apakah Anda yakin ingin menyetujui penawaran ini?">
                                        @csrf
                                        <input type="hidden"
                                            name="action"
                                            value="approve">
                                        <button type="submit"
                                            class="group flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                            title="Approve">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-check h-4 w-4">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Approve</span>
                                        </button>
                                    </form>

                                    <!-- Reject -->
                                    <button type="button"
                                        class="group flex h-full cursor-pointer items-center justify-center bg-red-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                        onclick="openTolakModal('custom', {{ $penawaran->id }}, '{{ $penawaran->penawaran_number }}')"
                                        title="Reject">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-x-circle h-4 w-4">
                                            <circle cx="12"
                                                cy="12"
                                                r="10"></circle>
                                            <path d="m15 9-6 6"></path>
                                            <path d="m9 9 6 6"></path>
                                        </svg>
                                        <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Reject</span>
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

        <nav class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 bg-white p-4 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $customPenawarans->firstItem() ?? 0 }}-{{ $customPenawarans->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $customPenawarans->total() ?? $customPenawarans->count() }}</span>
                </span>
                <form method="GET"
                    action="{{ route('supervisor.custom-penawaran.index') }}">
                    <input type="hidden"
                        name="search"
                        value="{{ request('search') }}">
                    <select name="perPage"
                        onchange="this.form.submit()"
                        class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}"
                            {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
            </div>
            <div>
                {{ $customPenawarans->links() }}
            </div>
        </nav>

        <script>
            // Single Item Script
            let currentPenawaranId = null;
        </script>
    </div>

    @include('admin.supervisor.custom-penawaran.partials.modal_tolak')

    @vite(['resources/js/table-sort.js'])
</x-app-layout>
