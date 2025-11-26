document.addEventListener('DOMContentLoaded', function () {
    // Populate form fields and set form action dynamically
    document.querySelectorAll('.editPicsButton').forEach(button => {
        button.addEventListener('pointerdown', () => {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const phone = button.getAttribute('data-phone');
            const email = button.getAttribute('data-email');
            const position = button.getAttribute('data-position');

            const inputId = document.getElementById('editPicsId');
            const inputName = document.getElementById('editName');
            const inputPhone = document.getElementById('editTelepon');
            const inputEmail = document.getElementById('editEmail');
            const inputPosition = document.getElementById('editPosition');

            if (inputId) inputId.value = id;
            if (inputName) inputName.value = name;
            if (inputPhone) inputPhone.value = phone;
            if (inputEmail) inputEmail.value = email;
            if (inputPosition) inputPosition.value = position;

            const form = document.getElementById('editPicsForm');
            if (form) form.action = `/pics/${id}`;
        });

        // Open the modal on click
        button.addEventListener('click', () => {
            const modal = document.getElementById('editPicsModal');
            if (modal && typeof modal.showModal === 'function') modal.showModal();
            else if (modal) modal.classList.remove('hidden');
        });
    });

    // Close modal on button click
    document.querySelectorAll('form[method="dialog"] button').forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('dialog');
            if (modal && typeof modal.close === 'function') {
                modal.close();
            } else {
                modal.classList.add('hidden'); // Fallback jika browser tidak mendukung <dialog>
            }
        });
    });
});


