<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <!-- Title Header Card -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-6 shadow-md text-white">
            <h2 class="text-xl font-bold tracking-wide">Pengaturan Sistem</h2>
            <p class="text-xs text-white/80 mt-1">Konfigurasi nama pimpinan, jabatan, dan informasi perusahaan untuk dokumen resmi (Laporan, PDF, & Excel).</p>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold shadow-sm animate-fade-in">
                <div class="flex items-start gap-2.5">
                    <svg class="h-5 w-5 text-rose-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <span class="font-bold">Gagal menyimpan pengaturan:</span>
                        <ul class="list-disc list-inside mt-1 space-y-1 font-normal">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Company Profile Card -->
            <div class="bg-white p-6 shadow-md dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                <div class="border-b border-gray-100 dark:border-gray-700 pb-3 mb-5">
                    <h3 class="text-lg font-bold text-[#225A97] dark:text-white">Profil Perusahaan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Informasi perusahaan yang akan muncul di header laporan Excel dan PDF.</p>
                </div>
                
                <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <x-input-label for="company_name" :value="__('Nama Perusahaan')" />
                        <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $settings['company_name'])" required />
                    </div>

                    <div>
                        <x-input-label for="company_email" :value="__('Email Perusahaan')" />
                        <x-text-input id="company_email" name="company_email" type="email" class="mt-1 block w-full" :value="old('company_email', $settings['company_email'])" required />
                    </div>

                    <div>
                        <x-input-label for="company_phone" :value="__('Nomor Telepon Perusahaan')" />
                        <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full" :value="old('company_phone', $settings['company_phone'])" required />
                    </div>

                    <div>
                        <x-input-label for="company_address" :value="__('Alamat Perusahaan')" />
                        <textarea id="company_address" name="company_address" rows="3" 
                            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm text-sm" 
                            required>{{ old('company_address', $settings['company_address']) }}</textarea>
                    </div>
            </div>

            <!-- Leadership / Pimpinan Card -->
            <div class="bg-white p-6 shadow-md dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50 flex flex-col justify-between">
                <div>
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-[#225A97] dark:text-white">Informasi Pimpinan</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pejabat berwenang yang menyetujui dan menandatangani laporan.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="leader_name" :value="__('Nama Pimpinan')" />
                            <x-text-input id="leader_name" name="leader_name" type="text" class="mt-1 block w-full" :value="old('leader_name', $settings['leader_name'])" required />
                        </div>

                        <div>
                            <x-input-label for="leader_position" :value="__('Jabatan Pimpinan')" />
                            <x-text-input id="leader_position" name="leader_position" type="text" class="mt-1 block w-full" :value="old('leader_position', $settings['leader_position'])" required />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end border-t border-gray-100 dark:border-gray-700 pt-4">
                    <x-primary-button class="bg-[#225A97] hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 rounded-xl px-6 py-2.5">
                        {{ __('Simpan Perubahan') }}
                    </x-primary-button>
                </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
