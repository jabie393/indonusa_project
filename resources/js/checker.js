document.addEventListener('input', function (e) {

    // Helper to check invalid email format
    function isInvalidEmail(email) {
        const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        return !emailRegex.test(email);
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

// Data kategori dan singkatan
const kategoriSingkatan = {
    "HANDTOOLS": "HT",
    "ADHESIVE AND SEALANT": "AS",
    "AUTOMOTIVE EQUIPMENT": "AE",
    "CLEANING": "CLN",
    "COMPRESSOR": "CMP",
    "CONSTRUCTION": "CST",
    "CUTTING TOOLS": "CT",
    "LIGHTING": "LTG",
    "FASTENING": "FST",
    "GENERATOR": "GEN",
    "HEALTH CARE EQUIPMENT": "HCE",
    "HOSPITALITY": "HSP",
    "HYDRAULIC TOOLS": "HYD",
    "MARKING MACHINE": "MM",
    "MATERIAL HANDLING EQUIPMENT": "MHE",
    "MEASURING AND TESTING EQUIPMENT": "MTE",
    "METAL CUTTING MACHINERY": "MCM",
    "PACKAGING": "PKG",
    "PAINTING AND COATING": "PC",
    "PNEUMATIC TOOLS": "PN",
    "POWER TOOLS": "PT",
    "SAFETY AND PROTECTION EQUIPMENT": "SPE",
    "SECURITY": "SEC",
    "SHEET METAL MACHINERY": "SMM",
    "STORAGE SYSTEM": "STS",
    "WELDING EQUIPMENT": "WLD",
    "WOODWORKING EQUIPMENT": "WWE",
    "MISCELLANEOUS": "MSC"
};

// Fungsi untuk menghasilkan kode barang
function generateKodeBarang(kategori, kodeBarangElement) {
    const singkatan = kategoriSingkatan[kategori] || "UNK"; // Default "UNK" jika kategori tidak ditemukan
    const timestamp = Date.now().toString().slice(-5); // Ambil 5 digit terakhir dari timestamp
    const kodeBarang = `${singkatan}-${timestamp}`;

    // Set nilai ke input kode_barang
    if (kodeBarangElement) {
        kodeBarangElement.value = kodeBarang;

        // Validasi ke server untuk memastikan tidak duplikat
        fetch(window.CHECK_KODE_BARANG_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify({ kode_barang: kodeBarang })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.valid) {
                    alert('Kode barang sudah ada, silakan coba lagi.');
                    kodeBarangElement.value = '';
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

// Event listener untuk kategori
document.addEventListener('DOMContentLoaded', function () {
    const kategoriElement = document.getElementById('kategori');
    const kodeBarangElement = document.getElementById('kode_barang');

    if (kategoriElement && kodeBarangElement) {
        kategoriElement.addEventListener('change', function () {
            generateKodeBarang(this.value, kodeBarangElement);
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-barang-btn');
    const editKategoriElement = document.getElementById('edit_kategori');
    const editKodeBarangElement = document.getElementById('edit_kode_barang');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari tombol edit
            const id = this.dataset.id;
            const kategori = this.dataset.kategori;
            const kodeBarang = this.dataset.kode;

            // Perbarui nilai awal kategori dan kode barang
            if (editKategoriElement && editKodeBarangElement) {
                editKategoriElement.dataset.initialKategori = kategori;
                editKategoriElement.value = kategori; // Set kategori yang dipilih
                editKodeBarangElement.dataset.initialKode = kodeBarang;
                editKodeBarangElement.value = kodeBarang; // Set kode barang
            }

            // Buka modal edit
            const modal = document.getElementById('editBarangModal');
            if (modal) {
                modal.showModal();
            }
        });
    });

    // Logika untuk menangani perubahan kategori
    if (editKategoriElement && editKodeBarangElement) {
        editKategoriElement.addEventListener('change', function () {
            const selectedKategori = this.value;
            const initialKategori = this.dataset.initialKategori;
            const initialKode = editKodeBarangElement.dataset.initialKode;

            // Jika kategori dikembalikan ke nilai awal, gunakan kode barang awal
            if (selectedKategori === initialKategori) {
                editKodeBarangElement.value = initialKode; // Kembalikan kode barang asli
            } else {
                // Generate kode barang baru berdasarkan kategori yang dipilih
                generateKodeBarang(selectedKategori, editKodeBarangElement);
            }
        });
    }
});
