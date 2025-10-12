<div id="requestOrderModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
  <div class="absolute inset-0 bg-black opacity-50"></div>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-3xl w-full z-10 overflow-hidden">
    <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center">
      <h3 class="text-lg font-medium" id="rom_order_number">Detail Order</h3>
      <button id="rom_close" class="text-gray-500 hover:text-gray-700">&times;</button>
    </div>

    <div class="p-4 space-y-2">
      <p><strong>Sales:</strong> <span id="rom_sales">-</span></p>
      <p><strong>Customer:</strong> <span id="rom_customer">-</span></p>
      <p><strong>Status:</strong> <span id="rom_status">-</span></p>

      <div class="overflow-x-auto">
        <table class="table-auto w-full text-left">
          <thead>
            <tr>
              <th class="px-2 py-1">#</th>
              <th class="px-2 py-1">Barang</th>
              <th class="px-2 py-1">Jumlah</th>
              <th class="px-2 py-1">Status Item</th>
            </tr>
          </thead>
          <tbody id="rom_items">
            <!-- items inserted by JS -->
          </tbody>
        </table>
      </div>
    </div>

    <div class="p-4 border-t dark:border-gray-700 text-right">
      <button id="rom_close_footer" class="btn btn-light">Tutup</button>
    </div>
  </div>
</div>
