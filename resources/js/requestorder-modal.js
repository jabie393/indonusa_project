// requestorder-modal.js
// Listen for clicks on elements with class .open-request-modal
// The trigger should have a data-order attribute containing JSON of the order

function formatItemRow(idx, item) {
    const barang = item?.barang?.nama_barang ?? '-';
    const qty = item?.quantity ?? '-';
    const status = item?.status_item ?? '-';
    return `
      <tr>
        <td class="px-2 py-1">${idx}</td>
        <td class="px-2 py-1">${escapeHtml(barang)}</td>
        <td class="px-2 py-1">${escapeHtml(qty)}</td>
        <td class="px-2 py-1">${escapeHtml(status)}</td>
      </tr>
    `;
}

function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function openRequestModalFromData(order) {
    const modal = document.getElementById('requestOrderModal');
    if (!modal) return;

    document.getElementById('rom_order_number').textContent = order.order_number || '-';
    document.getElementById('rom_sales').textContent = (order.sales && order.sales.name) ? order.sales.name : '-';
    document.getElementById('rom_customer').textContent = order.customer_name || '-';
    document.getElementById('rom_status').textContent = order.status || '-';

    const itemsTbody = document.getElementById('rom_items');
    itemsTbody.innerHTML = '';
    if (Array.isArray(order.items) && order.items.length) {
        order.items.forEach((it, i) => {
            itemsTbody.insertAdjacentHTML('beforeend', formatItemRow(i+1, it));
        });
    } else {
        itemsTbody.innerHTML = '<tr><td colspan="4" class="px-2 py-1">Belum ada item.</td></tr>';
    }

    // show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRequestModal() {
    const modal = document.getElementById('requestOrderModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Delegate clicks
document.addEventListener('click', function(e){
    const target = e.target.closest('.open-request-modal');
    if (!target) return;
    e.preventDefault();

    const json = target.getAttribute('data-order');
    if (!json) return;

    let order = null;
    try {
        order = JSON.parse(json);
    } catch (err) {
        console.error('Invalid order JSON', err);
        return;
    }

    openRequestModalFromData(order);
});

// close buttons
document.addEventListener('click', function(e){
    if (e.target.id === 'rom_close' || e.target.id === 'rom_close_footer') {
        e.preventDefault();
        closeRequestModal();
    }
});

// close when clicking backdrop
document.addEventListener('click', function(e){
    const modal = document.getElementById('requestOrderModal');
    if (!modal) return;
    const backdrop = modal.querySelector('.absolute.inset-0');
    if (backdrop && e.target === backdrop) {
        closeRequestModal();
    }
});

// export for Vite if using modules
if (typeof window !== 'undefined') {
    window.openRequestModalFromData = openRequestModalFromData;
}
