<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Daftar Sales Order</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Kelola semua sales order Anda</p>
            </div>
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
            <div class="flex gap-2 flex-col md:flex-row">
                <div class="flex-1 relative">
                    <input type="text" id="searchInput" placeholder="Cari berdasarkan No.SO, Customer, Subject, atau Email..." value="{{ $search }}" autocomplete="off" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    
                    <!-- Search Results Dropdown -->
                    <div id="searchResults" class="hidden absolute z-50 top-full left-0 right-0 mt-1 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg shadow-lg max-h-96 overflow-y-auto">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="searchBtn" class="rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white hover:bg-blue-700 flex items-center gap-2 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('sales.sales-order.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-600 whitespace-nowrap">
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
                            <td class="px-4 py-3">{{ $row['no_po'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['no_request'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['no_penawaran'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                {{ $row['no_sales_order'] ?? '-' }}
                                <div id="image-so-preview-{{ $row['id'] }}">
                                    @if(isset($row['image_so']) && $row['image_so'])
                                        <a href="{{ Storage::url($row['image_so']) }}" target="_blank">
                                            <img src="{{ Storage::url($row['image_so']) }}" alt="SO Image" style="width:50px;height:50px;object-fit:cover;border-radius:4px;border:1px solid #ccc;display:inline-block;vertical-align:middle;" />
                                        </a>
                                        <button class="inline-block ml-2 text-xs text-blue-600 hover:underline" onclick="removeImageSO({{ $row['id'] }})">Hapus</button>
                                    @else
                                        <input type="file" id="image-so-input-{{ $row['id'] }}" style="display:inline-block;width:120px;" accept="image/jpeg,image/png,image/jpg" onchange="uploadImageSO({{ $row['id'] }})">
                                    @endif
                                </div>
                            </td>
                            @push('scripts')
                            <script>
                            function uploadImageSO(id) {
                                const input = document.getElementById('image-so-input-' + id);
                                if (!input.files.length) return;
                                const file = input.files[0];
                                if (!['image/jpeg','image/png','image/jpg'].includes(file.type)) {
                                    alert('Format file harus JPG, JPEG, atau PNG');
                                    input.value = '';
                                    return;
                                }
                                if (file.size > 2 * 1024 * 1024) {
                                    alert('Ukuran file maksimal 2MB');
                                    input.value = '';
                                    return;
                                }
                                const formData = new FormData();
                                formData.append('image_so', file);
                                formData.append('_token', '{{ csrf_token() }}');
                                fetch('/request-order/' + id + '/upload-image-so', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'Accept': 'application/json',
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        document.getElementById('image-so-preview-' + id).innerHTML =
                                            `<a href="${data.image_url}" target="_blank"><img src="${data.image_url}" alt="SO Image" style="width:50px;height:50px;object-fit:cover;border-radius:4px;border:1px solid #ccc;display:inline-block;vertical-align:middle;" /></a>` +
                                            `<button class='inline-block ml-2 text-xs text-blue-600 hover:underline' onclick='removeImageSO(${id})'>Hapus</button>`;
                                    } else {
                                        alert(data.message || 'Upload gagal');
                                    }
                                })
                                .catch(() => {
                                    alert('Upload gagal');
                                });
                            }

                            function removeImageSO(id) {
                                if (!confirm('Hapus gambar SO ini?')) return;
                                const formData = new FormData();
                                formData.append('_token', '{{ csrf_token() }}');
                                formData.append('_method', 'DELETE');
                                fetch('/request-order/' + id + '/upload-image-so', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'Accept': 'application/json',
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        document.getElementById('image-so-preview-' + id).innerHTML =
                                            `<input type='file' id='image-so-input-${id}' style='display:inline-block;width:120px;' accept='image/jpeg,image/png,image/jpg' onchange='uploadImageSO(${id})'>`;
                                    } else {
                                        alert(data.message || 'Gagal menghapus gambar');
                                    }
                                })
                                .catch(() => {
                                    alert('Gagal menghapus gambar');
                                });
                            }
                            </script>
                            @endpush
                            <td class="px-4 py-3">{{ $row['tanggal'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['customer_name'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">{{ $row['jumlah_item'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($row['total'] ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['diskon'] ?? 0 }}%</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800">
                                    {{ ucfirst($row['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $row['berlaku_sampai'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ $row['aksi_url'] }}" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">Lihat</a>
                                @if(isset($row['type']) && $row['type'] === 'sales_order' && !empty($row['id']))
                                    <div style="margin-top:6px;">
                                        <span id="so-image-preview-aksi-{{ $row['id'] }}">
                                            @php $soModel = \App\Models\SalesOrder::find($row['id']); @endphp
                                            @if($soModel && $soModel->image)
                                                <a href="{{ $soModel->image_url }}" target="_blank">
                                                    <img src="{{ $soModel->image_url }}" alt="SO Image" style="width:32px;height:32px;object-fit:cover;border-radius:4px;border:1px solid #ccc;display:inline-block;vertical-align:middle;" />
                                                </a>
                                                <button class="inline-block ml-1 text-xs text-blue-600 hover:underline" onclick="removeSOImageAksi({{ $row['id'] }})">Ganti</button>
                                            @else
                                                <input type="file" id="so-image-input-aksi-{{ $row['id'] }}" style="display:inline-block;width:90px;" accept="image/jpeg,image/png,image/jpg" onchange="uploadSOImageAksi({{ $row['id'] }})">
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </td>
                        @push('scripts')
                        <script>
                        function uploadSOImageAksi(id) {
                            const input = document.getElementById('so-image-input-aksi-' + id);
                            if (!input.files.length) return;
                            const file = input.files[0];
                            if (!['image/jpeg','image/png','image/jpg'].includes(file.type)) {
                                alert('Format file harus JPG, JPEG, atau PNG');
                                input.value = '';
                                return;
                            }
                            if (file.size > 2 * 1024 * 1024) {
                                alert('Ukuran file maksimal 2MB');
                                input.value = '';
                                return;
                            }
                            const formData = new FormData();
                            formData.append('image', file);
                            formData.append('_token', '{{ csrf_token() }}');
                            fetch('/sales-order/' + id + '/upload-image', {
                                method: 'POST',
                                body: formData,
                                headers: { 'Accept': 'application/json' },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    document.getElementById('so-image-preview-aksi-' + id).innerHTML =
                                        `<a href="${data.image_url}" target="_blank"><img src="${data.image_url}" alt="SO Image" style="width:32px;height:32px;object-fit:cover;border-radius:4px;border:1px solid #ccc;display:inline-block;vertical-align:middle;" /></a>` +
                                        `<button class='inline-block ml-1 text-xs text-blue-600 hover:underline' onclick='removeSOImageAksi(${id})'>Ganti</button>`;
                                } else {
                                    alert(data.message || 'Upload gagal');
                                }
                            })
                            .catch(() => { alert('Upload gagal'); });
                        }
                        function removeSOImageAksi(id) {
                            if (!confirm('Hapus gambar SO ini?')) return;
                            const formData = new FormData();
                            formData.append('_token', '{{ csrf_token() }}');
                            formData.append('_method', 'DELETE');
                            fetch('/sales-order/' + id + '/upload-image', {
                                method: 'POST',
                                body: formData,
                                headers: { 'Accept': 'application/json' },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    document.getElementById('so-image-preview-aksi-' + id).innerHTML =
                                        `<input type='file' id='so-image-input-aksi-${id}' style='display:inline-block;width:90px;' accept='image/jpeg,image/png,image/jpg' onchange='uploadSOImageAksi(${id})'>`;
                                } else {
                                    alert(data.message || 'Gagal menghapus gambar');
                                }
                            })
                            .catch(() => { alert('Gagal menghapus gambar'); });
                        }
                        </script>
                        @endpush
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
    <div id="penawaranModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-4xl max-h-[90vh] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white flex justify-between items-center">
                <h2 class="text-2xl font-bold">Detail Penawaran</h2>
                <button id="closeModal" class="text-white hover:bg-blue-700 rounded-full p-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div id="modalContent" class="overflow-y-auto flex-1 p-6">
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
                'pending': { bg: '#fef3c7', text: '#b45309' },
                'in_process': { bg: '#dbeafe', text: '#1e40af' },
                'shipped': { bg: '#e9d5ff', text: '#6b21a8' },
                'completed': { bg: '#dcfce7', text: '#166534' },
                'cancelled': { bg: '#fee2e2', text: '#991b1b' },
                'draft': { bg: '#f3f4f6', text: '#374151' },
                'sent': { bg: '#dbeafe', text: '#1e40af' },
                'approved': { bg: '#dcfce7', text: '#166534' },
                'rejected': { bg: '#fee2e2', text: '#991b1b' },
            };
            return colors[status] || { bg: '#f3f4f6', text: '#374151' };
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
                        }[data.status] || data.status;

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
    </script>
</x-app-layout>