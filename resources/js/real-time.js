document.addEventListener("DOMContentLoaded", function () {
    const userRole = document.body.dataset.userRole;
    const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
    const soundfile = "/sounds/mixkit-doorbell-tone-2864.wav";

    // Fungsi untuk memperbarui badge di halaman secara dinamis
    function updateBadges(role, uid, data) {
        if (!data) return;

        // --- Peran Sales ---
        if (role === "Sales") {
            const quoteBadge = document.getElementById("quotation-notif-badge");
            if (quoteBadge && data.rejectedQuotationCount !== undefined) {
                quoteBadge.textContent = data.rejectedQuotationCount;
                if (data.rejectedQuotationCount > 0) {
                    quoteBadge.classList.remove("hidden");
                } else {
                    quoteBadge.classList.add("hidden");
                }
            }

            const customQuoteBadge = document.getElementById("custom-quotation-notif-badge");
            if (customQuoteBadge && data.rejectedCustomQuotationCount !== undefined) {
                customQuoteBadge.textContent = data.rejectedCustomQuotationCount;
                if (data.rejectedCustomQuotationCount > 0) {
                    customQuoteBadge.classList.remove("hidden");
                } else {
                    customQuoteBadge.classList.add("hidden");
                }
            }

            const salesModuleBadge = document.getElementById("sales-module-notif-badge");
            if (salesModuleBadge) {
                const qCount = parseInt(document.getElementById("quotation-notif-badge")?.textContent || 0);
                const cqCount = parseInt(document.getElementById("custom-quotation-notif-badge")?.textContent || 0);
                if (qCount + cqCount > 0) {
                    salesModuleBadge.classList.remove("hidden");
                } else {
                    salesModuleBadge.classList.add("hidden");
                }
            }
        }

        // --- Peran Supervisor ---
        if (role === "Supervisor") {
            const pendingQuoteBadge = document.getElementById("pending-quotation-notif-badge");
            if (pendingQuoteBadge && data.pendingSentQuotation !== undefined) {
                pendingQuoteBadge.textContent = data.pendingSentQuotation;
                if (data.pendingSentQuotation > 0) {
                    pendingQuoteBadge.classList.remove("hidden");
                } else {
                    pendingQuoteBadge.classList.add("hidden");
                }
            }

            const pendingCustomQuoteBadge = document.getElementById("pending-custom-quotation-notif-badge");
            if (pendingCustomQuoteBadge && data.pendingCustomQuotation !== undefined) {
                pendingCustomQuoteBadge.textContent = data.pendingCustomQuotation;
                if (data.pendingCustomQuotation > 0) {
                    pendingCustomQuoteBadge.classList.remove("hidden");
                } else {
                    pendingCustomQuoteBadge.classList.add("hidden");
                }
            }

            const approvalBadge = document.getElementById("quotation-approval-notif-badge");
            if (approvalBadge) {
                const pqCount = parseInt(document.getElementById("pending-quotation-notif-badge")?.textContent || 0);
                const pcqCount = parseInt(document.getElementById("pending-custom-quotation-notif-badge")?.textContent || 0);
                if (pqCount + pcqCount > 0) {
                    approvalBadge.classList.remove("hidden");
                } else {
                    approvalBadge.classList.add("hidden");
                }
            }
        }

        // --- Peran General Affair ---
        if (role === "General Affair") {
            const goodsInBadge = document.getElementById("goods-in-notif-badge");
            if (goodsInBadge && data.goodsInProcurementPendingCount !== undefined && data.goodsInProcurementRevisionCount !== undefined) {
                const totalGoodsIn = data.goodsInProcurementPendingCount + data.goodsInProcurementRevisionCount;
                if (totalGoodsIn > 0) {
                    goodsInBadge.classList.remove("hidden");
                } else {
                    goodsInBadge.classList.add("hidden");
                }
            }

            const sidebarProcBadge = document.getElementById("sidebar-procurement-notif-badge");
            if (sidebarProcBadge && data.goodsInProcurementPendingCount !== undefined && data.goodsInProcurementRevisionCount !== undefined) {
                const totalGoodsIn = data.goodsInProcurementPendingCount + data.goodsInProcurementRevisionCount;
                if (totalGoodsIn > 0) {
                    sidebarProcBadge.classList.remove("hidden");
                } else {
                    sidebarProcBadge.classList.add("hidden");
                }
            }

            const boxBadge = document.getElementById("goods-in-procurement-box-badge");
            if (boxBadge && data.goodsInProcurementPendingCount !== undefined && data.goodsInProcurementRevisionCount !== undefined) {
                const totalProc = data.goodsInProcurementPendingCount + data.goodsInProcurementRevisionCount;
                if (totalProc > 0) {
                    boxBadge.classList.remove("hidden");
                } else {
                    boxBadge.classList.add("hidden");
                }
            }
        }

        // --- Peran Warehouse ---
        if (role === "Warehouse") {
            const warehouseSidebarBadge = document.getElementById("warehouse-sidebar-notif-badge");
            if (warehouseSidebarBadge && data.supplyOrderCount !== undefined && data.procOrderCount !== undefined && data.deliveryOrderCount !== undefined) {
                const totalWarehouse = data.supplyOrderCount + data.procOrderCount + data.deliveryOrderCount;
                if (totalWarehouse > 0) {
                    warehouseSidebarBadge.classList.remove("hidden");
                } else {
                    warehouseSidebarBadge.classList.add("hidden");
                }
            }

            const supplyNavBadge = document.getElementById("supply-orders-nav-badge");
            if (supplyNavBadge && data.supplyOrderCount !== undefined && data.procOrderCount !== undefined) {
                const totalSupply = data.supplyOrderCount + data.procOrderCount;
                supplyNavBadge.textContent = totalSupply;
                if (totalSupply > 0) {
                    supplyNavBadge.classList.remove("hidden");
                } else {
                    supplyNavBadge.classList.add("hidden");
                }
            }

            const deliveryNavBadge = document.getElementById("delivery-orders-nav-badge");
            if (deliveryNavBadge && data.deliveryOrderCount !== undefined) {
                deliveryNavBadge.textContent = data.deliveryOrderCount;
                if (data.deliveryOrderCount > 0) {
                    deliveryNavBadge.classList.remove("hidden");
                } else {
                    deliveryNavBadge.classList.add("hidden");
                }
            }
        }
    }

    // Berlangganan ke channel terpadu realtime-notifications
    Echo.channel("realtime-notifications").listen("RealTimeNotification", (e) => {
        console.log("RealTimeNotification received:", e);

        // Selalu perbarui badge lokal di sidebar/tab untuk role pengguna saat ini
        updateBadges(userRole, userId, e.data);

        // Cek apakah user saat ini adalah penerima notifikasi
        const isRecipient = 
            e.recipientRole === "All" ||
            (e.recipientRole === userRole && userRole !== "Sales") ||
            (e.recipientRole === "Sales" && userRole === "Sales" && String(e.salesId) === String(userId));

        // Jika user adalah penerima dan notifikasi memiliki isi (bukan silent refresh)
        if (isRecipient && e.title && e.message) {
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
                title: e.title,
                text: e.message,
                width: "600px",
                customClass: {
                    popup: "rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700",
                },
                didOpen: function () {
                    var audplay = new Audio(soundfile);
                    audplay.play().catch(error => console.log("Audio play error:", error));
                },
            });
        }
    });

    // --- Legacy Listeners ---
    // Tetap pertahankan legacy listeners agar tidak merusak fungsionalitas lama jika ada broadcast manual
    if (userRole === "Warehouse") {
        Echo.channel("orders").listen("OrderStatusUpdated", (e) => {
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
                    audplay.play().catch(error => console.log("Audio play error:", error));
                },
            });

            const notifBadge = document.getElementById("delivery-orders-notif-badge");
            if (notifBadge) {
                notifBadge.textContent = e.barangCount;
                if (e.barangCount > 0) {
                    notifBadge.classList.remove("hidden");
                } else {
                    notifBadge.classList.add("hidden");
                }
            }
        });

        Echo.channel("goods").listen("BarangStatusUpdated", (e) => {
            let title = "Barang Baru!";
            let text = `Ada barang baru yang perlu ditinjau. Total permintaan: ${e.barangCount}`;

            if (e.tipeRequest === "new_stock") {
                title = "Permintaan Stok!";
                text = `Ada permintaan stok baru yang perlu ditinjau. Total permintaan: ${e.barangCount}`;
            }

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
                    audplay.play().catch(error => console.log("Audio play error:", error));
                },
            });

            const supplyBadge = document.getElementById("supply-orders-notif-badge");
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
