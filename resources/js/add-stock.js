
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
        gambarPreview.innerHTML = `
        <div class="h-48 w-48 rounded-lg border-2 border-dashed border-gray-300 bg-gray-100 flex items-center justify-center dark:border-gray-600 dark:bg-gray-800">
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
