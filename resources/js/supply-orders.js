// Bulk Action Logic
$(document).ready(function() {
    const table = new DataTable('#DataTable'); // This gets the existing instance

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

    // Bulk Approve
    $('#bulk-approve').on('click', function() {
        const selectedIds = table.rows({ selected: true }).data().toArray().map(row => row[0]);
        
        Swal.fire({
            title: 'Bulk Approve',
            text: `Are you sure you want to approve ${selectedIds.length} items?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#15803d',
            confirmButtonText: 'Yes, Approve All'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = $('#bulk-actions').data('approve-url');
                $.post(url, {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: selectedIds
                }, function(res) {
                    if (res.success) {
                        Swal.fire('Approved!', 'Items have been approved.', 'success').then(() => location.reload());
                    }
                });
            }
        });
    });

    // Bulk Reject
    $('#bulk-reject').on('click', function() {
        const selectedIds = table.rows({ selected: true }).data().toArray().map(row => row[0]);

        Swal.fire({
            title: 'Bulk Reject',
            text: `Provide a reason for rejecting ${selectedIds.length} items:`,
            input: 'textarea',
            inputPlaceholder: 'Reason for rejection...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b91c1c',
            confirmButtonText: 'Reject All'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = $('#bulk-actions').data('reject-url');
                $.post(url, {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: selectedIds,
                    catatan: result.value
                }, function(res) {
                    if (res.success) {
                        Swal.fire('Rejected!', 'Items have been rejected.', 'success').then(() => location.reload());
                    }
                });
            }
        });
    });
});

// Existing handlers...
$(document).on('click', '.reject-btn', function () {
    const id = $(this).data('id');
    $('#reject_barang_id').val(id);
    $('#reject_note').val('');
    const modal = document.getElementById('rejectNoteModal');
    if (modal && typeof modal.showModal === 'function') {
        modal.showModal();
    }
});

// Handler tutup modal
$(document).on('click', '#closeRejectNoteModal', function (e) {
    e.preventDefault();
    const modal = document.getElementById('rejectNoteModal');
    if (modal && typeof modal.close === 'function') {
        modal.close();
    }
});

// Handler submit modal
$(document).on('submit', '#rejectNoteForm', function (e) {
    e.preventDefault();
    const id = $('#reject_barang_id').val();
    const catatan = $('#reject_note').val();

    // Kirim via AJAX ke endpoint reject
    $.ajax({
        url: '/supply-orders/' + id + '/reject', // BENAR
        method: 'POST',
        data: {
            _token: $('input[name="_token"]').val(),
            catatan: catatan
        },
        success: function () {
            location.reload();
        },
        error: function () {
            alert('Gagal mengirim alasan penolakan.');
        }
    });
});
