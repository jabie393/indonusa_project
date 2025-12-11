document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action*="import-excel.import"], form[enctype="multipart/form-data"]');
    const fileInput = document.getElementById('excel');
    const progressSection = document.getElementById('progress-section');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const uploadArea = document.getElementById('upload-area');
    // mapping UI removed (we still keep autoMap/injectMapping to create hidden inputs)
    // const mappingControls = document.getElementById('mapping-controls');
    // const mappingFieldsContainer = document.getElementById('mapping-fields');
    // const previewWrapper = document.getElementById('preview-wrapper');
    // const previewTable = document.getElementById('preview-table');
    const importFilePathInput = document.getElementById('import_file_path');

    if (!form || !fileInput) return;

    let uploadInProgress = false;
    let uploadCompleted = false;
    const submitButton = form.querySelector('.submit-btn');

    // helper: auto-map headers to fields using keywords
    function autoMapHeaders(headers) {
        const lower = headers.map(h => (h || '').toString().toLowerCase());
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

        map['kode_barang'] = pick(['kode', 'sku', 'code', 'id barang', 'kode barang']);
        map['nama_barang'] = pick(['nama', 'product', 'barang', 'item', 'description']) ?? pick(['nama barang', 'nama_produk']);
        map['kategori'] = pick(['kategori', 'category']);
        map['deskripsi'] = pick(['deskripsi', 'description', 'keterangan', 'spesifikasi', 'note']);
        map['stok'] = pick(['stok', 'quantity', 'qty', 'jumlah']);
        map['satuan'] = pick(['satuan', 'unit']);
        map['harga'] = pick(['harga', 'price', 'rp']);
        map['gambar'] = pick(['gambar', 'image', 'photo', 'foto']);
        map['status_listing'] = pick(['status', 'listing']);

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
        "HANDTOOLS": "HT", "ADHESIVE AND SEALANT": "AS", "AUTOMOTIVE EQUIPMENT": "AE", "CLEANING": "CLN",
        "COMPRESSOR": "CMP", "CONSTRUCTION": "CST", "CUTTING TOOLS": "CT", "LIGHTING": "LTG",
        "FASTENING": "FST", "GENERATOR": "GEN", "HEALTH CARE EQUIPMENT": "HCE", "HOSPITALITY": "HSP",
        "HYDRAULIC TOOLS": "HYD", "MARKING MACHINE": "MM", "MATERIAL HANDLING EQUIPMENT": "MHE",
        "MEASURING AND TESTING EQUIPMENT": "MTE", "METAL CUTTING MACHINERY": "MCM", "PACKAGING": "PKG",
        "PAINTING AND COATING": "PC", "PNEUMATIC TOOLS": "PN", "POWER TOOLS": "PT",
        "SAFETY AND PROTECTION EQUIPMENT": "SPE", "SECURITY": "SEC", "SHEET METAL MACHINERY": "SMM",
        "STORAGE SYSTEM": "STS", "WELDING EQUIPMENT": "WLD", "WOODWORKING EQUIPMENT": "WWE",
        "MISCELLANEOUS": "MSC", "OTHER CATEGORIES": "OC",
    };

    function generateKodeFromCategory(kategori, nama, rowIndex) {
        // pakai singkatan kategori bila ada, jika tidak gunakan 2-3 huruf awal dari nama barang
        let sing = (kategori && kategoriSingkatanLocal[kategori]) ? kategoriSingkatanLocal[kategori] : '';
        if (!sing) {
            if (nama) {
                const parts = String(nama).trim().split(/\s+/);
                sing = parts.map(p => p[0] || '').join('').slice(0, 3).toUpperCase();
            } else {
                sing = 'UNK';
            }
            if (!sing) sing = 'UNK';
        }
        const timestamp = Date.now().toString().slice(-5); // 5 digit terakhir
        return `${sing}-${timestamp}`;
    }

    // helper: inject hidden mapping inputs into form (overwrites previous)
    function injectMappingInputs(mapping) {
        // remove previous mapping inputs
        form.querySelectorAll('input[name^="mapping["]').forEach(i => i.remove());
        for (const field in mapping) {
            const val = mapping[field];
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `mapping[${field}]`;
            input.value = (val === null || val === undefined) ? '' : String(val);
            form.appendChild(input);
        }
    }

    // helper: remove completely empty rows and trim each row to headers length
    function cleanRows(rows, headers) {
        if (!Array.isArray(rows)) return [];
        const hlen = Array.isArray(headers) ? headers.length : null;
        return rows
            .map(r => Array.isArray(r) ? r : [])
            .map(r => (hlen ? r.slice(0, hlen) : r))
            .map(r => r.map(c => (c === null || c === undefined) ? '' : String(c).trim()))
            .filter(r => r.some(c => c !== '')); // keep rows that have at least one non-empty cell
    }

    // render DataTableExcel from rows (rows are array-of-arrays, headers not included)
    function renderDataTableFromPreviewAll(rows, mapping, headers) {
        const table = document.getElementById('DataTableExcel');
        if (!table) return;
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const templateRow = tbody.querySelector('tr');
        // clear existing
        tbody.innerHTML = '';

        // if no template row, build one minimal for 9 columns
        let baseRow;
        if (templateRow) {
            baseRow = templateRow;
        } else {
            baseRow = document.createElement('tr');
            for (let i = 0; i < 9; i++) {
                const td = document.createElement('td');
                if (i === 8) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn remove-row rounded-md bg-red-500 text-white';
                    btn.innerText = 'Hapus';
                    td.appendChild(btn);
                } else {
                    const inp = document.createElement('input');
                    inp.type = 'text';
                    inp.className = 'block w-full';
                    td.appendChild(inp);
                }
                baseRow.appendChild(td);
            }
        }

        rows.forEach((r, rowIndex) => {
            const newRow = baseRow.cloneNode(true);

            const getVal = (field) => {
                const col = mapping[field];
                if (col === null || col === undefined || col === '') return '';
                return r[col] !== undefined && r[col] !== null ? r[col] : '';
            };

            // columns mapping based on table columns in blade:
            // 0: kode_barang (readonly input)
            const tdKode = newRow.children[0];
            if (tdKode) {
                let inp = tdKode.querySelector('input');
                if (!inp) {
                    inp = document.createElement('input');
                    inp.type = 'text';
                    tdKode.appendChild(inp);
                }
                inp.readOnly = true;
                const kodeVal = getVal('kode_barang') || generateKodeFromCategory(getVal('kategori'), getVal('nama_barang'), rowIndex);
                inp.value = kodeVal;
                // ensure visible input has name so edited value is submitted
                inp.name = `rows[${rowIndex}][kode_barang]`;
                // Jika fungsi validateKodeBarang tersedia (didefinisikan di checker.js), panggil untuk cek unik
                try { if (typeof validateKodeBarang === 'function') validateKodeBarang(inp); } catch (e) { /* ignore */ }
            }

            // 1: nama_barang
            const tdNama = newRow.children[1];
            if (tdNama) {
                let inp = tdNama.querySelector('input');
                if (!inp) {
                    inp = document.createElement('input'); inp.type = 'text'; tdNama.appendChild(inp);
                }
                const v = getVal('nama_barang') || '';
                inp.value = v;
                // set name on visible input so changes are submitted
                inp.name = `rows[${rowIndex}][nama_barang]`;
            }

            // 2: kategori (select exists in template). Try to set select or fallback hidden
            const tdKategori = newRow.children[2];
            if (tdKategori) {
                const sel = tdKategori.querySelector('select');
                const v = (getVal('kategori') || '').toString().trim();
                if (sel) {
                    // try match option text or value
                    let matched = false;
                    Array.from(sel.options).forEach(opt => {
                        if (!matched && opt.value && opt.value.toString().toLowerCase() === v.toLowerCase()) {
                            sel.value = opt.value; matched = true;
                        }
                    });
                    if (!matched) {
                        Array.from(sel.options).forEach(opt => {
                            if (!matched && opt.text && opt.text.toString().toLowerCase() === v.toLowerCase()) {
                                sel.value = opt.value; matched = true;
                            }
                        });
                    }
                    // set name on select so its value is submitted/updated by user
                    sel.name = `rows[${rowIndex}][kategori]`;
                } else {
                    // if template had no select (fallback input created earlier), set its name
                    const inp = tdKategori.querySelector('input');
                    if (inp) inp.name = `rows[${rowIndex}][kategori]`;
                }
            }

            // 3: stok
            const tdStok = newRow.children[3];
            if (tdStok) {
                let inp = tdStok.querySelector('input');
                if (!inp) { inp = document.createElement('input'); inp.type = 'number'; tdStok.appendChild(inp); }
                const v = getVal('stok') || 0;
                inp.value = v;
                inp.name = `rows[${rowIndex}][stok]`;
            }

            // 4: harga
            const tdHarga = newRow.children[4];
            if (tdHarga) {
                let inp = tdHarga.querySelector('input');
                if (!inp) { inp = document.createElement('input'); inp.type = 'text'; tdHarga.appendChild(inp); }
                let v = getVal('harga') || '';
                if (typeof v === 'string') v = v.replace(/[^\d\.,-]/g, '').replace(',', '.');
                inp.value = v;
                inp.name = `rows[${rowIndex}][harga]`;
            }

            // 5: satuan
            const tdSatuan = newRow.children[5];
            if (tdSatuan) {
                let inp = tdSatuan.querySelector('input');
                if (!inp) { inp = document.createElement('input'); inp.type = 'text'; tdSatuan.appendChild(inp); }
                const v = getVal('satuan') || '';
                inp.value = v;
                inp.name = `rows[${rowIndex}][satuan]`;
            }

            // 6: status_listing
            const tdStatus = newRow.children[6];
            if (tdStatus) {
                const sel = tdStatus.querySelector('select');
                const v = (getVal('status_listing') || 'listing').toString().toLowerCase();
                if (sel) {
                    Array.from(sel.options).forEach(opt => {
                        if (opt.value.toLowerCase() === v) sel.value = opt.value;
                    });
                    sel.name = `rows[${rowIndex}][status_listing]`;
                } else {
                    const inp = tdStatus.querySelector('input');
                    if (inp) inp.name = `rows[${rowIndex}][status_listing]`;
                }
            }

            // 7: deskripsi
            const tdDeskripsi = newRow.children[7];
            if (tdDeskripsi) {
                // prefer textarea if template has one, fallback to input
                let inp = tdDeskripsi.querySelector('textarea, input');
                if (!inp) {
                    // use textarea for longer text
                    inp = document.createElement('textarea');
                    inp.className = 'block w-full';
                    inp.rows = 2;
                    tdDeskripsi.appendChild(inp);
                }
                const vDes = getVal('deskripsi') || '';
                inp.value = vDes;
                inp.name = `rows[${rowIndex}][deskripsi]`;
            }

            // 8: aksi - keep remove button functional
            const aksiTd = newRow.children[8];
            if (aksiTd) {
                const removeBtn = aksiTd.querySelector('button.remove-row');
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        newRow.remove();
                    });
                }
            }

            tbody.appendChild(newRow);
            
            // Re-attach image preview handler if available
            if (typeof window.handleImagePreview === 'function' && newRow.querySelector('input[type="file"]')) {
                window.handleImagePreview(newRow);
            }
        });
    }

    // Handle file selection
    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        // Validate file extension and MIME type before proceeding
        const allowedExt = ['xlsx', 'xls'];
        const allowedMime = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
        ];

        if (!file) return;

        const nameParts = file.name.split('.');
        const ext = nameParts.length > 1 ? nameParts.pop().toLowerCase() : '';
        const mime = file.type;

        const isExtOk = allowedExt.includes(ext);
        const isMimeOk = allowedMime.includes(mime) || mime === '';

        if (!isExtOk || !isMimeOk) {
            Swal.fire({
                icon: 'error',
                title: 'File tidak valid',
                text: 'Harap pilih file Excel dengan ekstensi .xlsx atau .xls.'
            });
            e.target.value = '';
            progressSection.classList.add('hidden');
            const filenameElReset = document.getElementById('excel_filename');
            if (filenameElReset) {
                filenameElReset.textContent = '';
                filenameElReset.classList.add('hidden');
            }
            const svg = document.querySelector('#excel_label svg');
            if (svg) svg.classList.remove('hidden');
            return;
        }

        if (file) {
            progressSection.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';

            const filenameEl = document.getElementById('excel_filename');
            if (filenameEl) {
                filenameEl.textContent = file.name;
                filenameEl.classList.remove('hidden');
            }
            const svg = document.querySelector('#excel_label svg');
            if (svg) svg.classList.add('hidden');

            setTimeout(() => { startUpload(file); }, 300);
        }
    });

    function startUpload(file) {
        if (uploadInProgress) return;
        uploadInProgress = true;
        if (submitButton) submitButton.disabled = true;

        const formData = new FormData();
        formData.append('excel', file);
        formData.append('_token', window.CSRF_TOKEN);

        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
                progressText.textContent = Math.round(percentComplete) + '%';
            }
        });

        xhr.addEventListener('load', function () {
            uploadInProgress = false;
            if (xhr.status === 200 || xhr.status === 201) {
                try {
                    const resp = JSON.parse(xhr.responseText);
                    progressBar.style.width = '100%';
                    progressText.textContent = '100%';

                    if (importFilePathInput) importFilePathInput.value = resp.path || '';

                    if (resp.headers && Array.isArray(resp.headers)) {
                        // no preview UI: keep import path, auto-map and populate main table
                        const cleanedRows = cleanRows(resp.rows || [], resp.headers || []);
                        if (importFilePathInput) importFilePathInput.value = resp.path || '';
                        const mapping = autoMapHeaders(resp.headers || []);
                        injectMappingInputs(mapping); // hidden mapping[...] inputs
                        renderDataTableFromPreviewAll(cleanedRows, mapping, resp.headers || []);
                        if (submitButton) submitButton.disabled = false;
                    }

                    // show upload result
                    const uploadResult = document.getElementById('upload-result');
                    const uploadFilename = document.getElementById('upload-filename');
                    const uploadUrl = document.getElementById('upload-url');
                    if (uploadResult && uploadUrl && uploadFilename) {
                        const getBasename = (p) => {
                            if (!p) return '';
                            const parts = p.split('/');
                            return parts[parts.length - 1] || '';
                        };
                        const filename = resp.path ? getBasename(resp.path) : (resp.url ? getBasename(resp.url) : '');
                        uploadFilename.textContent = filename || '';
                        if (resp.url) {
                            uploadUrl.href = resp.url;
                            uploadUrl.textContent = 'Buka file';
                            uploadUrl.classList.remove('hidden');
                        } else {
                            uploadUrl.href = '#';
                            uploadUrl.textContent = 'Tidak tersedia';
                        }
                        uploadResult.classList.remove('hidden');
                    }
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memproses preview.' });
                }
            } else {
                progressSection.classList.add('hidden');
                try {
                    const response = JSON.parse(xhr.responseText);
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'Gagal mengupload file' });
                } catch (e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengupload file. Status: ' + xhr.status });
                }
                if (submitButton) submitButton.disabled = false;
            }
        });

        xhr.addEventListener('error', function () {
            progressSection.classList.add('hidden');
            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat mengupload file.' });
            uploadInProgress = false;
            if (submitButton) submitButton.disabled = false;
        });

        xhr.addEventListener('abort', function () {
            progressSection.classList.add('hidden');
            Swal.fire({ icon: 'warning', title: 'Dibatalkan', text: 'Upload dibatalkan.' });
            uploadInProgress = false;
            if (submitButton) submitButton.disabled = false;
        });

        const uploadUrl = window.IMPORT_EXCEL_STORE_URL || form.action.replace('/import', '');
        xhr.open('POST', uploadUrl);
        xhr.setRequestHeader('X-CSRF-TOKEN', window.CSRF_TOKEN);
        xhr.send(formData);
    }

    // Keep form submit as backup
    if (submitButton) {
        submitButton.addEventListener('click', function (e) {
            if (uploadInProgress) {
                e.preventDefault();
                Swal.fire({ icon: 'info', title: 'Tunggu', text: 'Upload sedang berlangsung. Tunggu hingga selesai.' });
                return;
            }
            // ensure file uploaded
            if (!importFilePathInput || !importFilePathInput.value) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Silakan unggah file Excel sebelum submit.' });
                return;
            }
            // allow submit to proceed
        });
    }

    // <-- ADD: event delegation for refresh buttons in DataTableExcel
    (function attachTableRefreshHandler() {
        const table = document.getElementById('DataTableExcel');
        if (!table) return;

        table.addEventListener('click', function (e) {
            const btn = e.target.closest('button#refreshKodeBarang, button.refresh-kode, button[data-action="refresh-kode"]');
            if (!btn) return;

            const tr = btn.closest('tr');
            if (!tr) return;

            // find visible nama and kategori in the row
            const namaEl = tr.children[1]?.querySelector('input, textarea, [name*="[nama_barang]"]');
            const kategoriEl = tr.children[2]?.querySelector('select, input, [name*="[kategori]"]');

            const nama = namaEl ? (namaEl.value || '').toString().trim() : '';
            const kategori = kategoriEl ? (kategoriEl.value || '').toString().trim() : '';

            // generate kode berdasarkan nama + kategori
            // try to compute a rowIndex if possible (fallback 0)
            let rowIndex = Array.prototype.indexOf.call(tr.parentNode.children, tr);
            if (rowIndex < 0) rowIndex = 0;
            const newKode = generateKodeFromCategory(kategori, nama, rowIndex);

            // update visible kode input (col 0)
            const kodeVisible = tr.children[0]?.querySelector('input[type="text"], input');
            if (kodeVisible) {
                kodeVisible.value = newKode;
                // ensure name exists so this updated value is submitted
                kodeVisible.name = kodeVisible.name || `rows[${rowIndex}][kode_barang]`;
            }

            // call server-side uniqueness check if available
            try {
                if (typeof validateKodeBarang === 'function') validateKodeBarang(kodeVisible);
            } catch (err) {
                // ignore validation errors here
                // console.error(err);
            }
        });
    })();

    // <-- ADD: update kode_barang when kategori select changes
    (function attachKategoriChangeHandler() {
        const table = document.getElementById('DataTableExcel');
        if (!table) return;

        table.addEventListener('change', function (e) {
            const sel = e.target.closest('select');
            if (!sel) return;

            const td = sel.closest('td');
            const tr = sel.closest('tr');
            if (!tr || !td) return;

            // only react when the select is in Kategori column (col index 2)
            const colIndex = Array.prototype.indexOf.call(tr.children, td);
            if (colIndex !== 2) return;

            const kategori = (sel.value || '').toString().trim();
            const namaEl = tr.children[1]?.querySelector('input, textarea, [name*="[nama_barang]"]');
            const nama = namaEl ? (namaEl.value || '').toString().trim() : '';

            // compute row index for naming hidden inputs
            let rowIndex = Array.prototype.indexOf.call(tr.parentNode.children, tr);
            if (rowIndex < 0) rowIndex = 0;

            const newKode = generateKodeFromCategory(kategori, nama, rowIndex);

            // update visible kode input (col 0)
            const kodeVisible = tr.children[0]?.querySelector('input[type="text"], input');
            if (kodeVisible) kodeVisible.value = newKode;

            // update/create hidden input rows[{i}][kode_barang]
            let hiddenKode = tr.children[0]?.querySelector('input[type="hidden"][name*="[kode_barang]"]');
            if (!hiddenKode) {
                hiddenKode = document.createElement('input');
                hiddenKode.type = 'hidden';
                hiddenKode.name = `rows[${rowIndex}][kode_barang]`;
                tr.children[0].appendChild(hiddenKode);
            }
            hiddenKode.value = newKode;

            // ensure hidden kategori value also updated for submission
            let hiddenKategori = tr.children[2]?.querySelector('input[type="hidden"][name*="[kategori]"]');
            if (!hiddenKategori) {
                hiddenKategori = document.createElement('input');
                hiddenKategori.type = 'hidden';
                hiddenKategori.name = `rows[${rowIndex}][kategori]`;
                tr.children[2].appendChild(hiddenKategori);
            }
            hiddenKategori.value = kategori;

            // trigger server-side uniqueness check if available
            try { if (typeof validateKodeBarang === 'function') validateKodeBarang(kodeVisible || hiddenKode); } catch (err) { /* ignore */ }
        });
    })();
});
