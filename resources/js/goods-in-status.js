function initImagePreviewOverlay(previewContainerId, labelId) {
    const previewContainer = document.getElementById(previewContainerId);
    const label = document.getElementById(labelId);
    if (previewContainer && label) {
        let filterDiv = label.querySelector('.preview-filter');
        if (!filterDiv) {
            filterDiv = document.createElement('div');
            filterDiv.className = 'preview-filter absolute left-0 top-0 w-full h-full bg-black/60 pointer-events-none transition-all duration-300 opacity-0';
            label.appendChild(filterDiv);
        }
        const img = label.querySelector('img');
        const svg = label.querySelector('svg');
        const h5 = label.querySelector('h5');
        if (img) {
            img.classList.add('relative', 'z-20', 'block', 'transition-all', 'duration-300', 'blur-none', 'opacity-100');
            img.classList.remove('hidden');
        }
        if (svg) {
            svg.classList.add('absolute', 'left-1/2', 'top-1/2', '-translate-x-1/2', '-translate-y-1/2', 'z-10', 'pointer-events-none', 'transition-all', 'duration-300', 'text-white');
            svg.classList.remove('text-gray-700');
        }
        if (h5) {
            h5.classList.add('absolute', 'left-1/2', 'top-[70%]', '-translate-x-1/2', '-translate-y-1/2', 'z-10', 'pointer-events-none', 'text-white', 'transition-all', 'duration-300');
            h5.classList.remove('text-gray-700');
        }
        label.classList.add('relative');
        previewContainer.classList.add('relative');
        // On hover, bring SVG/h5/filter in front of image and apply transitions
        label.onmouseenter = function () {
            if (svg) {
                svg.classList.remove('z-10');
                svg.classList.add('z-30');
            }
            if (h5) {
                h5.classList.remove('z-10');
                h5.classList.add('z-30');
            }
            if (img) {
                img.classList.remove('z-20', 'opacity-100', 'blur-none');
                img.classList.add('z-10', 'opacity-50', 'blur-md');
            }
            if (filterDiv) {
                filterDiv.classList.remove('opacity-0');
                filterDiv.classList.add('opacity-100');
            }
        };
        label.onmouseleave = function () {
            if (svg) {
                svg.classList.remove('z-30');
                svg.classList.add('z-10');
            }
            if (h5) {
                h5.classList.remove('z-30');
                h5.classList.add('z-10');
            }
            if (img) {
                img.classList.remove('z-10', 'opacity-50', 'blur-md');
                img.classList.add('z-20', 'opacity-100', 'blur-none');
            }
            if (filterDiv) {
                filterDiv.classList.remove('opacity-100');
                filterDiv.classList.add('opacity-0');
            }
        };
    }
}

