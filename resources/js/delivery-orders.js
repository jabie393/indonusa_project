// Attach handlers after DOM ready
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.js-show-order');
    const modal = document.getElementById('delivery-order-modal');
    const orderNumberEl = document.getElementById('delivery-order-number');
    const tbody = document.getElementById('delivery-order-items-body');
    const closeBtn = document.getElementById('delivery-order-close');
    const closeTopBtn = document.getElementById('delivery-order-close-top');

    function clearTbody() {
        while (tbody.firstChild) tbody.removeChild(tbody.firstChild);
    }

    function closeModal() {
        if (!modal) return;
        try {
            if (typeof modal.close === 'function') modal.close();
            else modal.style.display = 'none';
        } catch (e) {
            modal.style.display = 'none';
        }
    }

    function openModal() {
        if (!modal) return;
        try {
            if (typeof modal.showModal === 'function') modal.showModal();
            else modal.style.display = 'block';
        } catch (e) {
            // fallback
            modal.style.display = 'block';
        }
    }

    function renderRows(items) {
        clearTbody();
        if (!Array.isArray(items) || items.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.setAttribute('colspan', '5');
            td.className = 'px-4 py-2 text-sm text-gray-900 dark:text-white';
            td.textContent = 'Tidak ada item pada order ini.';
            tr.appendChild(td);
            tbody.appendChild(tr);
            return;
        }

        items.forEach(function (item) {
            const tr = document.createElement('tr');

            // prefer kode_barang coming from related barang, then item.kode_barang, then fallback to barang_id
            const kodeBarang = (item.barang && (item.barang.kode_barang ?? item.barang.kode)) ?? item.kode_barang ?? item.barang_id ?? '-';
            const namaBarang = (item.barang && (item.barang.nama || item.barang.name || item.barang.nama_barang)) || item.nama_barang || '-';
            const qty = item.quantity ?? '-';
            const delivered = item.delivered_quantity ?? '-';
            const status = item.status_item ?? item.status ?? '-';

            const cells = [kodeBarang, namaBarang, qty, delivered, status];

            cells.forEach(function (val) {
                const td = document.createElement('td');
                td.className = 'px-4 py-2 whitespace-nowrap text-sm text-gray-700 dark:text-white';
                td.textContent = val;
                tr.appendChild(td);
            });

            tbody.appendChild(tr);
        });
    }

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const itemsAttr = btn.getAttribute('data-items');
            let items = [];
            if (itemsAttr) {
                try {
                    items = JSON.parse(itemsAttr);
                } catch (err) {
                    // If JSON.parse fails, try to decode HTML entities then parse
                    try {
                        const txt = document.createElement('textarea');
                        txt.innerHTML = itemsAttr;
                        items = JSON.parse(txt.value);
                    } catch (err2) {
                        console.error('Failed to parse order items JSON', err, err2);
                    }
                }
            }

            const orderNumber = btn.getAttribute('data-order-number') || btn.dataset.orderNumber || '#-';
            if (orderNumberEl) orderNumberEl.textContent = orderNumber;

            renderRows(items);
            openModal();
        });
    });

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (closeTopBtn) closeTopBtn.addEventListener('click', closeModal);

    // allow clicking overlay to close (for <dialog> polyfill or custom)
    document.addEventListener('click', function (e) {
        if (!modal) return;
        if (e.target === modal) closeModal();
    });
});
