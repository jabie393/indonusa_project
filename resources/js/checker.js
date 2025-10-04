document.getElementById('kode_barang').addEventListener('input', function () {
    const kode = this.value;
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
                    const input = document.getElementById('kode_barang');
                    warning = document.createElement('div');
                    warning.id = 'kode-warning';
                    warning.className = 'text-red-600 text-sm mt-1';
                    warning.innerText = 'Kode barang sudah terdaftar!';
                    input.parentNode.appendChild(warning);
                }
                submitBtn.disabled = true;
            } else {
                warning?.remove();
                submitBtn.disabled = false;
            }
        });
});
