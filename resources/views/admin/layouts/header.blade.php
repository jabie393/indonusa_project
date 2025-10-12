<div class="bg-gray-50 antialiased dark:bg-gray-900">
    <nav class="fixed left-0 right-0 top-0 z-50 bg-gradient-to-r from-[#225A97] to-[#0B1D31] px-4 py-2.5 dark:bg-gradient-to-r dark:from-[#0B1D31] dark:to-[#225A97]">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center justify-start">
                <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation" aria-controls="drawer-navigation"
                    class="mr-2 cursor-pointer rounded-lg p-2 text-white hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:bg-gray-700 dark:focus:ring-gray-700 md:hidden">
                    <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <svg aria-hidden="true" class="hidden h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Toggle sidebar</span>
                </button>

                {{-- logo --}}
                <a href="{{ route('dashboard') }}" class="hidden md:flex mr-4 items-center justify-between">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mr-3 h-8">
                    <span class="self-center whitespace-nowrap text-2xl font-semibold text-white">Indonusa</span>
                </a>


            </div>
            <div class="flex items-center lg:order-2">
                

                <button id="theme-toggle" type="button" class="rounded-lg p-2.5 text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-700">
                    <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <!-- Dropdown menu -->

                <button type="button" class="mx-3 flex rounded-full bg-[#225A97] text-sm md:mr-0 hover:bg-[#1c4d81]" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                    <span class="sr-only">Open user menu</span>
                    <p class="inline-flex items-center px-4 py-2 text-white">
                        {{ Auth::user()->name }} ({{ Auth::user()->role }})
                        <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M5.70711 9.71069C5.31658 10.1012 5.31658 10.7344 5.70711 11.1249L10.5993 16.0123C11.3805 16.7927 12.6463 16.7924 13.4271 16.0117L18.3174 11.1213C18.708 10.7308 18.708 10.0976 18.3174 9.70708C17.9269 9.31655 17.2937 9.31655 16.9032 9.70708L12.7176 13.8927C12.3271 14.2833 11.6939 14.2832 11.3034 13.8927L7.12132 9.71069C6.7308 9.32016 6.09763 9.32016 5.70711 9.71069Z"
                                    fill="#ffffff"></path>
                            </g>
                        </svg>
                    </p>
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 my-4 hidden w-56 list-none divide-y divide-gray-100 rounded rounded-xl bg-white text-base shadow dark:divide-gray-600 dark:bg-gray-700" id="dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                        <span class="block truncate text-sm text-gray-900 dark:text-white">{{ Auth::user()->email }}</span>
                    </div>
                    <ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
                        <li>
                            <a href="{{ route('profile.edit') }}"
                                class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800">{{ __('Profile') }}</a>
                        </li>
                    </ul>
                    <ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

</div>
