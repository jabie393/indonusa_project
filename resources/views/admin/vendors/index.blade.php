<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <!-- Top Action Bar -->
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-between overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <div>
                <button type="button" onclick="createVendorModal.showModal()"
                    class="flex items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-medium text-white hover:bg-[#19426d] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-[#225A97] dark:focus:ring-primary-800 transition shadow">
                    <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Tambah Vendor
                </button>
            </div>

            <div class="flex items-center space-x-3">
                <form action="{{ route('vendors.index') }}" method="GET" class="block" data-realtime-table-search
                    data-search-input="#topbar-search" data-search-target="#tableContainer"
                    data-pagination-target="#pagination-nav" data-extra-fields="#pagination-nav select[name='perPage']">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative md:w-80">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                            </svg>
                        </div>
                        <input type="search" name="search" id="topbar-search" value="{{ request('search') }}"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                            placeholder="Cari vendor, kode, PIC, kota..." />
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="flex shrink-0 items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] px-5 py-3.5">
                <div class="flex items-center gap-2 text-white">
                    <span class="text-sm font-bold uppercase tracking-wider">Daftar Rekanan Vendor &amp; Supplier</span>
                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-900">
                        {{ $vendors->total() }} Vendor Terdaftar
                    </span>
                </div>
            </div>

            <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
                <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Vendor &amp; Kode</th>
                            <th class="px-4 py-3">Kontak &amp; Alamat</th>
                            <th class="px-4 py-3">PIC Vendor</th>
                            <th class="px-4 py-3">Rekening &amp; TOP</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="flex justify-end text-nowrap px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($vendors as $vendor)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/70 transition-colors duration-200 dark:border-gray-700 dark:hover:bg-gray-800/40">
                                <!-- Vendor & Kode -->
                                <td class="px-4 py-3 align-top">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-gray-900 dark:text-white text-[15px]">
                                            {{ $vendor->vendor_name }}
                                        </span>
                                        @if(!empty($vendor->company_type))
                                            <span class="rounded-md bg-blue-50 px-2 py-0.5 text-[11px] font-bold text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                                                {{ $vendor->company_type }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs font-mono text-blue-600 dark:text-blue-400 mt-0.5">
                                        {{ $vendor->vendor_code ?: '-' }}
                                    </div>
                                    @if(!empty($vendor->npwp))
                                        <div class="text-[11px] text-gray-400 mt-0.5">
                                            NPWP: {{ $vendor->npwp }}
                                        </div>
                                    @endif
                                    @if(!empty($vendor->notes))
                                        <div class="text-[11px] text-gray-500 italic mt-1 max-w-xs line-clamp-2">
                                            "{{ $vendor->notes }}"
                                        </div>
                                    @endif
                                </td>

                                <!-- Kontak & Alamat -->
                                <td class="px-4 py-3 align-top">
                                    <div class="space-y-1 text-xs">
                                        @if(!empty($vendor->phone))
                                            <div class="flex items-center gap-1.5 text-gray-700 dark:text-gray-300 font-medium">
                                                <svg class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span>{{ $vendor->phone }}</span>
                                            </div>
                                        @endif
                                        @if(!empty($vendor->email))
                                            <div class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                                <svg class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ $vendor->email }}</span>
                                            </div>
                                        @endif
                                        <div class="text-gray-500 text-[11px] mt-1 max-w-xs line-clamp-2">
                                            {{ $vendor->address ?: '-' }}
                                            @if(!empty($vendor->city))
                                                <span class="font-semibold text-gray-700 dark:text-gray-300">({{ $vendor->city }})</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- PIC Vendor -->
                                <td class="px-4 py-3 align-top">
                                    @if(!empty($vendor->pic_name))
                                        <div class="text-xs font-bold text-gray-900 dark:text-white">
                                            {{ $vendor->pic_name }}
                                        </div>
                                        @if(!empty($vendor->pic_phone))
                                            <div class="text-xs text-blue-600 dark:text-blue-400">
                                                {{ $vendor->pic_phone }}
                                            </div>
                                        @endif
                                        @if(!empty($vendor->pic_email))
                                            <div class="text-[11px] text-gray-400">
                                                {{ $vendor->pic_email }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum diatur</span>
                                    @endif
                                </td>

                                <!-- Rekening & TOP -->
                                <td class="px-4 py-3 align-top">
                                    @if(!empty($vendor->bank_name) || !empty($vendor->bank_account_number))
                                        <div class="text-xs font-bold text-slate-800 dark:text-white">
                                            {{ $vendor->bank_name ?: 'Bank' }} - <span class="font-mono">{{ $vendor->bank_account_number ?: '-' }}</span>
                                        </div>
                                        @if(!empty($vendor->bank_account_holder))
                                            <div class="text-[11px] text-gray-500">
                                                a/n {{ $vendor->bank_account_holder }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif

                                    <div class="mt-1">
                                        @if($vendor->term_of_payment !== null && $vendor->term_of_payment > 0)
                                            <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 border border-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-800">
                                                TOP: {{ $vendor->term_of_payment }} Hari
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                Cash / On Delivery
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3 text-center align-middle whitespace-nowrap">
                                    <form action="{{ route('vendors.status.update', $vendor->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="Klik untuk mengubah status"
                                            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold transition hover:opacity-80 {{ $vendor->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-300' }}">
                                            <span class="h-2 w-2 rounded-full {{ $vendor->status === 'active' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            {{ $vendor->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>

                                <!-- Action Buttons -->
                                <td class="px-4 py-3 text-right align-middle whitespace-nowrap">
                                    <div class="flex justify-end">
                                        <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                            <!-- Tombol Edit -->
                                            <button type="button"
                                                onclick="openEditVendorModal({{ json_encode($vendor) }})"
                                                class="group flex h-full cursor-pointer items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700"
                                                title="Edit Data Vendor">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('Apakah Anda yakin ingin menghapus vendor {{ addslashes($vendor->vendor_name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-red-800 focus:outline-none dark:bg-red-600 dark:hover:bg-red-700"
                                                    title="Hapus Vendor">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-sm font-semibold">Belum ada data vendor yang terdaftar.</p>
                                        <p class="text-xs text-gray-400 mt-1">Klik tombol "+ Tambah Vendor" di atas untuk mendaftarkan rekanan vendor baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <nav id="pagination-nav"
                class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Menampilkan
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $vendors->firstItem() ?? 0 }}-{{ $vendors->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $vendors->total() }}</span>
                    </span>
                    <form method="GET" action="{{ route('vendors.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" onchange="this.form.submit()"
                            class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach ([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </form>
                    <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                </div>
                <div>
                    {{ $vendors->links() }}
                </div>
            </nav>
        </div>
    </div>

    <!-- Modals -->
    @include('admin.vendors.partials.vendors-modal-tambah')
    @include('admin.vendors.partials.vendors-modal-edit')

    <script>
        function openEditVendorModal(vendor) {
            const form = document.getElementById('editVendorForm');
            form.action = `/vendors/${vendor.id}`;

            const subtitle = document.getElementById('edit_vendor_subtitle');
            if (subtitle) subtitle.textContent = (vendor.vendor_code ? vendor.vendor_code + ' - ' : '') + vendor.vendor_name;

            document.getElementById('edit_vendor_name').value = vendor.vendor_name || '';
            document.getElementById('edit_company_type').value = vendor.company_type || 'PT';
            document.getElementById('edit_vendor_code').value = vendor.vendor_code || '';
            document.getElementById('edit_npwp').value = vendor.npwp || '';
            document.getElementById('edit_phone').value = vendor.phone || '';
            document.getElementById('edit_email').value = vendor.email || '';
            document.getElementById('edit_address').value = vendor.address || '';
            document.getElementById('edit_city').value = vendor.city || '';
            document.getElementById('edit_province').value = vendor.province || '';
            document.getElementById('edit_pic_name').value = vendor.pic_name || '';
            document.getElementById('edit_pic_phone').value = vendor.pic_phone || '';
            document.getElementById('edit_pic_email').value = vendor.pic_email || '';
            document.getElementById('edit_bank_name').value = vendor.bank_name || '';
            document.getElementById('edit_bank_account_number').value = vendor.bank_account_number || '';
            document.getElementById('edit_bank_account_holder').value = vendor.bank_account_holder || '';
            document.getElementById('edit_term_of_payment').value = vendor.term_of_payment !== null ? vendor.term_of_payment : '';
            document.getElementById('edit_status').value = vendor.status || 'active';
            document.getElementById('edit_notes').value = vendor.notes || '';

            document.getElementById('editVendorModal').showModal();
        }
    </script>
</x-app-layout>
