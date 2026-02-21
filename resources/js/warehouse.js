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
                "opacity-100",
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
                "text-white",
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
                "duration-300",
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
    gambar = null,
) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_kode_barang").value = kode_barang;
    document.getElementById("edit_nama_barang").value = nama_barang;
    document.getElementById("edit_stok").value = stok;
    document.getElementById("edit_harga_tampil").value = harga;
    document.getElementById("edit_deskripsi").value = deskripsi;

    // Clear proposed fields
    const proposedPrice = document.getElementById("harga_diajukan");
    const proposedReason = document.getElementById("alasan_pengajuan");
    const proposedStock = document.getElementById("stok_diajukan");

    if (proposedPrice) proposedPrice.value = "";
    if (proposedReason) proposedReason.value = "";
    if (proposedStock) {
        proposedStock.value = "1";
        proposedStock.max = stok; // Set max based on current stock
    }

    // Preview gambar jika ada
    const gambarPreview = document.getElementById("edit_gambar_preview");
    if (gambar && gambar !== "") {
        gambarPreview.innerHTML = `
            <img src="files/${gambar}" alt="Gambar Barang" class="mx-auto relative z-2 h-full object-cover rounded-lg" />
        `;
    } else {
        gambarPreview.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full w-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto mb-4 h-8 w-8 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l1.259 1.259m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <p class="text-xs text-gray-400">Tidak ada gambar</p>
            </div>`;
    }

    // Set form action
    document.getElementById("editBarangForm").action = "/warehouse/" + id;
    // Show modal (direct)
    const modal = document.getElementById("editBarangModal");
    if (modal && typeof modal.showModal === "function") {
        modal.showModal();
    }
}

window.openEditModal = openEditModal;

// Use delegated event handler for dynamically loaded content
$(document).on("click", ".edit-barang-btn", function (e) {
    e.preventDefault();

    // Get data attributes from the clicked element
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

    // Call your modal function
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
        gambar,
    );
});

// Wait until the file input is changed
document.getElementById("edit_gambar").onchange = function () {
    const reader = new FileReader();
    reader.onload = function () {
        // Update the preview image src
        const previewImg = document.querySelector("#edit_gambar_preview img");
        if (previewImg) {
            previewImg.src = reader.result;
        }
        // Optionally set a hidden input for the modified image
        const hiddenInput = document.getElementById("modified_image");
        if (hiddenInput) {
            hiddenInput.value = reader.result;
        }
        // Re-init overlay and hover for new image
        initImagePreviewOverlay("edit_gambar_preview", "edit_gambar_label");
    };
    reader.readAsDataURL(this.files[0]);
};

document.getElementById("gambar").onchange = function () {
    const reader = new FileReader();
    reader.onload = function () {
        // Update the preview image src
        const previewImg = document.querySelector("#gambar_preview img");
        if (previewImg) {
            previewImg.src = reader.result;
        }
        // Optionally set a hidden input for the modified image
        const hiddenInput = document.getElementById("modified_image");
        if (hiddenInput) {
            hiddenInput.value = reader.result;
        }
        // Re-init overlay and hover for new image
        initImagePreviewOverlay("gambar_preview", "gambar_label");
    };
    reader.readAsDataURL(this.files[0]);
};
