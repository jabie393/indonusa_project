


// Delete alert
window.confirmDelete = function (callback) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Anda tidak akan dapat mengembalikan ini!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#225A97",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        customClass: {
            popup: "rounded-2xl!",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

// Cancel alert for defect request
window.confirmCancel = function (callback) {
    Swal.fire({
        title: "Batalkan Pengajuan?",
        text: "Stok akan dikembalikan ke barang utama dan data pengajuan ini akan dihapus.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, batalkan!",
        cancelButtonText: "Tidak",
        customClass: {
            popup: "rounded-2xl!",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

// Approve alert
window.confirmApprove = function (callback, text = "Apakah Anda yakin ingin menyetujui data ini?", confirmButtonText = "Ya, Setujui") {
    Swal.fire({
        title: "Konfirmasi",
        text: text,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#15803d",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmButtonText,
        cancelButtonText: "Batal",
        customClass: {
            popup: "rounded-2xl!",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

// Global listener for approve-form
document.addEventListener("submit", function (e) {
    const target = e.target;
    if (target && target.classList.contains("approve-form")) {
        e.preventDefault();
        const text = target.getAttribute("data-confirm-text") || "Apakah Anda yakin ingin menyetujui data ini?";
        const btnText = target.getAttribute("data-confirm-button-text") || "Ya, Setujui";
        window.confirmApprove(() => {
            target.submit();
        }, text, btnText);
    }
});

// Success alert
document.addEventListener("DOMContentLoaded", function () {
    if (window.sweetTitle || window.sweetText) {
        const titleLower = (window.sweetTitle || "").toLowerCase();
        const isError = titleLower.includes("gagal") || titleLower.includes("error") || titleLower.includes("failed");

        Swal.fire({
            title: window.sweetTitle || (isError ? "Gagal!" : "Berhasil!"),
            text: window.sweetText || "",
            icon: isError ? "error" : "success",
            showConfirmButton: isError,
            confirmButtonColor: isError ? "#d33" : undefined,
            timer: isError ? undefined : 3500,
            timerProgressBar: !isError,
            customClass: {
                popup: "rounded-2xl!",
            },
        }).then(() => {
            if (window.sweetCallback && typeof window.sweetCallback === 'function') {
                window.sweetCallback();
                window.sweetCallback = null;
            }
        });
        window.sweetTitle = null;
        window.sweetText = null;
    }

    // Error alert
    if (window.errorTitle || window.errorText) {
        Swal.fire({
            title: window.errorTitle || "Error",
            text: window.errorText || "Terjadi kesalahan.",
            icon: "error",
            showConfirmButton: true,
            confirmButtonColor: "#d33",
            customClass: {
                popup: "rounded-2xl!",
            },
        });
    }
});
