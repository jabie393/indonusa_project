function initImagePreviewOverlay(previewContainerId, labelId) {
    const previewContainer = document.getElementById(previewContainerId);
    const label = document.getElementById(labelId);
    if (previewContainer && label) {
        let filterDiv = label.querySelector(".preview-filter");
        if (!filterDiv) {
            filterDiv = document.createElement("div");
            filterDiv.className =
                "preview-filter absolute left-0 top-0 w-full h-full bg-black/60 pointer-events-none transition-all duration-300 opacity-0";
            label.appendChild(filterDiv);
        }
        const img = label.querySelector("img");
        const svg = label.querySelector("svg");
        const h5 = label.querySelector("h5");
        if (img) {
            img.classList.add(
                "relative",
                "z-20",
                "block",
                "transition-all",
                "duration-300",
                "blur-none",
                "opacity-100"
            );
            img.classList.remove("hidden");
        }
        if (svg) {
            svg.classList.add(
                "absolute",
                "left-1/2",
                "top-1/2",
                "-translate-x-1/2",
                "-translate-y-1/2",
                "z-10",
                "pointer-events-none",
                "transition-all",
                "duration-300",
                "text-white"
            );
            svg.classList.remove("text-gray-700");
        }
        if (h5) {
            h5.classList.add(
                "absolute",
                "left-1/2",
                "top-[70%]",
                "-translate-x-1/2",
                "-translate-y-1/2",
                "z-10",
                "pointer-events-none",
                "text-white",
                "transition-all",
                "duration-300"
            );
            h5.classList.remove("text-gray-700");
        }
        label.classList.add("relative");
        previewContainer.classList.add("relative");
        // On hover, bring SVG/h5/filter in front of image and apply transitions
        label.onmouseenter = function () {
            if (svg) {
                svg.classList.remove("z-10");
                svg.classList.add("z-30");
            }
            if (h5) {
                h5.classList.remove("z-10");
                h5.classList.add("z-30");
            }
            if (img) {
                img.classList.remove("z-20", "opacity-100", "blur-none");
                img.classList.add("z-10", "opacity-50", "blur-md");
            }
            if (filterDiv) {
                filterDiv.classList.remove("opacity-0");
                filterDiv.classList.add("opacity-100");
            }
        };
        label.onmouseleave = function () {
            if (svg) {
                svg.classList.remove("z-30");
                svg.classList.add("z-10");
            }
            if (h5) {
                h5.classList.remove("z-30");
                h5.classList.add("z-10");
            }
            if (img) {
                img.classList.remove("z-10", "opacity-50", "blur-md");
                img.classList.add("z-20", "opacity-100", "blur-none");
            }
            if (filterDiv) {
                filterDiv.classList.remove("opacity-100");
                filterDiv.classList.add("opacity-0");
            }
        };
    }
}

