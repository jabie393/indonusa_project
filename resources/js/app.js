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

// Decorate Swal.fire globally to apply consistent styling (confirm button color, dark mode, rounded styles, and toast defaults)
const originalFire = Swal.fire;
Swal.fire = function(options) {
    if (options && typeof options === 'object') {
        const isToast = options.toast || (this && this.params && this.params.toast);
        const userCustomClass = options.customClass || {};

        if (isToast) {
            options.toast = true;
            if (!options.position) options.position = "top-end";
            
            options.customClass = {
                popup: ("rounded-2xl! shadow-lg! border! border-gray-200! dark:border-gray-700! dark:bg-gray-800! dark:text-white! " + (userCustomClass.popup || "")).trim(),
                title: ("text-sm! font-bold! dark:text-white! " + (userCustomClass.title || "")).trim(),
                htmlContainer: ("text-xs! dark:text-gray-300! " + (userCustomClass.htmlContainer || "")).trim(),
                ...userCustomClass
            };
        } else {
            if (!options.confirmButtonColor) {
                const isError = options.icon === "error" || 
                                (typeof options.title === "string" && (options.title.toLowerCase().includes("gagal") || options.title.toLowerCase().includes("error")));
                options.confirmButtonColor = isError ? "#d33" : "#225A97";
            }
            if (!options.target) {
                const openDialog = document.querySelector('dialog[open]');
                if (openDialog) options.target = openDialog;
            }
            options.customClass = {
                popup: ("rounded-2xl! dark:bg-gray-800! dark:text-white! dark:border! dark:border-gray-700! " + (userCustomClass.popup || "")).trim(),
                title: ("text-base! font-bold! dark:text-white! " + (userCustomClass.title || "")).trim(),
                htmlContainer: ("text-sm! dark:text-gray-300! " + (userCustomClass.htmlContainer || "")).trim(),
                ...userCustomClass
            };
        }
    }
    return originalFire.apply(this, arguments);
};

// Global helper for toast notifications
Swal.toast = function(options) {
    if (typeof options === "string") {
        options = { title: options };
    }
    return Swal.fire({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        ...options,
    });
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
