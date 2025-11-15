    <!-- Create Sales Account Modal -->
    <div id="createSalesAccountModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-lg font-semibold mb-4">Tambah Akun Sales</h2>
            <form action="{{ route('akun-sales.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="createName" class="block text-sm font-medium">Nama</label>
                    <input type="text" id="createName" name="name" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="createEmail" class="block text-sm font-medium">Email</label>
                    <input type="email" id="createEmail" name="email" autocomplete="new-email" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="createPassword" class="block text-sm font-medium">Password</label>
                    <input type="password" id="createPassword" name="password" autocomplete="new-password" class="w-full border rounded px-3 py-2">
                </div>
                <div class="flex justify-end">
                    <button type="button" id="closeCreateModal" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Sales Account Modal -->
    <div id="editSalesAccountModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-lg font-semibold mb-4">Edit Akun Sales</h2>
            <form id="editUserForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="id">
                <div class="mb-4">
                    <label for="editName" class="block text-sm font-medium">Nama</label>
                    <input type="text" id="editName" name="name" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="editEmail" class="block text-sm font-medium">Email</label>
                    <input type="email" id="editEmail" name="email" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="editPassword" class="block text-sm font-medium">Password</label>
                    <input type="password" id="editPassword" name="password" placeholder="Isi untuk mengubah password" autocomplete="new-password" class="w-full border rounded px-3 py-2">
                </div>
                <div class="flex justify-end">
                    <button type="button" id="closeEditModal" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
