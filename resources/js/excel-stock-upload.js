document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action*="import-excel.import"], form[enctype="multipart/form-data"]');
    const fileInput = document.getElementById('excel');
    const progressSection = document.getElementById('progress-section');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const uploadArea = document.getElementById('upload-area');
    const uploadLabel = document.getElementById('upload-label');
    const uploadResult = document.getElementById('upload-result');
    const importFilePathInput = document.getElementById('import_file_path');

    if (!form || !fileInput) return;

    let uploadInProgress = false;
    let uploadCompleted = false;
    const submitButton = form.querySelector('.submit-btn');

    // helper: auto-map headers to fields using keywords
    function autoMapHeaders(headers) {
        const lower = headers.map(h => (h || '').toString().toLowerCase().replace(/\s+/g, ' ').trim());
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
        map['kode_barang'] = 0;
        map['nama_barang'] = 1;
        map['kategori'] = 2;
        map['stok'] = 3;

        // Mapping sudah hardcode, skip ensure unique

        return map;
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
            for (let i = 0; i < 10; i++) {
                const td = document.createElement('td');
                if (i === 9) {
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
                // add hidden input for kode_barang so it gets submitted as array
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `rows[${rowIndex}][kode_barang]`;
                hidden.value = kodeVal;
                tdKode.appendChild(hidden);
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
                // hidden
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `rows[${rowIndex}][nama_barang]`;
                hidden.value = v;
                tdNama.appendChild(hidden);
            }

            // 2: kategori (input exists in template)
            const tdKategori = newRow.children[2];
            if (tdKategori) {
                let inp = tdKategori.querySelector('input');
                if (!inp) {
                    inp = document.createElement('input');
                    inp.type = 'text';
                    tdKategori.appendChild(inp);
                }
                const v = (getVal('kategori') || '').toString().trim();
                inp.value = v;
                // hidden
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `rows[${rowIndex}][kategori]`;
                hidden.value = v;
                tdKategori.appendChild(hidden);
            }

            // 3: stok
            const tdStok = newRow.children[3];
            if (tdStok) {
                let inp = tdStok.querySelector('input');
                if (!inp) { inp = document.createElement('input'); inp.type = 'number'; tdStok.appendChild(inp); }
                const v = getVal('stok') || 0;
                inp.value = v;
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `rows[${rowIndex}][stok]`;
                hidden.value = v;
                tdStok.appendChild(hidden);
            }

            // 4: aksi - keep remove button functional
            const aksiTd = newRow.children[9];
            if (aksiTd) {
                const removeBtn = aksiTd.querySelector('button.remove-row');
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        newRow.remove();
                    });
                }
            }

            tbody.appendChild(newRow);
            if (window.handleImagePreview) {
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
            progressBar.style.width = '0%';
            progressSection.classList.add('hidden');
            if(uploadLabel) uploadLabel.classList.remove('hidden');
            return;
        }

        if (file) {
            // Reset and show progress
            if(uploadLabel) uploadLabel.classList.add('hidden');
            if(uploadResult) uploadResult.classList.add('hidden');
            progressSection.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
            const statusText = document.getElementById('upload-status-text');
            if(statusText) statusText.textContent = 'Uploading...';

            const filenameEl = document.getElementById('excel_filename');
            if (filenameEl) {
                filenameEl.textContent = file.name;
                // filenameEl.classList.remove('hidden'); 
            }

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

                     // show upload result and hide progress
                     progressSection.classList.add('hidden');
                     if (uploadResult) {
                         const uploadPath = document.getElementById('upload-path'); // hidden
                         const uploadUrl = document.getElementById('upload-url');
                         const uploadFilename = document.getElementById('upload-filename');
 
                         if(uploadFilename) uploadFilename.textContent = file.name; // Use file.name from closure
                         if(uploadPath) uploadPath.textContent = resp.path || '';
                         
                         if (resp.url) {
                             if(uploadUrl) {
                                 uploadUrl.href = resp.url;
                                 uploadUrl.textContent = 'Lihat File';
                                 uploadUrl.classList.remove('hidden');
                             }
                         } else {
                             if(uploadUrl) uploadUrl.classList.add('hidden');
                         }
                         uploadResult.classList.remove('hidden');
                     }
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memproses preview.' });
                }
            } else {
                progressSection.classList.add('hidden');
                if(uploadLabel) uploadLabel.classList.remove('hidden');
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
            if(uploadLabel) uploadLabel.classList.remove('hidden');
            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat mengupload file.' });
            uploadInProgress = false;
            if (submitButton) submitButton.disabled = false;
        });

        xhr.addEventListener('abort', function () {
            progressSection.classList.add('hidden');
            if(uploadLabel) uploadLabel.classList.remove('hidden');
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
            }

            // update or create hidden input rows[{i}][kode_barang]
            let hiddenKode = tr.children[0]?.querySelector('input[type="hidden"][name*="[kode_barang]"]');
            if (!hiddenKode) {
                hiddenKode = document.createElement('input');
                hiddenKode.type = 'hidden';
                // try to reuse rowIndex in name; if existing rows used different naming fallback to generic rows[][kode_barang]
                hiddenKode.name = `rows[${rowIndex}][kode_barang]`;
                tr.children[0].appendChild(hiddenKode);
            }
            hiddenKode.value = newKode;

            // call server-side uniqueness check if available
            try {
                if (typeof validateKodeBarang === 'function') validateKodeBarang(kodeVisible || hiddenKode);
            } catch (err) {
                // ignore validation errors here
                // console.error(err);
            }
        });
    })();

    // <-- ADD: update hidden inputs when visible inputs change
    (function attachInputChangeHandler() {
        const table = document.getElementById('DataTableExcel');
        if (!table) return;

        table.addEventListener('change', function (e) {
            const inp = e.target.closest('input, select, textarea');
            if (!inp) return;

            const td = inp.closest('td');
            const tr = inp.closest('tr');
            if (!tr || !td) return;

            const colIndex = Array.prototype.indexOf.call(tr.children, td);
            let fieldName = '';

            // Map column index to field name
            switch (colIndex) {
                case 1: fieldName = 'nama_barang'; break;
                case 2: fieldName = 'kategori'; break;
                case 3: fieldName = 'stok'; break;
                default: return; // skip kode_barang (0), aksi (4)
            }

            const value = (inp.value || '').toString().trim();

            // compute row index
            let rowIndex = Array.prototype.indexOf.call(tr.parentNode.children, tr);
            if (rowIndex < 0) rowIndex = 0;

            // update/create hidden input
            let hidden = td.querySelector(`input[type="hidden"][name*="[${fieldName}]"]`);
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `rows[${rowIndex}][${fieldName}]`;
                td.appendChild(hidden);
            }
            hidden.value = value;

            // Special handling for kategori change (to update kode_barang)
            if (fieldName === 'kategori') {
                const namaEl = tr.children[1]?.querySelector('input, textarea, [name*="[nama_barang]"]');
                const nama = namaEl ? (namaEl.value || '').toString().trim() : '';
                const newKode = generateKodeFromCategory(value, nama, rowIndex);

                // update visible kode
                const kodeVisible = tr.children[0]?.querySelector('input[type="text"], input');
                if (kodeVisible) kodeVisible.value = newKode;

                // update hidden kode
                let hiddenKode = tr.children[0]?.querySelector('input[type="hidden"][name*="[kode_barang]"]');
                if (!hiddenKode) {
                    hiddenKode = document.createElement('input');
                    hiddenKode.type = 'hidden';
                    hiddenKode.name = `rows[${rowIndex}][kode_barang]`;
                    tr.children[0].appendChild(hiddenKode);
                }
                hiddenKode.value = newKode;

                // trigger uniqueness check
                try { if (typeof validateKodeBarang === 'function') validateKodeBarang(kodeVisible || hiddenKode); } catch (err) { /* ignore */ }
            }
        });
    })();
});
