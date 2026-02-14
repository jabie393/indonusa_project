document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(
        'form[action*="import-excel.import"], form[enctype="multipart/form-data"]'
    );
    const fileInput = document.getElementById("excel");
    const progressSection = document.getElementById("progress-section");
    const progressBar = document.getElementById("progress-bar");
    const progressText = document.getElementById("progress-text");
    const uploadLabel = document.getElementById("upload-label");
    const uploadResult = document.getElementById("upload-result");
    const importFilePathInput = document.getElementById("import_file_path");

    if (!form || !fileInput) return;

    let uploadInProgress = false;
    let uploadCompleted = false;
    const submitButton = form.querySelector(".submit-btn");

    // helper: auto-map headers to fields using keywords
    function autoMapHeaders(headers) {
        const lower = headers.map((h) =>
            (h || "").toString().toLowerCase().replace(/\s+/g, " ").trim()
        );
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

        // Hardcode mapping berdasarkan header standar: Kode Barang, Nama Barang, Kategori, Stok
        map["kode_barang"] = 0;
        map["nama_barang"] = 1;
        map["kategori"] = 2;
        map["stok"] = 3;
        map["harga"] = 4;

        // Mapping sudah hardcode, skip ensure unique

        return map;
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

    // Strict Header Validation
    function validateHeaders(headers) {
        if (!Array.isArray(headers))
            return { valid: false, missing: [], extra: [] };

        const required = [
            "KODE BARANG",
            "NAMA BARANG",
            "KATEGORI",
            "STOK",
            "HARGA BELI",
        ];

        const upperHeaders = headers.map((h) => String(h).trim().toUpperCase());

        // 1. Check Missing
        const missing = required.filter((req) => !upperHeaders.includes(req));

        // 2. Check Extra (Unknown columns)
        // Any header in upperHeaders that is NOT in required list is an extra
        const extra = upperHeaders.filter(
            (h) => h !== "" && !required.includes(h)
        );

        return {
            valid: missing.length === 0 && extra.length === 0,
            missing: missing,
            extra: extra,
        };
    }

    // helper: remove completely empty rows and trim each row to headers length
    function cleanRows(rows, headers) {
        if (!Array.isArray(rows)) return [];
        const hlen = Array.isArray(headers) ? headers.length : null;

        return rows
            .map((r) => {
                // Check if row is our new object structure { data: [...], is_known: ... }
                if (
                    r &&
                    typeof r === "object" &&
                    !Array.isArray(r) &&
                    Array.isArray(r.data)
                ) {
                    // Return new object with cleaned data
                    let cleanedData = r.data;
                    if (hlen) cleanedData = cleanedData.slice(0, hlen);
                    cleanedData = cleanedData.map((c) =>
                        c === null || c === undefined ? "" : String(c).trim()
                    );
                    return { ...r, data: cleanedData };
                }
                // Fallback for old array-of-arrays structure
                if (Array.isArray(r)) {
                    let cleaned = r;
                    if (hlen) cleaned = cleaned.slice(0, hlen);
                    return cleaned.map((c) =>
                        c === null || c === undefined ? "" : String(c).trim()
                    );
                }
                return [];
            })
            .filter((r) => {
                // Filter out empty rows
                const dataToCheck =
                    r && !Array.isArray(r) && r.data
                        ? r.data
                        : Array.isArray(r)
                        ? r
                        : [];
                // Keep if check has at least one non-empty cell
                return dataToCheck.some((c) => c !== "");
            });
    }

    // render DataTableExcel from rows (rows are array-of-arrays, headers not included)
    function renderDataTableFromPreviewAll(rows, mapping, headers) {
        const tableEl = document.getElementById("DataTableExcel");
        if (!tableEl) return;

        // Get DataTable instance safely
        let dt;
        try {
            dt = new DataTable("#DataTableExcel");
        } catch (e) {
            console.warn("DataTable re-init managed:", e);
            return;
        }

        const tbody = tableEl.querySelector("tbody");
        if (!tbody) return;

        const templateRow = tbody.querySelector("tr");

        // if no template row, build one minimal for 5 columns
        let baseRow;
        if (templateRow) {
            baseRow = templateRow.cloneNode(true);
        } else {
            // fallback template
            baseRow = document.createElement("tr");
            for (let i = 0; i < 6; i++) {
                const td = document.createElement("td");
                if (i === 5) {
                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.className =
                        "btn remove-row rounded-md bg-red-500 text-white";
                    btn.innerText = "Hapus";
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

        // clear existing using DataTable API
        dt.clear();

        const newRows = rows.map((rowObj, rowIndex) => {
            // Handle rowObj structure: it might be raw array (old) or { data: [], is_known: bool } (new)
            let r = rowObj;
            let isKnown = true;
            if (
                rowObj &&
                typeof rowObj === "object" &&
                !Array.isArray(rowObj) &&
                rowObj.data
            ) {
                r = rowObj.data;
                isKnown = rowObj.is_known; // bool from server
            }

            const newRow = baseRow.cloneNode(true);

            // Styling jika item tidak known
            if (!isKnown) {
                newRow.classList.add("bg-red-50", "dark:bg-red-900/20");
            }

            const getVal = (field) => {
                const col = mapping[field];
                if (col === null || col === undefined || col === "") return "";
                return r[col] !== undefined && r[col] !== null ? r[col] : "";
            };

            // columns mapping based on table columns in blade:
            // 0: kode_barang (readonly input)
            const tdKode = newRow.children[0];
            if (tdKode) {
                let inp = tdKode.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    tdKode.appendChild(inp);
                }
                inp.readOnly = true;
                const kodeVal =
                    getVal("kode_barang") ||
                    generateKodeFromCategory(
                        getVal("kategori"),
                        getVal("nama_barang"),
                        rowIndex
                    );
                inp.value = kodeVal;

                // add hidden input ONLY IF KNOWN
                if (isKnown) {
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][kode_barang]`;
                    hidden.value = kodeVal;
                    tdKode.appendChild(hidden);

                    // Call check unik if code seems valid
                    try {
                        if (typeof validateKodeBarang === "function")
                            validateKodeBarang(inp);
                    } catch (e) {}
                } else {
                    // Visual indicator for unknown
                    // Fix: Add focus styles to keep it red on click
                    inp.classList.add(
                        "border-red-500",
                        "focus:border-red-500",
                        "focus:ring-red-500",
                        "cursor-not-allowed",
                        "bg-gray-100",
                        "dark:bg-gray-700",
                        "text-gray-500"
                    );
                    inp.title = "Barang belum terdaftar";
                    const span = document.createElement("span");
                    span.className = "text-xs text-red-500 block";
                    tdKode.appendChild(span);
                }
            }

            // 1: nama_barang
            const tdNama = newRow.children[1];
            if (tdNama) {
                let inp = tdNama.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    tdNama.appendChild(inp);
                }
                const v = getVal("nama_barang") || "";
                inp.value = v;

                if (isKnown) {
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][nama_barang]`;
                    hidden.value = v;
                    tdNama.appendChild(hidden);
                } else {
                    inp.readOnly = true;
                    // Apply style: border red & cursor not allowed
                    inp.classList.add(
                        "border-red-500",
                        "focus:border-red-500",
                        "focus:ring-red-500",
                        "cursor-not-allowed",
                        "bg-gray-100",
                        "dark:bg-gray-700",
                        "text-gray-500"
                    );
                    inp.title = "Barang belum terdaftar";
                }
            }

            // 2: kategori
            const tdKategori = newRow.children[2];
            if (tdKategori) {
                let inp = tdKategori.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    tdKategori.appendChild(inp);
                }
                const v = (getVal("kategori") || "").toString().trim();
                inp.value = v;

                if (isKnown) {
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][kategori]`;
                    hidden.value = v;
                    tdKategori.appendChild(hidden);
                } else {
                    inp.readOnly = true;
                    // Apply style: border red & cursor not allowed
                    inp.classList.add(
                        "border-red-500",
                        "focus:border-red-500",
                        "focus:ring-red-500",
                        "cursor-not-allowed",
                        "bg-gray-100",
                        "dark:bg-gray-700",
                        "text-gray-500"
                    );
                    inp.title = "Barang belum terdaftar";
                }
            }

            // 3: stok
            const tdStok = newRow.children[3];
            if (tdStok) {
                let inp = tdStok.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "number";
                    tdStok.appendChild(inp);
                }
                const v = getVal("stok") || 0;
                inp.value = v;

                if (isKnown) {
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][stok]`;
                    hidden.value = v;
                    tdStok.appendChild(hidden);
                } else {
                    inp.readOnly = true;
                    inp.classList.add(
                        "border-red-500",
                        "focus:border-red-500",
                        "focus:ring-red-500",
                        "cursor-not-allowed",
                        "bg-gray-100",
                        "dark:bg-gray-700",
                        "text-gray-500"
                    );
                    inp.title = "Barang belum terdaftar";
                }
            }

            // 4: harga
            const tdHarga = newRow.children[4];
            if (tdHarga) {
                let inp = tdHarga.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "number";
                    tdHarga.appendChild(inp);
                }
                const v = getVal("harga") || "";
                inp.value = v;

                if (isKnown) {
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][harga]`;
                    hidden.value = v;
                    tdHarga.appendChild(hidden);
                } else {
                    inp.readOnly = true;
                    inp.classList.add(
                        "border-red-500",
                        "focus:border-red-500",
                        "focus:ring-red-500",
                        "cursor-not-allowed",
                        "bg-gray-100",
                        "dark:bg-gray-700",
                        "text-gray-500"
                    );
                    inp.title = "Barang belum terdaftar";
                }
            }

            // 5: aksi
            const aksiTd = newRow.children[5];
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

        // Add all rows using the requested API and add the 'new' class
        dt.rows.add(newRows).draw().nodes().to$().addClass("new");

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
                // filenameEl.classList.remove('hidden');
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
                                "KODE BARANG",
                                "NAMA BARANG",
                                "KATEGORI",
                                "STOK",
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
                                        ? "bg-emerald-50"
                                        : "bg-red-50";
                                    const textColor = isPresent
                                        ? "text-emerald-600"
                                        : "text-red-500";
                                    const borderColor = isPresent
                                        ? "border-emerald-100"
                                        : "border-red-100";
                                    const icon = isPresent
                                        ? `<svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>`
                                        : `<svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>`;

                                    return `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold border ${bgColor} ${textColor} ${borderColor} m-0.5 uppercase">
                                        ${icon}${req}
                                    </span>`;
                                })
                                .join("");

                            Swal.fire({
                                html: `
                                    <style>
                                        .swal-error-popup {
                                            border-radius: 24px !important;
                                            padding: 1.5rem 1rem !important;
                                        }
                                        .swal-error-confirm {
                                            width: 100% !important;
                                            margin-top: 1rem !important;
                                            border-radius: 14px !important;
                                            padding: 14px !important;
                                            font-size: 16px !important;
                                            font-weight: 700 !important;
                                            background-color: #5850ec !important;
                                            box-shadow: 0 4px 6px -1px rgba(88, 80, 236, 0.2), 0 2px 4px -1px rgba(88, 80, 236, 0.1) !important;
                                        }
                                        .swal-error-close {
                                            color: #9ca3af !important;
                                            top: 15px !important;
                                            right: 15px !important;
                                        }
                                        .swal-error-close:hover {
                                            color: #4b5563 !important;
                                        }
                                    </style>
                                    <div class="mt-2">
                                        <!-- Error Icon -->
                                        <div class="flex justify-center mb-6">
                                            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center">
                                                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm">
                                                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Format Header Tidak Sesuai</h2>
                                        <p class="text-gray-500 text-sm mb-6 px-6">File Excel harus memiliki kolom yang sesuai dengan format yang ditentukan.</p>

                                        <!-- Required Columns Grid -->
                                        <div class="bg-gray-50 rounded-2xl p-5 mb-6">
                                            <div class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mb-4 text-center">KOLOM YANG DIPERLUKAN</div>
                                            <div class="flex flex-wrap justify-center gap-1.5">
                                                ${badgesHtml}
                                            </div>
                                        </div>

                                        <!-- Alert Boxes -->
                                        ${
                                            valResult.missing.length > 0
                                                ? `
                                        <div class="bg-red-50 border border-red-100 rounded-2xl p-4 mb-4 text-left flex items-start">
                                            <div class="text-red-500 mr-3 mt-0.5">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-red-500 font-bold text-sm">Kolom yang Hilang</div>
                                                <div class="text-red-700 font-extrabold text-base uppercase mt-1 tracking-tight">${valResult.missing.join(
                                                    ", "
                                                )}</div>
                                            </div>
                                        </div>
                                        `
                                                : ""
                                        }

                                        ${
                                            valResult.extra &&
                                            valResult.extra.length > 0
                                                ? `
                                        <div class="bg-orange-50 border border-amber-100 rounded-2xl p-4 mb-2 text-left flex items-start">
                                            <div class="text-amber-500 mr-3 mt-0.5">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-amber-800 font-bold text-sm">Kolom Tidak Dikenal (harus dihapus)</div>
                                                <div class="text-amber-900 font-extrabold text-base uppercase mt-1 tracking-tight">${valResult.extra.join(
                                                    ", "
                                                )}</div>
                                            </div>
                                        </div>
                                        `
                                                : ""
                                        }
                                    </div>
                                `,
                                showConfirmButton: true,
                                confirmButtonText: "Mengerti",
                                showCloseButton: true,
                                customClass: {
                                    popup: "swal-error-popup",
                                    confirmButton: "swal-error-confirm",
                                    closeButton: "swal-error-close",
                                },
                                width: "480px",
                                allowOutsideClick: false,
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
                        const cleanedRows = cleanRows(
                            resp.rows || [],
                            resp.headers || []
                        );
                        if (importFilePathInput)
                            importFilePathInput.value = resp.path || "";
                        const mapping = autoMapHeaders(resp.headers || []);
                        injectMappingInputs(mapping); // hidden mapping[...] inputs
                        renderDataTableFromPreviewAll(
                            cleanedRows,
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
            // allow submit to proceed
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
                'input, textarea, [name*="[nama_barang]"]'
            );
            const kategoriEl = tr.children[2]?.querySelector(
                'select, input, [name*="[kategori]"]'
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

            // update or create hidden input rows[{i}][kode_barang]
            let hiddenKode = tr.children[0]?.querySelector(
                'input[type="hidden"][name*="[kode_barang]"]'
            );
            if (!hiddenKode) {
                hiddenKode = document.createElement("input");
                hiddenKode.type = "hidden";
                // try to reuse rowIndex in name; if existing rows used different naming fallback to generic rows[][kode_barang]
                hiddenKode.name = `rows[${rowIndex}][kode_barang]`;
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
                    fieldName = "nama_barang";
                    break;
                case 2:
                    fieldName = "kategori";
                    break;
                case 3:
                    fieldName = "stok";
                    break;
                case 4:
                    fieldName = "harga";
                    break;
                default:
                    return; // skip kode_barang (0), aksi (5)
            }

            const value = (inp.value || "").toString().trim();

            // compute row index
            let rowIndex = Array.prototype.indexOf.call(
                tr.parentNode.children,
                tr
            );
            if (rowIndex < 0) rowIndex = 0;

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

            // Special handling for kategori change (to update kode_barang)
            if (fieldName === "kategori") {
                const namaEl = tr.children[1]?.querySelector(
                    'input, textarea, [name*="[nama_barang]"]'
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
                    'input[type="hidden"][name*="[kode_barang]"]'
                );
                if (!hiddenKode) {
                    hiddenKode = document.createElement("input");
                    hiddenKode.type = "hidden";
                    hiddenKode.name = `rows[${rowIndex}][kode_barang]`;
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
});
