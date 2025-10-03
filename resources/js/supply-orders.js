// Handler tombol Reject
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
