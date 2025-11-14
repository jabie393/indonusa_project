import Swal from 'sweetalert2'

// Delete alert
window.confirmDelete = function (callback) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        customClass: {
            popup: 'rounded-2xl!',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
        // Do nothing if cancelled
    });
};

// Success alert
if (window.sweetTitle && window.sweetText) {
    Swal.fire({
        title: window.sweetTitle,
        text: window.sweetText,
        icon: 'success',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            popup: 'rounded-2xl!',
        }
    });
}