<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            {{-- Bulk Actions --}}
            <div id="bulk-actions" class="hidden flex-row items-center space-x-2" data-approve-url="{{ route('supervisor.custom-penawaran.bulk-approval') }}" data-reject-url="{{ route('supervisor.custom-penawaran.bulk-approval') }}">
                <button id="bulk-approve" class="flex items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300">
                    Approve Selected (<span id="selected-count">0</span>)
                </button>
                <button id="bulk-reject" class="flex items-center justify-center rounded-lg bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300">
                    Reject Selected
                </button>
            </div>
        </div>
        <div class="p-4">

            {{-- Search --}}
            <form action="{{ route('supervisor.custom-penawaran.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search" value="{{ request('search') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Search" />
                </div>
            </form>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="selectCol w-4 px-4 py-3"></th>
                        <th class="px-4 py-3">No Penawaran</th>
                        <th class="px-4 py-3">Sales</th>
                        <th class="px-4 py-3">Kepada</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">Tanggal Dibuat</th>
                        <th class="w-fit px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($customPenawarans as $penawaran)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{-- ID Column for DataTables Select --}}
                            <td class="px-4 py-3">{{ $penawaran->id }}</td>
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
                            <td class="w-fit px-4 py-3">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-20 opacity-0">Placeholder</div>
                                    <div class="absolute right-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        <!-- View Detail -->
                                        <a href="{{ route('admin.custom-penawaran.show', $penawaran->id) }}" class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                        </a>

                                        <!-- Approve -->
                                        <form method="POST" action="{{ route('admin.custom-penawaran.approval', $penawaran->id) }}" class="m-0 inline p-0">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="group flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" onclick="return confirm('Apakah Anda yakin ingin menyetujui penawaran ini?')" title="Setujui">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-4 w-4">
                                                    <path d="M20 6 9 17l-5-5"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Setujui</span>
                                            </button>
                                        </form>

                                        <!-- Reject -->
                                        <button type="button" class="group flex h-full cursor-pointer items-center justify-center bg-red-600 p-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="rejectPenawaran({{ $penawaran->id }})" title="Tolak">
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

        <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
            {{ $customPenawarans->links() }}
        </div>

        <!-- Single Item Reject Modal (For Action Column) -->
        <div id="rejectModal" class="fixed inset-0 z-50 hidden h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50">
            <div class="relative top-20 mx-auto w-96 rounded-md border bg-white p-5 shadow-lg dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tolak Penawaran</h3>
                    <form id="rejectForm" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <textarea name="reason" rows="4" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Alasan penolakan..." required></textarea>
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="closeRejectModal()" class="mr-2 rounded-md bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">Batal</button>
                            <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Single Item Script
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

            // Bulk Action Script (Using DataTables API)
            $(document).ready(function() {
                // Access the DataTables instance using the ID
                const table = $('#DataTable').DataTable();

                function updateBulkActions() {
                    // Check selected rows
                    const selectedRows = table.rows({
                        selected: true
                    }).count();

                    if (selectedRows > 0) {
                        $('#bulk-actions').removeClass('hidden').addClass('flex');
                        $('#selected-count').text(selectedRows);
                    } else {
                        $('#bulk-actions').addClass('hidden').removeClass('flex');
                    }
                }

                // Listen for DataTables select/deselect events
                table.on('select deselect', function() {
                    updateBulkActions();
                });

                function getSelectedIds() {
                    // Map over the selected data and extract the first column (index 0) which contains the ID
                    // Note: row.data() returns an array for the row's data. Index 0 is the ID cell content.
                    return table.rows({
                        selected: true
                    }).data().toArray().map(row => row[0]);
                }

                // Bulk Approve
                $('#bulk-approve').on('click', function() {
                    const selectedIds = getSelectedIds();
                    if (selectedIds.length === 0) {
                        Swal.fire('Error', 'No items selected.', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Bulk Approve',
                        text: `Are you sure you want to approve ${selectedIds.length} items?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#15803d',
                        confirmButtonText: 'Yes, Approve All'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url = $('#bulk-actions').data('approve-url');
                            $.post(url, {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                ids: selectedIds,
                                action: 'approve'
                            }, function(res) {
                                if (res.success) {
                                    Swal.fire('Approved!', res.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', res.message || 'Something went wrong.', 'error');
                                }
                            }).fail(function() {
                                Swal.fire('Error', 'Server error.', 'error');
                            });
                        }
                    });
                });

                // Bulk Reject
                $('#bulk-reject').on('click', function() {
                    const selectedIds = getSelectedIds();
                    if (selectedIds.length === 0) {
                        Swal.fire('Error', 'No items selected.', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Bulk Reject',
                        text: `Provide a reason for rejecting ${selectedIds.length} items:`,
                        input: 'textarea',
                        inputPlaceholder: 'Reason for rejection...',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#b91c1c',
                        confirmButtonText: 'Reject All',
                        preConfirm: (reason) => {
                            if (!reason) {
                                Swal.showValidationMessage('Reason is required');
                            }
                            return reason;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url = $('#bulk-actions').data('reject-url');
                            $.post(url, {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                ids: selectedIds,
                                action: 'reject',
                                reason: result.value
                            }, function(res) {
                                if (res.success) {
                                    Swal.fire('Rejected!', res.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', res.message || 'Something went wrong.', 'error');
                                }
                            }).fail(function() {
                                Swal.fire('Error', 'Server error.', 'error');
                            });
                        }
                    });
                });
            });
        </script>
    </div>
</x-app-layout>
