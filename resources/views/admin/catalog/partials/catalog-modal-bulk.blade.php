<!-- Bulk Catalog Modal -->
<dialog id="bulkCatalogModal" class="modal">
    <div class="modal-box inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative flex max-w-6xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Tambah Banyak Katalog
            </h3>
            <div class="modal-action m-0">
                <form method="dialog">
                    <button class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 110 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </form>
            </div>
        </div>

        <form id="bulkUploadForm" onsubmit="event.preventDefault(); processBulkUpload();" class="flex flex-col overflow-hidden">
        <div class="space-y-6 overflow-y-auto p-6">
            <!-- Instructions and Global Settings -->
            <div class="flex flex-col gap-4 rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-800 md:flex-row ">
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-bold text-gray-700 dark:text-gray-300">Pilih File PDF (Bisa banyak sekaligus)</label>
                    <input type="file" id="bulk_file_input" multiple accept=".pdf" class="file-input file-input-bordered file-input-primary w-full shadow-sm">
                </div>
                <div class="flex-1" x-data="{
                    open: false,
                    search: '',
                    brands: {{ $brands->toJson() }},
                    get filteredBrands() {
                        return this.brands.filter(i => i.toLowerCase().includes(this.search.toLowerCase()))
                    }
                }">
                    <label class="mb-2 block text-sm font-bold text-gray-700 dark:text-gray-300">Atur Brand untuk Semua (Opsional)</label>
                    <div class="join w-full shadow-sm">
                        <div class="relative flex-1">
                            <input type="text" id="global_brand_name" x-model="search" @click="open = true" @click.away="open = false" @keydown.escape="open = false" placeholder="Pilih atau ketik brand baru..." class="input input-bordered join-item w-full transition-all" autocomplete="off">
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="no-scrollbar absolute z-[60] mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-xl dark:border-gray-600 dark:bg-gray-800">

                                <template x-if="filteredBrands.length === 0 && search.length > 0">
                                    <div class="px-4 py-3 text-sm italic text-gray-500">
                                        Tekan enter untuk buat baru "<span x-text="search" class="text-primary font-bold"></span>"
                                    </div>
                                </template>

                                <template x-for="brand in filteredBrands" :key="brand">
                                    <div @click="search = brand; open = false" class="hover:bg-primary flex cursor-pointer items-center justify-between px-4 py-2 text-sm transition-colors hover:text-white text-black dark:text-white">
                                        <span x-text="brand"></span>
                                        <svg x-show="search === brand" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </template>

                                <div x-show="filteredBrands.length > 0" class="border-t border-gray-100 dark:border-gray-700"></div>
                            </div>
                        </div>
                        <button type="button" onclick="applyGlobalBrand()" class="btn btn-primary join-item px-4">Terapkan</button>
                    </div>

                    <!-- Quick Badges -->
                    <div class="mt-3 flex flex-wrap items-center gap-1.5">
                        <span class="mr-1 text-[10px] font-bold uppercase tracking-wider opacity-40">Tersedia:</span>
                        <template x-for="brand in brands.slice(0, 10)" :key="brand">
                            <button type="button" @click="search = brand" class="badge badge-sm badge-outline hover:badge-primary cursor-pointer px-3 py-2.5 font-medium transition-all duration-300 text-black dark:text-white" :class="search === brand ? 'badge-primary text-white' : ''">
                                <span x-text="brand"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Queue Table -->
            <div class="relative overflow-x-auto rounded-xl border border-gray-200 shadow-sm dark:border-gray-600">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="border-b bg-gray-100 text-xs uppercase text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="min-w-[150px] px-4 py-3">Nama Brand</th>
                            <th class="min-w-[150px] px-4 py-3">Nama Katalog</th>
                            <th class="min-w-[200px] px-4 py-3">Progres & Status</th>
                            <th class="w-[80px] px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bulk_queue_body">
                        <tr id="empty_queue_row">
                            <td colspan="4" class="px-4 py-12 text-center italic text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mb-4 h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    Belum ada file yang dipilih.
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Global Action Bar -->
            <div id="bulk_action_bar" class="animate-pulse-slow hidden items-center justify-between rounded-xl border border-dashed border-[#225A97]/20 bg-[#225A97]/5 p-4 dark:bg-blue-900/10">
                <div class="flex items-center gap-3">
                    <div id="bulk_status_indicator" class="badge badge-primary px-4 py-3 font-bold transition-all duration-300">
                        Menunggu...
                    </div>
                    <span id="bulk_progress_text" class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        0 file berhasil dari 0 total
                    </span>
                </div>
                <button type="submit" id="start_bulk_upload_btn" class="btn btn-primary hover:shadow-primary/20 px-8 shadow-lg transition-all">
                    Mulai Unggah Semua
                </button>
            </div>
        </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-black/60 backdrop-blur-sm">
        <button id="close_bulk_modal_hidden">close</button>
    </form>
