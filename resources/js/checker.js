document.addEventListener('input', function (e) {
    // Checker Barang Baru
    if (e.target && e.target.id === 'kode_barang') {
        const kode = e.target.value;
        const submitBtn = document.querySelector('button[type="submit"]');
        if (kode.length === 0) {
            document.getElementById('kode-warning')?.remove();
            submitBtn.disabled = false;
            return;
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
            submitBtn.disabled = false;
            return;
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
});
