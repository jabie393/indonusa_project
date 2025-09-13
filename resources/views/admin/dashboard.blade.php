<x-app-layout>
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-xl font-bold">
                        Halo, {{ Auth::user()->name }}
                    </h1>

                    <p class="mt-2">
                        Anda login sebagai
                        <span class="font-semibold">
                            {{ Auth::user()->role ?? 'User' }}
                        </span>
                    </p>

                    <p class="mt-4">
                        {{ __("Selamat datang di Dashboard!") }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
