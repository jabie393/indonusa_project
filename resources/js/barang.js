function openEditModal(id, status_listing, kode_barang, nama_barang, kategori, stok, satuan, lokasi, harga, deskripsi = '') {
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

    // Set form action
    document.getElementById('editBarangForm').action = '/barang/' + id;
    // Show modal (Flowbite)
    window.dispatchEvent(new CustomEvent('open-modal', {
        detail: 'editBarangModal'
    }));
}

window.openEditModal = openEditModal;
