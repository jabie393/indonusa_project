// ...existing code...

function openEditModal(id, status_listing, kode_barang, nama_barang, kategori, stok, satuan, lokasi, harga, deskripsi = '', gambar = null) {
    // Set data ke elemen modal (bukan input)
    document.getElementById('edit_kode_barang').textContent = kode_barang ?? '-';
    document.getElementById('edit_nama_barang').textContent = nama_barang ?? '-';
    document.getElementById('edit_kategori').textContent = kategori ?? '-';
    document.getElementById('edit_lokasi').textContent = lokasi ?? '-';
    document.getElementById('edit_status_listing').textContent = status_listing ?? '-';
    document.getElementById('edit_harga').textContent = harga ?? '-';
    document.getElementById('edit_satuan').textContent = satuan ?? '-';
    document.getElementById('current_stok').textContent = stok ?? '-';
    document.getElementById('edit_deskripsi').textContent = deskripsi ?? '-';

    // Preview gambar jika ada
    const gambarPreview = document.getElementById('edit_gambar_preview');
    if (gambar && gambar !== '') {
        gambarPreview.innerHTML = `<img src="/storage/${gambar}" alt="Gambar Barang" class="h-48 w-48 object-cover rounded-lg" />`;
    } else {
        gambarPreview.innerHTML = `<div class="h-48 w-48 rounded-lg border-2 border-dashed border-gray-300 bg-gray-100 flex items-center justify-center dark:border-gray-600 dark:bg-gray-800">
            <span class="text-gray-500 dark:text-gray-400">Gambar tidak tersedia</span>
        </div>`;
    }

    // Set value untuk form update stok
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_stok').value = '';

    // Set form action (jika perlu)
    console.log('Form action:', '/add-stock/' + id);
    document.getElementById('tambahStockForm').action = '/add-stock/' + id;

    // Show modal (Flowbite)
    window.dispatchEvent(new CustomEvent('open-modal', {
        detail: 'tambahStockModal'
    }));
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
