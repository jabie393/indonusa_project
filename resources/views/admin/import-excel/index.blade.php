<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div
            class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <h3 class="text-lg font-semibold text-white">
                Import Dari Excel
            </h3>
            <div class="p-4">
                <a href="{{ asset('file/template_input.xlsx') }}" download
                    class="flex items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-[#225A97] dark:focus:ring-primary-800">
                    <svg class="mr-2 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <g id="Interface / Download">
                                <path id="Vector" d="M6 21H18M12 3V17M12 17L17 12M12 17L7 12" stroke="white"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </g>
                    </svg>
                    Download Excel Template
                </a>
            </div>
        </div>

        <form action="{{ route('import-excel.import') }}" method="POST"
            class="flex h-fit flex-col space-y-4 overflow-auto p-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="import_file_path" id="import_file_path" value="">
            <div class="h-full overflow-auto">
                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="col-span-3">
                        <div class="mb-4 w-full">
                            <label for="gambar" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                File Excel
                            </label>
                            <input type="file" name="excel" id="excel" class="hidden" accept=".xlsx,.xls" />

                            <div id="upload-area"
                                class="relative mx-auto mb-4 flex h-48 w-full cursor-pointer items-center justify-center rounded-2xl border-2 border-dashed border-gray-400 bg-gray-100 text-center transition-colors hover:bg-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                                <!-- 1. Initial State: Label/Dropzone -->
                                <label id="upload-label" for="excel"
                                    class="m-auto flex w-full cursor-pointer flex-col items-center justify-center p-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="mb-4 h-8 w-8 text-gray-700 dark:text-gray-300">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-700 dark:text-white">
                                        Upload File</h5>
                                    <p class="text-gray-500 dark:text-gray-400">Support Format .Excel</p>
                                    <!-- Hidden filename placeholder for JS compatibility if needed by old logic, though we will rewrite logic -->
                                    <div id="excel_filename" class="hidden"></div>
                                </label>

                                <!-- 2. Progress State -->
                                <div id="progress-section" class="hidden w-full max-w-md p-6">
                                    <div class="mb-2 flex items-center justify-between">
                                        <span id="upload-status-text"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">Uploading...</span>
                                        <span id="progress-text"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">0%</span>
                                    </div>
                                    <div class="h-3 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div id="progress-bar"
                                            class="h-3 rounded-full bg-gradient-to-r from-[#225A97] to-[#0D223A] transition-all duration-300"
                                            style="width: 0%"></div>
                                    </div>
                                </div>

                                <!-- 3. Success State -->
                                <div id="upload-result" class="hidden w-full cursor-default p-6">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div class="rounded-full bg-green-100 p-2 dark:bg-green-900">
                                            <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <h5 class="text-lg font-bold text-gray-700 dark:text-white">Upload Berhasil!
                                        </h5>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            File: <span id="upload-filename"
                                                class="font-medium text-gray-900 dark:text-gray-100"></span>
                                        </div>
                                        <div class="mt-2 flex items-center gap-4">
                                            <a id="upload-url" href="#" target="_blank"
                                                class="hidden text-sm text-blue-600 hover:underline dark:text-blue-400">Lihat
                                                File</a>
                                            <span class="text-gray-300">|</span>
                                            <label for="excel"
                                                class="cursor-pointer text-sm text-gray-500 underline hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Ganti
                                                File</label>
                                        </div>
                                        <!-- Hidden placeholder to satisfy any JS searching for this ID if strictly needed outside logic, but mainly we use upload-filename span now -->
                                        <span id="upload-path" class="hidden"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label for="" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Map
                        </label>
                    </div>

                </div>
                <div class="mb-3">
                    <table class="table w-full text-left text-sm text-gray-500 dark:text-gray-400" id="DataTableExcel">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="min-w-[180px] px-4 py-3">Kode Barang</th>
                                <th class="min-w-[200px] px-4 py-3">Nama Barang</th>
                                <th class="min-w-[200px] px-4 py-3">Kategori</th>
                                <th class="min-w-[150px] px-4 py-3">Stok</th>
                                <th class="min-w-[200px] px-4 py-3">Harga</th>
                                <th class="min-w-[150px] px-4 py-3">Satuan</th>
                                <th class="min-w-[150px] px-4 py-3">Status Listing</th>
                                <th class="min-w-[150px] px-4 py-3">Gambar</th>
                                <th class="min-w-[200px] px-4 py-3">Deskripsi</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="overflow-x-scroll">
                            <tr>
                                <td>
                                    <div class="relative">
                                        <input type="text"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pr-10 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                            readonly>
                                        <button type="button" id="refreshKodeBarang"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-5 w-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                                viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21 12C21 16.9706 16.9706 21 12 21C9.69494 21 7.59227 20.1334 6 18.7083L3 16M3 12C3 7.02944 7.02944 3 12 3C14.3051 3 16.4077 3.86656 18 5.29168L21 8M3 21V16M3 16H8M21 3V8M21 8H16"
                                                    stroke="#000000" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <input type="text"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </td>
                                <td>
                                    <select name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </td>
                                <td>
                                    <input type="number" name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </td>
                                <td>
                                    <input type="text" name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </td>
                                <td>
                                    <select name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                        <option value="listing">Listing</option>
                                        <option value="non listing">Non Listing</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="upload-btn-container relative">
                                        <input type="file" name="items[0][images][]"
                                            class="item-images-input absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                            multiple accept="image/*">
                                        <button type="button"
                                            class="btn rounded-lg bg-blue-500 text-sm font-semibold text-white hover:bg-blue-600">
                                            Upload Gambar
                                        </button>
                                    </div>
                                    <div class="item-images-preview mt-2 flex flex-wrap gap-2 space-y-2"></div>
                                </td>
                                <td>
                                    <input type="text" name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </td>
                                <td>
                                    <button type="button" class="btn remove-row rounded-md bg-red-500 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trash2 h-4 w-4">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            <line x1="10" x2="10" y1="11" y2="17">
                                            </line>
                                            <line x1="14" x2="14" y1="11" y2="17">
                                            </line>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="submit-btn relative w-full rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-[#225A97] dark:focus:ring-primary-800">Tambah
                </button>
            </div>
        </form>
    </div>

    <script>
        window.CSRF_TOKEN = "{{ csrf_token() }}";
        window.EXISTING_KODES = @json($existingCodes ?? []);
        window.CHECK_KODE_BARANG_URL = "{{ route('check.kode.barang') }}";
        window.IMPORT_EXCEL_STORE_URL = "{{ route('import-excel.store') }}";

        // Handle image preview
        window.handleImagePreview = function(row) {
            const fileInput = row.querySelector('input[type="file"]');
            if (!fileInput) return; // Guard clause if no file input found

            const preview = row.querySelector('.item-images-preview');
            const uploadBtn = row.querySelector('.upload-btn-container');

            fileInput.addEventListener('change', function() {
                preview.innerHTML = '';
                if (this.files.length > 0) {
                    uploadBtn.style.display = 'none';
                } else {
                    uploadBtn.style.display = 'block';
                }

                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'relative inline-block';
                        imgContainer.innerHTML = `
                        <img src="${e.target.result}" class="w-20 h-20 object-cover rounded border dark:border-gray-600" title="${file.name}">
                        <button type="button" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs remove-image-btn" data-index="${index}">
                            ✕
                        </button>
                    `;
                        preview.appendChild(imgContainer);

                        // Add click handler to remove button
                        const removeBtn = imgContainer.querySelector('.remove-image-btn');
                        removeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            const removeIndex = parseInt(this.dataset.index);
                            const dataTransfer = new DataTransfer();

                            Array.from(fileInput.files).forEach((file, i) => {
                                if (i !== removeIndex) {
                                    dataTransfer.items.add(file);
                                }
                            });

                            fileInput.files = dataTransfer.files;
                            fileInput.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                        });
                    };
                    reader.readAsDataURL(file);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#DataTableExcel tbody tr');
            rows.forEach(row => {
                if (row.querySelector('input[type="file"]')) {
                    window.handleImagePreview(row);
                }
            });
        });
    </script>

    @vite(['resources/js/checker.js', 'resources/js/excel-upload.js'])
</x-app-layout>
