<!-- Create Catalog Modal -->
<dialog id="createCatalogModal" class="modal">
    <div class="modal-box inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative flex max-w-xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Tambah Catalog
            </h3>
            <div class="modal-action m-0">
                <form method="dialog">
                    <button class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </form>
            </div>
        </div>
        <form action="{{ route('catalog.store') }}" method="POST" enctype="multipart/form-data" class="relative flex h-full flex-col space-y-4 overflow-hidden p-4">
            @csrf
            <div class="relative h-full overflow-auto text-black dark:text-gray-200">
                <div id="create_catalog_cover_preview" class="mt-2 flex w-full justify-center"></div>
                <div class="form-control relative w-full" x-data="{
                    open: false,
                    search: '',
                    selected: '',
                    brands: {{ $brands->toJson() }},
                    get filteredBrands() {
                        return this.brands.filter(i => i.toLowerCase().includes(this.search.toLowerCase()))
                    }
                }">
                    <label class="label pb-1">
                        <span class="label-text font-bold text-gray-700 dark:text-gray-300">Brand Name</span>
                        <span class="label-text-alt text-xs opacity-50">Pilih atau bikin baru</span>
                    </label>

                    <div class="relative">
                        <input type="text" id="brand_name" name="brand_name" x-model="search" @click="open = true" @click.away="open = false" @keydown.escape="open = false" placeholder="Select or type new brand..." class="input input-bordered focus:input-primary w-full pr-10 transition-all" autocomplete="off" required>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="no-scrollbar absolute z-[60] mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-xl dark:border-gray-600 dark:bg-gray-800">

                            <template x-if="filteredBrands.length === 0 && search.length > 0">
                                <div class="px-4 py-3 text-sm italic text-gray-500">
                                    Press enter to create "<span x-text="search" class="text-primary font-bold"></span>"
                                </div>
                            </template>

                            <template x-for="brand in filteredBrands" :key="brand">
                                <div @click="search = brand; open = false" class="hover:bg-primary flex cursor-pointer items-center justify-between px-4 py-2 text-sm transition-colors hover:text-white">
                                    <span x-text="brand"></span>
                                    <svg x-show="search === brand" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </template>

                            <div x-show="filteredBrands.length > 0" class="border-t border-gray-100 dark:border-gray-700"></div>
                        </div>
                    </div>

                    <!-- Quick Badges -->
                    <div class="mt-3 flex flex-wrap items-center gap-1.5">
                        <span class="mr-1 text-[10px] font-bold uppercase tracking-wider opacity-40">Added brands:</span>
                        <template x-for="brand in brands.slice(0, 5)" :key="brand">
                            <button type="button" @click="search = brand" class="badge badge-sm badge-outline hover:badge-primary cursor-pointer px-3 py-2.5 font-medium transition-all duration-300" :class="search === brand ? 'badge-primary' : ''">
                                <span x-text="brand"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="catalog_name" class="mb-2 block text-sm font-medium">Catalog Name</label>
                    <input type="text" id="catalog_name" name="catalog_name" placeholder="Catalog Name" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:placeholder-gray-400" required>
                </div>
                <div class="mb-4">
                    <label for="catalog_file" class="mb-2 block text-sm font-medium">Catalog File (PDF)</label>
                    <input type="file" accept=".pdf" id="catalog_file" name="catalog_file" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:placeholder-gray-400">
                </div>
            </div>
            <div class="flex justify-end p-2">
                <button type="submit" class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d] cursor-pointer">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    document.getElementById('catalog_file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const coverPreview = document.getElementById('create_catalog_cover_preview');
        const fileType = file.type;

        if (fileType === 'application/pdf') {
            coverPreview.innerHTML = `
                <div class="flex h-48 w-32 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-xs text-gray-400">
                    <span class="loading loading-spinner loading-xs mr-2"></span> Rendering...
                </div>
            `;
            const reader = new FileReader();
            reader.onload = function() {
                const typedarray = new Uint8Array(this.result);
                const pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;

                if (!pdfjsLib) {
                    console.error('pdf.js not loaded');
                    return;
                }

                pdfjsLib.getDocument(typedarray).promise.then(pdf => {
                    return pdf.getPage(1);
                }).then(page => {
                    const viewport = page.getViewport({
                        scale: 1.5
                    });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    return page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise.then(() => {
                        coverPreview.innerHTML = `
                            <img src="${canvas.toDataURL()}" 
                                 class="h-48 w-32 cursor-zoom-in rounded-lg border border-gray-200 object-cover shadow-sm transition-transform hover:scale-105" 
                                 alt="Catalog Cover Preview" 
                                 onclick="openImagePreview(this.src)">
                        `;
                    });
                }).catch(err => {
                    console.error('Error rendering PDF preview:', err);
                });
            };
            reader.readAsArrayBuffer(file);
        } else if (fileType.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                coverPreview.innerHTML = `
                    <img src="${e.target.result}" 
                         class="h-48 w-32 cursor-zoom-in rounded-lg border border-gray-200 object-cover shadow-sm transition-transform hover:scale-105" 
                         alt="Catalog Cover Preview" 
                         onclick="openImagePreview(this.src)">
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Reset preview when modal is closed (optional but good practice)
    document.getElementById('createCatalogModal').addEventListener('close', function() {
        document.getElementById('create_catalog_cover_preview').innerHTML = '';
        document.getElementById('catalog_file').value = '';
    });
</script>