// Gabungkan fungsi openEditModal
function openEditModal(params) {
    const {
        id, status_listing, kode_barang, nama_barang, kategori, stok, satuan, lokasi, harga, deskripsi = '', tipe_request = 'primary', gambar = null
    } = params;

    if (tipe_request === 'primary') {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_status_listing').value = status_listing;
        document.getElementById('edit_kode_barang').value = kode_barang;
        document.getElementById('edit_nama_barang').value = nama_barang;
        document.getElementById('edit_kategori').value = kategori;
        document.getElementById('edit_stok').value = stok;
        document.getElementById('edit_satuan').value = satuan;
        document.getElementById('edit_lokasi').value = lokasi;
        document.getElementById('edit_harga').value = harga;
        document.getElementById('edit_deskripsi').value = deskripsi;

        // Preview gambar jika ada
        const gambarPreview = document.getElementById('edit_gambar_preview');
        if (gambar && gambar !== '') {
            gambarPreview.innerHTML = `<input id="edit_gambar" type="file" class="hidden" accept="image/*" />
            <label id="edit_gambar_label" for="edit_gambar" class="cursor-pointer">
                <img src="files/${gambar}" alt="Gambar Barang" class="mx-auto relative z-2 h-full object-cover rounded-lg" />
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto mb-4 h-8 w-8 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-700">Upload picture</h5>
            </label>`;
            // Initialize overlay and hover for existing image
            setTimeout(() => {
                initImagePreviewOverlay('edit_gambar_preview', 'edit_gambar_label');
            }, 0);
        } else {
            gambarPreview.innerHTML = `
                                    <input id="edit_gambar" type="file" class="hidden" accept="image/*" />
                                    <label id="edit_gambar_label" for="edit_gambar" class="cursor-pointer m-auto">
                                        <img class="mx-auto h-full hidden max-h-48 max-w-48" id="modified_image" src="" alt="" />
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto mb-4 h-8 w-8 text-gray-700">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-700">Upload picture</h5>
                                    </label>`;
        }


        document.getElementById('editBarangForm').action = '/goods-in-status/' + id;
        const modal = document.getElementById('editBarangModalPrimary');
        if (modal && typeof modal.showModal === 'function') {
            modal.showModal();
        }
    } else if (tipe_request === 'new_stock') {
        document.getElementById('edit_kode_barang_new_stock').textContent = kode_barang ?? '-';
        document.getElementById('edit_nama_barang_new_stock').textContent = nama_barang ?? '-';
        document.getElementById('edit_kategori_new_stock').textContent = kategori ?? '-';
        document.getElementById('edit_lokasi_new_stock').textContent = lokasi ?? '-';
        document.getElementById('edit_status_listing_new_stock').textContent = status_listing ?? '-';
        document.getElementById('edit_harga_new_stock').textContent = harga ?? '-';
        document.getElementById('edit_satuan_new_stock').textContent = satuan ?? '-';
        document.getElementById('current_stok_new_stock').textContent = stok ?? '-';
        document.getElementById('edit_deskripsi_new_stock').textContent = deskripsi ?? '-';
        const gambarPreview = document.getElementById('edit_gambar_preview_new_stock');
        if (gambar && gambar !== '') {
            gambarPreview.innerHTML = `<img src="files/${gambar}" alt="Gambar Barang" class="h-48 w-48 object-cover rounded-lg" />`;
        } else {
            gambarPreview.innerHTML = `<div class="h-48 w-48 rounded-lg border-2 border-dashed border-gray-300 bg-gray-100 flex items-center justify-center dark:border-gray-600 dark:bg-gray-800">
                <svg fill="#a1a1a1" class="p-10" height="200px" width="200px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 60 60" xml:space="preserve" stroke="#a1a1a1">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <g>
                                                <path
                                                    d="M47,31c-7.168,0-13,5.832-13,13s5.832,13,13,13s13-5.832,13-13S54.168,31,47,31z M47,55c-6.065,0-11-4.935-11-11 s4.935-11,11-11s11,4.935,11,11S53.065,55,47,55z">
                                                </path>
                                                <path
                                                    d="M51.95,39.051c-0.391-0.391-1.023-0.391-1.414,0L47,42.586l-3.536-3.535c-0.391-0.391-1.023-0.391-1.414,0 s-0.391,1.023,0,1.414L45.586,44l-3.536,3.535c-0.391,0.391-0.391,1.023,0,1.414c0.195,0.195,0.451,0.293,0.707,0.293 s0.512-0.098,0.707-0.293L47,45.414l3.536,3.535c0.195,0.195,0.451,0.293,0.707,0.293s0.512-0.098,0.707-0.293 c0.391-0.391,0.391-1.023,0-1.414L48.414,44l3.536-3.535C52.34,40.074,52.34,39.441,51.95,39.051z">
                                                </path>
                                                <path d="M46.201,28.041l-6.138-5.626l-9.181,10.054l2.755,2.755C36.363,31.088,40.952,28.302,46.201,28.041z"></path>
                                                <path
                                                    d="M23.974,28.389L7.661,42.751C7.471,42.918,7.235,43,7,43c-0.277,0-0.553-0.114-0.751-0.339 c-0.365-0.415-0.325-1.047,0.09-1.412l17.017-14.982c0.396-0.348,0.994-0.329,1.368,0.044l4.743,4.743l9.794-10.727 c0.179-0.196,0.429-0.313,0.694-0.325c0.264-0.006,0.524,0.083,0.72,0.262l8.646,7.925c3.338,0.489,6.34,2.003,8.678,4.223V4 c0-0.553-0.448-1-1-1H1C0.448,3,0,3.447,0,4v44c0,0.553,0.448,1,1,1h30.811C31.291,47.425,31,45.747,31,44 c0-2.5,0.593-4.858,1.619-6.967L23.974,28.389z M16,14c3.071,0,5.569,2.498,5.569,5.569c0,3.07-2.498,5.568-5.569,5.568 s-5.569-2.498-5.569-5.568C10.431,16.498,12.929,14,16,14z">
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
            </div>`;
        }
        document.getElementById('edit_id_new_stock').value = id;
        document.getElementById('edit_stok_new_stock').value = (stok !== undefined && stok !== null && stok !== '' && stok !== '-') ? stok : '';
        document.getElementById('tambahStockForm').action = '/goods-in-status/' + id;
        const modal = document.getElementById('editBarangModalNewStock');
        if (modal && typeof modal.showModal === 'function') {
            modal.showModal();
        }
    }
}

window.openEditModal = openEditModal;

// Delegated event handler
$(document).on('click', '.edit-barang-btn', function (e) {
    e.preventDefault();

    // Ambil semua data
    const params = {
        id: $(this).data('id'),
        status_listing: $(this).data('status'),
        kode_barang: $(this).data('kode'),
        nama_barang: $(this).data('nama'),
        kategori: $(this).data('kategori'),
        stok: $(this).data('stok'),
        satuan: $(this).data('satuan'),
        lokasi: $(this).data('lokasi'),
        harga: $(this).data('harga'),
        deskripsi: $(this).data('deskripsi'),
        tipe_request: $(this).data('tipe_request'),
        gambar: $(this).data('gambar')
    };

    openEditModal(params);
});

// Handler untuk tombol Note
$(document).on('click', '.note-btn', function (e) {
    e.preventDefault();
    const catatan = $(this).data('catatan') || 'Tidak ada catatan';
    $('#catatanContent').text(catatan);

    const noteModal = document.getElementById('noteModal');
    if (noteModal && typeof noteModal.showModal === 'function') {
        noteModal.showModal();
    }
});

// Handler untuk tombol tutup modal Note
$(document).on('click', '#closeNoteModal', function (e) {
    e.preventDefault();
    const noteModal = document.getElementById('noteModal');
    if (noteModal && typeof noteModal.close === 'function') {
        noteModal.close();
    }
});


// Wait until the file input is changed
document.getElementById("edit_gambar").onchange = function () {
    const reader = new FileReader();
    reader.onload = function () {
        // Update the preview image src
        const previewImg = document.querySelector('#edit_gambar_preview img');
        if (previewImg) {
            previewImg.src = reader.result;
        }
        // Optionally set a hidden input for the modified image
        const hiddenInput = document.getElementById("modified_image");
        if (hiddenInput) {
            hiddenInput.value = reader.result;
        }
        // Re-init overlay and hover for new image
        initImagePreviewOverlay('edit_gambar_preview', 'edit_gambar_label');
    };
    reader.readAsDataURL(this.files[0]);
};