// Gabungkan fungsi openEditModal
function openEditModal(params) {
    const {
        id,
        status_listing,
        kode_barang,
        nama_barang,
        kategori,
        stok,
        satuan,
        lokasi,
        harga,
        selling_price,
        deskripsi = "",
        tipe_request = "primary",
        gambar = null,
    } = params;

    if (tipe_request === "primary") {
        document.getElementById("edit_id").value = id;
        document.getElementById("edit_status_listing").value = status_listing;
        document.getElementById("edit_goods_code").value = kode_barang;
        document.getElementById("edit_goods_name").value = nama_barang;
        document.getElementById("edit_category").value = kategori;
        document.getElementById("edit_stock").value = stok;
        document.getElementById("edit_unit").value = satuan;
        document.getElementById("edit_location").value = lokasi;
        document.getElementById("edit_buy_price").value = harga;
        document.getElementById("edit_selling_price").value = selling_price || "";
        document.getElementById("edit_deskripsi").value = deskripsi;

        // Preview gambar jika ada
        const imageDisplay = document.getElementById("edit_image_display");
        const placeholder = document.getElementById("edit_upload_placeholder");
        const overlay = document.getElementById("edit_image_overlay");

        if (imageDisplay && placeholder && overlay) {
            if (gambar && gambar !== "") {
                imageDisplay.src = `files/${gambar}`;
                imageDisplay.classList.remove("hidden");
                placeholder.classList.add("hidden");
                overlay.classList.remove("hidden");
            } else {
                imageDisplay.src = "";
                imageDisplay.classList.add("hidden");
                placeholder.classList.remove("hidden");
                overlay.classList.add("hidden");
            }
        }

        document.getElementById("editBarangForm").action =
            "/goods-in-status/" + id;
        const modal = document.getElementById("editBarangModalPrimary");
        if (modal?.showModal) modal.showModal();
    } else if (tipe_request === "new_stock") {
        document.getElementById("edit_goods_code_new_stock").textContent =
            kode_barang ?? "-";
        document.getElementById("edit_goods_name_new_stock").textContent =
            nama_barang ?? "-";
        document.getElementById("edit_category_new_stock").textContent =
            kategori ?? "-";
        document.getElementById("edit_location_new_stock").textContent =
            lokasi ?? "-";
        document.getElementById("edit_status_listing_new_stock").textContent =
            status_listing ?? "-";
        document.getElementById("edit_buy_price_new_stock").textContent = 
            harga && !isNaN(harga) ? `Rp ${parseInt(harga).toLocaleString('en-US')}` : (harga ?? "-");
        document.getElementById("edit_unit_new_stock").textContent =
            satuan ?? "-";
        document.getElementById("current_stock_new_stock").textContent =
            "Memeriksa...";
        document.getElementById("edit_description_new_stock").textContent =
            deskripsi ?? "-";
        const gambarPreview = document.getElementById(
            "edit_gambar_preview_new_stock"
        );
        if (gambar && gambar !== "") {
            gambarPreview.innerHTML = `<img src="files/${gambar}" alt="Gambar Barang" class="h-full w-full object-contain" />`;
        } else {
            gambarPreview.innerHTML = `<div class="flex h-full w-full items-center justify-center text-slate-300">
                <svg class="h-24 w-24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    <circle cx="9" cy="9" r="2"></circle>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                </svg>
            </div>`;
        }
        document.getElementById("edit_id_new_stock").value = id;
        document.getElementById("edit_stock_new_stock").value =
            stok && stok !== "-" ? stok : "";
        document.getElementById("edit_buy_price_input_new_stock").value =
            harga && harga !== "-" ? harga : "";

        // Ambil kode barang asli (tanpa # dan ekor angka)
        const kodeBarangInduk = (kode_barang ?? "").split("#")[0];

        // Ambil stok asli dari database melalui route Laravel
        fetch("/get-stock/" + kodeBarangInduk)
            .then((res) => res.json())
            .then((data) => {
                document.getElementById("current_stock_new_stock").textContent =
                    data.stock ?? "-";
            })
            .catch(() => {
                document.getElementById("current_stock_new_stock").textContent =
                    "-";
            });

        document.getElementById("tambahStockForm").action =
            "/goods-in-status/" + id;

        const modal = document.getElementById("editBarangModalNewStock");
        if (modal?.showModal) modal.showModal();
    }
}

window.openEditModal = openEditModal;

// Delegated event handler
$(document).on("click", ".edit-barang-btn", function (e) {
    e.preventDefault();

    // Ambil semua data
    const params = {
        id: $(this).data("id"),
        status_listing: $(this).data("status"),
        kode_barang: $(this).data("kode"),
        nama_barang: $(this).data("nama"),
        kategori: $(this).data("kategori"),
        stok: $(this).data("stok"),
        satuan: $(this).data("satuan"),
        lokasi: $(this).data("lokasi"),
        harga: $(this).data("harga"),
        selling_price: $(this).data("harga_jual"),
        deskripsi: $(this).data("deskripsi"),
        tipe_request: $(this).data("tipe_request"),
        gambar: $(this).data("gambar"),
    };

    openEditModal(params);
});

// Handler untuk tombol Note
$(document).on("click", ".note-btn", function (e) {
    e.preventDefault();
    const catatan = $(this).data("catatan") || "Tidak ada catatan";
    $("#catatanContent").text(catatan);

    const noteModal = document.getElementById("noteModal");
    if (noteModal && typeof noteModal.showModal === "function") {
        noteModal.showModal();
    }
});

// Handler untuk tombol tutup modal Note
$(document).on("click", "#closeNoteModal", function (e) {
    e.preventDefault();
    const noteModal = document.getElementById("noteModal");
    if (noteModal && typeof noteModal.close === "function") {
        noteModal.close();
    }
});

// Handler untuk input gambar (Preview)
$(document).on("change", "#edit_gambar", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#edit_image_display")
                .attr("src", e.target.result)
                .removeClass("hidden");
            $("#edit_upload_placeholder").addClass("hidden");
            $("#edit_image_overlay").removeClass("hidden");
        };
        reader.readAsDataURL(file);
    }
});
