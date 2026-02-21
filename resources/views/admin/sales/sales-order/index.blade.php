<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">

            {{-- <a href="{{ route('sales.sales-order.create') }}" class="inline-block rounded-lg bg-[#225A97] px-6 py-3 text-center font-semibold text-white hover:bg-[#1c4d81]">
                + Buat Sales Order
            </a> --}}
        </div>
    </div>

    <div class="mt-6">
        @if (session('title'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-900 dark:bg-green-900/30">
                <p class="font-semibold text-green-800 dark:text-green-300">{{ session('title') }}</p>
                @if (session('text'))
                    <p class="mt-1 text-sm text-green-700 dark:text-green-400">{{ session('text') }}</p>
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-900/30">
                <p class="font-semibold text-red-800 dark:text-red-300">Terjadi kesalahan:</p>
                <ul class="mt-2 list-inside list-disc text-sm text-red-700 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>


    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mt-6 overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
            <h3 class="flex items-center gap-2 text-xl font-semibold leading-none tracking-tight">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                    <line x1="4" x2="4" y1="15" y2="21"></line>
                    <line x1="12" x2="12" y1="15" y2="21"></line>
                    <line x1="20" x2="20" y1="15" y2="21"></line>
                </svg>
                Daftar Sales Order
            </h3>
        </div>

        <div class="border-b border-gray-200 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-700">
            <div class="flex flex-col gap-2 md:flex-row">
                <div class="relative flex-1">
                    <input type="text" id="searchInput" placeholder="Cari berdasarkan No.SO, Customer, Subject, atau Email..." value="{{ $search }}" autocomplete="off" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">

                    <!-- Search Results Dropdown -->
                    <div id="searchResults" class="absolute left-0 right-0 top-full z-50 mt-1 hidden max-h-96 overflow-y-auto rounded-lg border border-gray-300 bg-white shadow-lg dark:border-gray-500 dark:bg-gray-600">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="searchBtn" class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-6 py-2 font-semibold text-white hover:bg-[#19426d]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('sales.sales-order.index') }}" class="whitespace-nowrap rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-600">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">No.PO</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">No. Request</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">No. Penawaran</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">No. SO</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Customer</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Jumlah Item</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">Total</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">Diskon</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Berlaku Sampai</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $row['no_po'] ?? '-' }}</span>
                                    <div id="image-po-preview-{{ $row['id'] }}-{{ $row['type'] }}" class="mt-1">
                                        @if (isset($row['image_po']) && $row['image_po'])
                                            <div class="group relative inline-block">
                                                <a href="{{ Storage::url($row['image_po']) }}" target="_blank">
                                                    <img src="{{ Storage::url($row['image_po']) }}" alt="PO Image" class="h-10 w-10 rounded border border-gray-300 object-cover shadow-sm transition-transform hover:scale-110" />
                                                </a>
                                                <button class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white opacity-0 shadow-lg transition-opacity group-hover:opacity-100" onclick="handleDeleteImage('{{ $row['type'] }}', {{ $row['id'] }}, 'po')" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18" />
                                                        <path d="m6 6 12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @elseif($row['type'] === 'request_order')
                                            <label class="inline-flex cursor-pointer items-center gap-1 rounded-md border border-green-500 bg-white px-2 py-1 text-[10px] font-semibold text-green-600 shadow-sm transition-colors hover:bg-green-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="17 8 12 3 7 8" />
                                                    <line x1="12" x2="12" y1="3" y2="15" />
                                                </svg>
                                                Upload PO
                                                <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="handleUploadImage(this, 'request_order', {{ $row['id'] }}, 'po')">
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $row['no_request'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['no_penawaran'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $row['no_sales_order'] ?? '-' }}</span>
                                    <div id="image-so-preview-{{ $row['id'] }}-{{ $row['type'] }}" class="mt-1">
                                        @if (isset($row['image_so']) && $row['image_so'])
                                            <div class="group relative inline-block">
                                                <a href="{{ Storage::url($row['image_so']) }}" target="_blank">
                                                    <img src="{{ Storage::url($row['image_so']) }}" alt="SO Image" class="h-10 w-10 rounded border border-gray-300 object-cover shadow-sm transition-transform hover:scale-110" />
                                                </a>
                                                <button class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white opacity-0 shadow-lg transition-opacity group-hover:opacity-100" onclick="handleDeleteImage('{{ $row['type'] }}', {{ $row['id'] }}, 'so')" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18" />
                                                        <path d="m6 6 12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @elseif($row['type'] === 'request_order')
                                            <label class="inline-flex cursor-pointer items-center gap-1 rounded-md border border-blue-500 bg-white px-2 py-1 text-[10px] font-semibold text-blue-600 shadow-sm transition-colors hover:bg-blue-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="17 8 12 3 7 8" />
                                                    <line x1="12" x2="12" y1="3" y2="15" />
                                                </svg>
                                                Upload SO
                                                <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="handleUploadImage(this, 'request_order', {{ $row['id'] }}, 'so')">
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $row['tanggal'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['customer_name'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">{{ $row['jumlah_item'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($row['total'] ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['diskon'] ?? 0 }}%</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">
                                    {{ ucfirst($row['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $row['berlaku_sampai'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <a href="{{ $row['aksi_url'] }}" class="w-full rounded-lg bg-blue-600 px-3 py-1.5 text-center text-xs font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">Lihat</a>

                                    @if ($row['type'] === 'sales_order')
                                        <div id="image-preview-aksi-{{ $row['id'] }}-sales_order">
                                            @if (isset($row['image']) && $row['image'])
                                                <div class="group relative inline-block">
                                                    <a href="{{ $row['image_url'] }}" target="_blank">
                                                        <img src="{{ $row['image_url'] }}" alt="SO Image" class="h-8 w-8 rounded border border-gray-300 object-cover shadow-sm" />
                                                    </a>
                                                    <button class="absolute -right-1.5 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white opacity-0 shadow-sm transition-opacity group-hover:opacity-100" onclick="handleDeleteImage('sales_order', {{ $row['id'] }}, 'main')" title="Ganti">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M18 6 6 18" />
                                                            <path d="m6 6 12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @else
                                                <label class="shadow-xs inline-flex cursor-pointer items-center gap-1 rounded-md border border-gray-300 bg-white px-2 py-1 text-[9px] font-semibold text-gray-700 transition-colors hover:bg-gray-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" x2="12" y1="3" y2="15" />
                                                    </svg>
                                                    Gambar
                                                    <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="handleUploadImage(this, 'sales_order', {{ $row['id'] }}, 'main')">
                                                </label>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-400 dark:text-gray-600">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                                <p class="text-lg font-semibold">
                                    @if ($search)
                                        Tidak ada hasil untuk pencarian "{{ $search }}"
                                    @else
                                        Tidak ada data
                                    @endif
                                </p>
                                <p class="mt-1 text-sm">
                                    @if ($search)
                                        Coba ubah kata kunci pencarian atau <a href="{{ route('sales.sales-order.index') }}" class="text-blue-600 hover:underline">reset pencarian</a>
                                    @else
                                        Mulai buat sales order baru dengan klik tombol di atas
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (!$isSearch && $salesOrders && $salesOrders->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-600">
                {{ $salesOrders->links() }}
            </div>
        @endif
    </div>

    {{-- $penawaranQuery section dihapus, sudah digabung ke tabel utama --}}

    <!-- Modal Detail Penawaran -->
    <div id="penawaranModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/50 p-4">
        <div class="flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
            <!-- Header -->
            <div class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <h2 class="text-2xl font-bold">Detail Penawaran</h2>
                <button id="closeModal" class="rounded-full p-2 text-white transition hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div id="modalContent" class="flex-1 overflow-y-auto p-6">
                <!-- Will be filled by JavaScript -->
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const searchBtn = document.getElementById('searchBtn');
        let searchTimeout;

        // Autocomplete search dengan AJAX
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 1) {
                searchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('sales.sales-order.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data.length > 0) {
                            displaySearchResults(data.data);
                        } else {
                            searchResults.innerHTML = `
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    <p>Tidak ada hasil yang ditemukan</p>
                                </div>
                            `;
                            searchResults.classList.remove('hidden');
                        }
                    });
            }, 300);
        });

        function displaySearchResults(results) {
            if (results.length === 0) {
                searchResults.innerHTML = `
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        <p>Data tidak ditemukan</p>
                    </div>
                `;
                searchResults.classList.remove('hidden');
                return;
            }

            searchResults.innerHTML = results.map(item => {
                return `
                    <div class="p-3 border-b border-gray-200 dark:border-gray-500 flex justify-between items-center">
                        <div>
                            <strong>${item.sales_order_number}</strong> - ${item.customer_name}
                            <span class="ml-2 text-xs font-bold px-2 py-0.5 rounded ${item.type === 'penawaran' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">${item.badge}</span>
                            <span class="ml-2 text-xs font-bold px-2 py-0.5 rounded bg-yellow-100 text-yellow-800">NO.PO: ${item.no_po ? item.no_po : '<i>tidak ada</i>'}</span>
                        </div>
                        <a href="${item.url}" class="text-blue-600 hover:underline">Detail</a>
                    </div>
                `;
            }).join('');
            searchResults.classList.remove('hidden');
        }

        function getStatusColor(status) {
            const colors = {
                'pending': {
                    bg: '#fef3c7',
                    text: '#b45309'
                },
                'in_process': {
                    bg: '#dbeafe',
                    text: '#1e40af'
                },
                'shipped': {
                    bg: '#e9d5ff',
                    text: '#6b21a8'
                },
                'completed': {
                    bg: '#dcfce7',
                    text: '#166534'
                },
                'cancelled': {
                    bg: '#fee2e2',
                    text: '#991b1b'
                },
                'draft': {
                    bg: '#f3f4f6',
                    text: '#374151'
                },
                'sent': {
                    bg: '#dbeafe',
                    text: '#1e40af'
                },
                'approved': {
                    bg: '#dcfce7',
                    text: '#166534'
                },
                'rejected': {
                    bg: '#fee2e2',
                    text: '#991b1b'
                },
            };
            return colors[status] || {
                bg: '#f3f4f6',
                text: '#374151'
            };
        }

        function getStatusLabel(status) {
            const labels = {
                'pending': 'Pending',
                'in_process': 'Dalam Proses',
                'shipped': 'Dikirim',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan',
                'draft': 'Draft',
                'sent': 'Terkirim',
                'approved': 'Disetujui',
                'rejected': 'Ditolak',
            };
            return labels[status] || status;
        }

        // Tombol search untuk form submission
        searchBtn.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query) {
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('sales.sales-order.index') }}';
                form.innerHTML = `<input type="hidden" name="search" value="${query}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Enter key untuk search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBtn.click();
            }
        });

        // Close dropdown ketika klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#searchInput') && !e.target.closest('#searchResults')) {
                searchResults.classList.add('hidden');
            }
        });

        // Modal handling
        const modal = document.getElementById('penawaranModal');
        const closeBtn = document.getElementById('closeModal');
        const modalContent = document.getElementById('modalContent');

        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Function untuk display modal penawaran detail
        function showPenawaranDetail(penawaranId) {
            fetch(`{{ route('sales.sales-order.penawaran-detail') }}?id=${penawaranId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;
                        const itemsHtml = data.items.map(item => `
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-3">${item.nama_barang}</td>
                                <td class="px-4 py-3 text-center">${item.qty}</td>
                                <td class="px-4 py-3 text-center">${item.satuan}</td>
                                <td class="px-4 py-3 text-right">Rp ${formatCurrency(item.harga)}</td>
                                <td class="px-4 py-3 text-center">${item.diskon}%</td>
                                <td class="px-4 py-3 text-right font-semibold">Rp ${formatCurrency(item.subtotal)}</td>
                            </tr>
                        `).join('');

                        const statusColor = getStatusColor(data.status);

                        const statusLabel = {
                            'draft': 'Draft',
                            'sent': 'Terkirim',
                            'approved': 'Disetujui',
                            'rejected': 'Ditolak',
                        } [data.status] || data.status;

                        modalContent.innerHTML = `
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">No Penawaran</h3>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">${data.penawaran_number}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal</h3>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">${data.date}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tujuan (To)</h3>
                                    <p class="font-semibold text-gray-900 dark:text-white">${data.to}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Attn (Up)</h3>
                                    <p class="font-semibold text-gray-900 dark:text-white">${data.up || '-'}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</h3>
                                    <p class="font-semibold text-gray-900 dark:text-white">${data.email}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</h3>
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold" style="
                                        background-color: ${statusColor.bg};
                                        color: ${statusColor.text};
                                    ">
                                        ${statusLabel}
                                    </span>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-gray-700">

                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subject</h3>
                                <p class="text-gray-900 dark:text-white">${data.subject}</p>
                            </div>

                            ${data.intro_text ? `
                                                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Teks Pembuka</h3>
                                                        <p class="text-gray-700 dark:text-gray-300">${data.intro_text}</p>
                                                    </div>
                                                ` : ''}

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Items</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Barang</th>
                                                <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">Qty</th>
                                                <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">Satuan</th>
                                                <th class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Harga</th>
                                                <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">Diskon</th>
                                                <th class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${itemsHtml}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <div class="flex justify-end gap-8">
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">Subtotal</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp ${formatCurrency(data.subtotal)}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">PPN (Tax)</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp ${formatCurrency(data.tax)}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">Grand Total</p>
                                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">Rp ${formatCurrency(data.grand_total)}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        modal.classList.remove('hidden');
                    }
                });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        function handleUploadImage(input, type, id, imageType) {
            if (!input.files.length) return;
            const file = input.files[0];

            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                Swal.fire('Format Salah!', 'Format file harus JPG, JPEG, atau PNG', 'error');
                input.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire('File Terlalu Besar!', 'Ukuran file maksimal 2MB', 'error');
                input.value = '';
                return;
            }

            const formData = new FormData();
            let endpoint = '';
            let fieldName = '';

            if (imageType === 'po') {
                endpoint = `/request-order/${id}/upload-image-po`;
                fieldName = 'image_po';
            } else if (imageType === 'so') {
                endpoint = `/request-order/${id}/upload-image-so`;
                fieldName = 'image_so';
            } else {
                endpoint = `/sales-order/${id}/upload-image`;
                fieldName = 'image';
            }

            formData.append(fieldName, file);
            formData.append('_token', '{{ csrf_token() }}');

            let containerId = '';
            if (imageType === 'po') containerId = `image-po-preview-${id}-${type}`;
            else if (imageType === 'so') containerId = `image-so-preview-${id}-${type}`;
            else containerId = `image-preview-aksi-${id}-${type}`;

            const container = document.getElementById(containerId);
            const originalContent = container.innerHTML;
            container.innerHTML = `<div class="flex items-center justify-center p-2"><svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>`;

            fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Gambar berhasil diupload.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        if (imageType === 'po' || imageType === 'so') {
                            container.innerHTML = `
                            <div class="relative inline-block group">
                                <a href="${data.image_url}" target="_blank">
                                    <img src="${data.image_url}" alt="${imageType.toUpperCase()} Image" class="w-10 h-10 object-cover rounded border border-gray-300 shadow-sm transition-transform hover:scale-110" />
                                </a>
                                <button class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg" onclick="handleDeleteImage('${type}', ${id}, '${imageType}')" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>`;
                        } else {
                            container.innerHTML = `
                            <div class="relative inline-block group">
                                <a href="${data.image_url}" target="_blank">
                                    <img src="${data.image_url}" alt="Image" class="w-8 h-8 object-cover rounded border border-gray-300 shadow-sm" />
                                </a>
                                <button class="absolute -top-1.5 -right-1.5 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm" onclick="handleDeleteImage('${type}', ${id}, 'main')" title="Ganti">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>`;
                        }
                    } else {
                        Swal.fire('Gagal!', data.message || 'Upload gagal', 'error');
                        container.innerHTML = originalContent;
                    }
                })
                .catch(() => {
                    Swal.fire('Gagal!', 'Terjadi kesalahan sistem', 'error');
                    container.innerHTML = originalContent;
                });
        }

        function handleDeleteImage(type, id, imageType) {
            Swal.fire({
                title: 'Hapus gambar?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let endpoint = '';
                    if (imageType === 'po') endpoint = `/request-order/${id}/upload-image-po`;
                    else if (imageType === 'so') endpoint = `/request-order/${id}/upload-image-so`;
                    else endpoint = `/sales-order/${id}/upload-image`;

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'DELETE');

                    fetch(endpoint, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Gambar telah dihapus.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                let containerId = '';
                                if (imageType === 'po') containerId = `image-po-preview-${id}-${type}`;
                                else if (imageType === 'so') containerId = `image-so-preview-${id}-${type}`;
                                else containerId = `image-preview-aksi-${id}-${type}`;

                                const container = document.getElementById(containerId);

                                if (imageType === 'po') {
                                    container.innerHTML = `
                                    <label class="cursor-pointer inline-flex items-center gap-1 px-2 py-1 bg-white border border-green-500 text-green-600 rounded-md text-[10px] font-semibold hover:bg-green-50 transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                        Upload PO
                                        <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="handleUploadImage(this, '${type}', ${id}, 'po')">
                                    </label>`;
                                } else if (imageType === 'so') {
                                    container.innerHTML = `
                                    <label class="cursor-pointer inline-flex items-center gap-1 px-2 py-1 bg-white border border-blue-500 text-blue-600 rounded-md text-[10px] font-semibold hover:bg-blue-50 transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                        Upload SO
                                        <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="handleUploadImage(this, '${type}', ${id}, 'so')">
                                    </label>`;
                                } else {
                                    container.innerHTML = `
                                    <label class="cursor-pointer inline-flex items-center gap-1 px-2 py-1 bg-white border border-gray-300 text-gray-700 rounded-md text-[9px] font-semibold hover:bg-gray-50 transition-colors shadow-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                        Gambar
                                        <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="handleUploadImage(this, '${type}', ${id}, 'main')">
                                    </label>`;
                                }
                            } else {
                                Swal.fire('Gagal!', data.message || 'Gagal menghapus gambar', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Gagal!', 'Terjadi kesalahan sistem', 'error');
                        });
                }
            });
        }
    </script>
</x-app-layout>
