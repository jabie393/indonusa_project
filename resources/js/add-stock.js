
function openEditModal(id, status_listing, kode_barang, nama_barang, kategori, stok, satuan, lokasi, harga, deskripsi = '', gambar = null) {
    // Set data ke elemen modal (bukan input)
    document.getElementById('kode_barang').textContent = kode_barang ?? '-';
    document.getElementById('nama_barang').textContent = nama_barang ?? '-';
    document.getElementById('kategori').textContent = kategori ?? '-';
    document.getElementById('lokasi').textContent = lokasi ?? '-';
    document.getElementById('status_listing').textContent = status_listing ?? '-';
    document.getElementById('harga').textContent = harga ?? '-';
    document.getElementById('satuan').textContent = satuan ?? '-';
    document.getElementById('current_stok').textContent = stok ?? '-';
    document.getElementById('deskripsi').textContent = deskripsi ?? '-';

    // Preview gambar jika ada
    const gambarPreview = document.getElementById('gambar_preview');
    if (gambar && gambar !== '') {
        gambarPreview.innerHTML = `<img src="files/${gambar}" alt="Gambar Barang" class="h-48 w-48 object-cover rounded-lg" />`;
    } else {
        gambarPreview.innerHTML = `<div class="h-48 w-48 rounded-lg border-2 border-dashed border-gray-300 bg-gray-100 flex items-center justify-center dark:border-gray-600 dark:bg-gray-800">
        <img class="mx-auto h-full" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg" alt="" />
        </div>`;
    }

    // Set value untuk form update stok
    document.getElementById('id').value = id;
    document.getElementById('stok').value = '';

    // Set form action (jika perlu)
    // console.log('Form action:', '/add-stock/' + id);
    document.getElementById('tambahStockForm').action = '/add-stock/' + id;

    // Show modal
    const modal = document.getElementById('editBarangModal');
    if (modal && typeof modal.showModal === 'function') {
        modal.showModal();
    }
}

window.openEditModal = openEditModal;

// Delegated event handler
$(document).on('click', '.edit-barang-btn', function (e) {
    e.preventDefault();

    // Get data attributes
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
    const gambar = $(this).data('gambar');

    openEditModal(id, status, kode, nama, kategori, stok, satuan, lokasi, harga, deskripsi, gambar);

});
