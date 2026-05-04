import Swal from "sweetalert2";

window.Swal = Swal;

// Delete alert
window.confirmDelete = function (callback) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Anda tidak akan dapat mengembalikan ini!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus!",
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
        Swal.fire({
            title: window.sweetTitle || "Berhasil!",
            text: window.sweetText || "",
            icon: "success",
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: {
                popup: "rounded-2xl!",
            },
        });
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
