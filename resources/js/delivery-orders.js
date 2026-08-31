// Attach handlers after DOM ready
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".js-show-order");
    const modal = document.getElementById("delivery-order-modal");
    const orderNumberEl = document.getElementById("delivery-order-number");
    const tbody = document.getElementById("delivery-order-items-body");
    const closeBtn = document.getElementById("delivery-order-close");
    const closeTopBtn = document.getElementById("delivery-order-close-top");

    function clearTbody() {
        while (tbody.firstChild) tbody.removeChild(tbody.firstChild);
    }

    function closeModal() {
        if (!modal) return;
        try {
            if (typeof modal.close === "function") modal.close();
            else modal.style.display = "none";
        } catch (e) {
            modal.style.display = "none";
        }
    }

    function openModal() {
        if (!modal) return;
        try {
            if (typeof modal.showModal === "function") modal.showModal();
            else modal.style.display = "block";
        } catch (e) {
            // fallback
            modal.style.display = "block";
        }
    }

    function renderRows(items) {
        clearTbody();
        if (!Array.isArray(items) || items.length === 0) {
            const tr = document.createElement("tr");
            const td = document.createElement("td");
            td.setAttribute("colspan", "6");
            td.className = "px-4 py-2 text-sm text-gray-900 dark:text-white";
            td.textContent = "Tidak ada item pada order ini.";
            tr.appendChild(td);
            tbody.appendChild(tr);
            return;
        }

        items.forEach(function (item) {
            const tr = document.createElement("tr");

            // prefer goods_code coming from related barang, then item.goods_code, then fallback to barang_id
            const kodeBarang =
                (item.barang &&
                    (item.barang.goods_code ?? item.barang.kode)) ??
                item.goods_code ??
                item.barang_id ??
                "-";
            const namaBarang =
                (item.barang &&
                    (item.barang.nama ||
                        item.barang.name ||
                        item.barang.goods_name)) ||
                item.goods_name ||
                "-";
            const deskripsi =
                (item.barang && item.barang.description) ||
                item.goods_description ||
                item.description ||
                "-";

            const qty = item.quantity ?? "-";
            const delivered = item.delivered_quantity ?? "-";
            let status = item.status_item ?? item.status ?? "-";
            if (status === "partially_delivered" || status === "partial") {
                status = "Partially Delivered";
            } else if (status === "cancel") {
                status = "Cancelled";
            } else if (status === "delivered") {
                status = "Delivered";
            } else if (status === "pending") {
                status = "Pending";
            }

            const cells = [kodeBarang, namaBarang, deskripsi, qty, delivered, status];

            cells.forEach(function (val, idx) {
                const td = document.createElement("td");
                td.className =
                    "px-4 py-3 text-sm text-gray-700 dark:text-gray-300" +
                    (idx !== 2 ? " whitespace-nowrap" : "");
                td.textContent = val;
                tr.appendChild(td);
            });

            tbody.appendChild(tr);
        });
    }

    buttons.forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            const orderId = btn.getAttribute("data-order-id");
            const orderNumber =
                btn.getAttribute("data-order-number") ||
                btn.dataset.orderNumber ||
                "#-";
            if (orderNumberEl) orderNumberEl.textContent = orderNumber;

            const reason = btn.getAttribute("data-reason");
            const reasonContainer = document.getElementById(
                "delivery-order-reason-container",
            );
            const reasonText = document.getElementById(
                "delivery-order-reason-text",
            );

            if (reason && reason !== "null" && reason.trim() !== "") {
                if (reasonContainer) reasonContainer.classList.remove("hidden");
                if (reasonText) reasonText.textContent = reason;
            } else {
                if (reasonContainer) reasonContainer.classList.add("hidden");
            }

            // Show loading state
            clearTbody();
            const trLoading = document.createElement("tr");
            const tdLoading = document.createElement("td");
            tdLoading.setAttribute("colspan", "6");
            tdLoading.className =
                "px-4 py-2 text-center text-sm text-gray-500 dark:text-gray-400";
            tdLoading.textContent = "Loading items...";
            trLoading.appendChild(tdLoading);
            tbody.appendChild(trLoading);

            openModal();

            // Fetch latest items
            fetch(`/delivery-orders/${orderId}/items`)
                .then((res) => res.json())
                .then((items) => {
                    renderRows(items);
                })
                .catch((err) => {
                    console.error("Failed to fetch order items", err);
                    // Fallback to data-items if AJAX fails
                    const itemsAttr = btn.getAttribute("data-items");
                    let items = [];
                    if (itemsAttr) {
                        try {
                            items = JSON.parse(itemsAttr);
                        } catch (parseErr) {
                            const txt = document.createElement("textarea");
                            txt.innerHTML = itemsAttr;
                            items = JSON.parse(txt.value);
                        }
                    }
                    renderRows(items);
                });
        });
    });

    if (closeBtn) closeBtn.addEventListener("click", closeModal);
    if (closeTopBtn) closeTopBtn.addEventListener("click", closeModal);

    // --- Logic for Approval Selection Modal ---
    const approveButtons = document.querySelectorAll(".js-approve-order");
    const approveModal = document.getElementById(
        "delivery-orders-approve-modal",
    );
    const approveOrderNumberEl = document.getElementById(
        "approve-order-number",
    );
    const fullDeliveryForm = document.getElementById("full-delivery-form");
    const approveModalCloseBtn = document.querySelector(
        ".js-approve-modal-close",
    );

    // New DOM elements for partial delivery
    const selectionView = document.getElementById("selection-view");
    const partialView = document.getElementById("partial-view");
    const btnPartialDelivery = document.getElementById("btn-partial-delivery");
    const btnBackToSelection = document.getElementById("btn-back-to-selection");
    const partialItemsBody = document.getElementById("partial-items-body");
    const partialOrderNumberEl = document.getElementById(
        "partial-order-number",
    );
    const partialDeliveryForm = document.getElementById(
        "partial-delivery-form",
    );

    let currentOrderId = null;

    function toggleView(view) {
        const modalBox = approveModal?.querySelector(".modal-box");
        if (view === "partial") {
            selectionView?.classList.add("hidden");
            partialView?.classList.remove("hidden");
            partialView?.classList.add("flex");
            if (modalBox) {
                modalBox.classList.remove("max-w-lg");
                modalBox.classList.add("max-w-5xl", "h-full", "sm:max-h-[90vh]");
            }
        } else {
            selectionView?.classList.remove("hidden");
            partialView?.classList.add("hidden");
            partialView?.classList.remove("flex");
            if (modalBox) {
                modalBox.classList.remove("max-w-5xl", "h-full", "sm:max-h-[90vh]");
                modalBox.classList.add("max-w-lg");
            }
        }
    }

    function renderPartialItems(items) {
        if (!partialItemsBody) return;
        partialItemsBody.innerHTML = "";

        let totalAllocated = 0;

        items.forEach((item) => {
            const qtySisa = Math.max(
                0,
                item.qty_pesanan - (item.qty_teririm || item.qty_terkirim || 0),
            );
            const allocated = item.allocated_quantity || 0;
            totalAllocated += allocated;
            const maxDeliverable = allocated;
            const defaultValue = allocated;

            const isOutOfStock = qtySisa > item.stok_gudang;
            const stockColor = isOutOfStock
                ? "text-red-600 font-bold"
                : "text-green-600";

            // Skip if already fully delivered
            if (qtySisa <= 0) return;

            const tr = document.createElement("tr");
            tr.className = "dark:hover:bg-gray-700/50";

            tr.innerHTML = `
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${
                        item.goods_name
                    }</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">${
                        item.goods_code
                    }</div>
                </td>
                <td class="px-4 py-3 text-left text-sm text-gray-700 dark:text-gray-300">
                    ${item.goods_description || "-"}
                </td>
                <td class="px-4 py-3 text-center align-middle">
                    <div class="inline-flex items-baseline justify-center gap-1 text-gray-900 dark:text-white">
                        <span class="text-sm font-bold font-mono">${item.qty_pesanan}</span>
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400">${item.satuan}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-center align-middle">
                    <div class="flex flex-col items-center justify-center gap-1">
                        ${
                            (item.qty_terkirim || 0) > 0
                                ? `<span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    Terkirim: <span class="font-bold">${item.qty_terkirim}</span>
                                </span>`
                                : ""
                        }
                        ${
                            allocated > 0
                                ? `<span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/40">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Siap Kirim: <span class="font-bold">${allocated}</span>
                                </span>`
                                : `<span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Siap Kirim: <span class="font-bold">0</span>
                                </span>`
                        }
                        ${
                            item.shortage_quantity > 0
                                ? `<span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/40">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    Shortage: <span class="font-bold">${item.shortage_quantity}</span>
                                </span>`
                                : ""
                        }
                    </div>
                </td>
                <td class="px-4 py-3 text-center align-middle text-sm ${stockColor}">
                    ${item.stok_gudang} ${item.satuan}
                </td>
                <td class="px-4 py-3 text-center align-middle">
                    <input type="number" 
                        name="items[${item.id}]" 
                        value="${defaultValue}"
                        max="${maxDeliverable}"
                        min="0"
                        class="qty-input bg-white text-black block w-full rounded-md border-gray-300 py-1 text-center text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        data-stok="${item.stok_gudang}"
                        data-allocated="${allocated}"
                        ${allocated === 0 ? 'disabled' : 'required'}
                    >
                    ${
                        allocated === 0
                            ? '<div class="mt-1 text-[10px] text-amber-500 font-medium leading-tight">0 Siap Kirim</div>'
                            : ""
                    }
                </td>
            `;
            partialItemsBody.appendChild(tr);
        });

        const warningNoStock = document.getElementById("partial-warning-no-stock");
        const btnSubmit = document.getElementById("btn-submit-partial");
        if (totalAllocated === 0) {
            if (warningNoStock) {
                warningNoStock.classList.remove("hidden");
                warningNoStock.classList.add("flex");
            }
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.classList.add("opacity-50", "cursor-not-allowed", "pointer-events-none");
                btnSubmit.title = "Tidak ada stok yang dialokasikan untuk dikirim saat ini";
            }
        } else {
            if (warningNoStock) {
                warningNoStock.classList.add("hidden");
                warningNoStock.classList.remove("flex");
            }
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove("opacity-50", "cursor-not-allowed", "pointer-events-none");
                btnSubmit.removeAttribute("title");
            }
        }
    }

    function closeApproveModal() {
        if (!approveModal) return;
        try {
            if (typeof approveModal.close === "function") approveModal.close();
            else approveModal.style.display = "none";
        } catch (e) {
            approveModal.style.display = "none";
        }
    }

    approveButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const orderId = btn.getAttribute("data-id");
            const orderNumber = btn.getAttribute("data-order-number");
            const approveUrl = btn.getAttribute("data-approve-url");
            const deliveryOptions = btn.getAttribute("data-delivery-options");
            const status = btn.getAttribute("data-status");
            const hasEnoughStock = btn.getAttribute("data-has-enough-stock") !== 'false';
            const totalRemainingQty = parseInt(btn.getAttribute("data-total-remaining-qty") || "0");
            const remainingItemsCount = parseInt(btn.getAttribute("data-remaining-items-count") || "0");

            currentOrderId = orderId;
            if (approveOrderNumberEl)
                approveOrderNumberEl.textContent = orderNumber;
            if (partialOrderNumberEl)
                partialOrderNumberEl.textContent = orderNumber;
            const approveHeaderEl = document.getElementById("approve-order-number-header");
            if (approveHeaderEl)
                approveHeaderEl.textContent = orderNumber;

            if (fullDeliveryForm) {
                fullDeliveryForm.setAttribute("action", approveUrl);
                // Hide Full Delivery option if order is already partial or stock is insufficient
                if (deliveryOptions === "partial" || !hasEnoughStock || status === "not_completed" || status === "under_procurement") {
                    fullDeliveryForm.classList.add("hidden");
                } else {
                    fullDeliveryForm.classList.remove("hidden");
                }
            }
            if (partialDeliveryForm) {
                // Construct partial approve URL from approveUrl or set it directly
                const partialUrl = approveUrl.replace(
                    "/approve",
                    "/partial-approve",
                );
                partialDeliveryForm.setAttribute("action", partialUrl);
            }

            // Hide Partial Delivery option if total remaining quantity is 1 or less (can't be split)
            if (btnPartialDelivery) {
                if (totalRemainingQty <= 1) {
                    btnPartialDelivery.classList.add("hidden");
                } else {
                    btnPartialDelivery.classList.remove("hidden");
                }
            }

            // Open modal
            if (approveModal) {
                try {
                    if (typeof approveModal.showModal === "function")
                        approveModal.showModal();
                    else approveModal.style.display = "block";
                } catch (e) {
                    approveModal.style.display = "block";
                }
            }

            // If order cannot be fully delivered, go straight to partial view and fetch items
            if (deliveryOptions === "partial" || !hasEnoughStock || status === "not_completed" || status === "under_procurement") {
                toggleView("partial");
                partialItemsBody.innerHTML =
                    '<tr><td colspan="6" class="p-4 text-center">Loading items...</td></tr>';

                fetch(`/delivery-orders/${currentOrderId}/items`)
                    .then((res) => res.json())
                    .then((items) => {
                        renderPartialItems(items);
                    })
                    .catch((err) => {
                        console.error("Failed to fetch items", err);
                        partialItemsBody.innerHTML =
                            '<tr><td colspan="6" class="p-4 text-center text-red-500">Gagal mengambil data.</td></tr>';
                    });
            } else {
                toggleView("selection");
            }
        });
    });

    if (btnPartialDelivery) {
        btnPartialDelivery.addEventListener("click", () => {
            if (!currentOrderId) return;

            toggleView("partial");
            partialItemsBody.innerHTML =
                '<tr><td colspan="5" class="p-4 text-center">Loading...</td></tr>';

            fetch(`/delivery-orders/${currentOrderId}/items`)
                .then((res) => res.json())
                .then((items) => {
                    renderPartialItems(items);
                })
                .catch((err) => {
                    console.error("Failed to fetch items", err);
                    partialItemsBody.innerHTML =
                        '<tr><td colspan="5" class="p-4 text-center text-red-500">Gagal mengambil data.</td></tr>';
                });
        });
    }

    if (btnBackToSelection) {
        btnBackToSelection.addEventListener("click", () =>
            toggleView("selection"),
        );
    }

    if (partialDeliveryForm) {
        partialDeliveryForm.addEventListener("submit", function (e) {
            // Manually check validity of inputs in the table (outside the form)
            const inputs = partialItemsBody.querySelectorAll(".qty-input");
            let isValid = true;

            for (const input of inputs) {
                const max = parseFloat(input.getAttribute("max"));
                const stok = parseFloat(input.getAttribute("data-stok"));
                const allocated = parseFloat(input.getAttribute("data-allocated") || "0");
                const val = parseFloat(input.value);

                if (val > allocated) {
                    input.setCustomValidity(
                        `Jumlah kirim (${val}) tidak boleh melebihi stok yang dialokasikan untuk SO ini (${allocated})`,
                    );
                    isValid = false;
                } else if (val > stok) {
                    input.setCustomValidity(
                        `Stok fisik gudang tidak mencukupi (Tersedia: ${stok})`,
                    );
                    isValid = false;
                } else if (val < 0) {
                    input.setCustomValidity(
                        "Jumlah kirim tidak boleh kurang dari 0",
                    );
                    isValid = false;
                } else if (input.value === "") {
                    input.setCustomValidity("Jumlah kirim harus diisi");
                    isValid = false;
                } else {
                    input.setCustomValidity("");
                }

                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                    break; // Stop at first invalid input
                }
            }

            if (!isValid) {
                e.preventDefault();
                return;
            }

            // Clear existing inputs in container
            const container = document.getElementById(
                "partial-inputs-container",
            );
            if (container) container.innerHTML = "";

            // Collect values from table inputs and append to form
            inputs.forEach((input) => {
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = input.name;
                hiddenInput.value = input.value;
                container.appendChild(hiddenInput);
            });
        });

        // Add input event listener to clear custom validity as user types
        partialItemsBody.addEventListener("input", function (e) {
            if (e.target.classList.contains("qty-input")) {
                e.target.setCustomValidity("");
            }
        });
    }

    if (approveModalCloseBtn) {
        approveModalCloseBtn.addEventListener("click", closeApproveModal);
    }

    // --- Logic for History Modal ---
    const historyButtons = document.querySelectorAll(".js-history-order");
    const historyModal = document.getElementById("historyModal");
    const historyOrderNumberEl = document.getElementById("historyOrderNumber");
    const historyTableBody = document.getElementById("historyTableBody");

    function openHistoryModal() {
        if (!historyModal) return;
        if (typeof historyModal.showModal === "function") {
            historyModal.showModal();
        } else {
            historyModal.style.display = "flex";
        }
    }

    function closeHistoryModal() {
        if (!historyModal) return;
        if (typeof historyModal.close === "function") {
            historyModal.close();
        } else {
            historyModal.style.display = "none";
        }
    }

    historyButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const orderId = btn.getAttribute("data-id");
            const orderNumber = btn.getAttribute("data-order-number");
            const historyUrl = btn.getAttribute("data-history-url");

            if (historyOrderNumberEl)
                historyOrderNumberEl.textContent = orderNumber;
            if (historyTableBody)
                historyTableBody.innerHTML =
                    '<tr><td colspan="4" class="p-4 text-center">Loading...</td></tr>';

            openHistoryModal();

            fetch(historyUrl)
                .then((res) => res.json())
                .then((data) => {
                    if (historyTableBody) {
                        historyTableBody.innerHTML = "";
                        if (data.length === 0) {
                            historyTableBody.innerHTML =
                                '<tr><td colspan="4" class="p-4 text-center">Belum ada histori pengiriman.</td></tr>';
                            return;
                        }

                        data.forEach((batch) => {
                            const tr = document.createElement("tr");
                            tr.className = "border-b dark:border-gray-600";

                            const itemsList = batch.items
                                .map(
                                    (item) =>
                                        `<li>
                                            <span class="font-semibold">${item.goods_name}</span> (${item.quantity_sent})
                                            ${item.goods_description && item.goods_description !== '-' ? `<div class="text-[11px] text-gray-500 dark:text-gray-400 pl-2 italic">${item.goods_description}</div>` : ''}
                                         </li>`,
                                )
                                .join("");

                            tr.innerHTML = `
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">Batch #${batch.batch_number}</td>
                                <td class="px-4 py-3">${batch.created_at}</td>
                                <td class="px-4 py-3">
                                    <ul class="list-disc pl-4 text-xs font-normal">
                                        ${itemsList}
                                    </ul>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="${batch.pdf_url}" target="_blank" class="flex items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300">Cetak PDO</a>
                                </td>
                            `;
                            historyTableBody.appendChild(tr);
                        });
                    }
                })
                .catch((err) => {
                    console.error("Failed to fetch history", err);
                    if (historyTableBody)
                        historyTableBody.innerHTML =
                            '<tr><td colspan="4" class="p-4 text-center text-red-500">Gagal mengambil data.</td></tr>';
                });
        });
    });

    // Close buttons for history modal
    document
        .querySelectorAll('[data-modal-hide="historyModal"]')
        .forEach((btn) => {
            btn.addEventListener("click", closeHistoryModal);
        });

    // allow clicking overlay to close
    document.addEventListener("click", function (e) {
        if (modal && e.target === modal) closeModal();
        if (approveModal && e.target === approveModal) closeApproveModal();
        if (historyModal && e.target === historyModal) closeHistoryModal();
    });

    // --- Logic for Reject with Reason ---
});
