function openEditModal(
    id,
    status_listing,
    kode_barang,
    nama_barang,
    kategori,
    stok,
    satuan,
    lokasi,
    harga,
    deskripsi = "",
    gambar = null
) {
    // Set data ke elemen modal (bukan input)
    document.getElementById("goods_code").textContent = kode_barang ?? "-";
    document.getElementById("goods_name").textContent = nama_barang ?? "-";
    document.getElementById("category").textContent = kategori ?? "-";
    document.getElementById("location").textContent = lokasi ?? "-";
    document.getElementById("status_listing").textContent =
        status_listing ?? "-";
    document.getElementById("buy_price").textContent = 
        harga && !isNaN(harga) ? `Rp ${parseInt(harga).toLocaleString('en-US')}` : (harga ?? "-");
    document.getElementById("unit").textContent = satuan ?? "-";
    document.getElementById("current_stock").textContent = stok ?? "-";
    document.getElementById("description").textContent = deskripsi ?? "-";

    // Preview gambar jika ada
    const gambarPreview = document.getElementById("gambar_preview");
    if (gambar && gambar !== "") {
        gambarPreview.innerHTML = `<img src="files/${gambar}" alt="Gambar Barang" class="h-full w-full object-contain" />`;
    } else {
        gambarPreview.innerHTML = `
        <div class="flex h-full w-full items-center justify-center text-slate-300">
            <svg class="h-24 w-24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                <circle cx="9" cy="9" r="2"></circle>
                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
            </svg>
        </div>`;
    }

    // Set value untuk form update stok
    document.getElementById("id").value = id;
    document.getElementById("stock").value = "";
    document.getElementById("unit_cost").value = "";

    // Set form action (jika perlu)
    // console.log('Form action:', '/add-stock/' + id);
    document.getElementById("tambahStockForm").action = "/add-stock/" + id;

    // Show modal
    const modal = document.getElementById("editBarangModal");
    if (modal && typeof modal.showModal === "function") {
        modal.showModal();
    }
}

window.openEditModal = openEditModal;

// Delegated event handler
$(document).on("click", ".edit-barang-btn", function (e) {
    e.preventDefault();

    // Get data attributes
    const id = $(this).data("id");
    const status = $(this).data("status");
    const kode = $(this).data("kode");
    const nama = $(this).data("nama");
    const kategori = $(this).data("kategori");
    const stok = $(this).data("stok");
    const satuan = $(this).data("satuan");
    const lokasi = $(this).data("lokasi");
    const harga = $(this).data("harga");
    const deskripsi = $(this).data("deskripsi");
    const gambar = $(this).data("gambar");

    openEditModal(
        id,
        status,
        kode,
        nama,
        kategori,
        stok,
        satuan,
        lokasi,
        harga,
        deskripsi,
        gambar
    );
});
