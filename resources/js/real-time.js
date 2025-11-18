document.addEventListener('DOMContentLoaded', function () {
    const userRole = document.body.dataset.userRole;

    if (userRole === 'Warehouse') {
        Echo.channel('orders')
            .listen('OrderStatusUpdated', (e) => {
                const notificationContainer = document.getElementById('notification-container');
                const notificationMessage = document.getElementById('notification-message');
                const notifBadge = document.getElementById('delivery-orders-notif-badge');

                if (notificationContainer && notificationMessage) {
                    notificationMessage.textContent = `Order ID ${e.orderId} telah dikirim ke warehouse. Total orders: ${e.orderCount}`;
                    notificationContainer.classList.remove('hidden');

                    setTimeout(() => {
                        notificationContainer.classList.add('hidden');
                    }, 5000); // Sembunyikan notifikasi setelah 5 detik
                }

                if (notifBadge) {

                    notifBadge.textContent = e.orderCount;
                    notifBadge.classList.remove('hidden');
                }
            });
    }
});