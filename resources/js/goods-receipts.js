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
    fetch(`/goods-receipts/${id}/logs`)
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
