<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <!-- Top Action Bar -->
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-between overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <div>
                <button type="button" onclick="openCreateVendorModal()"
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
                                        @if(($vendor->tax_status ?? '') === 'PKP')
                                            <span class="inline-flex items-center gap-1 rounded-md bg-sky-50 px-2 py-0.5 text-[10px] font-bold text-sky-800 border border-sky-300 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800" title="Pengusaha Kena Pajak">
                                                <span class="h-1.5 w-1.5 rounded-full bg-sky-600"></span>
                                                PKP
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-800 border border-amber-300 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-800" title="Non Pengusaha Kena Pajak">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Non PKP
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs font-mono text-blue-600 dark:text-blue-400 mt-0.5">
                                        {{ $vendor->vendor_code ?: '-' }}
                                    </div>
                                    @if(($vendor->tax_status ?? '') === 'PKP' && !empty($vendor->npwp))
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1 font-mono">
                                            <span class="font-sans text-[10px] uppercase font-bold text-gray-400">NPWP:</span> {{ $vendor->npwp }}
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
        const DEFAULT_PROVINCES = [
            { id: "11", name: "Aceh" },
            { id: "12", name: "Sumatera Utara" },
            { id: "13", name: "Sumatera Barat" },
            { id: "14", name: "Riau" },
            { id: "15", name: "Jambi" },
            { id: "16", name: "Sumatera Selatan" },
            { id: "17", name: "Bengkulu" },
            { id: "18", name: "Lampung" },
            { id: "19", name: "Kepulauan Bangka Belitung" },
            { id: "21", name: "Kepulauan Riau" },
            { id: "31", name: "DKI Jakarta" },
            { id: "32", name: "Jawa Barat" },
            { id: "33", name: "Jawa Tengah" },
            { id: "34", name: "DI Yogyakarta" },
            { id: "35", name: "Jawa Timur" },
            { id: "36", name: "Banten" },
            { id: "51", name: "Bali" },
            { id: "52", name: "Nusa Tenggara Barat" },
            { id: "53", name: "Nusa Tenggara Timur" },
            { id: "61", name: "Kalimantan Barat" },
            { id: "62", name: "Kalimantan Tengah" },
            { id: "63", name: "Kalimantan Selatan" },
            { id: "64", name: "Kalimantan Timur" },
            { id: "65", name: "Kalimantan Utara" },
            { id: "71", name: "Sulawesi Utara" },
            { id: "72", name: "Sulawesi Tengah" },
            { id: "73", name: "Sulawesi Selatan" },
            { id: "74", name: "Sulawesi Tenggara" },
            { id: "75", name: "Gorontalo" },
            { id: "76", name: "Sulawesi Barat" },
            { id: "81", name: "Maluku" },
            { id: "82", name: "Maluku Utara" },
            { id: "91", name: "Papua Barat" },
            { id: "94", name: "Papua" }
        ];

        const WilayahAPI = {
            dataCache: null,

            toTitleCase(str) {
                if (!str) return '';
                return str.toLowerCase().replace(/(?:^|\s|-|\/)\S/g, function(m) {
                    return m.toUpperCase();
                }).replace(/\bDki\b/g, 'DKI').replace(/\bDi\b/g, 'DI');
            },

            async getWilayahData() {
                if (this.dataCache) return this.dataCache;
                try {
                    // 1. Ambil data lokal project (cepat, tanpa CORS, offline-ready)
                    const res = await fetch("{{ asset('data/indonesia-wilayah.json') }}");
                    if (res.ok) {
                        const json = await res.json();
                        if (Array.isArray(json) && json.length > 0) {
                            this.dataCache = json;
                            return json;
                        }
                    }
                } catch (e) {
                    console.warn('Gagal memuat local wilayah JSON, mencoba fallback API:', e);
                }

                // 2. Fallback jika ada akses API external
                try {
                    const res = await fetch('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
                    if (res.ok) {
                        const provinces = await res.json();
                        this.dataCache = provinces.map(p => ({
                            id: p.id,
                            name: p.name,
                            cities: []
                        }));
                        return this.dataCache;
                    }
                } catch (err) {
                    console.warn('Fallback external API gagal:', err);
                }

                // 3. Fallback terakhir ke list internal
                this.dataCache = DEFAULT_PROVINCES.map(p => ({
                    id: p.id,
                    name: p.name.toUpperCase(),
                    cities: []
                }));
                return this.dataCache;
            },

            async getRegencies(provinceId) {
                if (!provinceId) return [];
                const data = await this.getWilayahData();
                const prov = data.find(p => String(p.id) === String(provinceId));
                if (prov && Array.isArray(prov.cities) && prov.cities.length > 0) {
                    return prov.cities;
                }
                try {
                    const res = await fetch(`https://emsifa.github.io/api-wilayah-indonesia/api/regencies/${provinceId}.json`);
                    if (res.ok) {
                        const cities = await res.json();
                        if (prov) prov.cities = cities;
                        return cities;
                    }
                } catch (e) {
                    console.warn(`Gagal memuat regencies ${provinceId}:`, e);
                }
                return [];
            },

            positionDropdownMenu(btn, menu) {
                const btnRect = btn.getBoundingClientRect();
                const modalBox = btn.closest('.modal-box') || document.body;
                const modalRect = modalBox.getBoundingClientRect();

                const defaultMaxWidth = Math.max(btnRect.width, 340);
                const maxAllowedWidth = Math.min(defaultMaxWidth, modalBox.clientWidth - 24);
                const menuWidth = Math.max(260, Math.min(maxAllowedWidth, modalRect.width - 24));
                menu.style.width = menuWidth + 'px';

                let left = btnRect.left - modalRect.left;
                left = Math.max(12, Math.min(left, modalBox.clientWidth - menuWidth - 12));
                menu.style.left = left + 'px';

                const spaceBelow = modalRect.bottom - btnRect.bottom;
                const menuHeight = 260;
                if (spaceBelow < 260 && (btnRect.top - modalRect.top) > spaceBelow) {
                    menu.style.top = Math.max(12, (btnRect.top - modalRect.top - menuHeight - 4)) + 'px';
                } else {
                    menu.style.top = (btnRect.bottom - modalRect.top + 4) + 'px';
                }
            },

            async initDropdowns(provinceInputId, cityInputId, provinceLoadingId, cityLoadingId, initialProvince = '', initialCity = '') {
                const provInput = document.getElementById(provinceInputId);
                const cityInput = document.getElementById(cityInputId);
                const provLoading = document.getElementById(provinceLoadingId);
                const cityLoading = document.getElementById(cityLoadingId);

                if (!provInput || !cityInput) return;

                const provContainer = provInput.closest('.province-dropdown-container');
                const cityContainer = cityInput.closest('.city-dropdown-container');
                if (!provContainer || !cityContainer) return;

                const provBtn = provContainer.querySelector('.custom-dropdown-toggle-btn');
                const provMenu = provContainer.querySelector('.wilayah-dropdown-menu');
                const provSearch = provContainer.querySelector('.search-wilayah-input');
                const provList = provContainer.querySelector('.wilayah-options-list');
                const provLabel = provBtn.querySelector('.selected-label');

                const cityBtn = cityContainer.querySelector('.custom-dropdown-toggle-btn');
                const cityMenu = cityContainer.querySelector('.wilayah-dropdown-menu');
                const citySearch = cityContainer.querySelector('.search-wilayah-input');
                const cityList = cityContainer.querySelector('.wilayah-options-list');
                const cityLabel = cityBtn.querySelector('.selected-label');

                // Initial setup for Province
                provInput.value = initialProvince || '';
                if (initialProvince) {
                    provLabel.textContent = initialProvince;
                    provLabel.classList.remove('text-gray-400');
                    provLabel.classList.add('text-gray-900', 'dark:text-white', 'font-medium');
                } else {
                    provLabel.textContent = '-- Pilih Provinsi --';
                    provLabel.classList.add('text-gray-400');
                    provLabel.classList.remove('text-gray-900', 'dark:text-white', 'font-medium');
                }

                // Initial setup for City
                cityInput.value = initialCity || '';
                if (initialCity) {
                    cityBtn.disabled = false;
                    cityLabel.textContent = initialCity;
                    cityLabel.classList.remove('text-gray-400');
                    cityLabel.classList.add('text-gray-900', 'dark:text-white', 'font-medium');
                } else if (initialProvince) {
                    cityBtn.disabled = false;
                    cityLabel.textContent = '-- Pilih Kota / Kabupaten --';
                    cityLabel.classList.add('text-gray-400');
                    cityLabel.classList.remove('text-gray-900', 'dark:text-white', 'font-medium');
                } else {
                    cityBtn.disabled = true;
                    cityLabel.textContent = '-- Pilih Provinsi Dahulu --';
                    cityLabel.classList.add('text-gray-400');
                    cityLabel.classList.remove('text-gray-900', 'dark:text-white', 'font-medium');
                }

                // Toggle logic
                provBtn.onclick = (e) => {
                    e.stopPropagation();
                    document.querySelectorAll('.wilayah-dropdown-menu').forEach(m => {
                        if (m !== provMenu) m.classList.add('hidden');
                    });
                    provMenu.classList.toggle('hidden');
                    if (!provMenu.classList.contains('hidden')) {
                        this.positionDropdownMenu(provBtn, provMenu);
                        if (provSearch) {
                            provSearch.value = '';
                            provSearch.dispatchEvent(new Event('input'));
                            setTimeout(() => provSearch.focus({ preventScroll: true }), 50);
                        }
                    }
                };

                cityBtn.onclick = (e) => {
                    e.stopPropagation();
                    if (cityBtn.disabled) return;
                    document.querySelectorAll('.wilayah-dropdown-menu').forEach(m => {
                        if (m !== cityMenu) m.classList.add('hidden');
                    });
                    cityMenu.classList.toggle('hidden');
                    if (!cityMenu.classList.contains('hidden')) {
                        this.positionDropdownMenu(cityBtn, cityMenu);
                        if (citySearch) {
                            citySearch.value = '';
                            citySearch.dispatchEvent(new Event('input'));
                            setTimeout(() => citySearch.focus({ preventScroll: true }), 50);
                        }
                    }
                };

                // Search filtering helper
                const setupSearch = (container) => {
                    const searchInput = container.querySelector('.search-wilayah-input');
                    const noFound = container.querySelector('.no-options-found');
                    if (!searchInput) return;

                    searchInput.oninput = function() {
                        const query = this.value.toLowerCase().trim();
                        const rows = container.querySelectorAll('.wilayah-option-row');
                        let matches = 0;
                        rows.forEach(r => {
                            const name = (r.dataset.name || '').toLowerCase();
                            if (!query || name.includes(query)) {
                                r.style.display = '';
                                matches++;
                            } else {
                                r.style.display = 'none';
                            }
                        });
                        if (noFound) {
                            noFound.classList.toggle('hidden', matches > 0);
                        }
                    };

                    searchInput.onclick = (e) => e.stopPropagation();
                };

                setupSearch(provContainer);
                setupSearch(cityContainer);

                // Helper to populate City options
                const loadCitiesForProvince = async (provId, selectedCity = '') => {
                    if (!provId) {
                        cityBtn.disabled = true;
                        cityLabel.textContent = '-- Pilih Provinsi Dahulu --';
                        cityLabel.classList.add('text-gray-400');
                        cityLabel.classList.remove('text-gray-900', 'dark:text-white', 'font-medium');
                        cityInput.value = '';
                        return;
                    }

                    cityBtn.disabled = true;
                    if (cityLoading) cityLoading.classList.remove('hidden');
                    cityLabel.textContent = 'Memuat kota...';

                    const regencies = await this.getRegencies(provId);
                    if (cityLoading) cityLoading.classList.add('hidden');

                    cityBtn.disabled = false;
                    cityLabel.textContent = selectedCity || '-- Pilih Kota / Kabupaten --';
                    if (selectedCity) {
                        cityLabel.classList.remove('text-gray-400');
                        cityLabel.classList.add('text-gray-900', 'dark:text-white', 'font-medium');
                        cityInput.value = selectedCity;
                    } else {
                        cityLabel.classList.add('text-gray-400');
                        cityLabel.classList.remove('text-gray-900', 'dark:text-white', 'font-medium');
                        cityInput.value = '';
                    }

                    // Populate city rows
                    Array.from(cityList.querySelectorAll('.wilayah-option-row')).forEach(r => r.remove());

                    regencies.forEach(r => {
                        const formatted = this.toTitleCase(r.name);
                        const row = document.createElement('div');
                        row.className = 'wilayah-option-row flex items-center px-3 py-2 text-xs cursor-pointer hover:bg-blue-50/80 dark:hover:bg-gray-700/60 transition';
                        row.dataset.name = formatted;
                        row.dataset.id = r.id;

                        const isSelected = selectedCity && (
                            selectedCity.toLowerCase() === formatted.toLowerCase() ||
                            selectedCity.toLowerCase() === r.name.toLowerCase()
                        );
                        if (isSelected) {
                            row.classList.add('bg-blue-50', 'dark:bg-gray-700/80');
                        }

                        row.innerHTML = `<span class="option-name font-medium ${isSelected ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-900 dark:text-gray-100'} truncate">${formatted}</span>`;

                        row.onclick = (e) => {
                            e.stopPropagation();
                            cityInput.value = formatted;
                            cityLabel.textContent = formatted;
                            cityLabel.classList.remove('text-gray-400');
                            cityLabel.classList.add('text-gray-900', 'dark:text-white', 'font-medium');
                            cityMenu.classList.add('hidden');

                            cityList.querySelectorAll('.wilayah-option-row').forEach(cr => {
                                const active = cr.dataset.name === formatted;
                                cr.classList.toggle('bg-blue-50', active);
                                cr.classList.toggle('dark:bg-gray-700/80', active);
                                const span = cr.querySelector('.option-name');
                                if (span) {
                                    span.classList.toggle('text-blue-600', active);
                                    span.classList.toggle('dark:text-blue-400', active);
                                    span.classList.toggle('font-semibold', active);
                                    span.classList.toggle('text-gray-900', !active);
                                    span.classList.toggle('dark:text-gray-100', !active);
                                }
                            });
                        };

                        cityList.appendChild(row);
                    });
                };

                // Populate Province rows
                if (provLoading) provLoading.classList.remove('hidden');
                const fullWilayah = await this.getWilayahData();
                if (provLoading) provLoading.classList.add('hidden');

                Array.from(provList.querySelectorAll('.wilayah-option-row')).forEach(r => r.remove());

                let selectedProvId = '';
                fullWilayah.forEach(p => {
                    const formatted = this.toTitleCase(p.name);
                    const row = document.createElement('div');
                    row.className = 'wilayah-option-row flex items-center px-3 py-2 text-xs cursor-pointer hover:bg-blue-50/80 dark:hover:bg-gray-700/60 transition';
                    row.dataset.name = formatted;
                    row.dataset.id = p.id;

                    const isSelected = initialProvince && (
                        initialProvince.toLowerCase() === formatted.toLowerCase() ||
                        initialProvince.toLowerCase() === p.name.toLowerCase()
                    );
                    if (isSelected) {
                        row.classList.add('bg-blue-50', 'dark:bg-gray-700/80');
                        selectedProvId = p.id;
                    }

                    row.innerHTML = `<span class="option-name font-medium ${isSelected ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-900 dark:text-gray-100'} truncate">${formatted}</span>`;

                    row.onclick = (e) => {
                        e.stopPropagation();
                        provInput.value = formatted;
                        provLabel.textContent = formatted;
                        provLabel.classList.remove('text-gray-400');
                        provLabel.classList.add('text-gray-900', 'dark:text-white', 'font-medium');
                        provMenu.classList.add('hidden');

                        provList.querySelectorAll('.wilayah-option-row').forEach(pr => {
                            const active = pr.dataset.id == p.id;
                            pr.classList.toggle('bg-blue-50', active);
                            pr.classList.toggle('dark:bg-gray-700/80', active);
                            const span = pr.querySelector('.option-name');
                            if (span) {
                                span.classList.toggle('text-blue-600', active);
                                span.classList.toggle('dark:text-blue-400', active);
                                span.classList.toggle('font-semibold', active);
                                span.classList.toggle('text-gray-900', !active);
                                span.classList.toggle('dark:text-gray-100', !active);
                            }
                        });

                        // Cascade to city
                        loadCitiesForProvince(p.id);
                    };

                    provList.appendChild(row);
                });

                // If already had a province selected, load cities
                if (selectedProvId) {
                    await loadCitiesForProvince(selectedProvId, initialCity);
                } else if (initialCity) {
                    cityBtn.disabled = false;
                    cityLabel.textContent = initialCity;
                    cityLabel.classList.remove('text-gray-400');
                    cityLabel.classList.add('text-gray-900', 'dark:text-white', 'font-medium');
                }
            }
        };

        // Attach global outside-click listener for wilayah dropdown menus
        if (!window._wilayahDropdownGlobalAttached) {
            window._wilayahDropdownGlobalAttached = true;
            document.addEventListener('click', function(e) {
                document.querySelectorAll('.wilayah-dropdown-menu').forEach(menu => {
                    if (!menu.classList.contains('hidden') && !menu.contains(e.target) && !e.target.closest('.custom-dropdown-toggle-btn')) {
                        menu.classList.add('hidden');
                    }
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.wilayah-dropdown-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });
        }

        function toggleNpwpVisibility(prefix) {
            const statusSelect = document.getElementById(prefix + '_tax_status');
            const npwpContainer = document.getElementById(prefix + '_npwp_container');
            const npwpInput = document.getElementById(prefix + '_npwp');
            const taxCol = document.getElementById(prefix + '_tax_status_col');

            if (!statusSelect || !npwpContainer || !npwpInput) return;

            if (statusSelect.value === 'PKP') {
                npwpContainer.classList.remove('hidden');
                npwpInput.required = true;
                if (taxCol) {
                    taxCol.classList.remove('md:col-span-2');
                }
            } else {
                npwpContainer.classList.add('hidden');
                npwpInput.required = false;
                npwpInput.value = '';
                if (taxCol) {
                    taxCol.classList.add('md:col-span-2');
                }
            }
        }

        function openCreateVendorModal() {
            WilayahAPI.initDropdowns('create_province', 'create_city', 'create_province_loading', 'create_city_loading');
            const taxStatusSelect = document.getElementById('create_tax_status');
            if (taxStatusSelect) taxStatusSelect.value = '';
            toggleNpwpVisibility('create');
            const modal = document.getElementById('createVendorModal');
            if (modal) modal.showModal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            WilayahAPI.initDropdowns('create_province', 'create_city', 'create_province_loading', 'create_city_loading');
            toggleNpwpVisibility('create');
        });

        function openEditVendorModal(vendor) {
            const form = document.getElementById('editVendorForm');
            form.action = `/vendors/${vendor.id}`;

            const subtitle = document.getElementById('edit_vendor_subtitle');
            if (subtitle) subtitle.textContent = (vendor.vendor_code ? vendor.vendor_code + ' - ' : '') + vendor.vendor_name;

            document.getElementById('edit_vendor_name').value = vendor.vendor_name || '';
            document.getElementById('edit_company_type').value = vendor.company_type || 'PT';
            document.getElementById('edit_vendor_code').value = vendor.vendor_code || '';
            
            const taxStatus = vendor.tax_status || (vendor.npwp ? 'PKP' : 'Non PKP');
            document.getElementById('edit_tax_status').value = taxStatus;
            toggleNpwpVisibility('edit');
            if (taxStatus === 'PKP') {
                document.getElementById('edit_npwp').value = vendor.npwp || '';
            } else {
                document.getElementById('edit_npwp').value = '';
            }

            document.getElementById('edit_phone').value = vendor.phone || '';
            document.getElementById('edit_email').value = vendor.email || '';
            document.getElementById('edit_address').value = vendor.address || '';
            document.getElementById('edit_pic_name').value = vendor.pic_name || '';
            document.getElementById('edit_pic_phone').value = vendor.pic_phone || '';
            document.getElementById('edit_pic_email').value = vendor.pic_email || '';
            document.getElementById('edit_bank_name').value = vendor.bank_name || '';
            document.getElementById('edit_bank_account_number').value = vendor.bank_account_number || '';
            document.getElementById('edit_bank_account_holder').value = vendor.bank_account_holder || '';
            document.getElementById('edit_term_of_payment').value = vendor.term_of_payment !== null ? vendor.term_of_payment : '';
            document.getElementById('edit_status').value = vendor.status || 'active';
            document.getElementById('edit_notes').value = vendor.notes || '';

            WilayahAPI.initDropdowns('edit_province', 'edit_city', 'edit_province_loading', 'edit_city_loading', vendor.province || '', vendor.city || '');

            document.getElementById('editVendorModal').showModal();
        }
    </script>
</x-app-layout>
