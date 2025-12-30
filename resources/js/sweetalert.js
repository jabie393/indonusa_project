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
        // Do nothing if cancelled
    });
};

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
