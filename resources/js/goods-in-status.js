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
            gambarPreview.innerHTML = `<img src="/storage/${gambar}" alt="Gambar Barang" class="h-48 w-48 object-cover rounded-lg" />`;
        } else {
            gambarPreview.innerHTML = `<div class="h-48 w-48 rounded-lg border-2 border-dashed border-gray-300 bg-gray-100 flex items-center justify-center dark:border-gray-600 dark:bg-gray-800">
                <span class="text-gray-500 dark:text-gray-400">Gambar tidak tersedia</span>
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

document.getElementById("gambar").onchange = function () {
    const reader = new FileReader();
    reader.onload = function () {
        // Update the preview image src
        const previewImg = document.querySelector('#gambar_preview img');
        if (previewImg) {
            previewImg.src = reader.result;
        }
        // Optionally set a hidden input for the modified image
        const hiddenInput = document.getElementById("modified_image");
        if (hiddenInput) {
            hiddenInput.value = reader.result;
        }
        // Re-init overlay and hover for new image
        initImagePreviewOverlay('gambar_preview', 'gambar_label');
    };
    reader.readAsDataURL(this.files[0]);
};
