<!-- Edit Catalog Modal -->
<dialog id="editCatalogModal" class="modal">
    <div class="modal-box inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative flex max-w-4xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Edit Katalog
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
        <form id="editCatalogForm" action="" method="POST" enctype="multipart/form-data" class="relative flex h-full flex-col space-y-4 overflow-hidden p-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-5 gap-4">
                <div id="edit_catalog_cover_preview" class="mt-2 flex w-full h-full justify-center col-span-2 row-span-full">
                    <div class="flex h-96 w-64 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-xs text-gray-400">
                        Cover PDF akan muncul setelah upload
                    </div>
                </div>
                <div class="relative h-full overflow-auto text-black dark:text-gray-200 col-span-3">
                    <div id="edit_brand_container" class="form-control relative w-full px-2" x-data="{
                        open: false,
                        search: '',
                        brands: {{ $brands->toJson() }},
                        get filteredBrands() {
                            return this.brands.filter(i => i.toLowerCase().includes(this.search.toLowerCase()))
                        }
                    }" @set-brand.window="if($event.detail.id === 'edit_brand_container') search = $event.detail.value">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-gray-700 dark:text-gray-300">Nama Brand</span>
                            <span class="label-text-alt text-xs opacity-50">Pilih atau buat baru</span>
                        </label>

                        <div class="relative">
                            <input type="text" id="edit_brand_name" name="brand_name" x-model="search" @click="open = true" @click.away="open = false" @keydown.escape="open = false" placeholder="Pilih atau ketik brand baru..." class="input input-bordered focus:input-primary w-full pr-10 transition-all" autocomplete="off" required>

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
                            <span class="mr-1 text-[10px] font-bold uppercase tracking-wider opacity-40">Brand yang sudah ada:</span>
                            <template x-for="brand in brands.slice(0, 5)" :key="brand">
                                <button type="button" @click="search = brand" class="badge badge-sm badge-outline hover:badge-primary cursor-pointer px-3 py-2.5 font-medium transition-all duration-300" :class="search === brand ? 'badge-primary' : ''">
                                    <span x-text="brand"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <div class="mb-4 px-2">
                        <label for="edit_catalog_name" class="mb-2 block text-sm font-medium">Nama Katalog</label>
                        <input type="text" id="edit_catalog_name" name="catalog_name" placeholder="Nama Katalog" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:placeholder-gray-400" required>
                    </div>
                    <div class="mb-4 px-2">
                        <label for="edit_catalog_file" class="mb-2 block text-sm font-medium">File Katalog (Biarkan kosong untuk tetap menggunakan file saat ini)</label>
                        <input type="file" accept=".pdf" id="edit_catalog_file" name="catalog_file" class="file-input file-input-bordered file-input-primary w-full">
                        
                        <!-- Progress Bar Container -->
                        <div id="edit_upload_progress_container" class="mt-4 hidden">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-medium text-primary">Sedang mengunggah...</span>
                                <span id="edit_upload_percentage" class="text-xs font-medium text-primary">0%</span>
                            </div>
                            <progress id="edit_upload_progress_bar" class="progress progress-primary w-full" value="0" max="100"></progress>
                        </div>

                        <input type="hidden" name="catalog_file_path" id="edit_catalog_file_path">
                        <input type="hidden" name="catalog_cover_base64" id="edit_catalog_cover_base64">
                        <div id="current_file_info" class="mt-2 text-xs text-gray-400"></div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end p-2 ">
                <button type="submit" id="edit_submit_btn" class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-6 py-2.5 font-semibold text-white hover:bg-[#19426d] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="edit_submit_btn_text">Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    window.openEditModal = function(catalog) {
        const form = document.getElementById('editCatalogForm');
        form.action = `/catalog/${catalog.id}`;

        // Dispatch event to update Alpine.js search value
        window.dispatchEvent(new CustomEvent('set-brand', { 
            detail: { id: 'edit_brand_container', value: catalog.brand_name } 
        }));

        document.getElementById('edit_catalog_name').value = catalog.catalog_name;
        document.getElementById('edit_catalog_file').value = '';
        document.getElementById('edit_catalog_cover_base64').value = '';
        document.getElementById('edit_catalog_file_path').value = '';
        
        const fileInfo = document.getElementById('current_file_info');
        if (catalog.catalog_file) {
            fileInfo.innerHTML = `File saat ini: <span class="font-medium text-primary">${catalog.catalog_file.split('/').pop()}</span>`;
        } else {
            fileInfo.innerHTML = 'Belum ada file yang diunggah.';
        }

        const coverPreview = document.getElementById('edit_catalog_cover_preview');
        if (catalog.catalog_cover) {
            coverPreview.innerHTML = `
                <img src="/files/${catalog.catalog_cover}" 
                     class="h-96 w-64 cursor-zoom-in rounded-lg border border-gray-200 object-cover shadow-sm transition-transform hover:scale-105" 
                     alt="Catalog Cover" 
                     onclick="openImagePreview(this.src)" 
                     onerror="this.onerror=null; this.src='https://placehold.co/100x150?text=No+Preview';">
            `;
        } else {
            coverPreview.innerHTML = `
                <div class="flex h-96 w-64 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-xs text-gray-400 text-center">
                    Tidak ada gambar
                </div>
            `;
        }

        document.getElementById('editCatalogModal').showModal();
    }

    document.getElementById('edit_catalog_file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const coverPreview = document.getElementById('edit_catalog_cover_preview');
        const fileType = file.type;
        const submitBtn = document.getElementById('edit_submit_btn');
        const submitBtnText = document.getElementById('edit_submit_btn_text');
        
        // Disable submit button during upload
        submitBtn.disabled = true;
        submitBtnText.innerText = 'Tunggu upload selesai...';

        // 1. Show Cover Preview
        if (fileType === 'application/pdf') {
            coverPreview.innerHTML = `
                <div class="flex h-96 w-64 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-xs text-gray-400">
                    <span class="loading loading-spinner loading-xs mr-2"></span> Menyiapkan pratinjau...
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
                    const viewport = page.getViewport({ scale: 1.5 });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    return page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise.then(() => {
                        const dataUrl = canvas.toDataURL('image/png');
                        document.getElementById('edit_catalog_cover_base64').value = dataUrl;
                        coverPreview.innerHTML = `
                            <img src="${dataUrl}" 
                                 class="h-96 w-64 cursor-zoom-in rounded-lg border border-gray-200 object-cover shadow-sm transition-transform hover:scale-105" 
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
                         class="h-96 w-64 cursor-zoom-in rounded-lg border border-gray-200 object-cover shadow-sm transition-transform hover:scale-105" 
                         alt="Catalog Cover Preview" 
                         onclick="openImagePreview(this.src)">
                `;
            };
            reader.readAsDataURL(file);
        }

        // 2. Start Upload
        const formData = new FormData();
        formData.append('catalog_file', file);
        formData.append('_token', '{{ csrf_token() }}');

        const progressContainer = document.getElementById('edit_upload_progress_container');
        const progressBar = document.getElementById('edit_upload_progress_bar');
        const progressPercent = document.getElementById('edit_upload_percentage');

        progressContainer.classList.remove('hidden');
        progressBar.value = 0;
        progressPercent.innerText = '0%';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('catalog.upload') }}', true);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.value = percent;
                progressPercent.innerText = percent + '%';
            }
        };

        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                document.getElementById('edit_catalog_file_path').value = response.path;
                
                // Finalize progress
                progressBar.value = 100;
                progressPercent.innerText = '100% - Selesai';
                progressBar.classList.remove('progress-primary');
                progressBar.classList.add('progress-success');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtnText.innerText = 'Simpan Perubahan';
            } else {
                let errorMsg = 'Upload gagal';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        errorMsg = Object.values(response.errors).flat().join('\n');
                    } else if (response.message) {
                        errorMsg = response.message;
                    }
                } catch (e) {
                    errorMsg = xhr.statusText || 'Terjadi kesalahan pada server';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Unggahan Gagal',
                    text: errorMsg,
                    confirmButtonColor: '#225A97'
                });

                submitBtnText.innerText = 'Simpan Perubahan';
                progressContainer.classList.add('hidden');
                submitBtn.disabled = false; // Re-enable so user can try again
            }
        };

        xhr.onerror = function() {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Koneksi',
                text: 'Terjadi kesalahan koneksi saat mengunggah.',
                confirmButtonColor: '#225A97'
            });
            submitBtnText.innerText = 'Simpan Perubahan';
        };

        xhr.send(formData);
    });

    // Reset preview when modal is closed
    document.getElementById('editCatalogModal').addEventListener('close', function() {
        document.getElementById('edit_catalog_cover_preview').innerHTML = `
            <div class="flex h-96 w-64 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-xs text-gray-400 text-center">
                Tidak ada gambar
            </div>
        `;
        document.getElementById('edit_catalog_file').value = '';
        document.getElementById('edit_catalog_cover_base64').value = '';
        document.getElementById('edit_catalog_file_path').value = '';
        document.getElementById('edit_upload_progress_container').classList.add('hidden');
        document.getElementById('edit_upload_progress_bar').classList.remove('progress-success');
        document.getElementById('edit_upload_progress_bar').classList.add('progress-primary');
        document.getElementById('edit_submit_btn').disabled = false;
        document.getElementById('edit_submit_btn_text').innerText = 'Simpan Perubahan';
    });
</script>
