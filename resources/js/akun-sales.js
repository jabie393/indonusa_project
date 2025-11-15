document.getElementById('createUserButton').addEventListener('click', () => {
    document.getElementById('createSalesAccountModal').classList.remove('hidden');
});

document.getElementById('closeCreateModal').addEventListener('click', () => {
    document.getElementById('createSalesAccountModal').classList.add('hidden');
});

document.querySelectorAll('.editUserButton').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');

        document.getElementById('editUserId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;

        const form = document.getElementById('editUserForm');
        form.action = `/akun-sales/${id}`;

        document.getElementById('editSalesAccountModal').classList.remove('hidden');
    });
});

document.getElementById('closeEditModal').addEventListener('click', () => {
    document.getElementById('editSalesAccountModal').classList.add('hidden');
});
