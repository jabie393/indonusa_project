<x-app-layout>
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            <a href="{{ asset('file/template_input.xlsx') }}"
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

    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>

        <form action="{{ route('import-excel.import') }}" method="POST"
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex h-fit flex-col space-y-4 overflow-auto p-4"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="import_file_path" id="import_file_path" value="">
            <input type="hidden" name="rows_json" id="rows_json" value="">
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
                <style>
                    /* Apply overrides only when dark mode is active so light mode keeps default DataTables styles */
                    .dark body table#DataTableExcel thead th,
                    .dark table#DataTableExcel thead th,
                    .dark .dataTables_wrapper table.dataTable thead th,
                    .dark .dataTables_wrapper .dataTables_scrollHead table thead th,
                    .dark .dataTables_wrapper .dataTables_scrollHead table thead th,
                    .dark .dataTables_wrapper table.dataTable thead th,
                    /* FixedColumns clones and cells in dark mode */
                    .dark .dtfc-fixed-start,
                    .dark .dtfc-fixed-end,
                    .dark .dtfc-fixed-center,
                    .dark .dtfc-fixed,
                    .dark .DTFC_Cloned,
                    .dark .DTFC_LeftWrapper,
                    .dark .DTFC_RightWrapper {
                        background-color: #374151 !important;
                        /* gray-700 */
                        color: #9CA3AF !important;
                        /* gray-400 */
                        border-color: rgba(255, 255, 255, 0.04) !important;
                    }

                    /* FixedHeader / ScrollHead clones (dark mode only) */
                    .dark .dataTables_fixedHeader thead th,
                    .dark .dataTables_scrollHeadInner table thead th,
                    .dark .dataTables_scrollHeadInner table thead td {
                        background-color: #374151 !important;
                        color: #9CA3AF !important;
                        border-color: rgba(255, 255, 255, 0.04) !important;
                    }

                    /* Dark-mode CSS variable override for FixedColumns only inside .dark */
                    .dark :root[data-theme-dark] {
                        --dtfc-thead-cell_background: #374151;
                    }

                    /* Remove DataTables header background-image in dark mode which may show white tile */
                    .dark .dataTables_wrapper .sorting,
                    .dark .dataTables_wrapper .sorting_asc,
                    .dark .dataTables_wrapper .sorting_desc,
                    .dark .dataTables_wrapper thead th {
                        background-image: none !important;
                    }

                    /* Styling untuk DataTable Excel Controls */
                    div.dt-container {
                        padding: 1rem 0;
                    }

                    /* Top Controls Bar (Search) Positioning & Styling */
                    div.dt-container div.grid-x:has(.dt-search),
                    div.dt-container div.dt-layout-row:has(.dt-search) {
                        display: flex !important;
                        justify-content: flex-end !important;
                        align-items: center !important;
                        padding: 1rem !important;
                        background-color: #ffffff !important;
                        border-top-left-radius: 1rem !important;
                        border-top-right-radius: 1rem !important;
                        border: 1px solid #e5e7eb !important;
                        border-bottom: none !important;
                        margin: 0 !important;
                    }

                    .dark div.dt-container div.grid-x:has(.dt-search),
                    .dark div.dt-container div.dt-layout-row:has(.dt-search) {
                        background-color: #1f2937 !important;
                        border-color: #374151 !important;
                    }

                    /* Scroll Body Borders */
                    div.dt-container div.dt-scroll-body {
                        border-left: 1px solid #e5e7eb !important;
                        border-right: 1px solid #e5e7eb !important;
                    }

                    .dark div.dt-container div.dt-scroll-body {
                        border-left-color: #374151 !important;
                        border-right-color: #374151 !important;
                    }

                    /* Bottom Controls Bar (Info, Length, Pagination) Positioning & Styling */
                    div.dt-container div.grid-x:has(.dt-paging),
                    div.dt-container div.dt-layout-row:has(.dt-paging) {
                        position: sticky !important;
                        bottom: 0 !important;
                        z-index: 20 !important;
                        display: flex !important;
                        flex-direction: column !important;
                        align-items: flex-start !important;
                        justify-content: space-between !important;
                        gap: 0.75rem !important;
                        /* space-y-3 */
                        background-color: #ffffff !important;
                        /* bg-white */
                        padding: 1rem !important;
                        /* p-4 */
                        border-bottom-left-radius: 1rem !important;
                        border-bottom-right-radius: 1rem !important;
                        border: 1px solid #e5e7eb !important;
                        border-top: 1px solid #e5e7eb !important;
                        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.03) !important;
                        margin: 0 !important;
                    }

                    .dark div.dt-container div.grid-x:has(.dt-paging),
                    .dark div.dt-container div.dt-layout-row:has(.dt-paging) {
                        background-color: #1f2937 !important;
                        /* bg-gray-800 */
                        border-color: #374151 !important;
                    }

                    @media (min-width: 768px) {

                        div.dt-container div.grid-x:has(.dt-paging),
                        div.dt-container div.dt-layout-row:has(.dt-paging) {
                            flex-direction: row !important;
                            align-items: center !important;
                            gap: 1rem !important;
                        }
                    }

                    /* Layout grid cells */
                    div.dt-container div.grid-x>div.cell,
                    div.dt-container div.dt-layout-row>div.dt-layout-cell {
                        display: flex !important;
                        align-items: center !important;
                        width: auto !important;
                        flex: 0 1 auto !important;
                    }

                    div.dt-container div.grid-x:has(.dt-paging)>div.cell:first-child,
                    div.dt-container div.dt-layout-row:has(.dt-paging)>div.dt-layout-cell:first-child {
                        flex-direction: row !important;
                        flex-wrap: wrap !important;
                        gap: 0.5rem !important;
                        justify-content: flex-start !important;
                    }

                    div.dt-container div.grid-x:has(.dt-paging)>div.cell:last-child,
                    div.dt-container div.dt-layout-row:has(.dt-paging)>div.dt-layout-cell:last-child {
                        justify-content: flex-end !important;
                        margin-left: auto !important;
                    }

                    @media (max-width: 767px) {

                        div.dt-container div.grid-x:has(.dt-paging)>div.cell:first-child,
                        div.dt-container div.dt-layout-row:has(.dt-paging)>div.dt-layout-cell:first-child,
                        div.dt-container div.grid-x:has(.dt-paging)>div.cell:last-child,
                        div.dt-container div.dt-layout-row:has(.dt-paging)>div.dt-layout-cell:last-child {
                            width: 100% !important;
                            justify-content: center !important;
                            margin-left: 0 !important;
                        }
                    }

                    div.dt-container div.dt-length,
                    div.dt-container div.dt-info {
                        display: flex !important;
                        align-items: center !important;
                        justify-content: flex-start !important;
                    }

                    div.dt-container div.dt-search,
                    div.dt-container div.dt-paging {
                        display: flex !important;
                        align-items: center !important;
                        justify-content: flex-end !important;
                    }

                    /* Styling Search Input */
                    div.dt-container div.dt-search input {
                        border-radius: 0.5rem;
                        border: 1px solid #d1d5db;
                        background-color: #f9fafb;
                        padding: 0.5rem 1rem;
                        font-size: 0.875rem;
                        color: #111827;
                        outline: none;
                        width: auto;
                        min-width: 200px;
                        transition: all 0.2s ease-in-out;
                    }

                    div.dt-container div.dt-search input:focus {
                        border-color: #3b82f6 !important;
                        box-shadow: 0 0 0 1px #3b82f6 !important;
                        background-color: #ffffff;
                    }

                    .dark div.dt-container div.dt-search input {
                        border-color: #4b5563;
                        background-color: #374151;
                        color: #ffffff;
                    }

                    .dark div.dt-container div.dt-search input:focus {
                        background-color: #1f2937;
                    }

                    div.dt-container div.dt-search label {
                        display: flex !important;
                        align-items: center !important;
                        gap: 0.5rem !important;
                        font-size: 0.875rem !important;
                        color: #4b5563 !important;
                    }

                    .dark div.dt-container div.dt-search label {
                        color: #d1d5db !important;
                    }

                    /* Styling Length Select */
                    div.dt-container div.dt-length select {
                        border-radius: 0.75rem !important;
                        /* rounded-xl */
                        border: 1px solid #d1d5db !important;
                        /* border-gray-300 */
                        background-color: #f9fafb !important;
                        /* bg-gray-50 */
                        padding: 0.25rem 2rem 0.25rem 0.5rem !important;
                        /* p-1 pl-2 pr-8 */
                        font-size: 0.875rem !important;
                        /* text-sm */
                        color: #111827 !important;
                        /* text-gray-900 */
                        outline: none !important;
                        margin-left: 0.5rem !important;
                        /* mx-2 */
                        margin-right: 0.5rem !important;
                        line-height: inherit !important;
                        height: auto !important;
                        transition: all 0.2s ease-in-out;
                    }

                    div.dt-container div.dt-length select:focus {
                        border-color: #3b82f6 !important;
                        box-shadow: 0 0 0 1px #3b82f6 !important;
                    }

                    .dark div.dt-container div.dt-length select {
                        border-color: #4b5563 !important;
                        background-color: #374151 !important;
                        color: #ffffff !important;
                    }

                    div.dt-container div.dt-length label {
                        display: flex !important;
                        align-items: center !important;
                        font-size: 0.875rem !important;
                        color: #6b7280 !important;
                        font-weight: 400 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }

                    .dark div.dt-container div.dt-length label {
                        color: #9ca3af !important;
                    }

                    /* Styling Info Text */
                    div.dt-container div.dt-info {
                        font-size: 0.875rem !important;
                        font-weight: 400 !important;
                        color: #6b7280 !important;
                        display: inline-block !important;
                        padding: 0 !important;
                        margin: 0 !important;
                    }

                    .dark div.dt-container div.dt-info {
                        color: #9ca3af !important;
                    }

                    div.dt-container div.dt-info b,
                    div.dt-container div.dt-info strong,
                    div.dt-container div.dt-info span {
                        font-weight: 600 !important;
                        color: #111827 !important;
                    }

                    .dark div.dt-container div.dt-info b,
                    .dark div.dt-container div.dt-info strong,
                    .dark div.dt-container div.dt-info span {
                        color: #ffffff !important;
                    }

                    /* Styling Zurb Foundation Pagination list structure */
                    div.dt-paging ul.pagination {
                        display: inline-flex !important;
                        flex-direction: row !important;
                        align-items: center !important;
                        list-style: none !important;
                        padding: 0 !important;
                        margin: 0 !important;
                        gap: 0 !important;
                        border: 1px solid #d1d5db !important;
                        border-radius: 0.5rem !important;
                        /* rounded-lg */
                        overflow: hidden !important;
                        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                        background-color: #ffffff !important;
                    }

                    .dark div.dt-paging ul.pagination {
                        border-color: #4b5563 !important;
                        background-color: #1f2937 !important;
                    }

                    /* Style the list item as the flex-grid cell */
                    div.dt-paging ul.pagination li {
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        list-style-type: none !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        min-width: 2.25rem !important;
                        height: 2.25rem !important;
                        box-sizing: border-box !important;
                        border-right: 1px solid #d1d5db !important;
                        background-color: #ffffff !important;
                        transition: all 0.2s ease-in-out !important;
                    }

                    .dark div.dt-paging ul.pagination li {
                        border-right-color: #4b5563 !important;
                        background-color: #1f2937 !important;
                    }

                    div.dt-paging ul.pagination li:last-child {
                        border-right: none !important;
                    }

                    /* Interactive / normal page links */
                    div.dt-container div.dt-paging ul.pagination li a,
                    div.dt-container div.dt-paging ul.pagination li span {
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        width: 100% !important;
                        height: 100% !important;
                        padding: 0 0.75rem !important;
                        color: #374151 !important;
                        /* Dark grey/black text */
                        font-size: 0.875rem !important;
                        font-weight: 500 !important;
                        text-decoration: none !important;
                        box-sizing: border-box !important;
                        border: none !important;
                        outline: none !important;
                        box-shadow: none !important;
                        background: transparent !important;
                        cursor: pointer !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li a,
                    .dark div.dt-container div.dt-paging ul.pagination li span {
                        color: #d1d5db !important;
                    }

                    /* Hover states on interactive links */
                    div.dt-container div.dt-paging ul.pagination li:has(a:hover),
                    div.dt-container div.dt-paging ul.pagination li:hover {
                        background-color: #f9fafb !important;
                    }

                    div.dt-container div.dt-paging ul.pagination li:has(a:hover) a {
                        color: #111827 !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li:has(a:hover),
                    .dark div.dt-container div.dt-paging ul.pagination li:hover {
                        background-color: #374151 !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li:has(a:hover) a {
                        color: #ffffff !important;
                    }

                    /* Active / Current Page */
                    div.dt-container div.dt-paging ul.pagination li.current,
                    div.dt-container div.dt-paging ul.pagination li.active {
                        background-color: #e5e7eb !important;
                        /* Light grey background matching image */
                        color: #111827 !important;
                        /* Black text matching image */
                        font-weight: 500 !important;
                        cursor: default !important;
                    }

                    div.dt-container div.dt-paging ul.pagination li.current a,
                    div.dt-container div.dt-paging ul.pagination li.current span,
                    div.dt-container div.dt-paging ul.pagination li.active a,
                    div.dt-container div.dt-paging ul.pagination li.active span {
                        color: #111827 !important;
                        font-weight: 500 !important;
                        cursor: default !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li.current,
                    .dark div.dt-container div.dt-paging ul.pagination li.active {
                        background-color: #374151 !important;
                        color: #ffffff !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li.current a,
                    .dark div.dt-container div.dt-paging ul.pagination li.current span {
                        color: #ffffff !important;
                    }

                    /* Previous/Next Arrows Text/Icon Color */
                    div.dt-container div.dt-paging ul.pagination li.pagination-previous,
                    div.dt-container div.dt-paging ul.pagination li.pagination-next {
                        color: #4b5563 !important;
                        /* Grey text for arrows matching image */
                    }

                    div.dt-container div.dt-paging ul.pagination li.pagination-previous a,
                    div.dt-container div.dt-paging ul.pagination li.pagination-previous span,
                    div.dt-container div.dt-paging ul.pagination li.pagination-next a,
                    div.dt-container div.dt-paging ul.pagination li.pagination-next span {
                        color: #4b5563 !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li.pagination-previous,
                    .dark div.dt-container div.dt-paging ul.pagination li.pagination-next,
                    .dark div.dt-container div.dt-paging ul.pagination li.pagination-previous a,
                    .dark div.dt-container div.dt-paging ul.pagination li.pagination-next a {
                        color: #9ca3af !important;
                    }

                    /* Disabled state (e.g. arrow on first/last page) */
                    div.dt-container div.dt-paging ul.pagination li.disabled {
                        color: #9ca3af !important;
                        /* Muted text */
                        background-color: #ffffff !important;
                        pointer-events: none !important;
                        cursor: not-allowed !important;
                    }

                    div.dt-container div.dt-paging ul.pagination li.disabled a,
                    div.dt-container div.dt-paging ul.pagination li.disabled span {
                        color: #9ca3af !important;
                        cursor: not-allowed !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li.disabled {
                        background-color: #1f2937 !important;
                        color: #4b5563 !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li.disabled a,
                    .dark div.dt-container div.dt-paging ul.pagination li.disabled span {
                        color: #4b5563 !important;
                    }

                    /* Ellipsis (...) Styling */
                    div.dt-container div.dt-paging ul.pagination li.ellipsis {
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        background-color: #ffffff !important;
                        color: #4b5563 !important;
                        /* Black/dark grey text */
                        cursor: default !important;
                    }

                    div.dt-container div.dt-paging ul.pagination li.ellipsis:empty::before {
                        content: "..." !important;
                        font-weight: 500 !important;
                    }

                    div.dt-container div.dt-paging ul.pagination li.ellipsis a,
                    div.dt-container div.dt-paging ul.pagination li.ellipsis span {
                        color: #4b5563 !important;
                        cursor: default !important;
                        background: transparent !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li.ellipsis {
                        background-color: #1f2937 !important;
                        color: #9ca3af !important;
                    }

                    .dark div.dt-container div.dt-paging ul.pagination li.ellipsis a,
                    .dark div.dt-container div.dt-paging ul.pagination li.ellipsis span {
                        color: #9ca3af !important;
                    }
                </style>

                <div class="mb-3">
                    <table class="table w-full text-left text-sm text-gray-500 dark:text-gray-400" id="DataTableExcel">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="min-w-[180px] px-4 py-3">Kode Barang</th>
                                <th class="min-w-[200px] px-4 py-3">Nama Barang</th>
                                <th class="min-w-[200px] px-4 py-3">Deskripsi</th>
                                <th class="min-w-[200px] px-4 py-3">Kategori</th>
                                <th class="min-w-[150px] px-4 py-3">Stok</th>
                                <th class="min-w-[200px] px-4 py-3">Harga Beli</th>
                                <th class="min-w-[200px] px-4 py-3">Harga Jual (Opsional)</th>
                                <th class="min-w-[150px] px-4 py-3">Satuan</th>
                                <th class="min-w-[150px] px-4 py-3">Status Listing</th>
                                <th class="min-w-[150px] px-4 py-3">Gambar</th>
                                <th class="px-4 py-3 bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="overflow-x-scroll">
                            <tr>
                                <td>
                                    <div class="relative">
                                        <input type="text"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pr-10 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                            placeholder="Auto Generate" readonly>
                                        <button type="button"
                                            class="refresh-kode-barang-btn absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-5 w-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                        placeholder="cth. Kopi Arabika 250g" required>
                                </td>
                                <td>
                                    <input type="text" name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        placeholder="cth. Kopi bubuk premium dari biji pilihan" required>
                                </td>
                                <td>
                                    <select disabled name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-slate-500 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required
                                        onchange="this.classList.remove('text-slate-500'); this.classList.add('text-slate-900', 'dark:text-white')">
                                        <option disabled selected value="">Pilih Kategori</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        placeholder="0" required>
                                </td>
                                <!-- Harga Beli (Index 5) -->
                                <td>
                                    <div class="relative">
                                        <span
                                            class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">Rp</span>
                                        <input
                                            class="w-full rounded-lg border border-gray-300 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                            placeholder="0" type="text" required />
                                        <input type="hidden" name="" id="" />
                                    </div>
                                </td>
                                <!-- Harga Jual (Index 6) -->
                                <td>
                                    <div class="relative">
                                        <span
                                            class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">Rp</span>
                                        <input
                                            class="w-full rounded-lg border border-gray-300 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                            placeholder="Otomatis +15%" type="text" />
                                        <input type="hidden" name="" id="" />
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="" id=""
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        placeholder="pcs, kg, box…" required>
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
                                            class="btn rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] text-sm font-semibold text-white">
                                            Upload Gambar
                                        </button>
                                    </div>
                                    <div class="item-images-preview mt-2 flex flex-wrap gap-2 space-y-2"></div>
                                </td>

                                <td>
                                    <button type="button"
                                        class="btn remove-row rounded-md bg-red-500 text-white dark:bg-red-600 dark:hover:bg-red-700 dark:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
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
        window.handleImagePreview = function (row) {
            const fileInput = row.querySelector('input[type="file"]');
            if (!fileInput) return; // Guard clause if no file input found

            const preview = row.querySelector('.item-images-preview');
            const uploadBtn = row.querySelector('.upload-btn-container');

            fileInput.addEventListener('change', function () {
                preview.innerHTML = '';
                if (this.files.length > 0) {
                    uploadBtn.style.display = 'none';
                } else {
                    uploadBtn.style.display = '';
                }

                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'group relative inline-block';
                        imgContainer.innerHTML = `
                        <a href="${e.target.result}" target="_blank">
                            <img src="${e.target.result}" class="w-20 h-20 object-cover rounded border dark:border-gray-600 transition-transform hover:scale-105" title="${file.name}">
                        </a>
                        <button type="button" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs remove-image-btn opacity-0 group-hover:opacity-100 transition-opacity shadow-lg" data-index="${index}">
                            \u2715
                        </button>
                    `;
                        preview.appendChild(imgContainer);

                        // Add click handler to remove button
                        const removeBtn = imgContainer.querySelector('.remove-image-btn');
                        removeBtn.addEventListener('click', function (e) {
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

        document.addEventListener('DOMContentLoaded', function () {
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