</dialog>

<script>
    let bulkQueue = [];
    let isUploading = false;

    document.getElementById('bulk_file_input').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;

        const globalBrand = document.getElementById('global_brand_name').value.trim();
        const tbody = document.getElementById('bulk_queue_body');
        const emptyRow = document.getElementById('empty_queue_row');

        if (emptyRow) emptyRow.remove();
        document.getElementById('bulk_action_bar').classList.remove('hidden');

        files.forEach((file, index) => {
            const id = 'row_' + Date.now() + '_' + index;
            const fileName = file.name;
            const catalogName = fileName.replace(/\.[^/.]+$/, "");

            const item = {
                id: id,
                file: file,
                brand: globalBrand,
                name: catalogName,
                status: 'Menunggu',
                progress: 0,
                path: '',
                coverBase64: '',
                error: ''
            };

            bulkQueue.push(item);

            const tr = document.createElement('tr');
            tr.id = id;
            tr.className = 'border-b border-gray-200 dark:border-gray-600 group hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors';
            tr.innerHTML = `
                <td class="px-4 py-4">
                    <div class="flex flex-col gap-1">
                        <input type="text" class="input text-black dark:text-white input-bordered input-sm w-full brand-input" 
                            value="${globalBrand}" 
                            list="brand_list"
                            onchange="updateQueueItem('${id}', 'brand', this.value)" 
                            oninput="hideInputError(this); updateQueueItem('${id}', 'brand', this.value)" 
                            placeholder="Nama Brand" required>
                        <span class="text-[10px] text-error hidden error-msg">Harap isi brand</span>
                    </div>
                </td>
                <td class="px-4 py-4">
                    <div class="flex flex-col gap-1">
                        <input type="text" class="input text-black dark:text-white input-bordered input-sm w-full name-input" 
                            value="${catalogName}" 
                            onchange="updateQueueItem('${id}', 'name', this.value)" 
                            oninput="hideInputError(this); updateQueueItem('${id}', 'name', this.value)" 
                            placeholder="Nama Katalog" required>
                        <span class="text-[10px] text-error hidden error-msg">Harap isi katalog</span>
                    </div>
                </td>
                <td class="px-4 py-4">
                    <div class="flex flex-col gap-1.5">
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider">
                            <span class="status-label text-gray-500">Menunggu</span>
                            <span class="progress-label text-gray-400">0%</span>
                        </div>
                        <progress class="progress progress-primary w-full h-2 shadow-inner" value="0" max="100"></progress>
                        <span class="error-label text-[10px] text-error hidden truncate max-w-[200px]"></span>
                        <div class="flex items-center gap-1.5 file-info text-black dark:text-white opacity-40 group-hover:opacity-60 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            <span class="text-[10px] truncate max-w-[150px]">${fileName}</span>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4 text-center">
                    <button type="button" onclick="removeFromQueue('${id}')" class="btn btn-ghost btn-xs text-error hover:bg-error/10 remove-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        updateActionBar();
    });

    function updateActionStatus() {
        const total = bulkQueue.length;
        const success = bulkQueue.filter(i => i.status === 'Selesai').length;
        document.getElementById('bulk_progress_text').innerText = `${success} file berhasil dari ${total} total`;
    }

    function updateQueueItem(id, key, value) {
        const item = bulkQueue.find(i => i.id === id);
        if (item) item[key] = value;
    }

    function removeFromQueue(id) {
        if (isUploading) return;
        bulkQueue = bulkQueue.filter(i => i.id !== id);
        const row = document.getElementById(id);
        if (row) row.remove();

        if (bulkQueue.length === 0) {
            resetBulkModal();
        } else {
            updateActionBar();
        }
    }

    function applyGlobalBrand() {
        const brand = document.getElementById('global_brand_name').value.trim();
        if (!brand) return;

        bulkQueue.forEach(item => {
            item.brand = brand;
            const row = document.getElementById(item.id);
            if (row) row.querySelector('.brand-input').value = brand;
        });
    }

    function updateActionBar() {
        const bar = document.getElementById('bulk_action_bar');
        if (bulkQueue.length > 0) {
            bar.classList.remove('hidden');
            bar.classList.add('flex');
            updateActionStatus();
        } else {
            bar.classList.add('hidden');
            bar.classList.remove('flex');
        }
    }

    async function processBulkUpload() {
        if (isUploading) return;

        // 1. Check if queue is empty
        const itemsToUpload = bulkQueue.filter(i => i.status !== 'Selesai' && i.status !== 'Gagal');
        if (itemsToUpload.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: 'Tidak ada file baru untuk diunggah.'
            });
            return;
        }

        // Browser will handle 'required' validation natively now
        // But we still need to sync item data from inputs
        bulkQueue.forEach(item => {
            const row = document.getElementById(item.id);
            if (row) {
                item.brand = row.querySelector('.brand-input').value.trim();
                item.name = row.querySelector('.name-input').value.trim();
            }
        });


        isUploading = true;
        document.getElementById('start_bulk_upload_btn').disabled = true;
        document.getElementById('bulk_status_indicator').innerText = 'Sedang Memproses...';
        document.querySelectorAll('.remove-btn').forEach(b => b.classList.add('hidden'));
        document.querySelectorAll('.brand-input, .name-input').forEach(i => i.disabled = true);

        for (const item of bulkQueue) {
            if (item.status === 'Selesai') continue;

            try {
                // 1. Upload File
                await uploadFileItem(item);

                // 2. Process Cover (Client side)
                updateRowUI(item.id, 'Memproses Cover', 100);
                item.coverBase64 = await generatePdfCover(item.file);

                // 3. Finalize Save
                updateRowUI(item.id, 'Finalisasi', 100);
                await finalizeItemCreation(item);

                item.status = 'Selesai';
                updateRowUI(item.id, 'Selesai', 100, true);
            } catch (err) {
                console.error(err);
                item.status = 'Gagal';
                item.error = err.message || 'Gagal diproses';
                updateRowUI(item.id, 'Gagal', 0, false, true);
            }
            updateActionStatus();
        }

        isUploading = false;
        document.getElementById('start_bulk_upload_btn').disabled = false;
        document.getElementById('start_bulk_upload_btn').innerText = 'Selesai - Segarkan Halaman';
        document.getElementById('start_bulk_upload_btn').onclick = () => window.location.reload();
        document.getElementById('bulk_status_indicator').innerText = 'Selesai';
        document.getElementById('bulk_status_indicator').className = 'badge badge-success py-3 px-4 font-bold';
    }

    function uploadFileItem(item) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('catalog_file', item.file);
            formData.append('_token', '{{ csrf_token() }}');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('catalog.upload') }}', true);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    const pct = Math.round((e.loaded / e.total) * 100);
                    updateRowUI(item.id, 'Mengunggah...', pct);
                }
            };

            xhr.onload = () => {
                if (xhr.status === 200) {
                    const resp = JSON.parse(xhr.responseText);
                    item.path = resp.path;
                    resolve(resp);
                } else {
                    let msg = 'Upload gagal';
                    try {
                        const r = JSON.parse(xhr.responseText);
                        msg = r.message || r.errors ? Object.values(r.errors).flat().join(',') : msg;
                    } catch (e) {}
                    reject(new Error(msg));
                }
            };
            xhr.onerror = () => reject(new Error('Koneksi terputus'));
            xhr.send(formData);
        });
    }

    function generatePdfCover(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function() {
                const typedarray = new Uint8Array(this.result);
                const pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;

                if (!pdfjsLib) {
                    reject(new Error('PDF.js library missing'));
                    return;
                }

                pdfjsLib.getDocument(typedarray).promise.then(pdf => {
                    return pdf.getPage(1);
                }).then(page => {
                    const viewport = page.getViewport({
                        scale: 1
                    });
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    return page.render({
                        canvasContext: ctx,
                        viewport: viewport
                    }).promise.then(() => {
                        resolve(canvas.toDataURL('image/png'));
                    });
                }).catch(err => {
                    console.error('PDF Preview Error:', err);
                    resolve(''); // Resolve empty if cover fails, but don't fail whole item
                });
            };
            reader.onerror = () => resolve('');
            reader.readAsArrayBuffer(file);
        });
    }

    async function finalizeItemCreation(item) {
        const resp = await fetch('{{ route('catalog.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                brand_name: item.brand,
                catalog_name: item.name,
                catalog_file_path: item.path,
                catalog_cover_base64: item.coverBase64
            })
        });

        if (!resp.ok) {
            const data = await resp.json();
            throw new Error(data.message || 'Gagal menyimpan database');
        }
        return await resp.json();
    }

    function updateRowUI(id, status, progress, success = false, failure = false) {
        const row = document.getElementById(id);
        if (!row) return;

        const label = row.querySelector('.status-label');
        const pctLabel = row.querySelector('.progress-label');
        const progressBar = row.querySelector('progress');
        const errorLabel = row.querySelector('.error-label');

        label.innerText = status;
        pctLabel.innerText = progress + '%';
        progressBar.value = progress;

        if (success) {
            label.className = 'status-label text-success';
            pctLabel.className = 'progress-label text-success';
            progressBar.className = 'progress progress-success w-full h-2';
        } else if (failure) {
            label.className = 'status-label text-error';
            pctLabel.className = 'progress-label text-error';
            progressBar.className = 'progress progress-error w-full h-2';
            const item = bulkQueue.find(i => i.id === id);
            if (item && item.error) {
                errorLabel.innerText = item.error;
                errorLabel.classList.remove('hidden');
            }
        } else {
            label.className = 'status-label text-primary font-bold animate-pulse';
            progressBar.className = 'progress progress-primary w-full h-2 shadow-inner';
        }
    }

    function resetBulkModal() {
        bulkQueue = [];
        isUploading = false;
        document.getElementById('bulk_queue_body').innerHTML = `
            <tr id="empty_queue_row">
                <td colspan="4" class="px-4 py-12 text-center text-gray-400 italic">
                    <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        Belum ada file yang dipilih.
                    </div>
                </td>
            </tr>
        `;
        document.getElementById('bulk_action_bar').classList.add('hidden');
        const globalBrandInput = document.getElementById('global_brand_name');
        if (globalBrandInput) {
            globalBrandInput.value = '';
            globalBrandInput.dispatchEvent(new Event('input'));
        }
        document.getElementById('bulk_file_input').value = '';

        const startBtn = document.getElementById('start_bulk_upload_btn');
        startBtn.disabled = false;
        startBtn.innerText = 'Mulai Unggah Semua';
        startBtn.onclick = processBulkUpload;

        document.getElementById('bulk_status_indicator').innerText = 'Menunggu...';
        document.getElementById('bulk_status_indicator').className = 'badge badge-primary py-3 px-4 font-bold';
    }

    // Helper to hide errors on input
    function hideInputError(input) {
        input.classList.remove('border-error');
        const errorMsg = input.nextElementSibling;
        if (errorMsg) errorMsg.classList.add('hidden');
    }

    // Reset when modal closed
    document.getElementById('bulkCatalogModal').addEventListener('close', function() {
        if (!isUploading) resetBulkModal();
    });
</script>

<!-- Brand Datalist for individual rows -->
<datalist id="brand_list">
    @foreach($brands as $brand)
        <option value="{{ $brand }}">
    @endforeach
</datalist>

<style>
    @keyframes pulse-slow {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
