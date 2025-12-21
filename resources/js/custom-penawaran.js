$(document).ready(function() {
    // DataTables is initialized globally in dataTable.js as 'datatable' for #DataTable
    // We can reach it via its selector or the global variable if it matches.
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
            text: `Are you sure you want to delete ${selectedIds.length} custom penawarans? This action cannot be undone.`,
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
                        Swal.fire('Deleted!', 'Penawarans have been deleted.', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message || 'Failed to delete items.', 'error');
                    }
                });
            }
        });
    });
});
