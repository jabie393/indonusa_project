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
    document.getElementById('editBarangForm').action = '/warehouse/' + id;
    // Show modal (direct)
    const modal = document.getElementById('editBarangModal');
    if (modal && typeof modal.showModal === 'function') {
        modal.showModal();
    }
}

window.openEditModal = openEditModal;


// Use delegated event handler for dynamically loaded content
$(document).on('click', '.edit-barang-btn', function (e) {
    e.preventDefault();

    // Get data attributes from the clicked element
    const id = $(this).data('id');
    const status = $(this).data('status');
    const kode = $(this).data('kode');
    const nama = $(this).data('nama');
    const kategori = $(this).data('kategori');
    const stok = $(this).data('stok');
    const satuan = $(this).data('satuan');
    const lokasi = $(this).data('lokasi');
    const harga = $(this).data('harga');
    const deskripsi = $(this).data('deskripsi');

    // Call your modal function
    openEditModal(id, status, kode, nama, kategori, stok, satuan, lokasi, harga, deskripsi);
});