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

