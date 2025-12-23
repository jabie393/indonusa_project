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
        map["kode_barang"] = null;
        map["nama_barang"] =
            pick(["nama", "product", "barang", "item", "description"]) ??
            pick(["nama barang", "nama_produk"]);
        map["kategori"] = pick(["kategori", "category"]);
        map["deskripsi"] = pick([
            "deskripsi",
            "description",
            "keterangan",
            "note",
        ]);
        map["stok"] = pick(["stok", "quantity", "qty", "jumlah"]);
        map["satuan"] = pick(["satuan", "unit"]);
        map["harga"] = pick(["harga", "price", "rp"]);
        map["gambar"] = pick(["gambar", "image", "photo", "foto"]);
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

    // Prepare Existing Codes Set for fast lookup
    const existingCodesSet = new Set(
        (window.EXISTING_KODES || []).map((k) => String(k).toUpperCase())
    );

    function generateKodeFromCategory(kategori, nama, rowIndex) {
        // pakai singkatan kategori bila ada, jika tidak gunakan 2-3 huruf awal dari nama barang
        let sing =
            kategori && kategoriSingkatanLocal[kategori]
                ? kategoriSingkatanLocal[kategori]
                : "";
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

        // Base logic: SING-TIMESTAMP
        // Using increment strategy for collisions as requested (no suffix)

        // Initial seed from time
        let seed = parseInt(Date.now().toString().slice(-5), 10);
        let candidate = ``;

        let attempt = 0;
        // Try creating a code. If taken, increment seed and try again.
        while (attempt < 2000) {
            candidate = `${sing}-${seed.toString().padStart(5, "0")}`;

            if (!existingCodesSet.has(candidate)) {
                // Found a free slot
                existingCodesSet.add(candidate);
                return candidate;
            }

            // Collision -> Increment seed
            seed++;
            if (seed > 99999) seed = 0; // Wrap around
            attempt++;
        }

        // Fallback (extremely unlikely) - if wrapped 2000 times and all taken?
        // Just return the last candidate or maybe fallback to original random logic if critical failure
        return candidate;
    }

    // Strict Header Validation
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
            "HARGA",
        ];

        // Columns that are allowed but will be ignored during import
        const ignored = ["LIST KATEGORI"];

        const upperHeaders = headers.map((h) => String(h).trim().toUpperCase());

        // 1. Check Missing
        const missing = required.filter((req) => !upperHeaders.includes(req));

        // 2. Check Extra (Unknown columns)
        // Any header in upperHeaders that is NOT in required list AND NOT in ignored list is an extra
        const extra = upperHeaders.filter(
            (h) => h !== "" && !required.includes(h) && !ignored.includes(h)
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
    // Also validates that rows have essential product data (nama_barang)
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
                    // Check if nama_barang column has data (essential field)
                    const namaBarangIdx = mapping["nama_barang"];
                    if (
                        namaBarangIdx !== null &&
                        namaBarangIdx !== undefined &&
                        namaBarangIdx !== ""
                    ) {
                        const namaBarangValue = r[namaBarangIdx];
                        // Only include row if nama_barang has actual data
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

    // render DataTableExcel from rows (rows are array-of-arrays, headers not included)
    function renderDataTableFromPreviewAll(rows, mapping, headers) {
        const tableEl = document.getElementById("DataTableExcel");
        if (!tableEl) return;

        // Get atau inisialisasi DataTable instance dengan aman
        let dt;
        if ($.fn.DataTable.isDataTable("#DataTableExcel")) {
            // Jika sudah ada instance, ambil instance yang ada
            dt = $("#DataTableExcel").DataTable();
        } else {
            // Jika belum ada, inisialisasi baru
            dt = $("#DataTableExcel").DataTable({
                paging: true,
                searching: true,
                ordering: true,
            });
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

        // clear existing rows using DataTable API
        dt.clear();

        const newRows = rows.map((r, rowIndex) => {
            const newRow = baseRow.cloneNode(true);

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
                // add hidden input for kode_barang so it gets submitted as array
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][kode_barang]`;
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
                // hidden
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][nama_barang]`;
                hidden.value = v;
                tdNama.appendChild(hidden);
            }

            // 2: kategori (select exists in template). Try to set select or fallback hidden
            const tdKategori = newRow.children[2];
            if (tdKategori) {
                const sel = tdKategori.querySelector("select");
                const v = (getVal("kategori") || "").toString().trim();
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
                    hidden.name = `rows[${rowIndex}][kategori]`;
                    hidden.value = sel.value || v;
                    tdKategori.appendChild(hidden);
                } else {
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = `rows[${rowIndex}][kategori]`;
                    hidden.value = v;
                    tdKategori.appendChild(hidden);
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
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][stok]`;
                hidden.value = v;
                tdStok.appendChild(hidden);
            }

            // 4: harga
            const tdHarga = newRow.children[4];
            if (tdHarga) {
                let inp = tdHarga.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    tdHarga.appendChild(inp);
                }
                let v = getVal("harga") || "";
                if (typeof v === "string")
                    v = v.replace(/[^\d\.,-]/g, "").replace(",", ".");
                inp.value = v;
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][harga]`;
                hidden.value = v;
                tdHarga.appendChild(hidden);
            }

            // 5: satuan
            const tdSatuan = newRow.children[5];
            if (tdSatuan) {
                let inp = tdSatuan.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    tdSatuan.appendChild(inp);
                }
                const v = getVal("satuan") || "";
                inp.value = v;
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][satuan]`;
                hidden.value = v;
                tdSatuan.appendChild(hidden);
            }

            // 6: status_listing
            const tdStatus = newRow.children[6];
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

            // 7: Gambar
            const tdGambar = newRow.children[7];
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

            // 8: Deskripsi
            const tdDeskripsi = newRow.children[8];
            if (tdDeskripsi) {
                let inp = tdDeskripsi.querySelector("input");
                if (!inp) {
                    inp = document.createElement("input");
                    inp.type = "text";
                    inp.className =
                        "block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400";
                    tdDeskripsi.appendChild(inp);
                }
                const v = getVal("deskripsi") || "";
                inp.value = v;

                // Hidden input for submission
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = `rows[${rowIndex}][deskripsi]`;
                hidden.value = v;
                tdDeskripsi.appendChild(hidden);
            }

            // 9: aksi - keep remove button functional
            const aksiTd = newRow.children[9];
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
                            let errorHtml = `File Excel harus MEMILIKI kolom PERSIS berikut:<br><b>KATEGORI, NAMA BARANG, DESKRIPSI, STOK, SATUAN, HARGA</b>.<br><br>`;

                            if (valResult.missing.length > 0) {
                                errorHtml += `Kolom yang hilang: <span class="text-red-500 font-bold">${valResult.missing.join(
                                    ", "
                                )}</span><br>`;
                            }

                            if (valResult.extra && valResult.extra.length > 0) {
                                errorHtml += `Kolom tidak dikenal (harus dihapus): <span class="text-red-500 font-bold">${valResult.extra.join(
                                    ", "
                                )}</span>`;
                            }

                            Swal.fire({
                                icon: "error",
                                title: "Format Header Tidak Sesuai",
                                html: errorHtml,
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
                        if (importFilePathInput)
                            importFilePathInput.value = resp.path || "";
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

            // only react when the select is in Kategori column (col index 2)
            const colIndex = Array.prototype.indexOf.call(tr.children, td);
            if (colIndex !== 2) return;

            const kategori = (sel.value || "").toString().trim();
            const namaEl = tr.children[1]?.querySelector(
                'input, textarea, [name*="[nama_barang]"]'
            );
            const nama = namaEl ? (namaEl.value || "").toString().trim() : "";

            // compute row index for naming hidden inputs
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
            if (kodeVisible) kodeVisible.value = newKode;

            // update/create hidden input rows[{i}][kode_barang]
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

            // ensure hidden kategori value also updated for submission
            let hiddenKategori = tr.children[2]?.querySelector(
                'input[type="hidden"][name*="[kategori]"]'
            );
            if (!hiddenKategori) {
                hiddenKategori = document.createElement("input");
                hiddenKategori.type = "hidden";
                hiddenKategori.name = `rows[${rowIndex}][kategori]`;
                tr.children[2].appendChild(hiddenKategori);
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
                case 5:
                    fieldName = "satuan";
                    break;
                case 6:
                    fieldName = "status_listing";
                    break;
                case 8:
                    fieldName = "deskripsi";
                    break;
                default:
                    return; // skip kode_barang (0), gambar (7), aksi (9)
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
