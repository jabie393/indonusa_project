<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">

        <div class="p-4">
            <button onclick="createCatalogModal.showModal()" class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
                <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
                Tambah Katalog
            </button>
        </div>

        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('catalog.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="catalogTable" value="{{ request('search') }}" class="dt-input block w-full rounded-lg bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Cari..." />
                </div>
            </form>
        </div>

    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="w-[50px] px-4 py-2">ID</th>
                        <th class="text-nowrap px-4 py-2">Nama Brand</th>
                        <th class="text-nowrap px-4 py-2">Nama Katalog</th>
                        <th class="text-nowrap px-4 py-2">File Katalog</th>
                        <th class="text-nowrap px-4 py-2">Cover Katalog</th>
                        <th class="px-4 py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($catalogs as $catalog)
                        <tr class="dark:border-gray-700">
                            <td class="px-4 py-2">{{ $catalog->id }}</td>
                            <td class="text-nowrap px-4 py-2">{{ $catalog->brand_name }}</td>
                            <td class="text-nowrap px-4 py-2">{{ $catalog->catalog_name }}</td>
                            <td class="text-nowrap px-4 py-2">
                                @if ($catalog->catalog_file)
                                    <a href="{{ asset('files/' . $catalog->catalog_file) }}" target="_blank" class="group flex w-fit items-center justify-center rounded-lg bg-blue-50 p-2 text-blue-700 transition-all hover:bg-blue-600 hover:text-white dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-600 dark:hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
                                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z">
                                            </path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <line x1="10" y1="9" x2="8" y2="9"></line>
                                        </svg>
                                        <span class="overflow-hidden text-xs font-semibold transition-all duration-300 ease-in-out max-w-xs pl-2 opacity-100">Lihat
                                            File</span>
                                    </a>
                                @else
                                    <span class="text-gray-400">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if ($catalog->catalog_cover)
                                    <img src="{{ asset('files/' . $catalog->catalog_cover) }}" class="h-24 w-16 cursor-zoom-in rounded-lg border border-gray-200 object-cover shadow-sm transition-transform hover:scale-105" alt="Catalog Cover" onclick="openImagePreview(this.src)" onerror="this.onerror=null; this.src='https://placehold.co/100x150?text=No+Preview';">
                                @else
                                    <div class="flex h-24 w-16 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-xs text-gray-400">
                                        Tidak ada gambar
                                    </div>
                                @endif
                            </td>
                            <td class="w-fit px-4 py-3 text-right">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-20 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Edit --}}
                                        <button onclick="openEditModal({{ $catalog->toJson() }})" class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4">
                                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                </path>
                                                <path d="m15 5 4 4"></path>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                        </button>
                                        {{-- Delete --}}
                                        <form action="{{ route('catalog.destroy', $catalog->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="confirmDelete(() => this.closest('form').submit())">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Menampilkan
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $catalogs->firstItem() ?? 0 }}-{{ $catalogs->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $catalogs->total() ?? $catalogs->count() }}</span>
                </span>
                <form method="GET" action="{{ route('catalog.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()" class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
            </div>
            <div>
                {{ $catalogs->links() }}
            </div>
        </nav>

    </div>
    <!-- Modals -->
    @include('admin.catalog.partials.catalog-modal-tambah')
    @include('admin.catalog.partials.catalog-modal-edit')

    <!-- Image Preview Modal -->
    <dialog id="image_preview_modal" class="modal">
        <div class="modal-box max-w-4xl overflow-hidden bg-transparent p-0 shadow-none">
            <form method="dialog">
                <button class="btn btn-circle btn-ghost btn-sm absolute right-4 top-4 z-50 bg-black/50 text-white hover:bg-black/70">✕</button>
            </form>
            <div class="flex items-center justify-center p-4">
                <img id="preview_image_src" src="" class="h-auto max-h-[90vh] w-auto rounded-xl object-contain shadow-2xl" alt="Preview">
            </div>
        </div>
        <form method="dialog" class="modal-backdrop bg-black/90 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    <script>
        function openImagePreview(src) {
            const modal = document.getElementById('image_preview_modal');
            const img = document.getElementById('preview_image_src');
            img.src = src;
            modal.showModal();
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script>
        // Use a more robust way to set the worker
        const pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

        function initPdfPreviews() {
            const previewContainers = document.querySelectorAll('.pdf-preview-container:not([data-processed])');

            previewContainers.forEach(container => {
                container.setAttribute('data-processed', 'true');
                const url = container.getAttribute('data-url');
                const canvas = container.querySelector('.pdf-canvas');
                const loading = container.querySelector('.loading');

                if (!url) return;

                pdfjsLib.getDocument({
                    url: url,
                    disableRange: true, // Better for some environments
                    disableStream: true
                }).promise.then(pdf => {
                    return pdf.getPage(1);
                }).then(page => {
                    const viewport = page.getViewport({
                        scale: 0.8 // Slightly higher scale for better quality before resizing
                    });
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    return page.render(renderContext).promise;
                }).then(() => {
                    loading.classList.add('hidden');
                    canvas.classList.remove('hidden');
                }).catch(err => {
                    console.error('Error loading PDF:', url, err);
                    loading.innerHTML = '<span class="text-[10px] text-error text-center px-1">Error</span>';
                });
            });
        }

        document.addEventListener('DOMContentLoaded', initPdfPreviews);

        // If DataTable is initialized, re-run after table draws
        if (typeof jQuery !== 'undefined' && typeof DataTable !== 'undefined') {
            $(document).on('draw.dt', function() {
                initPdfPreviews();
            });
        }

        // Final fallback to ensure it runs
        setTimeout(initPdfPreviews, 2000);
    </script>
</x-app-layout>
