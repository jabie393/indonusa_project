<x-app-layout>

	<div>
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

	<div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl bg-white shadow-md dark:bg-gray-800">
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
						<a href="{{ route('ga.sales-order.index') }}" class="whitespace-nowrap rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-600">
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
        <div class="flex gap-1">
            @if (!empty($row['image_po']))
                <a href="{{ Storage::url($row['image_po']) }}" target="_blank">
                    <img src="{{ Storage::url($row['image_po']) }}"
                         alt="PO Image"
                         class="h-10 w-10 rounded border border-gray-300 object-cover shadow-sm transition-transform hover:scale-110" />
                </a>
            @endif
            @if (!empty($row['pdf_po']))
                <a href="{{ Storage::url($row['pdf_po']) }}" target="_blank">
                    <div class="flex h-10 w-10 items-center justify-center rounded border border-red-300 bg-red-50 transition-transform hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                </a>
            @endif
        </div>
    </div>
</td>
							<td class="px-4 py-3">{{ $row['no_request'] ?? '-' }}</td>
							<td class="px-4 py-3">{{ $row['no_penawaran'] ?? '-' }}</td>
							<td class="px-4 py-3">{{ $row['no_sales_order'] ?? '-' }}</td>
							<td class="px-4 py-3">{{ $row['tanggal'] ?? '-' }}</td>
							<td class="px-4 py-3">{{ $row['customer_name'] ?? '-' }}</td>
							<td class="px-4 py-3 text-center">{{ $row['jumlah_item'] ?? '-' }}</td>
							<td class="px-4 py-3 text-right">Rp {{ number_format($row['total'] ?? 0, 0, ',', '.') }}</td>
							<td class="px-4 py-3 text-right">{{ $row['diskon'] ?? 0 }}%</td>
							<td class="px-4 py-3 text-center">
								@php
									$statusClass =
										[
											'Pending' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
											'Open' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
											'Dikirim ke Supervisor' => 'bg-blue-50 text-blue-700 inset-ring inset-ring-blue-600',
											'Disetujui Supervisor' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
											'Ditolak Supervisor' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-600',
											'Dikirim ke Gudang' => 'bg-blue-50 text-blue-700 inset-ring inset-ring-blue-600',
											'Disetujui Gudang' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
											'Ditolak Gudang' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-600',
											'Selesai' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
											'Tidak Selesai' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-600',
										][$row['status']] ?? 'bg-gray-100 text-gray-800 inset-ring inset-ring-gray-600';
								@endphp
								<div class="flex items-center justify-center gap-2">
									<span class="{{ $statusClass }} badge">
										{{ $row['status'] }}
									</span>
								</div>
							</td>
							<td class="px-4 py-3">
								@if (!empty($row['berlaku_sampai'])  && $row['berlaku_sampai'] !== '-')
									{{ $row['berlaku_sampai'] }}
								@else
									-
								@endif
							</td>
							<td class="px-4 py-3 text-center">
								<a href="{{ route('ga.sales-order.invoice', $row['id']) }}?type={{ $row['type'] }}"
								   target="_blank"
								   class="inline-flex items-center gap-1 rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white hover:bg-green-700">
									<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
										 fill="none" stroke="currentColor" stroke-width="2.5"
										 stroke-linecap="round" stroke-linejoin="round">
										<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
										<polyline points="14 2 14 8 20 8"/>
										<path d="M9 13h6"/><path d="M9 17h3"/>
									</svg>
									Invoice
								</a>
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
										Coba ubah kata kunci pencarian atau <a href="{{ route('ga.sales-order.index') }}" class="text-blue-600 hover:underline">reset pencarian</a>
									@else
										Data sales order belum tersedia
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
				fetch(`{{ route('ga.sales-order.search') }}?q=${encodeURIComponent(query)}`)
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

		function selectSearchResult(query) {
			searchInput.value = query;
			searchResults.classList.add('hidden');

			// Trigger actual search
			const form = document.createElement('form');
			form.method = 'GET';
			form.action = '{{ route('ga.sales-order.index') }}';
			form.innerHTML = `<input type="hidden" name="search" value="${query}">`;
			document.body.appendChild(form);
			form.submit();
		}

		function displaySearchResults(results) {
			if (!results || results.length === 0) {
				searchResults.innerHTML = `
					<div class="p-6 text-center">
						<svg class="mx-auto h-10 w-10 text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
						<p class="text-gray-500 dark:text-gray-400 font-medium">Data tidak ditemukan</p>
					</div>
				`;
				searchResults.classList.remove('hidden');
				return;
			}

			searchResults.innerHTML = results.map(item => {
				const typeClass = item.type === 'penawaran' ?
					'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' :
					'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20';

				// Use the order number or reference for searching
				const searchTerm = (item.sales_order_number || '').replace(/'/g, "\\'");

				return `
					<div onclick="selectSearchResult('${searchTerm}')" class="cursor-pointer block p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-colors">
						<div class="flex justify-between items-start">
							<div class="flex-1">
								<div class="flex items-center gap-2 mb-1">
									<span class="font-bold text-gray-900 dark:text-white">${item.sales_order_number || 'No Number'}</span>
									<span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-medium ${typeClass}">
										${item.badge}
									</span>
								</div>
								<div class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
									<svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
									${item.customer_name}
								</div>
								<div class="mt-2 text-[10px] font-bold uppercase tracking-wider text-yellow-600 flex items-center gap-1">
									<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
									PO: ${item.no_po || '<span class="italic text-gray-300">tidak ada</span>'}
								</div>
							</div>
							<svg class="w-5 h-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
						</div>
					</div>
				`;
			}).join('');
			searchResults.classList.remove('hidden');
		}

		// Tombol search untuk form submission
		searchBtn.addEventListener('click', function() {
			const query = searchInput.value.trim();
			if (query) {
				const form = document.createElement('form');
				form.method = 'GET';
				form.action = '{{ route('ga.sales-order.index') }}';
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
	</script>
</x-app-layout>
