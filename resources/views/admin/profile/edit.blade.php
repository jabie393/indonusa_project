<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Profile') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm shadow-md dark:bg-gray-800 sm:rounded-xl sm:p-8">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white p-4 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm shadow-md dark:bg-gray-800 sm:rounded-xl sm:p-8">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-white p-4 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm shadow-md dark:bg-gray-800 sm:rounded-xl sm:p-8">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
