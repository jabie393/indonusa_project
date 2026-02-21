$(document).ready(function() {
    const table = new DataTable('#DataTable');

    function updateBulkActions() {
        const selectedIds = table.rows({ selected: true }).data().toArray().map(row => row[0]);
        const count = selectedIds.length;
        
        if (count > 0) {
            $('#bulk-actions').removeClass('hidden').addClass('flex');
            $('#selected-count').text(count);
        } else {
            $('#bulk-actions').addClass('hidden').removeClass('flex');
        }
    }

    // Listen for select/deselect events
    table.on('select deselect', function() {
        updateBulkActions();
    });

    // Bulk Delete
    $('#bulk-delete').on('click', function() {
        const selectedIds = table.rows({ selected: true }).data().toArray().map(row => row[0]);
        
        Swal.fire({
            title: 'Bulk Delete',
            text: `Are you sure you want to delete ${selectedIds.length} request orders? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            confirmButtonText: 'Yes, Delete All'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = $('#bulk-actions').data('delete-url');
                $.post(url, {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: selectedIds
                }, function(res) {
                    if (res.success) {
                        Swal.fire('Deleted!', 'Request orders have been deleted.', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message || 'Failed to delete items.', 'error');
                    }
                });
            }
        });
    });

    // Bulk Send to Warehouse
    $('#bulk-send').on('click', function() {
        const selectedIds = table.rows({ selected: true }).data().toArray().map(row => row[0]);

        Swal.fire({
            title: 'Bulk Send to Warehouse',
            text: `Send ${selectedIds.length} request orders to Warehouse? Only 'Open' or 'Approved' items will be processed.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1d4ed8',
            confirmButtonText: 'Yes, Send All'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = $('#bulk-actions').data('sent-url');
                $.post(url, {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: selectedIds
                }, function(res) {
                    if (res.success) {
                        Swal.fire('Sent!', `${res.count} items have been sent to Warehouse.`, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Failed', 'No items were processed. Ensure they have valid status and are not already sent.', 'warning');
                    }
                });
            }
        });
    });

    window.uploadImageSO = function(id) {
        const input = document.getElementById('image-so-input-' + id);
        if (!input.files.length) return;
        const file = input.files[0];
        if (!['image/jpeg','image/png','image/jpg'].includes(file.type)) {
            alert('Format file harus JPG, JPEG, atau PNG');
            input.value = '';
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        const formData = new FormData();
        formData.append('image_so', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        fetch('/request-order/' + id + '/upload-image-so', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('image-so-preview-' + id).innerHTML =
                    `<a href="${data.image_url}" target="_blank"><img src="${data.image_url}" alt="SO Image" style="width:50px;height:50px;object-fit:cover;border-radius:4px;border:1px solid #ccc;display:inline-block;vertical-align:middle;" /></a>` +
                    `<button class='inline-block ml-2 text-xs text-blue-600 hover:underline' onclick='removeImageSO(${id})'>Hapus</button>`;
            } else {
                alert(data.message || 'Upload gagal');
            }
        })
        .catch(() => {
            alert('Upload gagal');
        });
    }

    window.removeImageSO = function(id) {
        if (!confirm('Hapus gambar SO ini?')) return;
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'DELETE');
        fetch('/request-order/' + id + '/upload-image-so', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('image-so-preview-' + id).innerHTML =
                    `<input type='file' id='image-so-input-${id}' style='display:inline-block;width:120px;' accept='image/jpeg,image/png,image/jpg' onchange='uploadImageSO(${id})'>`;
            } else {
                alert(data.message || 'Gagal menghapus gambar');
            }
        })
        .catch(() => {
            alert('Gagal menghapus gambar');
        });
    }
});
