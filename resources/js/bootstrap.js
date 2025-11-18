import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

document.addEventListener('DOMContentLoaded', function () {
    console.log('Laravel Echo initialized');
    Echo.channel('orders')
        .listen('OrderStatusUpdated', (e) => {
            console.log('Event received:', e);
            const notifBadge = document.getElementById('delivery-orders-notif-badge');
            if (notifBadge) {
                notifBadge.textContent = e.orderCount;
                notifBadge.classList.remove('hidden');
            }
        });
});
