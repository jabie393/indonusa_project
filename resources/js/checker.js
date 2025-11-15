document.addEventListener('input', function (e) {
    // Helper to check invalid characters for kode_barang (allow only letters and numbers)
    function containsInvalidKodeChars(str) {
        return /[^a-zA-Z0-9]/.test(str);
    }

    // Helper to check invalid email format
    function isInvalidEmail(email) {
        const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        return !emailRegex.test(email);
    }

    // Checker Barang Baru
    if (e.target && e.target.id === 'kode_barang') {
        const kode = e.target.value;
        const submitBtn = document.querySelector('button[type="submit"]');
        if (kode.length === 0) {
            document.getElementById('kode-warning')?.remove();
            document.getElementById('kode-invalid-warning')?.remove();
            submitBtn.disabled = false;
            return;
        }

        // Cek karakter unik / tidak valid
        if (containsInvalidKodeChars(kode)) {
            let invalidWarn = document.getElementById('kode-invalid-warning');
            if (!invalidWarn) {
                invalidWarn = document.createElement('div');
                invalidWarn.id = 'kode-invalid-warning';
                invalidWarn.className = 'text-red-600 text-sm mt-1';
                invalidWarn.innerText = "Karakter tidak valid. Hanya huruf dan angka yang diperbolehkan.";
                e.target.parentNode.appendChild(invalidWarn);
            }
            // Disable submit when invalid
            submitBtn.disabled = true;
            return; // skip uniqueness check
        } else {
            document.getElementById('kode-invalid-warning')?.remove();
        }

        fetch(window.CHECK_KODE_BARANG_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.CSRF_TOKEN
            },
            body: JSON.stringify({ kode_barang: kode })
        })
            .then(res => res.json())
            .then(data => {
                let warning = document.getElementById('kode-warning');
                if (data.exists) {
                    if (!warning) {
                        warning = document.createElement('div');
                        warning.id = 'kode-warning';
                        warning.className = 'text-red-600 text-sm mt-1';
                        warning.innerText = 'Kode barang sudah terdaftar!';
                        e.target.parentNode.appendChild(warning);
                    }
                    submitBtn.disabled = true;
                } else {
                    warning?.remove();
                    submitBtn.disabled = false;
                }
            });
    }

    // Checker Edit Barang
    if (e.target && e.target.id === 'edit_kode_barang') {
        const kode = e.target.value;
        const id = document.getElementById('edit_id').value;
        const submitBtn = document.querySelector('#editBarangForm button[type="submit"]');
        if (kode.length === 0) {
            document.getElementById('edit-kode-warning')?.remove();
            document.getElementById('edit-kode-invalid-warning')?.remove();
            submitBtn.disabled = false;
            return;
        }

        // Cek karakter unik / tidak valid
        if (containsInvalidKodeChars(kode)) {
            let invalidWarn = document.getElementById('edit-kode-invalid-warning');
            if (!invalidWarn) {
                invalidWarn = document.createElement('div');
                invalidWarn.id = 'edit-kode-invalid-warning';
                invalidWarn.className = 'text-red-600 text-sm mt-1';
                invalidWarn.innerText = "Karakter tidak valid. Hanya huruf dan angka yang diperbolehkan.";
                e.target.parentNode.appendChild(invalidWarn);
            }
            submitBtn.disabled = true;
            return; // skip uniqueness check
        } else {
            document.getElementById('edit-kode-invalid-warning')?.remove();
        }

        fetch(window.CHECK_KODE_BARANG_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.CSRF_TOKEN
            },
            body: JSON.stringify({ kode_barang: kode, id: id })
        })
            .then(res => res.json())
            .then(data => {
                let warning = document.getElementById('edit-kode-warning');
                if (data.exists) {
                    if (!warning) {
                        warning = document.createElement('div');
                        warning.id = 'edit-kode-warning';
                        warning.className = 'text-red-600 text-sm mt-1';
                        warning.innerText = 'Kode barang sudah terdaftar!';
                        e.target.parentNode.appendChild(warning);
                    }
                    submitBtn.disabled = true;
                } else {
                    warning?.remove();
                    submitBtn.disabled = false;
                }
            });
    }

    // Checker for new sales account email
    if (e.target && e.target.id === 'createEmail') {
        const email = e.target.value;
        const submitBtn = document.querySelector('#createUserModal button[type="submit"]');
        if (email.length === 0) {
            document.getElementById('email-warning')?.remove();
            document.getElementById('email-invalid-warning')?.remove();
            submitBtn.disabled = false;
            return;
        }

        // Check invalid email format
        if (isInvalidEmail(email)) {
            let invalidWarn = document.getElementById('email-invalid-warning');
            if (!invalidWarn) {
                invalidWarn = document.createElement('div');
                invalidWarn.id = 'email-invalid-warning';
                invalidWarn.className = 'text-red-600 text-sm mt-1';
                invalidWarn.innerText = "Format email tidak valid.";
                e.target.parentNode.appendChild(invalidWarn);
            }
            submitBtn.disabled = true;
            return; // skip uniqueness check
        } else {
            document.getElementById('email-invalid-warning')?.remove();
        }

        fetch(window.CHECK_EMAIL_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.CSRF_TOKEN
            },
            body: JSON.stringify({ email: email })
        })
            .then(res => res.json())
            .then(data => {
                let warning = document.getElementById('email-warning');
                if (data.exists) {
                    if (!warning) {
                        warning = document.createElement('div');
                        warning.id = 'email-warning';
                        warning.className = 'text-red-600 text-sm mt-1';
                        warning.innerText = 'Email sudah terdaftar!';
                        e.target.parentNode.appendChild(warning);
                    }
                    submitBtn.disabled = true;
                } else {
                    warning?.remove();
                    submitBtn.disabled = false;
                }
            });
    }

    // Checker for editing sales account email
    if (e.target && e.target.id === 'editEmail') {
        const email = e.target.value;
        const id = document.getElementById('editUserId').value;
        const submitBtn = document.querySelector('#editUserModal button[type="submit"]');
        if (email.length === 0) {
            document.getElementById('edit-email-warning')?.remove();
            document.getElementById('edit-email-invalid-warning')?.remove();
            submitBtn.disabled = false;
            return;
        }

        // Check invalid email format
        if (isInvalidEmail(email)) {
            let invalidWarn = document.getElementById('edit-email-invalid-warning');
            if (!invalidWarn) {
                invalidWarn = document.createElement('div');
                invalidWarn.id = 'edit-email-invalid-warning';
                invalidWarn.className = 'text-red-600 text-sm mt-1';
                invalidWarn.innerText = "Format email tidak valid.";
                e.target.parentNode.appendChild(invalidWarn);
            }
            submitBtn.disabled = true;
            return; // skip uniqueness check
        } else {
            document.getElementById('edit-email-invalid-warning')?.remove();
        }

        fetch(window.CHECK_EMAIL_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.CSRF_TOKEN
            },
            body: JSON.stringify({ email: email, id: id })
        })
            .then(res => res.json())
            .then(data => {
                let warning = document.getElementById('edit-email-warning');
                if (data.exists) {
                    if (!warning) {
                        warning = document.createElement('div');
                        warning.id = 'edit-email-warning';
                        warning.className = 'text-red-600 text-sm mt-1';
                        warning.innerText = 'Email sudah terdaftar!';
                        e.target.parentNode.appendChild(warning);
                    }
                    submitBtn.disabled = true;
                } else {
                    warning?.remove();
                    submitBtn.disabled = false;
                }
            });
    }

    // Real-time password validation for create form
    if (e.target && e.target.id === 'createPassword') {
        const password = e.target.value;
        const submitBtn = document.querySelector('#createUserModal button[type="submit"]');
        let warning = document.getElementById('password-warning');

        if (password.length < 6) {
            if (!warning) {
                warning = document.createElement('div');
                warning.id = 'password-warning';
                warning.className = 'text-red-600 text-sm mt-1';
                warning.innerText = 'Password harus minimal 6 karakter.';
                e.target.parentNode.appendChild(warning);
            }
            submitBtn.disabled = true;
        } else {
            warning?.remove();
            submitBtn.disabled = false;
        }
    }

    // Real-time password validation for edit form
    if (e.target && e.target.id === 'editPassword') {
        const password = e.target.value;
        const submitBtn = document.querySelector('#editUserModal button[type="submit"]');
        let warning = document.getElementById('edit-password-warning');

        if (password.length > 0 && password.length < 6) {
            if (!warning) {
                warning = document.createElement('div');
                warning.id = 'edit-password-warning';
                warning.className = 'text-red-600 text-sm mt-1';
                warning.innerText = 'Password harus minimal 6 karakter.';
                e.target.parentNode.appendChild(warning);
            }
            submitBtn.disabled = true;
        } else {
            warning?.remove();
            submitBtn.disabled = false;
        }
    }
});
