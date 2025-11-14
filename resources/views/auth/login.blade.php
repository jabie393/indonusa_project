<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-4 block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-4 flex flex-col items-center justify-end">
            <x-primary-button class="h-[55px] w-[309px] justify-center rounded-[30px] bg-[#2784E9]">
                {{ __('Log in') }}
            </x-primary-button>

            @if (Route::has('password.request'))
                <a class="mt-5 rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif


        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Attempt to fetch modal data; route returns 200 JSON when a pending login exists
            fetch('{{ route('confirm.login') }}')
                .then(response => {
                    if (response.ok) return response.json();
                    throw new Error('No pending login');
                })
                .then(data => {
                    Swal.fire({
                        title: data.title,
                        html: data.html,
                        icon: data.icon,
                        showCancelButton: data.showCancelButton,
                        confirmButtonColor: data.confirmButtonColor,
                        cancelButtonColor: data.cancelButtonColor,
                        confirmButtonText: data.confirmButtonText,
                        cancelButtonText: data.cancelButtonText,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch('{{ route('auth.continue-session') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    },
                                })
                                .then(r => r.json())
                                .then(json => {
                                    window.location.href = json['redirect'] ?? '{{ route('dashboard') }}';
                                })
                                .catch(() => window.location.href = '{{ route('dashboard') }}');
                        } else {
                            fetch('{{ route('auth.cancel-login') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    },
                                })
                                .then(r => r.json())
                                .then(json => {
                                    window.location.href = json['redirect'] ?? '{{ route('login') }}';
                                })
                                .catch(() => window.location.href = '{{ route('login') }}');
                        }
                    });
                })
                .catch(() => {
                    // No pending login; nothing to do
                });
        });
    </script>
</x-guest-layout>
