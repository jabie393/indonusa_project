// Handles opening the order detail modal and populating fields
document.addEventListener('DOMContentLoaded', function () {
    function showModal() {
        const modal = document.getElementById('orderDetailModal');
        if (!modal) return;
        modal.classList.remove('hidden');
    }

    function hideModal() {
        const modal = document.getElementById('orderDetailModal');
        if (!modal) return;
        modal.classList.add('hidden');
    }

    function populateModal(button) {
        const orderNumber = button.getAttribute('data-order-number') || '';
        const status = button.getAttribute('data-status') || '';
        const reason = button.getAttribute('data-reason') || '';
        const itemsJson = button.getAttribute('data-items') || '[]';

        const orderNumberEl = document.getElementById('modal_order_number');
        const statusEl = document.getElementById('modal_status');
        const reasonEl = document.getElementById('modal_reason');
        const itemsEl = document.getElementById('modal_items');

        if (orderNumberEl) orderNumberEl.textContent = orderNumber;
        if (statusEl) statusEl.textContent = status;
        if (reasonEl) reasonEl.textContent = reason || '-';

        // Clear items
        if (itemsEl) {
            itemsEl.innerHTML = '';
            let items = [];
            try {
                items = JSON.parse(itemsJson.replace(/&quot;/g, '"'));
            } catch (e) {
                // fallback: try decoding from HTML entity encoding
                try {
                    items = JSON.parse(itemsJson);
                } catch (er) {
                    items = [];
                }
            }

            items.forEach(function (it) {
                const li = document.createElement('li');
                li.textContent = (it.nama || ('Barang ID ' + (it.barang_id || '')) ) + ' — qty: ' + (it.quantity || 0);
                itemsEl.appendChild(li);
            });
        }
    }

    // Open buttons
    document.querySelectorAll('.open-order-detail').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            populateModal(btn);
            showModal();
        });
    });

    // Close handlers
    const closeBtn = document.getElementById('close_order_modal');
    if (closeBtn) closeBtn.addEventListener('click', hideModal);
    const modalCloseBtn = document.getElementById('modal_close_btn');
    if (modalCloseBtn) modalCloseBtn.addEventListener('click', hideModal);

    // Close when clicking outside modal content
    const modal = document.getElementById('orderDetailModal');
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) hideModal();
        });
    }
});
