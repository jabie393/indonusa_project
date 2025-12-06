document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#importExcel form');
    const fileInput = document.getElementById('excel');
    const progressSection = document.getElementById('progress-section');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const uploadArea = document.getElementById('upload-area');

    if (!form || !fileInput) return;

    let uploadInProgress = false;
    let uploadCompleted = false;
    const submitButton = form.querySelector('.submit-btn');

    // Handle file selection
    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        // Validate file extension and MIME type before proceeding
        const allowedExt = ['xlsx', 'xls'];
        const allowedMime = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.ms-excel', // .xls (and sometimes for .csv some browsers)
        ];

        if (!file) return;

        const nameParts = file.name.split('.');
        const ext = nameParts.length > 1 ? nameParts.pop().toLowerCase() : '';
        const mime = file.type;

        const isExtOk = allowedExt.includes(ext);
        const isMimeOk = allowedMime.includes(mime) || mime === '';

        if (!isExtOk || !isMimeOk) {
            Swal.fire({
                icon: 'error',
                title: 'File tidak valid',
                text: 'Harap pilih file Excel dengan ekstensi .xlsx atau .xls.'
            });
            // reset input and UI
            e.target.value = '';
            progressSection.classList.add('hidden');
            const filenameElReset = document.getElementById('excel_filename');
            if (filenameElReset) {
                filenameElReset.textContent = '';
                filenameElReset.classList.add('hidden');
            }
            const svg = document.querySelector('#excel_label svg');
            if (svg) svg.classList.remove('hidden');
            return;
        }

        if (file) {
            // Show progress section
            progressSection.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';

            // Show filename preview (no image preview for Excel files)
            const filenameEl = document.getElementById('excel_filename');
            if (filenameEl) {
                filenameEl.textContent = file.name;
                filenameEl.classList.remove('hidden');
            }
            const svg = document.querySelector('#excel_label svg');
            if (svg) svg.classList.add('hidden');

            // Start upload after a short delay
            setTimeout(() => {
                startUpload(file);
            }, 500);
        }
    });

    function startUpload(file) {
        if (uploadInProgress) return;
        uploadInProgress = true;
        if (submitButton) submitButton.disabled = true;

        const formData = new FormData();
        formData.append('excel', file);
        formData.append('_token', window.CSRF_TOKEN);

        const xhr = new XMLHttpRequest();

        // Track upload progress
        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
                progressText.textContent = Math.round(percentComplete) + '%';
            }
        });

        xhr.addEventListener('load', function () {
            if (xhr.status === 200 || xhr.status === 201) {
                progressBar.style.width = '100%';
                progressText.textContent = '100%';

                // Mark complete and keep modal open; do NOT submit the form or reload
                uploadInProgress = false;
                uploadCompleted = true;
                if (submitButton) {
                    // Keep submit disabled to avoid accidental form submission after upload
                    submitButton.disabled = true;
                }

                // Try to parse JSON response and show stored path/url in modal
                try {
                    const resp = JSON.parse(xhr.responseText);
                    const uploadResult = document.getElementById('upload-result');
                    const uploadPath = document.getElementById('upload-path');
                    const uploadUrl = document.getElementById('upload-url');
                    if (uploadResult && uploadPath && uploadUrl) {
                        uploadPath.textContent = resp.path || '';
                        if (resp.url) {
                            uploadUrl.href = resp.url;
                            uploadUrl.textContent = resp.url;
                            uploadUrl.classList.remove('hidden');
                        } else {
                            uploadUrl.href = '#';
                            uploadUrl.textContent = 'Tidak tersedia';
                        }
                        uploadResult.classList.remove('hidden');
                    }
                } catch (err) {
                    // fallback: show a simple alert if JSON parse fails
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'File Excel berhasil diupload!'
                        });
                    }, 600);
                    return;
                }
            } else {
                progressSection.classList.add('hidden');
                try {
                    const response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Gagal mengupload file'
                    });
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengupload file. Status: ' + xhr.status
                    });
                }
                uploadInProgress = false;
                if (submitButton) submitButton.disabled = false;
            }
        });

        xhr.addEventListener('error', function () {
            progressSection.classList.add('hidden');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengupload file.'
            });
            uploadInProgress = false;
            if (submitButton) submitButton.disabled = false;
        });

        xhr.addEventListener('abort', function () {
            progressSection.classList.add('hidden');
            Swal.fire({
                icon: 'warning',
                title: 'Dibatalkan',
                text: 'Upload dibatalkan.'
            });
            uploadInProgress = false;
            if (submitButton) submitButton.disabled = false;
        });

        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', window.CSRF_TOKEN);
        xhr.send(formData);
    }

    // Keep form submit as backup
    if (submitButton) {
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();
            if (uploadInProgress) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tunggu',
                    text: 'Upload sedang berlangsung. Tunggu hingga selesai.'
                });
                return;
            }
            if (uploadCompleted) {
                // Prevent submitting form after upload
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: 'File telah diupload melalui AJAX. Form tidak akan dikirim.'
                });
                return;
            }

            const file = fileInput.files[0];
            if (file) {
                startUpload(file);
            } else {
                // No file: allow falling back to normal submit if desired
                // e.g., form.submit(); but here we prevent submission by default
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Tidak ada file yang dipilih.'
                });
            }
        });
    }
});
