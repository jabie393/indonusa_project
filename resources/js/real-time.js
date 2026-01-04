document.addEventListener("DOMContentLoaded", function () {
    const userRole = document.body.dataset.userRole;
    var soundfile = "sounds/mixkit-doorbell-tone-2864.wav";

    if (userRole === "Warehouse") {
        Echo.channel("orders").listen("OrderStatusUpdated", (e) => {
            // Show SweetAlert notification

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });
            Toast.fire({
                icon: "info",
                title: "Order Baru!",
                text: `Ada order baru yang perlu ditinjau. Total orders: ${e.orderCount}`,
                width: "600px",
                customClass: {
                    popup: "rounded-2xl!",
                },
                didOpen: function () {
                    var audplay = new Audio(soundfile);
                    audplay.play();
                },
            });

            // Update badge if it exists
            const notifBadge = document.getElementById(
                "delivery-orders-notif-badge"
            );
            if (notifBadge) {
                notifBadge.textContent = e.orderCount;
                notifBadge.classList.remove("hidden");
            }
        });

        Echo.channel("goods").listen("BarangStatusUpdated", (e) => {
            // Determine title and text based on request type
            let title = "Barang Baru!";
            let text = `Ada barang baru yang perlu ditinjau. Total permintaan: ${e.barangCount}`;

            if (e.tipeRequest === "new_stock") {
                title = "Permintaan Stok!";
                text = `Ada permintaan stok baru yang perlu ditinjau. Total permintaan: ${e.barangCount}`;
            }

            // Show SweetAlert notification
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });
            Toast.fire({
                icon: "info",
                title: title,
                text: text,
                width: "600px",
                customClass: {
                    popup: "rounded-2xl!",
                },
                didOpen: function () {
                    var audplay = new Audio(soundfile);
                    audplay.play();
                },
            });

            // Update badge for supply orders if it exists
            const supplyBadge = document.getElementById(
                "supply-orders-notif-badge"
            );
            if (supplyBadge) {
                supplyBadge.textContent = e.barangCount;
                if (e.barangCount > 0) {
                    supplyBadge.classList.remove("hidden");
                } else {
                    supplyBadge.classList.add("hidden");
                }
            }
        });
    }
});
