document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(
        'form[action*="import-excel.import"], form[enctype="multipart/form-data"]'
    );
    const fileInput = document.getElementById("excel");
    const progressSection = document.getElementById("progress-section");
    const progressBar = document.getElementById("progress-bar");
    const progressText = document.getElementById("progress-text");
    const uploadArea = document.getElementById("upload-area");
    const uploadLabel = document.getElementById("upload-label");
    const uploadResult = document.getElementById("upload-result");
    const importFilePathInput = document.getElementById("import_file_path");

    if (!form || !fileInput) return;

    let uploadInProgress = false;
    let uploadCompleted = false;
    const submitButton = form.querySelector(".submit-btn");

    // Intercept form submission
    form.addEventListener("submit", function (e) {
        try {
            if (window.allExcelRows && window.excelMapping) {
                const dt = $("#DataTableExcel").DataTable();
                const visibleRows = dt.rows().nodes();

                Array.from(visibleRows).forEach((tr) => {
                    if (!tr) return;
                    const rowIndex = dt.row(tr).index();
                    if (rowIndex === undefined || rowIndex === null || rowIndex >= window.allExcelRows.length) return;

                    const rowData = window.allExcelRows[rowIndex];
                    let r = rowData;
                    if (rowData && typeof rowData === 'object' && !Array.isArray(rowData) && rowData.data) {
                        r = rowData.data;
                    }

                    const getDomVal = (colIdx, selector) => {
                        const td = tr.children[colIdx];
                        if (!td) return '';
                        const el = td.querySelector(selector);
                        return el ? el.value : '';
                    };

                    const domValues = {
                        goods_code: getDomVal(0, "input[type='text']") || getDomVal(0, "input[type='hidden']"),
                        goods_name: getDomVal(1, "input[type='text']") || getDomVal(1, "input[type='hidden']"),
                        description: getDomVal(2, "input[type='text']") || getDomVal(2, "input[type='hidden']"),
                        category: getDomVal(3, "select") || getDomVal(3, "input[type='hidden']"),
                        stock: getDomVal(4, "input[type='number']") || getDomVal(4, "input[type='text']") || getDomVal(4, "input[type='hidden']"),
                        buy_price: getDomVal(5, "input[type='text']") || getDomVal(5, "input[type='hidden']"),
                    };

                    if (tr.children.length > 7) {
                        domValues.selling_price = getDomVal(6, "input[type='text']") || getDomVal(6, "input[type='hidden']");
                        domValues.unit = getDomVal(7, "input[type='text']") || getDomVal(7, "input[type='hidden']");
                        domValues.status_listing = getDomVal(8, "select") || getDomVal(8, "input[type='hidden']");
                    }

                    for (const field in domValues) {
                        const excelColIdx = window.excelMapping[field];
                        if (excelColIdx !== null && excelColIdx !== undefined && excelColIdx !== "") {
                            let val = domValues[field];
                            if (field === 'buy_price' || field === 'selling_price' || field === 'stock') {
                                val = val.replace(/[^\d]/g, "");
                            }
                            r[excelColIdx] = val;
                        }
                    }
                    rowData.goods_code = domValues.goods_code;
                });

                const rowsToSubmit = window.allExcelRows.map((rowObj, index) => {
                    let r = rowObj;
                    if (rowObj && typeof rowObj === 'object' && !Array.isArray(rowObj) && rowObj.data) {
                        r = rowObj.data;
                    }

                    const getVal = (field) => {
                        const col = window.excelMapping[field];
                        if (col === null || col === undefined || col === "") return "";
                        return r[col] !== undefined && r[col] !== null ? r[col] : "";
                    };

                    let buyPriceRaw = getVal("buy_price") || "0";
                    if (typeof buyPriceRaw === "string") buyPriceRaw = buyPriceRaw.replace(/[^\d]/g, "");
                    
                    let sellPriceRaw = getVal("selling_price") || "";
                    if (typeof sellPriceRaw === "string") sellPriceRaw = sellPriceRaw.replace(/[^\d]/g, "");

                    let stockRaw = getVal("stock") || "0";
                    if (typeof stockRaw === "string") stockRaw = stockRaw.replace(/[^\d]/g, "");

                    let codeVal = rowObj.goods_code || getVal("goods_code");
                    if (!codeVal) {
                        codeVal = generateKodeFromCategory(getVal("category"), getVal("goods_name"), index);
                        rowObj.goods_code = codeVal;
                    }

                    return {
                        goods_code: codeVal,
                        goods_name: getVal("goods_name") || "Unnamed",
                        description: getVal("description") || "Deskripsi otomatis",
                        category: getVal("category") || "",
                        stock: stockRaw,
                        buy_price: buyPriceRaw,
                        selling_price: sellPriceRaw,
                        unit: getVal("unit") || "pcs",
                        status_listing: getVal("status_listing") || "listing",
                    };
                });

                const rowsJsonInput = document.getElementById("rows_json");
                if (rowsJsonInput) {
                    rowsJsonInput.value = JSON.stringify(rowsToSubmit);
                }
            }

            const hasDuplicates = validateDuplicateCodes(true);
            if (hasDuplicates) {
                e.preventDefault();
                e.stopImmediatePropagation();
                if (window.Swal) {
                    window.Swal.fire({
                        icon: "error",
                        title: "Terdapat Duplikat Kode Barang",
                        text: "Terdapat kode barang yang sama (duplikat) atau sudah ada di database. Silakan perbaiki/refresh kode yang bertanda merah sebelum menyimpan.",
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    });
                } else {
                    alert("Terdapat kode barang duplikat. Harap perbaiki sebelum submit.");
                }
                return false;
            }

            if (window.Swal) {
                window.Swal.fire({
                    title: "Sedang Mengimpor Data...",
                    html: "Harap tunggu beberapa saat. Jangan menutup atau memuat ulang halaman ini.",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        window.Swal.showLoading();
                    }
                });
            }
        } catch (err) {
            e.preventDefault();
            console.error("Submission failed:", err);
            if (window.Swal) {
                window.Swal.fire({
                    icon: "error",
                    title: "Gagal Mengimpor",
                    text: "Terjadi kesalahan sistem saat memproses data sebelum dikirim: " + err.message,
                });
            } else {
                alert("Terjadi kesalahan sistem saat memproses data sebelum dikirim: " + err.message);
            }
            return false;
        }
    });

    // Simpan template row di awal sebelum DataTable diinisialisasi
    const tableEl = document.getElementById("DataTableExcel");
    const tbody = tableEl ? tableEl.querySelector("tbody") : null;
    const savedTemplateRow =
        tbody && tbody.querySelector("tr")
            ? tbody.querySelector("tr").cloneNode(true)
            : null;

    // helper: auto-map headers to fields using keywords
    function autoMapHeaders(headers) {
        const lower = headers.map((h) => (h || "").toString().toLowerCase());
        const map = {};
        const pick = (keywords) => {
            for (let i = 0; i < lower.length; i++) {
                const h = lower[i];
                for (const k of keywords) {
                    if (h.includes(k)) return i;
                }
            }
            return null;
        };

        // FORCE NULL so it is always generated
        map["goods_code"] = null;
        map["goods_name"] =
            pick(["nama", "product", "barang", "item", "description"]) ??
            pick(["nama barang", "nama_produk"]);
        map["category"] = pick(["kategori", "category"]);
        map["description"] = pick([
            "deskripsi",
            "description",
            "keterangan",
            "note",
        ]);
        map["stock"] = pick(["stok", "quantity", "qty", "jumlah"]);
        map["unit"] = pick(["satuan", "unit"]);
        map["buy_price"] = pick(["harga beli", "buy price", "beli"]);
        if (map["buy_price"] === null) {
            map["buy_price"] = pick(["harga", "price", "rp"]);
        }
        map["selling_price"] = pick(["harga jual", "selling price", "jual"]);
        map["image"] = pick(["gambar", "image", "photo", "foto"]);
        map["status_listing"] = pick(["status", "listing"]);

        // ensure unique: if two fields picked same index keep first and clear later ones
        const used = {};
        for (const key of Object.keys(map)) {
            const idx = map[key];
            if (idx === null) continue;
            if (used[idx]) {
                map[key] = null;
            } else {
                used[idx] = key;
            }
        }

        return map;
    }

    // tambahkan map singkatan & helper generator (pakai window.kategoriSingkatan bila ada)
    const kategoriSingkatanLocal = window.kategoriSingkatan || {
        HANDTOOLS: "HT",
        "ADHESIVE AND SEALANT": "AS",
        "AUTOMOTIVE EQUIPMENT": "AE",
        CLEANING: "CLN",
        COMPRESSOR: "CMP",
        CONSTRUCTION: "CST",
        "CUTTING TOOLS": "CT",
        LIGHTING: "LTG",
        FASTENING: "FST",
        GENERATOR: "GEN",
        "HEALTH CARE EQUIPMENT": "HCE",
        HOSPITALITY: "HSP",
        "HYDRAULIC TOOLS": "HYD",
        "MARKING MACHINE": "MM",
        "MATERIAL HANDLING EQUIPMENT": "MHE",
        "MEASURING AND TESTING EQUIPMENT": "MTE",
        "METAL CUTTING MACHINERY": "MCM",
        PACKAGING: "PKG",
        "PAINTING AND COATING": "PC",
        "PNEUMATIC TOOLS": "PN",
        "POWER TOOLS": "PT",
        "SAFETY AND PROTECTION EQUIPMENT": "SPE",
        SECURITY: "SEC",
        "SHEET METAL MACHINERY": "SMM",
        "STORAGE SYSTEM": "STS",
        "WELDING EQUIPMENT": "WLD",
        "WOODWORKING EQUIPMENT": "WWE",
        MISCELLANEOUS: "MSC",
        "OTHER CATEGORIES": "OC",
    };

    // Prepare Existing Codes Set for fast lookup (includes generated ones during session)
    const existingCodesSet = new Set(
        (window.EXISTING_KODES || []).map((k) => String(k).toUpperCase())
    );
    // Keep a pure Set of DB codes for validation (static)
    const dbCodesSet = new Set(
        (window.EXISTING_KODES || []).map((k) => String(k).toUpperCase())
    );

    function generateKodeFromCategory(kategori, nama, rowIndex) {
        // pakai singkatan kategori bila ada (normalize key), jika tidak gunakan 2-3 huruf awal dari nama barang
        let sing = "";
        if (kategori) {
            const key = String(kategori).trim().toUpperCase();
            sing = kategoriSingkatanLocal[kategori] || kategoriSingkatanLocal[key] || "";
        }
        if (!sing) {
            if (nama) {
                const parts = String(nama).trim().split(/\s+/);
                sing = parts
                    .map((p) => p[0] || "")
                    .join("")
                    .slice(0, 3)
                    .toUpperCase();
            } else {
                sing = "UNK";
            }
            if (!sing) sing = "UNK";
        }

        // Base logic: SING-RANDOM7DIGIT
        // Using increment strategy for collisions as requested (no suffix)

        // Initial seed from random 7-digit number
        let seed = Math.floor(Math.random() * 10000000);
        let candidate = ``;

        let attempt = 0;
        // Try creating a code. If taken, increment seed and try again.
        while (attempt < 2000) {
            candidate = `${sing}-${seed.toString().padStart(7, "0")}`;

            if (!existingCodesSet.has(candidate)) {
                // Found a free slot
                existingCodesSet.add(candidate);
                return candidate;
            }

            // Collision -> Increment seed
            seed++;
            if (seed > 9999999) seed = 0; // Wrap around
            attempt++;
        }

        // Fallback (extremely unlikely) - if wrapped 2000 times and all taken?
        // Just return the last candidate or maybe fallback to original random logic if critical failure
        return candidate;
    }

    // Strict Header Validation
    function validateHeaders(headers) {
        if (!Array.isArray(headers))
            return { valid: false, missing: [], extra: [] };

        const required = [
            "KATEGORI",
            "NAMA BARANG",
            "DESKRIPSI",
            "STOK",
            "SATUAN",
        ];

        // Columns that are allowed but will be ignored during import
        const ignored = ["LIST KATEGORI"];

        const upperHeaders = headers.map((h) => String(h).trim().toUpperCase());

        // Check if either "HARGA BELI" or "HARGA" exists as the required price column
        const hasHargaBeli = upperHeaders.includes("HARGA BELI") || upperHeaders.includes("HARGA");
        const missing = required.filter((req) => !upperHeaders.includes(req));
        if (!hasHargaBeli) {
            missing.push("HARGA BELI");
        }

        // Any header in upperHeaders that is NOT in required list AND NOT "HARGA BELI"/"HARGA"/"HARGA JUAL" AND NOT in ignored list is an extra
        const extra = upperHeaders.filter(
            (h) => h !== "" && 
                   !required.includes(h) && 
                   h !== "HARGA BELI" && 
                   h !== "HARGA" && 
                   h !== "HARGA JUAL" && 
                   !ignored.includes(h)
        );

        return {
            valid: missing.length === 0 && extra.length === 0,
            missing: missing,
            extra: extra,
        };
    }

    // helper: inject hidden mapping inputs into form (overwrites previous)
    function injectMappingInputs(mapping) {
        // remove previous mapping inputs
        form.querySelectorAll('input[name^="mapping["]').forEach((i) =>
            i.remove()
        );
        for (const field in mapping) {
            const val = mapping[field];
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = `mapping[${field}]`;
            input.value = val === null || val === undefined ? "" : String(val);
            form.appendChild(input);
        }
    }

    // helper: remove completely empty rows and trim each row to headers length
    // Also validates that rows have essential product data (goods_name)
    function cleanRows(rows, headers, mapping) {
        if (!Array.isArray(rows)) return [];
        const hlen = Array.isArray(headers) ? headers.length : null;

        return rows
            .map((r) => (Array.isArray(r) ? r : []))
            .map((r) => (hlen ? r.slice(0, hlen) : r))
            .map((r) =>
                r.map((c) =>
                    c === null || c === undefined ? "" : String(c).trim()
                )
            )
            .filter((r) => {
                // Check if row has at least one non-empty cell
                if (!r.some((c) => c !== "")) return false;

                // If mapping is provided, validate that essential fields have data
                if (mapping && typeof mapping === "object") {
                    // Check if goods_name column has data (essential field)
                    const namaBarangIdx = mapping["goods_name"];
                    if (
                        namaBarangIdx !== null &&
                        namaBarangIdx !== undefined &&
                        namaBarangIdx !== ""
                    ) {
                        const namaBarangValue = r[namaBarangIdx];
                        // Only include row if goods_name has actual data
                        return (
                            namaBarangValue &&
                            String(namaBarangValue).trim() !== ""
                        );
                    }
                }

                // Fallback: keep rows that have at least one non-empty cell
                return true;
            });
    }

    // Fungsi untuk mendeteksi NAMA BARANG yang sama dengan KATEGORI berbeda
    function validateDuplicateNames(rows, mapping) {
        const duplicateIndices = new Set();
        const nameKategoriMap = new Map(); // Map: nama_barang -> Set of {kategori, rowIndices[]}

        // Iterasi semua baris untuk mengumpulkan data
        rows.forEach((r, idx) => {
            const namaIdx = mapping["goods_name"];
            const kategoriIdx = mapping["category"];

            if (namaIdx === null || namaIdx === undefined || namaIdx === "")
                return;
            if (
                kategoriIdx === null ||
                kategoriIdx === undefined ||
                kategoriIdx === ""
            )
                return;

            const nama = String(r[namaIdx] || "")
                .trim()
                .toUpperCase();
            const kategori = String(r[kategoriIdx] || "")
                .trim()
                .toUpperCase();

            if (!nama) return; // Skip jika nama kosong

            if (!nameKategoriMap.has(nama)) {
                nameKategoriMap.set(nama, new Map());
            }

            const kategoriMap = nameKategoriMap.get(nama);
            if (!kategoriMap.has(kategori)) {
                kategoriMap.set(kategori, []);
            }
            kategoriMap.get(kategori).push(idx);
        });

        // Cek konflik: nama yang sama dengan kategori berbeda
        nameKategoriMap.forEach((kategoriMap, nama) => {
            if (kategoriMap.size > 1) {
                // Ada lebih dari 1 kategori untuk nama yang sama = KONFLIK!
                kategoriMap.forEach((indices) => {
                    indices.forEach((idx) => duplicateIndices.add(idx));
                });
            }
        });

        return Array.from(duplicateIndices);
    }

    // render DataTableExcel from rows (rows are array-of-arrays, headers not included)
    function renderDataTableFromPreviewAll(rows, mapping, headers) {
        const tableEl = document.getElementById("DataTableExcel");
        if (!tableEl) return;

        window.dataTableSearchTerm = ""; // Reset search term for new uploads

        // Get atau inisialisasi DataTable instance dengan aman
        if ($.fn.DataTable.isDataTable("#DataTableExcel")) {
            $("#DataTableExcel").DataTable().destroy();
        }

        let dt = $("#DataTableExcel").DataTable({
            scrollX: true,
            paging: true,
            searching: true,
            ordering: true,
            pageLength: 10,
            pagingType: 'simple_numbers',
            columnDefs: [
                {
                    targets: '_all',
                    searchable: false
                }
            ],
            layout: {
                topStart: null,
                topEnd: 'search',
                bottomStart: ['info', 'pageLength'],
                bottomEnd: 'paging'
            },
            drawCallback: function(settings) {
                const api = this.api();
                const container = api.table().container();
                if (container) {
                    const pagingEl = container.querySelector('.dt-paging');
                    if (pagingEl) {
                        if (api.page.info().pages <= 1) {
                            pagingEl.style.setProperty('display', 'none', 'important');
                        } else {
                            pagingEl.style.removeProperty('display');
                        }
                    }
                }
            },
            language: {
                info: "Menampilkan <span class='font-semibold text-gray-900 dark:text-white'>_START_-_END_</span> dari <span class='font-semibold text-gray-900 dark:text-white'>_TOTAL_</span>",
                lengthMenu: "_MENU_ per halaman",
                search: "",
                searchPlaceholder: "Search",
                paginate: {
                    previous: '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>',
                    next: '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>'
                }
            }
        });

        // Intercept default search input to bypass built-in DataTable search
        const container = dt.table().container();
        if (container) {
            const searchInput = container.querySelector('.dt-search input');
            if (searchInput) {
                $(searchInput).off().on('keyup input search', function() {
                    window.dataTableSearchTerm = this.value;
                    dt.draw();
                });
            }
        }

        const tbody = tableEl.querySelector("tbody");
        if (!tbody) return;

        // Gunakan template row yang sudah disimpan di awal
        let baseRow;
        if (savedTemplateRow) {
            baseRow = savedTemplateRow.cloneNode(true);
        } else {
            // Fallback: jika tidak ada template tersimpan, buat manual
            baseRow = document.createElement("tr");
            for (let i = 0; i < 10; i++) {
                const td = document.createElement("td");
                if (i === 9) {
                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.className =
                        "btn remove-row rounded-md bg-red-500 text-white";
                    btn.innerText = "Delete";
                    td.appendChild(btn);
                } else {
                    const inp = document.createElement("input");
                    inp.type = "text";
                    inp.className = "block w-full";
                    td.appendChild(inp);
                }
                baseRow.appendChild(td);
            }
        }

        // Save to window
        window.allExcelRows = rows;
        window.excelMapping = mapping;

        // clear existing rows using DataTable API
        dt.clear();

        const renderLimit = 100;
        if (rows.length > renderLimit && window.Swal) {
            window.Swal.fire({
                icon: "info",
                title: "File Excel Besar Terdeteksi",
                text: `Menampilkan ${renderLimit} baris pertama sebagai preview untuk memverifikasi pemetaan kolom. Seluruh ${rows.length} baris akan diimpor secara langsung saat Anda menekan tombol Simpan.`,
                confirmButtonText: "Mengerti"
            });
        }

        const rowsToRender = rows.slice(0, renderLimit);
        const newRows = rowsToRender.map((r, rowIndex) => {
            const newRow = baseRow.cloneNode(true);

            const getVal = (field) => {
                const col = mapping[field];
                if (col === null || col === undefined || col === "") return "";
                return r[col] !== undefined && r[col] !== null ? r[col] : "";
            };

            // columns mapping based on table columns in blade:
            // 0: goods_code (readonly input)
            const tdKode = newRow.children[0];
            if (tdKode) {
                let inp = tdKode.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    tdKode.appendChild(inp);
                }
                inp.readOnly = true;
                const rowObj = rowsToRender[rowIndex];
                const kodeVal =
                    getVal("goods_code") ||
                    rowObj.goods_code ||
                    generateKodeFromCategory(
                        getVal("category"),
                        getVal("goods_name"),
                        rowIndex
                    );
                inp.value = kodeVal;
                rowObj.goods_code = kodeVal;
                // add hidden input for goods_code so it gets submitted as array
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][goods_code]`;
                hidden.value = kodeVal;
                tdKode.appendChild(hidden);
                // Jika fungsi validateKodeBarang tersedia (didefinisikan di checker.js), panggil untuk cek unik
                try {
                    if (typeof validateKodeBarang === "function")
                        validateKodeBarang(inp);
                } catch (e) {
                    /* ignore */
                }
            }

            // 1: goods_name
            const tdNama = newRow.children[1];
            if (tdNama) {
                let inp = tdNama.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    tdNama.appendChild(inp);
                }
                const v = getVal("goods_name") || "";
                inp.value = v;
                // hidden
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][goods_name]`;
                hidden.value = v;
                tdNama.appendChild(hidden);
            }

            // 2: Description
            const tdDeskripsi = newRow.children[2];
            if (tdDeskripsi) {
                let inp = tdDeskripsi.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    inp.className =
                        "block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400";
                    tdDeskripsi.appendChild(inp);
                }
                const v = getVal("description") || "";
                inp.value = v;

                // Hidden input for submission
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][description]`;
                hidden.value = v;
                tdDeskripsi.appendChild(hidden);
            }

            // 3: category (select exists in template). Try to set select or fallback hidden
            const tdKategori = newRow.children[3];
            if (tdKategori) {
                const sel = tdKategori.querySelector("select");
                const v = (getVal("category") || "").toString().trim();
                if (sel) {
                    // try match option text or value
                    let matched = false;
                    Array.from(sel.options).forEach((opt) => {
                        if (
                            !matched &&
                            opt.value &&
                            opt.value.toString().toLowerCase() ===
                                v.toLowerCase()
                        ) {
                            sel.value = opt.value;
                            matched = true;
                        }
                    });
                    if (!matched) {
                        Array.from(sel.options).forEach((opt) => {
                            if (
                                !matched &&
                                opt.text &&
                                opt.text.toString().toLowerCase() ===
                                    v.toLowerCase()
                            ) {
                                sel.value = opt.value;
                                matched = true;
                            }
                        });
                    }
                    // hidden to submit value
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][category]`;
                    hidden.value = sel.value || v;
                    tdKategori.appendChild(hidden);
                } else {
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][category]`;
                    hidden.value = v;
                    tdKategori.appendChild(hidden);
                }
            }

            // 4: stock
            const tdStok = newRow.children[4];
            if (tdStok) {
                const displayInput = tdStok.querySelector('input[type="text"]');
                const hiddenInput = tdStok.querySelector('input[type="hidden"]');
                
                let v = getVal("stock") || "0";
                if (typeof v === "string") {
                    v = v.replace(/[^\d]/g, ""); // Keep only digits
                }
                
                if (displayInput) {
                    displayInput.value = v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    displayInput.addEventListener('input', function(e) {
                        let rawValue = this.value.replace(/\D/g, "");
                        this.value = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        if (hiddenInput) hiddenInput.value = rawValue;
                    });
                }
                
                if (hiddenInput) {
                    hiddenInput.name = `rows[${rowIndex}][stock]`;
                    hiddenInput.value = v;
                } else {
                    // Fallback to basic input if premium structure missing
                    let inp = tdStok.querySelector("input");
                    if (inp) {
                        inp.value = v;
                        inp.name = `rows[${rowIndex}][stock]`;
                    }
                }
            }

            // 5: buy_price
            const tdHargaBeli = newRow.children[5];
            if (tdHargaBeli) {
                const displayInput = tdHargaBeli.querySelector('input[type="text"]');
                const hiddenInput = tdHargaBeli.querySelector('input[type="hidden"]');
                
                let v = getVal("buy_price") || "0";
                if (typeof v === "string") {
                    v = v.replace(/[^\d]/g, ""); // Keep only digits
                }
                
                if (displayInput) {
                    displayInput.value = v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    displayInput.addEventListener('input', function(e) {
                        let rawValue = this.value.replace(/\D/g, "");
                        this.value = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        if (hiddenInput) hiddenInput.value = rawValue;
                    });
                }
                
                if (hiddenInput) {
                    hiddenInput.name = `rows[${rowIndex}][buy_price]`;
                    hiddenInput.value = v;
                }
            }

            // 6: selling_price
            const tdHargaJual = newRow.children[6];
            if (tdHargaJual) {
                const displayInput = tdHargaJual.querySelector('input[type="text"]');
                const hiddenInput = tdHargaJual.querySelector('input[type="hidden"]');
                
                let v = getVal("selling_price") || "";
                if (typeof v === "string") {
                    v = v.replace(/[^\d]/g, ""); // Keep only digits
                }
                
                if (displayInput) {
                    displayInput.value = v !== "" ? v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : "";
                    displayInput.addEventListener('input', function(e) {
                        let rawValue = this.value.replace(/\D/g, "");
                        this.value = rawValue !== "" ? rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",") : "";
                        if (hiddenInput) hiddenInput.value = rawValue;
                    });
                }
                
                if (hiddenInput) {
                    hiddenInput.name = `rows[${rowIndex}][selling_price]`;
                    hiddenInput.value = v;
                }
            }

            // 7: unit
            const tdSatuan = newRow.children[7];
            if (tdSatuan) {
                let inp = tdSatuan.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    tdSatuan.appendChild(inp);
                }
                const v = getVal("unit") || "";
                inp.value = v;
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][unit]`;
                hidden.value = v;
                tdSatuan.appendChild(hidden);
            }

            // 8: status_listing
            const tdStatus = newRow.children[8];
            if (tdStatus) {
                const sel = tdStatus.querySelector("select");
                const v = (getVal("status_listing") || "listing")
                    .toString()
                    .toLowerCase();
                if (sel) {
                    Array.from(sel.options).forEach((opt) => {
                        if (opt.value.toLowerCase() === v)
                            sel.value = opt.value;
                    });
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][status_listing]`;
                    hidden.value = sel.value || v;
                    tdStatus.appendChild(hidden);
                } else {
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][status_listing]`;
                    hidden.value = v;
                    tdStatus.appendChild(hidden);
                }
            }

            // 9: Gambar
            const tdGambar = newRow.children[9];
            if (tdGambar) {
                const fileInput = tdGambar.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.name = `rows[${rowIndex}][images][]`;
                    fileInput.value = ""; // clear selection
                    const preview = tdGambar.querySelector(
                        ".item-images-preview"
                    );
                    if (preview) preview.innerHTML = "";
                    const uploadBtn = tdGambar.querySelector(
                        ".upload-btn-container"
                    );
                    if (uploadBtn) uploadBtn.style.display = "block";
                }
            }

            // 10: aksi - keep remove button functional
            const aksiTd = newRow.children[10];
            if (aksiTd) {
                const removeBtn = aksiTd.querySelector("button.remove-row");
                if (removeBtn) {
                    removeBtn.addEventListener("click", () => {
                        newRow.remove();
                    });
                }
            }

            if (window.handleImagePreview) {
                window.handleImagePreview(newRow);
            }

            return newRow;
        });
        // Add all rows using the requested API and add the 'new' class safely
        const nodes = dt.rows.add(newRows).draw().nodes();
        if (nodes && typeof nodes.to$ === "function") {
            nodes.to$().addClass("new");
        } else if (nodes) {
            $(nodes).addClass("new");
        }

        // Validasi duplikat nama barang dengan kategori berbeda juga tetap jalan
        const duplicateIndices = validateDuplicateNames(rows, mapping);

        if (duplicateIndices.length > 0) {
            // Terapkan styling merah pada baris yang konflik
            duplicateIndices.forEach((idx) => {
                const row = newRows[idx];
                if (!row) return;

                // Tambahkan class warning pada row
                row.classList.add("duplicate-name-warning");
                row.setAttribute("data-has-duplicate", "true");

                // Style untuk NAMA BARANG (kolom 1)
                const tdNama = row.children[1];
                if (tdNama) {
                    const inp = tdNama.querySelector("input");
                    if (inp) {
                        inp.classList.add(
                            "border-red-500",
                            "focus:border-red-500",
                            "focus:ring-red-500",
                            "bg-red-50",
                            "dark:bg-red-900/20"
                        );
                        inp.title =
                            "Nama barang ini memiliki kategori berbeda di baris lain!";
                    }
                }

                // Style untuk KATEGORI (kolom 2)
                const tdKategori = row.children[2];
                if (tdKategori) {
                    const sel = tdKategori.querySelector("select");
                    if (sel) {
                        sel.classList.add(
                            "border-red-500",
                            "focus:border-red-500",
                            "focus:ring-red-500",
                            "bg-red-50",
                            "dark:bg-red-900/20"
                        );
                        sel.title =
                            "Nama barang ini memiliki kategori berbeda di baris lain!";
                    }
                }
            });

            // Tampilkan peringatan
            if (window.Swal) {
                window.Swal.fire({
                    icon: "warning",
                    title: "Duplikat Nama Barang Terdeteksi",
                    html: `Ditemukan <b>${duplicateIndices.length} baris</b> dengan nama barang yang sama tapi kategori berbeda.<br><br>Field yang konflik ditandai dengan <span class="text-red-500">warna merah</span>.<br><br>Silakan ubah salah satu sebelum submit.`,
                    confirmButtonText: "Mengerti",
                });
            }
        }

        // Add event listener for Refresh Kode Barang buttons
        const refreshBtns = document.querySelectorAll(
            ".refresh-kode-barang-btn"
        );
        refreshBtns.forEach((btn) => {
            // Remove old listeners to be safe (though this is a fresh render)
            // btn.replaceWith(btn.cloneNode(true)); // Brute force clean if needed, but dt.clear() handles dom
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                const tr = btn.closest("tr");
                if (!tr) return;

                // Find inputs
                const kodeInput = tr.querySelector(
                    "td:nth-child(1) input[type='text']"
                );
                const hiddenKode = tr.querySelector(
                    "td:nth-child(1) input[type='hidden']"
                );

                // Get necessary data for generation
                let kategori = "";
                // Category cell is the 4th column (Kode=1, Nama=2, Deskripsi=3, Kategori=4)
                const catSel = tr.querySelector("td:nth-child(4) select");
                const catHidden = tr.querySelector(
                    "td:nth-child(4) input[type='hidden']"
                );
                if (catSel) kategori = catSel.value;
                else if (catHidden) kategori = catHidden.value;

                let nama = "";
                const namaInput = tr.querySelector(
                    "td:nth-child(2) input[type='text']"
                );
                if (namaInput) nama = namaInput.value;

                // Generate new code
                const newKode = generateKodeFromCategory(
                    kategori,
                    nama,
                    Math.floor(Math.random() * 1000)
                );

                if (kodeInput) kodeInput.value = newKode;
                if (hiddenKode) hiddenKode.value = newKode;

                // Re-validate all codes
                validateDuplicateCodes();
            });
        });

        // Run validation initially
        validateDuplicateCodes();

        // Add SweetAlert Toast for AJAX Success
        if (window.Swal) {
            window.Swal.fire({
                icon: "success",
                title: "File Berhasil Diproses",
                text: `Ditemukan ${rows.length} baris data untuk di-preview.`,
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }
    }

    // Fungsi Validasi Code Duplicate (Internal & External)
    function validateDuplicateCodes() {
        // Ambil semua input kode barang yang ada di tabel
        const inputs = document.querySelectorAll(
            "#DataTableExcel tbody tr td:nth-child(1) input[type='text']"
        );
        if (inputs.length === 0) return false;

        const codeMap = new Map(); // code -> [elements]
        let foundDuplicate = false;

        // 1. Collect codes and check Internal Duplicates
        inputs.forEach((input) => {
            const code = String(input.value || "")
                .trim()
                .toUpperCase();
            if (!code) return;

            if (!codeMap.has(code)) {
                codeMap.set(code, []);
            }
            codeMap.get(code).push(input);
        });

        // 2. Validate against Internal & External
        codeMap.forEach((duplicates, code) => {
            const isInternalDup = duplicates.length > 1;
            const isExternalDup = dbCodesSet.has(code);

            if (isInternalDup || isExternalDup) {
                foundDuplicate = true;
                duplicates.forEach((inp) => {
                    inp.classList.add(
                        "border-red-500",
                        "bg-red-50",
                        "focus:border-red-500",
                        "focus:ring-red-500",
                        "text-red-900"
                    );
                    inp.classList.remove("border-gray-300", "bg-gray-50");

                    if (isInternalDup && isExternalDup) {
                        inp.title =
                            "Kode ini duplikat dalam tabel ini DAN sudah ada di database!";
                    } else if (isInternalDup) {
                        inp.title =
                            "Kode ini duplikat (ada baris lain dengan kode sama)!";
                    } else {
                        inp.title = "Kode ini sudah digunakan di database!";
                    }
                });
            } else {
                // Clear error styles
                duplicates.forEach((inp) => {
                    inp.classList.remove(
                        "border-red-500",
                        "bg-red-50",
                        "focus:border-red-500",
                        "focus:ring-red-500",
                        "text-red-900"
                    );
                    inp.classList.add("border-gray-300", "bg-gray-50");
                    inp.title = "";
                });
            }
        });

        return foundDuplicate;
    }
    // Clear value on click to allow re-upload of the same file
    fileInput.addEventListener("click", function () {
        this.value = "";
    });

    // Handle file selection
    fileInput.addEventListener("change", function (e) {
        const file = e.target.files[0];

        // FULL UI RESET for repeat uploads
        if (progressSection) progressSection.classList.add("hidden");
        if (uploadResult) uploadResult.classList.add("hidden");
        if (uploadLabel) uploadLabel.classList.remove("hidden");
        if (progressBar) progressBar.style.width = "0%";
        if (progressText) progressText.textContent = "0%";
        if (importFilePathInput) importFilePathInput.value = "";

        // Validate file extension and MIME type before proceeding
        const allowedExt = ["xlsx", "xls"];
        const allowedMime = [
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/vnd.ms-excel",
        ];

        if (!file) return;

        const nameParts = file.name.split(".");
        const ext = nameParts.length > 1 ? nameParts.pop().toLowerCase() : "";
        const mime = file.type;

        const isExtOk = allowedExt.includes(ext);
        const isMimeOk = allowedMime.includes(mime) || mime === "";

        if (!isExtOk || !isMimeOk) {
            Swal.fire({
                icon: "error",
                title: "File tidak valid",
                text: "Harap pilih file Excel dengan ekstensi .xlsx atau .xls.",
            });
            e.target.value = "";
            progressBar.style.width = "0%";
            progressSection.classList.add("hidden");
            if (uploadLabel) uploadLabel.classList.remove("hidden");
            return;
        }

        if (file) {
            // Reset and show progress
            if (uploadLabel) uploadLabel.classList.add("hidden");
            if (uploadResult) uploadResult.classList.add("hidden");
            progressSection.classList.remove("hidden");
            progressBar.style.width = "0%";
            progressText.textContent = "0%";
            const statusText = document.getElementById("upload-status-text");
            if (statusText) statusText.textContent = "Uploading...";

            const filenameEl = document.getElementById("excel_filename");
            if (filenameEl) {
                filenameEl.textContent = file.name;
                // filenameEl.classList.remove('hidden'); // We keep it hidden in new design, or use it for data only
            }

            setTimeout(() => {
                startUpload(file);
            }, 300);
        }
    });

    function startUpload(file) {
        if (uploadInProgress) return;
        uploadInProgress = true;
        if (submitButton) submitButton.disabled = true;

        const formData = new FormData();
        formData.append("excel", file);
        formData.append("_token", window.CSRF_TOKEN);

        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener("progress", function (e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + "%";
                progressText.textContent = Math.round(percentComplete) + "%";
            }
        });

        xhr.addEventListener("load", function () {
            uploadInProgress = false;
            if (xhr.status === 200 || xhr.status === 201) {
                try {
                    const resp = JSON.parse(xhr.responseText);
                    progressBar.style.width = "100%";
                    progressText.textContent = "100%";

                    if (importFilePathInput)
                        importFilePathInput.value = resp.path || "";

                    if (resp.headers && Array.isArray(resp.headers)) {
                        // STRICT VALIDATION
                        const valResult = validateHeaders(resp.headers);
                        if (!valResult.valid) {
                            const required = [
                                "KATEGORI",
                                "NAMA BARANG",
                                "DESKRIPSI",
                                "STOK",
                                "SATUAN",
                                "HARGA BELI",
                            ];
                            const upperHeaders = resp.headers.map((h) =>
                                String(h).trim().toUpperCase()
                            );

                            const badgesHtml = required
                                .map((req) => {
                                    const isPresent =
                                        upperHeaders.includes(req);
                                    const bgColor = isPresent
                                        ? "bg-white dark:bg-gray-800"
                                        : "bg-red-50 dark:bg-red-950/20";
                                    const textColor = isPresent
                                        ? "text-slate-800 dark:text-gray-200"
                                        : "text-red-600 dark:text-red-400";
                                    const borderColor = isPresent
                                        ? "border-slate-200 dark:border-gray-700"
                                        : "border-red-200 dark:border-red-900/30";
                                    const icon = isPresent
                                        ? `<svg class="w-3 h-3 mr-1.5 text-slate-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`
                                        : `<svg class="w-3 h-3 mr-1.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>`;

                                    return `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold border ${bgColor} ${textColor} ${borderColor} m-0.5 uppercase shadow-sm">
                                        ${icon}${req}
                                    </span>`;
                                })
                                .join("");

                            Swal.fire({
                                html: `
                                    <style>
                                        .swal2-html-container {
                                            padding: 0!important;
                                        }
                                        .swal-modal-custom {
                                            border-radius: 16px !important;
                                            overflow: hidden !important;
                                            padding: 0 !important;
                                            background-color: #ffffff !important;
                                            border: none !important;
                                            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
                                            width: 100% !important;
                                            max-width: 640px !important;
                                        }
                                        .dark .swal-modal-custom {
                                            background-color: #1f2937 !important;
                                        }
                                        .swal-modal-header {
                                            display: flex !important;
                                            align-items: center !important;
                                            justify-content: space-between !important;
                                            padding: 1.25rem 1.75rem !important;
                                            color: #ffffff !important;
                                            background: linear-gradient(to right, #225A97, #0D223A) !important;
                                        }
                                        .swal-modal-header-title {
                                            text-align: left !important;
                                        }
                                        .swal-modal-body {
                                            padding: 1.75rem !important;
                                            text-align: left !important;
                                        }
                                        .swal-modal-footer {
                                            display: flex !important;
                                            align-items: center !important;
                                            justify-content: space-between !important;
                                            padding: 1.25rem 1.75rem !important;
                                            background-color: #f8fafc !important;
                                            border-top: 1px solid #e2e8f0 !important;
                                        }
                                        .dark .swal-modal-footer {
                                            background-color: #1f2937 !important;
                                            border-top-color: #374151 !important;
                                        }
                                        .swal-modal-btn-confirm {
                                            padding: 0.625rem 1.75rem !important;
                                            font-size: 0.875rem !important;
                                            font-weight: 600 !important;
                                            color: #ffffff !important;
                                            border-radius: 0.75rem !important;
                                            background: linear-gradient(to right, #225A97, #0D223A) !important;
                                            box-shadow: 0 4px 6px -1px rgba(34, 90, 151, 0.2) !important;
                                            border: none !important;
                                            cursor: pointer !important;
                                            transition: opacity 0.2s ease-in-out !important;
                                        }
                                        .swal-modal-btn-confirm:hover {
                                            opacity: 0.9 !important;
                                        }
                                        .alert-boxes-grid {
                                            display: grid !important;
                                            grid-template-columns: 1fr !important;
                                            gap: 1rem !important;
                                            margin-top: 1rem !important;
                                        }
                                        @media (min-width: 640px) {
                                            .alert-boxes-grid {
                                                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)) !important;
                                            }
                                        }
                                    </style>
                                    <div class="swal-modal-container" >
                                        <!-- Header -->
                                        <header class="swal-modal-header">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                    </svg>
                                                </div>
                                                <div class="swal-modal-header-title">
                                                    <h1 class="text-lg font-semibold leading-tight text-white">Format Header Tidak Sesuai</h1>
                                                    <p class="text-xs text-white/80">File Excel harus memiliki kolom yang sesuai dengan format yang ditentukan.</p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="Swal.close()" aria-label="Tutup" class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </header>

                                        <!-- Body -->
                                        <div class="swal-modal-body">
                                            <!-- Required Columns -->
                                            <div class="bg-gray-50 dark:bg-gray-800/40 rounded-2xl p-5 mb-4 border border-gray-100 dark:border-gray-700/50 shadow-inner">
                                                <div class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mb-3 text-center">KOLOM YANG DIPERLUKAN</div>
                                                <div class="flex flex-wrap justify-center gap-1.5">
                                                    ${badgesHtml}
                                                </div>
                                            </div>

                                            <!-- Alert Boxes Grid -->
                                            <div class="alert-boxes-grid">
                                                <!-- Column Missing Alert -->
                                                ${
                                                    valResult.missing.length > 0
                                                        ? `
                                                        <div class="bg-red-50/50 dark:bg-red-950/20 border border-red-200/60 dark:border-red-900/30 rounded-2xl p-4 text-left flex items-start h-full">
                                                            <div class="text-red-500 mr-3 mt-0.5">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="text-red-800 dark:text-red-400 font-bold text-sm">Kolom yang Hilang</div>
                                                                <div class="flex flex-wrap gap-1 mt-2">
                                                                    ${valResult.missing.map(m => `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-red-100/60 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800/40 uppercase shadow-sm"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>${m}</span>`).join('')}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        `
                                                        : ''
                                                }

                                                <!-- Column Extra Alert -->
                                                ${
                                                    valResult.extra.length > 0
                                                        ? `
                                                        <div class="bg-orange-50/50 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/30 rounded-2xl p-4 text-left flex items-start h-full">
                                                            <div class="text-amber-500 mr-3 mt-0.5">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="text-amber-800 dark:text-amber-400 font-bold text-sm">Kolom Tidak Dikenal</div>
                                                                <div class="flex flex-wrap gap-1 mt-2">
                                                                    ${valResult.extra.map(m => `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-amber-100/60 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/40 uppercase shadow-sm"><svg class="w-3.5 h-3.5 mr-1.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>${m}</span>`).join('')}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        `
                                                        : ''
                                                }
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <footer class="swal-modal-footer">
                                            <p class="hidden text-xs text-slate-500 sm:block dark:text-gray-400">Pastikan format kolom sudah sesuai sebelum melanjutkan.</p>
                                            <button type="button" onclick="Swal.close()" class="swal-modal-btn-confirm">Mengerti</button>
                                        </footer>
                                    </div>
                                `,
                                showConfirmButton: false,
                                customClass: {
                                    popup: "swal-modal-custom"
                                },
                                width: "640px",
                                allowOutsideClick: true,
                            });
                            // Reset UI
                            progressBar.style.width = "0%";
                            progressSection.classList.add("hidden");
                            if (uploadLabel)
                                uploadLabel.classList.remove("hidden");
                            fileInput.value = ""; // clear file
                            if (submitButton) submitButton.disabled = false;
                            return; // STOP
                        }

                        // no preview UI: keep import path, auto-map and populate main table
                        // Create mapping first so we can use it to validate rows
                        const mapping = autoMapHeaders(resp.headers || []);
                        const cleanedRows = cleanRows(
                            resp.rows || [],
                            resp.headers || [],
                            mapping
                        );

                        // Filter out rows where stok is 0
                        const stokColIndex = mapping["stok"];
                        let filteredRows = cleanedRows;
                        if (stokColIndex !== null && stokColIndex !== undefined) {
                            filteredRows = cleanedRows.filter((row) => {
                                const stokVal = parseInt(row[stokColIndex] || 0);
                                return stokVal > 0;
                            });
                        }

                        if (importFilePathInput)
                            importFilePathInput.value = resp.path || "";
                        injectMappingInputs(mapping); // hidden mapping[...] inputs
                        renderDataTableFromPreviewAll(
                            filteredRows,
                            mapping,
                            resp.headers || []
                        );
                        if (submitButton) submitButton.disabled = false;
                    }

                    // show upload result and hide progress
                    progressSection.classList.add("hidden");
                    if (uploadResult) {
                        const uploadPath =
                            document.getElementById("upload-path"); // hidden
                        const uploadUrl = document.getElementById("upload-url");
                        const uploadFilename =
                            document.getElementById("upload-filename");

                        if (uploadFilename)
                            uploadFilename.textContent = file.name; // Use file.name from closure
                        if (uploadPath)
                            uploadPath.textContent = resp.path || "";

                        if (resp.url) {
                            if (uploadUrl) {
                                uploadUrl.href = resp.url;
                                uploadUrl.textContent = "Lihat File";
                                uploadUrl.classList.remove("hidden");
                            }
                        } else {
                            if (uploadUrl) uploadUrl.classList.add("hidden");
                        }
                        uploadResult.classList.remove("hidden");
                    }
                } catch (err) {
                    console.error("Preview general processing error details:", err);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Gagal memproses preview.",
                    });
                }
            } else {
                progressSection.classList.add("hidden");
                if (uploadLabel) uploadLabel.classList.remove("hidden");
                try {
                    const response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message || "Gagal mengupload file",
                    });
                } catch (e) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Gagal mengupload file. Status: " + xhr.status,
                    });
                }
                if (submitButton) submitButton.disabled = false;
            }
        });

        xhr.addEventListener("error", function () {
            progressSection.classList.add("hidden");
            if (uploadLabel) uploadLabel.classList.remove("hidden");
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Terjadi kesalahan saat mengupload file.",
            });
            uploadInProgress = false;
            if (submitButton) submitButton.disabled = false;
        });

        xhr.addEventListener("abort", function () {
            progressSection.classList.add("hidden");
            if (uploadLabel) uploadLabel.classList.remove("hidden");
            Swal.fire({
                icon: "warning",
                title: "Dibatalkan",
                text: "Upload dibatalkan.",
            });
            uploadInProgress = false;
            if (submitButton) submitButton.disabled = false;
        });

        const uploadUrl =
            window.IMPORT_EXCEL_STORE_URL || form.action.replace("/import", "");
        xhr.open("POST", uploadUrl);
        xhr.setRequestHeader("X-CSRF-TOKEN", window.CSRF_TOKEN);
        xhr.send(formData);
    }

    // Keep form submit as backup
    if (submitButton) {
        submitButton.addEventListener("click", function (e) {
            if (uploadInProgress) {
                e.preventDefault();
                Swal.fire({
                    icon: "info",
                    title: "Tunggu",
                    text: "Upload sedang berlangsung. Tunggu hingga selesai.",
                });
                return;
            }
            // ensure file uploaded
            if (!importFilePathInput || !importFilePathInput.value) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Perhatian",
                    text: "Silakan unggah file Excel sebelum submit.",
                });
                return;
            }

            // Cek apakah masih ada duplikat nama barang
            const tableEl = document.getElementById("DataTableExcel");
            if (tableEl) {
                const duplicateRows = tableEl.querySelectorAll(
                    'tr[data-has-duplicate="true"]'
                );
                if (duplicateRows.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "error",
                        title: "Tidak Bisa Submit",
                        html: `Masih ada <b>${duplicateRows.length} baris</b> dengan nama barang yang sama tapi kategori berbeda.<br><br>Field yang konflik ditandai dengan <span class="text-red-500">warna merah</span>.<br><br>Silakan ubah salah satu sebelum submit.`,
                        confirmButtonText: "Mengerti",
                    });
                    return;
                }
            }

            // allow submit to proceed
        });
    }

    // Event listener untuk tombol "Ganti File" - reset file input agar bisa upload file dengan nama sama
    const gantiFileLabel = document.querySelector(
        '#upload-result label[for="excel"]'
    );
    if (gantiFileLabel) {
        gantiFileLabel.addEventListener("click", function () {
            // Reset file input value agar event change ter-trigger meskipun nama file sama
            if (fileInput) {
                fileInput.value = "";
            }
        });
    }

    // <-- ADD: event delegation for refresh buttons in DataTableExcel
    (function attachTableRefreshHandler() {
        const table = document.getElementById("DataTableExcel");
        if (!table) return;

        table.addEventListener("click", function (e) {
            const btn = e.target.closest(
                'button#refreshKodeBarang, button.refresh-kode, button[data-action="refresh-kode"]'
            );
            if (!btn) return;

            const tr = btn.closest("tr");
            if (!tr) return;

            // find visible nama and kategori in the row
            const namaEl = tr.children[1]?.querySelector(
                'input, textarea, [name*="[goods_name]"]'
            );
            const kategoriEl = tr.children[3]?.querySelector(
                'select, input, [name*="[category]"]'
            );

            const nama = namaEl ? (namaEl.value || "").toString().trim() : "";
            const kategori = kategoriEl
                ? (kategoriEl.value || "").toString().trim()
                : "";

            // generate kode berdasarkan nama + kategori
            // try to compute a rowIndex if possible (fallback 0)
            let rowIndex = Array.prototype.indexOf.call(
                tr.parentNode.children,
                tr
            );
            if (rowIndex < 0) rowIndex = 0;
            const newKode = generateKodeFromCategory(kategori, nama, rowIndex);

            // update visible kode input (col 0)
            const kodeVisible = tr.children[0]?.querySelector(
                'input[type="text"], input'
            );
            if (kodeVisible) {
                kodeVisible.value = newKode;
            }

            // update or create hidden input rows[{i}][goods_code]
            let hiddenKode = tr.children[0]?.querySelector(
                'input[type="hidden"][name*="[goods_code]"]'
            );
            if (!hiddenKode) {
                hiddenKode = document.createElement("input");
                hiddenKode.type = "hidden";
                // try to reuse rowIndex in name; if existing rows used different naming fallback to generic rows[][goods_code]
                hiddenKode.name = `rows[${rowIndex}][goods_code]`;
                tr.children[0].appendChild(hiddenKode);
            }
            hiddenKode.value = newKode;

            // call server-side uniqueness check if available
            try {
                if (typeof validateKodeBarang === "function")
                    validateKodeBarang(kodeVisible || hiddenKode);
            } catch (err) {
                // ignore validation errors here
                // console.error(err);
            }
        });
    })();

    // <-- ADD: update kode_barang when kategori select changes
    (function attachKategoriChangeHandler() {
        const table = document.getElementById("DataTableExcel");
        if (!table) return;

        table.addEventListener("change", function (e) {
            const sel = e.target.closest("select");
            if (!sel) return;

            const td = sel.closest("td");
            const tr = sel.closest("tr");
            if (!tr || !td) return;

            // only react when the select is in Kategori column (col index 3)
            const colIndex = Array.prototype.indexOf.call(tr.children, td);
            if (colIndex !== 3) return;

            const kategori = (sel.value || "").toString().trim();
            const namaEl = tr.children[1]?.querySelector(
                'input, textarea, [name*="[goods_name]"]'
            );
            const nama = namaEl ? (namaEl.value || "").toString().trim() : "";

            // compute row index for naming hidden inputs using DataTable API to handle pagination correctly
            const dt = $("#DataTableExcel").DataTable();
            let rowIndex = dt.row(tr).index();
            if (rowIndex === undefined || rowIndex === null || rowIndex < 0) {
                rowIndex = 0;
            }

            const newKode = generateKodeFromCategory(kategori, nama, rowIndex);

            // update visible kode input (col 0)
            const kodeVisible = tr.children[0]?.querySelector(
                'input[type="text"], input'
            );
            if (kodeVisible) kodeVisible.value = newKode;

            // update/create hidden input rows[{i}][goods_code]
            let hiddenKode = tr.children[0]?.querySelector(
                'input[type="hidden"][name*="[goods_code]"]'
            );
            if (!hiddenKode) {
                hiddenKode = document.createElement("input");
                hiddenKode.type = "hidden";
                hiddenKode.name = `rows[${rowIndex}][goods_code]`;
                tr.children[0].appendChild(hiddenKode);
            }
            hiddenKode.value = newKode;

            // ensure hidden kategori value also updated for submission
            let hiddenKategori = tr.children[3]?.querySelector(
                'input[type="hidden"][name*="[category]"]'
            );
            if (!hiddenKategori) {
                hiddenKategori = document.createElement("input");
                hiddenKategori.type = "hidden";
                hiddenKategori.name = `rows[${rowIndex}][category]`;
                tr.children[3].appendChild(hiddenKategori);
            }
            hiddenKategori.value = kategori;

            // trigger server-side uniqueness check if available
            try {
                if (typeof validateKodeBarang === "function")
                    validateKodeBarang(kodeVisible || hiddenKode);
            } catch (err) {
                /* ignore */
            }
        });
    })();
    // <-- ADD: update hidden inputs when visible inputs change
    (function attachInputChangeHandler() {
        const table = document.getElementById("DataTableExcel");
        if (!table) return;

        table.addEventListener("change", function (e) {
            const inp = e.target.closest("input, select, textarea");
            if (!inp) return;

            const td = inp.closest("td");
            const tr = inp.closest("tr");
            if (!tr || !td) return;

            const colIndex = Array.prototype.indexOf.call(tr.children, td);
            let fieldName = "";

            // Map column index to field name
            switch (colIndex) {
                case 1:
                    fieldName = "goods_name";
                    break;
                case 2:
                    fieldName = "description";
                    break;
                case 3:
                    fieldName = "category";
                    break;
                case 4:
                    fieldName = "stock";
                    break;
                case 5:
                    fieldName = "buy_price";
                    break;
                case 6:
                    fieldName = "selling_price";
                    break;
                case 7:
                    fieldName = "unit";
                    break;
                case 8:
                    fieldName = "status_listing";
                    break;
                default:
                    return; // skip goods_code (0), image (9), action (10)
            }

            const value = (inp.value || "").toString().trim();

            // compute row index using DataTable API to handle pagination correctly
            const dt = $("#DataTableExcel").DataTable();
            let rowIndex = dt.row(tr).index();
            if (rowIndex === undefined || rowIndex === null || rowIndex < 0) {
                rowIndex = 0;
            }

            // update/create hidden input
            let hidden = td.querySelector(
                `input[type="hidden"][name*="[${fieldName}]"]`
            );
            if (!hidden) {
                hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][${fieldName}]`;
                td.appendChild(hidden);
            }
            hidden.value = value;

            // Sync with window.allExcelRows
            if (window.allExcelRows && window.excelMapping) {
                const rowObj = window.allExcelRows[rowIndex];
                let r = rowObj;
                if (rowObj && typeof rowObj === 'object' && !Array.isArray(rowObj) && rowObj.data) {
                    r = rowObj.data;
                }
                const excelColIdx = window.excelMapping[fieldName];
                if (excelColIdx !== null && excelColIdx !== undefined && excelColIdx !== "") {
                    let valueToStore = value;
                    if (fieldName === "stock" || fieldName === "buy_price" || fieldName === "selling_price") {
                        valueToStore = value.replace(/\D/g, "");
                    }
                    r[excelColIdx] = valueToStore;
                }
            }

            // Special handling for category change (to update goods_code)
            if (fieldName === "category") {
                const namaEl = tr.children[1]?.querySelector(
                    'input, textarea, [name*="[goods_name]"]'
                );
                const nama = namaEl
                    ? (namaEl.value || "").toString().trim()
                    : "";
                const newKode = generateKodeFromCategory(value, nama, rowIndex);

                // update visible kode
                const kodeVisible = tr.children[0]?.querySelector(
                    'input[type="text"], input'
                );
                if (kodeVisible) kodeVisible.value = newKode;

                // update hidden kode
                let hiddenKode = tr.children[0]?.querySelector(
                    'input[type="hidden"][name*="[goods_code]"]'
                );
                if (!hiddenKode) {
                    hiddenKode = document.createElement("input");
                    hiddenKode.type = "hidden";
                    hiddenKode.name = `rows[${rowIndex}][goods_code]`;
                    tr.children[0].appendChild(hiddenKode);
                }
                hiddenKode.value = newKode;

                // trigger uniqueness check
                try {
                    if (typeof validateKodeBarang === "function")
                        validateKodeBarang(kodeVisible || hiddenKode);
                } catch (err) {
                    /* ignore */
                }
            }
        });
    })();

    // Re-validasi duplikat nama ketika NAMA BARANG atau KATEGORI berubah
    (function attachDuplicateNameValidator() {
        const table = document.getElementById("DataTableExcel");
        if (!table) return;

        table.addEventListener("change", function (e) {
            const inp = e.target.closest("input, select");
            if (!inp) return;

            const td = inp.closest("td");
            const tr = inp.closest("tr");
            if (!tr || !td) return;

            const colIndex = Array.prototype.indexOf.call(tr.children, td);

            // Hanya proses jika kolom NAMA BARANG (1) atau KATEGORI (3)
            if (colIndex !== 1 && colIndex !== 3) return;

            // Kumpulkan semua data dari tabel untuk re-validasi
            const tbody = table.querySelector("tbody");
            if (!tbody) return;

            const allRows = Array.from(tbody.querySelectorAll("tr"));
            const rowsData = [];
            const mapping = {
                goods_name: 1, // kolom index untuk nama barang
                category: 3, // kolom index untuk kategori
            };

            allRows.forEach((row) => {
                const namaInput = row.children[1]?.querySelector("input");
                const kategoriSelect = row.children[3]?.querySelector("select");

                if (namaInput && kategoriSelect) {
                    // Buat array data seperti format rows asli
                    const rowData = [];
                    rowData[1] = namaInput.value || "";
                    rowData[3] = kategoriSelect.value || "";
                    rowsData.push(rowData);
                }
            });

            // Validasi ulang semua baris
            const duplicateIndices = validateDuplicateNames(rowsData, mapping);

            // Reset semua styling duplikat terlebih dahulu
            allRows.forEach((row) => {
                row.classList.remove("duplicate-name-warning");
                row.removeAttribute("data-has-duplicate");

                // Reset NAMA BARANG
                const tdNama = row.children[1];
                if (tdNama) {
                    const inp = tdNama.querySelector("input");
                    if (inp) {
                        inp.classList.remove(
                            "border-red-500",
                            "focus:border-red-500",
                            "focus:ring-red-500",
                            "bg-red-50",
                            "dark:bg-red-900/20"
                        );
                        inp.title = "";
                    }
                }

                // Reset KATEGORI
                const tdKategori = row.children[3];
                if (tdKategori) {
                    const sel = tdKategori.querySelector("select");
                    if (sel) {
                        sel.classList.remove(
                            "border-red-500",
                            "focus:border-red-500",
                            "focus:ring-red-500",
                            "bg-red-50",
                            "dark:bg-red-900/20"
                        );
                        sel.title = "";
                    }
                }
            });

            // Terapkan styling merah pada baris yang masih konflik
            if (duplicateIndices.length > 0) {
                duplicateIndices.forEach((idx) => {
                    const row = allRows[idx];
                    if (!row) return;

                    row.classList.add("duplicate-name-warning");
                    row.setAttribute("data-has-duplicate", "true");

                    // Style NAMA BARANG
                    const tdNama = row.children[1];
                    if (tdNama) {
                        const inp = tdNama.querySelector("input");
                        if (inp) {
                            inp.classList.add(
                                "border-red-500",
                                "focus:border-red-500",
                                "focus:ring-red-500",
                                "bg-red-50",
                                "dark:bg-red-900/20"
                            );
                            inp.title =
                                "Nama barang ini memiliki kategori berbeda di baris lain!";
                        }
                    }

                    // Style KATEGORI
                    const tdKategori = row.children[3];
                    if (tdKategori) {
                        const sel = tdKategori.querySelector("select");
                        if (sel) {
                            sel.classList.add(
                                "border-red-500",
                                "focus:border-red-500",
                                "focus:ring-red-500",
                                "bg-red-50",
                                "dark:bg-red-900/20"
                            );
                            sel.title =
                                "Nama barang ini memiliki kategori berbeda di baris lain!";
                        }
                    }
                });
            }
        });
    })();
});

// Custom search function to match text within inputs and select elements inside cells
if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.ext && $.fn.dataTable.ext.search) {
    if (!window.hasRegisteredExcelSearch) {
        window.hasRegisteredExcelSearch = true;
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            try {
                if (!settings || !settings.nTable || settings.nTable.id !== 'DataTableExcel') {
                    return true;
                }
                
                const rowNode = settings.aoData && settings.aoData[dataIndex] ? settings.aoData[dataIndex].nTr : null;
                if (!rowNode) {
                    if (dataIndex === 0) {
                        console.warn("[DataTableExcel Search] rowNode is null for index 0");
                    }
                    return true;
                }
                
                const searchObj = settings.oPreviousSearch || {};
                const searchTerm = (window.dataTableSearchTerm || searchObj.sSearch || searchObj.search || "").trim().toLowerCase();
                if (!searchTerm) {
                    return true;
                }
                
                // Check all inputs, select, and textarea elements in the row
                const inputs = rowNode.querySelectorAll('input, select, textarea');
                
                // Detailed log for debugging
                if (dataIndex === 0) {
                    console.log(`[DataTableExcel Search] Term: "${searchTerm}"`);
                    console.log(`[DataTableExcel Search] Row 0 outerHTML:`, rowNode.outerHTML);
                    console.log(`[DataTableExcel Search] Row 0 inputs count: ${inputs.length}`);
                    console.log(`[DataTableExcel Search] Row 0 inputs values:`, Array.from(inputs).map(inp => `${inp.tagName}:${inp.type}:${inp.value}`));
                }
                
                for (let i = 0; i < inputs.length; i++) {
                    const val = (inputs[i].value || "").toString().toLowerCase();
                    if (val.includes(searchTerm)) {
                        return true;
                    }
                }
                
                // Also fallback to the default textual values (if any columns don't have inputs)
                for (let i = 0; i < data.length; i++) {
                    if (data[i] && data[i].toString().toLowerCase().includes(searchTerm)) {
                        return true;
                    }
                }
                
                return false;
            } catch (e) {
                console.error("Error in DataTables custom search filter:", e);
                return true; // Keep row on error to avoid blank screen
            }
        });
    }
}

