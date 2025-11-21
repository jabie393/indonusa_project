document.addEventListener('DOMContentLoaded', function () {
    const userRole = document.body.dataset.userRole;
    var soundfile = "sounds/mixkit-doorbell-tone-2864.wav";

    if (userRole === 'Warehouse') {
        Echo.channel('orders')
            .listen('OrderStatusUpdated', (e) => {
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
                    }
                });
                Toast.fire({
                    icon: 'info',
                    title: 'Order Baru!',
                    text: `Order ID ${e.orderId} telah dikirim ke warehouse. Total orders: ${e.orderCount}`,
                    width: '600px',
                    customClass: {
                        popup: 'rounded-2xl!',
                    },
                    didOpen: function () {
                        var audplay = new Audio(soundfile)
                        audplay.play();
                    }
                });

                // Update badge if it exists
                const notifBadge = document.getElementById('delivery-orders-notif-badge');
                if (notifBadge) {
                    notifBadge.textContent = e.orderCount;
                    notifBadge.classList.remove('hidden');
                }
            });
    }
});