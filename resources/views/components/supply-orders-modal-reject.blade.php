<dialog id="rejectNoteModal" class="modal">
    <form method="POST" id="rejectNoteForm" class="modal-box relative w-full max-w-md rounded-lg bg-white p-6 shadow dark:bg-gray-700">
        @csrf
        <input type="hidden" name="id" id="reject_barang_id">
        <div class="mb-4">
            <label for="reject_note" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alasan Penolakan</label>
            <textarea name="catatan" id="reject_note" rows="3" required class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-white"></textarea>
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" id="closeRejectNoteModal" class="rounded bg-gray-400 px-4 py-2 text-white">Cancel</button>
            <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white">Send</button>
        </div>
    </form>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
