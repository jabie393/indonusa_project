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
    gambar = null
) {
    const editIdEl = document.getElementById("edit_id");
    if (editIdEl) editIdEl.value = id;

    const editKodeEl = document.getElementById("edit_kode_barang");
    if (editKodeEl) editKodeEl.value = kode_barang;

    const editNamaEl = document.getElementById("edit_nama_barang");
    if (editNamaEl) editNamaEl.value = nama_barang;

    const editStokEl = document.getElementById("edit_stok");
    if (editStokEl) editStokEl.value = stok;

    const editHargaEl = document.getElementById("edit_harga_tampil");
    if (editHargaEl) editHargaEl.value = harga;

    const editDeskripsiEl = document.getElementById("edit_deskripsi");
    if (editDeskripsiEl) editDeskripsiEl.value = deskripsi;

    // Update modal title
    const modalTitle = document.getElementById("editBarangModalTitle");
    if (modalTitle) {
        modalTitle.innerText = "Edit Barang";
    }

    // Preview gambar jika ada
    const editImagePreview = document.getElementById("edit_image-preview");
    const editUploadPlaceholder = document.getElementById("edit_upload-placeholder");
    if (editImagePreview && editUploadPlaceholder) {
        if (gambar && gambar !== "") {
            editImagePreview.src = "files/" + gambar;
            editImagePreview.classList.remove('hidden');
            editUploadPlaceholder.classList.add('hidden');
        } else {
            editImagePreview.src = "";
            editImagePreview.classList.add('hidden');
            editUploadPlaceholder.classList.remove('hidden');
        }
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
        gambar
    );
});

// Wait until the file input is changed
const editGambar = document.getElementById("edit_gambar");
if (editGambar) {
    editGambar.onchange = function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function () {
                const previewImg = document.getElementById("edit_image-preview");
                const placeholder = document.getElementById("edit_upload-placeholder");
                if (previewImg) {
                    previewImg.src = reader.result;
                    previewImg.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    };
}

if (document.getElementById("gambar")) {
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
}

// History Logs (Moved from goods-receipts.js)
function formatRupiah(number) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(number);
}

function formatDate(dateString) {
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
}

function fetchLogs(id, nama, kode) {
    const modal = document.getElementById("historyModal");
    const tableBody = document.getElementById("history_log_body");
    const loadingSpinner = document.getElementById("loading_spinner");
    const noHistoryMessage = document.getElementById("no_history_message");

    // Set Modal Header
    document.getElementById("modal_nama_barang").textContent = nama;
    document.getElementById("modal_kode_barang").textContent = kode;

    // Reset Modal State
    tableBody.innerHTML = "";
    loadingSpinner.classList.remove("hidden");
    noHistoryMessage.classList.add("hidden");

    // Open Modal
    if (modal && typeof modal.showModal === "function") {
        modal.showModal();
    }

    // Fetch Data
    fetch(`/warehouse/${id}/logs`)
        .then((response) => response.json())
        .then((data) => {
            loadingSpinner.classList.add("hidden");

            if (data.length === 0) {
                noHistoryMessage.classList.remove("hidden");
            } else {
                data.forEach((log) => {
                    const row = document.createElement("tr");
                    row.className = "border-b dark:border-gray-700";
                    row.innerHTML = `
                        <td class="px-4 py-3">${formatDate(
                            log.received_at
                        )}</td>
                        <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">${
                            log.quantity
                        }</td>
                        <td class="px-4 py-3 text-right font-semibold text-blue-600 dark:text-blue-400">${formatRupiah(
                            log.unit_cost
                        )}</td>
                        <td class="px-4 py-3">${
                            log.supplier ? log.supplier.name : "-"
                        }</td>
                        <td class="px-4 py-3 text-xs text-gray-500">${
                            log.approver ? log.approver.name : "-"
                        }</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        })
        .catch((error) => {
            console.error("Error fetching logs:", error);
            loadingSpinner.classList.add("hidden");
            alert("Gagal mengambil data riwayat.");
        });
}

// Event listener for view logs button
$(document).on("click", ".view-history-btn", function (e) {
    e.preventDefault();
    const id = $(this).data("id");
    const nama = $(this).data("nama");
    const kode = $(this).data("kode");
    fetchLogs(id, nama, kode);
});

