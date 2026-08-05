


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
            popup: "rounded-2xl dark:bg-gray-800 dark:text-white dark:border dark:border-gray-700",
            title: "dark:text-white",
            htmlContainer: "dark:text-gray-300",
        },
        target: document.querySelector('dialog[open]') || 'body',
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

// Force Complete alert
window.confirmForceComplete = function (callback) {
    Swal.fire({
        title: "Paksa Selesai?",
        text: "Sisa barang yang belum diterima akan dianggap batal, dan status Custom Quotation akan diubah ke Ready for Delivery.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#225A97",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Paksa Selesai!",
        cancelButtonText: "Batal",
        customClass: {
            popup: "rounded-2xl dark:bg-gray-800 dark:text-white dark:border dark:border-gray-700",
            title: "dark:text-white",
            htmlContainer: "dark:text-gray-300",
        },
        target: document.querySelector('dialog[open]') || 'body',
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
        confirmButtonColor: "#225A97",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, batalkan!",
        cancelButtonText: "Tidak",
        customClass: {
            popup: "rounded-2xl dark:bg-gray-800 dark:text-white dark:border dark:border-gray-700",
            title: "dark:text-white",
            htmlContainer: "dark:text-gray-300",
        },
        target: document.querySelector('dialog[open]') || 'body',
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
        confirmButtonColor: "#225A97",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmButtonText,
        cancelButtonText: "Batal",
        customClass: {
            popup: "rounded-2xl dark:bg-gray-800 dark:text-white dark:border dark:border-gray-700",
            title: "dark:text-white",
            htmlContainer: "dark:text-gray-300",
        },
        target: document.querySelector('dialog[open]') || 'body',
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
    setTimeout(() => {
        if (window.sweetTitle || window.sweetText) {
            const titleLower = (window.sweetTitle || "").toLowerCase();
            const isError = titleLower.includes("gagal") || titleLower.includes("error") || titleLower.includes("failed");

            Swal.fire({
                title: window.sweetTitle || (isError ? "Gagal!" : "Berhasil!"),
                text: window.sweetText || "",
                icon: isError ? "error" : "success",
                showConfirmButton: isError || !!window.sweetShowConfirmButton,
                confirmButtonColor: isError ? "#d33" : "#225A97",
                confirmButtonText: "OK",
                timer: (isError || !!window.sweetShowConfirmButton) ? undefined : 3500,
                timerProgressBar: !(isError || !!window.sweetShowConfirmButton),
                customClass: {
                    popup: "rounded-2xl dark:bg-gray-800 dark:text-white dark:border dark:border-gray-700",
                    title: "dark:text-white",
                    htmlContainer: "dark:text-gray-300",
                },
                target: document.querySelector('dialog[open]') || 'body',
            }).then(() => {
                if (window.sweetCallback && typeof window.sweetCallback === 'function') {
                    window.sweetCallback();
                    window.sweetCallback = null;
                }
            });
            window.sweetTitle = null;
            window.sweetText = null;
            window.sweetShowConfirmButton = null;
        }

        // Error alert
        if (window.errorTitle || window.errorText) {
            const urlParams = new URLSearchParams(window.location.search);
            const isModalAutoOpen = urlParams.has('open_show') || urlParams.has('open_create');

            if (!isModalAutoOpen) {
                Swal.fire({
                    title: window.errorTitle || "Error",
                    text: window.errorText || "Terjadi kesalahan.",
                    icon: "error",
                    showConfirmButton: true,
                    confirmButtonColor: "#d33",
                    customClass: {
                        popup: "rounded-2xl dark:bg-gray-800 dark:text-white dark:border dark:border-gray-700",
                        title: "dark:text-white",
                        htmlContainer: "dark:text-gray-300",
                    },
                    target: document.querySelector('dialog[open]') || 'body',
                });
            }
            window.errorTitle = null;
            window.errorText = null;
        }
    }, 100);
});
