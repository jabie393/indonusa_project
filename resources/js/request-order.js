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
});
