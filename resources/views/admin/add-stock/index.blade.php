<x-app-layout>
    <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div
                class="flex w-full flex-shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">

            </div>
        </div>


    </div>
    </div>

    </div>

    <!-- Modals -->
    @include('components.barang-modal-tambah')

    <!-- Js -->
    @vite(['resources/js/barang.js'])
</x-app-layout>
