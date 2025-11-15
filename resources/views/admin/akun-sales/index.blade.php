<x-app-layout>
    <div class="relative h-full overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Kelola akun sales</h2>
            </div>
        </div>

        <div class="p-4">
            <button id="createUserButton" class="px-4 py-2 text-white bg-blue-500 rounded">Tambah Akun Sales</button>
        </div>

        <div class="p-4">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Nama</th>
                        <th class="border border-gray-300 px-4 py-2">Email</th>
                        <th class="border border-gray-300 px-4 py-2">Penjualan Sukses</th>
                        <th class="border border-gray-300 px-4 py-2">Total Barang Terjual</th>
                        <th class="border border-gray-300 px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salesUsers as $user)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $user->orders()->where('status', 'completed')->count() }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $user->orders()->where('status', 'completed')->withSum('orderItems', 'quantity')->get()->sum('order_items_sum_quantity') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button class="editUserButton px-2 py-1 text-white bg-yellow-500 rounded" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}">Edit</button>
                                <form action="{{ route('akun-sales.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-white bg-red-500 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->
    @include('components.akun-sales-modal')
    @vite(['resources/js/akun-sales.js'])
</x-app-layout>
