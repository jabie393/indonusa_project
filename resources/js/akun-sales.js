

document.querySelectorAll('.editUserButton').forEach(button => {
    // Populate form fields on pointerdown so values are set before any inline showModal() call
    button.addEventListener('pointerdown', () => {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');

        const inputId = document.getElementById('editUserId');
        const inputName = document.getElementById('editName');
        const inputEmail = document.getElementById('editEmail');

        if (inputId) inputId.value = id;
        if (inputName) inputName.value = name;
        if (inputEmail) inputEmail.value = email;

        const form = document.getElementById('editUserForm');
        if (form) form.action = `/akun-sales/${id}`;
    });

    // In case a button doesn't use inline showModal(), open the dialog on click after populating
    button.addEventListener('click', (e) => {
        const modal = document.getElementById('editUserModal');
        if (modal && typeof modal.showModal === 'function') modal.showModal();
        else if (modal) modal.classList.remove('hidden');
    });
});

document.getElementById('closeEditModal').addEventListener('click', () => {
    const modal = document.getElementById('editUserModal');
    if (modal) modal.classList.add('hidden');
});
