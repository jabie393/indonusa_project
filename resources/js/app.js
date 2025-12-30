import "./bootstrap";

import Alpine from "alpinejs";
import "flowbite";

import "./darkmode.js";
import "./order-modal.js";

window.Alpine = Alpine;

Alpine.start();

// Handle Single Session Login (Realtime Force Logout)
const userId = document
    .querySelector('meta[name="user-id"]')
    ?.getAttribute("content");

if (userId && window.Echo) {
    window.Echo.private(`user.${userId}`).listen(
        ".UserLoggedInElsewhere",
        (e) => {
            Swal.fire({
                title: "Ada yang login di device lain",
                text: `Akun Anda baru saja login di perangkat lain (${
                    e.device ?? "System"
                }). Sesi Anda di sini telah berakhir.`,
                icon: "warning",
                confirmButtonText: "Kembali ke Login",
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    confirmButton:
                        "bg-[#225A97] text-white px-6 py-2 rounded-lg",
                },
            }).then((result) => {
                // Device A baru akan redirect ke /login SAAT tombol diklik
                window.location.href = "/login";
            });
        }
    );

    window.Echo.connector.pusher.connection.bind("error", (err) => {
        // Handle connection error silently or log to monitoring service if needed
    });
}
