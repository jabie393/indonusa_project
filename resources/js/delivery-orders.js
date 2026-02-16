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
            td.setAttribute("colspan", "5");
            td.className = "px-4 py-2 text-sm text-gray-900 dark:text-white";
            td.textContent = "Tidak ada item pada order ini.";
            tr.appendChild(td);
            tbody.appendChild(tr);
            return;
        }

        items.forEach(function (item) {
            const tr = document.createElement("tr");

            // prefer kode_barang coming from related barang, then item.kode_barang, then fallback to barang_id
            const kodeBarang =
                (item.barang &&
                    (item.barang.kode_barang ?? item.barang.kode)) ??
                item.kode_barang ??
                item.barang_id ??
                "-";
            const namaBarang =
                (item.barang &&
                    (item.barang.nama ||
                        item.barang.name ||
                        item.barang.nama_barang)) ||
                item.nama_barang ||
                "-";
            const qty = item.quantity ?? "-";
            const delivered = item.delivered_quantity ?? "-";
            const status = item.status_item ?? item.status ?? "-";

            const cells = [kodeBarang, namaBarang, qty, delivered, status];

            cells.forEach(function (val) {
                const td = document.createElement("td");
                td.className =
                    "px-4 py-2 whitespace-nowrap text-sm text-gray-700 dark:text-white";
                td.textContent = val;
                tr.appendChild(td);
            });

            tbody.appendChild(tr);
        });
    }

    buttons.forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            const itemsAttr = btn.getAttribute("data-items");
            let items = [];
            if (itemsAttr) {
                try {
                    items = JSON.parse(itemsAttr);
                } catch (err) {
                    // If JSON.parse fails, try to decode HTML entities then parse
                    try {
                        const txt = document.createElement("textarea");
                        txt.innerHTML = itemsAttr;
                        items = JSON.parse(txt.value);
                    } catch (err2) {
                        console.error(
                            "Failed to parse order items JSON",
                            err,
                            err2,
                        );
                    }
                }
            }

            const orderNumber =
                btn.getAttribute("data-order-number") ||
                btn.dataset.orderNumber ||
                "#-";
            if (orderNumberEl) orderNumberEl.textContent = orderNumber;

            renderRows(items);
            openModal();
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
        if (view === "partial") {
            selectionView?.classList.add("hidden");
            partialView?.classList.remove("hidden");
            partialView?.classList.add("flex");
        } else {
            selectionView?.classList.remove("hidden");
            partialView?.classList.add("hidden");
            partialView?.classList.remove("flex");
        }
    }

    function renderPartialItems(items) {
        if (!partialItemsBody) return;
        partialItemsBody.innerHTML = "";

        items.forEach((item) => {
            const tr = document.createElement("tr");
            tr.className = "dark:hover:bg-gray-700/50";

            const isOutOfStock = item.qty_pesanan > item.stok_gudang;
            const stockColor = isOutOfStock
                ? "text-red-600 font-bold"
                : "text-green-600";

            tr.innerHTML = `
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${
                        item.nama_barang
                    }</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">${
                        item.kode_barang
                    }</div>
                </td>
                <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-300">${
                    item.qty_pesanan
                } ${item.satuan}</td>
                <td class="px-4 py-3 text-center text-sm ${stockColor}">${
                    item.stok_gudang
                } ${item.satuan}</td>
                <td class="px-4 py-3 text-center">
                    <input type="number" 
                        name="items[${item.id}]" 
                        value="${Math.min(item.qty_pesanan, item.stok_gudang)}"
                        max="${item.qty_pesanan}"
                        min="0"
                        class="qty-input block w-full rounded-md border-gray-300 py-1 text-center text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                    ${
                        isOutOfStock
                            ? '<div class="mt-1 text-[10px] text-red-500 leading-tight">Stok Kurang!</div>'
                            : ""
                    }
                </td>
            `;
            partialItemsBody.appendChild(tr);
        });
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

            currentOrderId = orderId;
            if (approveOrderNumberEl)
                approveOrderNumberEl.textContent = orderNumber;
            if (partialOrderNumberEl)
                partialOrderNumberEl.textContent = orderNumber;
            if (fullDeliveryForm)
                fullDeliveryForm.setAttribute("action", approveUrl);

            // Initial view
            toggleView("selection");

            if (approveModal) {
                try {
                    if (typeof approveModal.showModal === "function")
                        approveModal.showModal();
                    else approveModal.style.display = "block";
                } catch (e) {
                    approveModal.style.display = "block";
                }
            }
        });
    });

    if (btnPartialDelivery) {
        btnPartialDelivery.addEventListener("click", () => {
            if (!currentOrderId) return;

            toggleView("partial");
            partialItemsBody.innerHTML =
                '<tr><td colspan="4" class="p-4 text-center">Loading...</td></tr>';

            fetch(`/delivery-orders/${currentOrderId}/items`)
                .then((res) => res.json())
                .then((items) => {
                    renderPartialItems(items);
                })
                .catch((err) => {
                    console.error("Failed to fetch items", err);
                    partialItemsBody.innerHTML =
                        '<tr><td colspan="4" class="p-4 text-center text-red-500">Gagal mengambil data.</td></tr>';
                });
        });
    }

    if (btnBackToSelection) {
        btnBackToSelection.addEventListener("click", () =>
            toggleView("selection"),
        );
    }

    if (approveModalCloseBtn) {
        approveModalCloseBtn.addEventListener("click", closeApproveModal);
    }

    // allow clicking overlay to close
    document.addEventListener("click", function (e) {
        if (modal && e.target === modal) closeModal();
        if (approveModal && e.target === approveModal) closeApproveModal();
    });
});
