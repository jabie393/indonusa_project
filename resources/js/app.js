import "./bootstrap";

import Alpine from "alpinejs";
import "flowbite";

import "./darkmode.js";
import "./order-modal.js";
import "./reveal.js";
import "./sweetalert.js";
import 'datatables.net-fixedheader-dt';
import Swal from "sweetalert2";

window.Swal = Swal;

// Decorate Swal.fire globally to apply consistent styling (confirm button color and dark mode classes)
const originalFire = Swal.fire;
Swal.fire = function(options) {
    if (options && typeof options === 'object') {
        if (!options.confirmButtonColor) {
            const isError = options.icon === "error" || 
                            (typeof options.title === "string" && (options.title.toLowerCase().includes("gagal") || options.title.toLowerCase().includes("error")));
            options.confirmButtonColor = isError ? "#d33" : "#225A97";
        }
        if (!options.customClass) {
            options.customClass = {
                popup: "rounded-2xl dark:bg-gray-800 dark:text-white dark:border dark:border-gray-700",
                title: "dark:text-white",
                htmlContainer: "dark:text-gray-300",
            };
        }
    }
    return originalFire.call(Swal, options);
};
// Add global reference
window.Swal = Swal;

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
