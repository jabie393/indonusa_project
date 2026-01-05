<!-- Sidebar -->
<aside
    class="mt-22 fixed left-0 top-0 z-40 h-full w-64 -translate-x-full rounded-xl shadow-sm transition-transform lg:relative lg:mt-0 lg:w-full lg:translate-x-0"
    aria-label="Sidenav" id="drawer-navigation">
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm h-full overflow-y-auto rounded-xl bg-white px-3 py-5 dark:bg-[#0D223A]">

        <ul class="space-y-2">
            {{-- Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                    <svg aria-hidden="true"
                        class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 transition duration-75 group-hover:text-white"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span
                        class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Dashboard</span>
                </a>
            </li>

            {{-- Menu untuk General Affair --}}
            @if (in_array(auth()->user()->role, ['General Affair']))
                {{-- Goods In --}}
                <li>
                    <a href="{{ route('goods-in.index') }}"
                        class="{{ request()->routeIs('goods-in.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27"
                            fill="none"
                            class="{{ request()->routeIs('goods-in.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M13.5 0.0795898L26.625 6.79129V20.2147L13.5 26.9264L0.375 20.2147V6.79129L13.5 0.0795898ZM9.12493 12.6078L9.125 21.7063L12.0417 23.1978V14.0993L9.12493 12.6078ZM3.29167 9.62501V18.7232L6.20831 20.2148V11.1164L3.29167 9.62501ZM19.2416 5.99859L10.6571 10.4086L13.5 11.8623L22.1042 7.46246L19.2416 5.99859ZM13.5 3.06257L4.89583 7.46246L7.7438 8.91877L16.3283 4.50884L13.5 3.06257Z"
                                fill="currentColor" />
                        </svg>
                        <span
                            class="{{ request()->routeIs('goods-in.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Goods
                            In</span>
                    </a>
                    {{-- Add Stock --}}
                    @if (request()->routeIs('add-stock.*'))
                        <ul class="pt-2">
                            <li class="flex items-center justify-end">
                                <svg width="64px"
                                    class="h-6 w-6 text-black transition duration-75 group-hover:text-gray-900 dark:text-gray-100 dark:group-hover:text-white"
                                    height="64px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M3 4C3 3.44772 3.44771 3 4 3C4.55229 3 5 3.44772 5 4L5 11C5 11.7956 5.31607 12.5587 5.87868 13.1213C6.44129 13.6839 7.20435 14 8 14H17.5858L14.2929 10.7071C13.9024 10.3166 13.9024 9.68342 14.2929 9.29289C14.6834 8.90237 15.3166 8.90237 15.7071 9.29289L20.7071 14.2929C21.0976 14.6834 21.0976 15.3166 20.7071 15.7071L15.7071 20.7071C15.3166 21.0976 14.6834 21.0976 14.2929 20.7071C13.9024 20.3166 13.9024 19.6834 14.2929 19.2929L17.5858 16H8C6.67392 16 5.40215 15.4732 4.46447 14.5355C3.52678 13.5979 3 12.3261 3 11V4Z"
                                            fill="currentColor">
                                        </path>
                                    </g>
                                </svg>
                                <a href="{{ route('add-stock.index') }}"
                                    class="group ml-2 flex w-[75%] items-center rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] p-2 text-base font-medium text-white transition-all duration-200 hover:shadow-lg">

                                    <span class="">Add Stock</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                    @if (request()->routeIs('import-excel.*'))
                        <ul class="pt-2">
                            <li class="flex items-center justify-end">
                                <svg width="64px"
                                    class="h-6 w-6 text-black transition duration-75 group-hover:text-gray-900 dark:text-gray-100 dark:group-hover:text-white"
                                    height="64px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M3 4C3 3.44772 3.44771 3 4 3C4.55229 3 5 3.44772 5 4L5 11C5 11.7956 5.31607 12.5587 5.87868 13.1213C6.44129 13.6839 7.20435 14 8 14H17.5858L14.2929 10.7071C13.9024 10.3166 13.9024 9.68342 14.2929 9.29289C14.6834 8.90237 15.3166 8.90237 15.7071 9.29289L20.7071 14.2929C21.0976 14.6834 21.0976 15.3166 20.7071 15.7071L15.7071 20.7071C15.3166 21.0976 14.6834 21.0976 14.2929 20.7071C13.9024 20.3166 13.9024 19.6834 14.2929 19.2929L17.5858 16H8C6.67392 16 5.40215 15.4732 4.46447 14.5355C3.52678 13.5979 3 12.3261 3 11V4Z"
                                            fill="currentColor">
                                        </path>
                                    </g>
                                </svg>
                                <a href="{{ route('import-excel.index') }}"
                                    class="group ml-2 flex w-[75%] items-center rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] p-2 text-base font-medium text-white transition-all duration-200 hover:shadow-lg">

                                    <span class="">Import Excel</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                    @if (request()->routeIs('import-stock-excel.*'))
                        <ul class="pt-2">
                            <li class="flex items-center justify-end">
                                <svg width="64px"
                                    class="h-6 w-6 text-black transition duration-75 group-hover:text-gray-900 dark:text-gray-100 dark:group-hover:text-white"
                                    height="64px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M3 4C3 3.44772 3.44771 3 4 3C4.55229 3 5 3.44772 5 4L5 11C5 11.7956 5.31607 12.5587 5.87868 13.1213C6.44129 13.6839 7.20435 14 8 14H17.5858L14.2929 10.7071C13.9024 10.3166 13.9024 9.68342 14.2929 9.29289C14.6834 8.90237 15.3166 8.90237 15.7071 9.29289L20.7071 14.2929C21.0976 14.6834 21.0976 15.3166 20.7071 15.7071L15.7071 20.7071C15.3166 21.0976 14.6834 21.0976 14.2929 20.7071C13.9024 20.3166 13.9024 19.6834 14.2929 19.2929L17.5858 16H8C6.67392 16 5.40215 15.4732 4.46447 14.5355C3.52678 13.5979 3 12.3261 3 11V4Z"
                                            fill="currentColor">
                                        </path>
                                    </g>
                                </svg>
                                <a href="{{ route('import-stock-excel.index') }}"
                                    class="group ml-2 flex w-[75%] items-center rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] p-2 text-base font-medium text-white transition-all duration-200 hover:shadow-lg">

                                    <span class="">Import Stok Excel</span>
                                </a>
                            </li>
                        </ul>
                    @endif
                </li>



                {{-- Goods In Status --}}
                <li>
                    <a href="{{ route('goods-in-status.index') }}"
                        class="{{ request()->routeIs('goods-in-status.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="29" viewBox="0 0 28 29"
                            fill="currentColor"
                            class="{{ request()->routeIs('goods-in-status.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                            <path
                                d="M8.20312 13.0952H19.8187V14.4546H8.20312V13.0952ZM8.20312 19.0765H15.3125V20.4358H8.20312V19.0765Z"
                                fill="currentColor" />
                            <path
                                d="M22.3781 27.7992H5.62188C5.27188 27.7992 4.94375 27.7313 4.61563 27.5953C4.30938 27.4594 4.025 27.2781 3.80625 27.0289C3.56562 26.7797 3.39062 26.5078 3.25937 26.1906C3.12812 25.8508 3.0625 25.5109 3.0625 25.1484V7.09141C3.0625 6.72891 3.12812 6.38906 3.25937 6.04922C3.39062 5.73203 3.56562 5.4375 3.80625 5.21094C4.04687 4.96172 4.30938 4.78047 4.61563 4.64453C4.94375 4.50859 5.27188 4.44063 5.62188 4.44063H8.15937V5.77734H5.62188C4.92188 5.77734 4.33125 6.36641 4.33125 7.11406V25.1711C4.33125 25.8961 4.9 26.5078 5.62188 26.5078H22.3562C23.0562 26.5078 23.6469 25.9188 23.6469 25.1711V7.09141C23.6469 6.36641 23.0781 5.75469 22.3562 5.75469H19.8406V4.41797H22.3781C22.7281 4.41797 23.0562 4.48594 23.3844 4.62187C23.6906 4.75781 23.975 4.93906 24.1938 5.18828C24.4344 5.4375 24.6094 5.70938 24.7406 6.02656C24.8719 6.36641 24.9375 6.70625 24.9375 7.06875V25.1258C24.9375 25.4883 24.8719 25.8281 24.7406 26.168C24.6094 26.4852 24.4344 26.7797 24.1938 27.0063C23.9531 27.2555 23.6906 27.4367 23.3844 27.5727C23.0562 27.7313 22.7281 27.7992 22.3781 27.7992Z"
                                fill="currentColor" />
                            <path
                                d="M20.4313 8.42822H7.56885V4.07822C7.56885 3.14932 8.29072 2.40166 9.1876 2.40166H10.8501C11.4407 1.17822 12.6657 0.385254 14.022 0.385254C15.3782 0.385254 16.6032 1.17822 17.1938 2.40166H18.8563C19.7532 2.40166 20.4751 3.14932 20.4751 4.07822L20.4313 8.42822ZM8.85947 7.0915H19.1626V4.07822C19.1626 3.89697 19.0095 3.73838 18.8345 3.73838H16.2751L16.122 3.28525C15.8157 2.35635 14.9626 1.72197 14.0001 1.72197C13.0376 1.72197 12.1845 2.35635 11.8782 3.28525L11.7251 3.73838H9.16572C8.99072 3.73838 8.8376 3.89697 8.8376 4.07822V7.0915H8.85947Z"
                                fill="currentColor" />
                        </svg>
                        <span
                            class="{{ request()->routeIs('goods-in-status.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Goods
                            In Status</span>
                    </a>
                </li>
                {{-- Manajemen Entitas (collapsible) --}}
                <details
                    {{ request()->routeIs('akun-sales.*') || request()->routeIs('customer.*') || request()->routeIs('pics.*') ? 'open' : '' }}
                    class="">
                    <summary
                        class="{{ request()->routeIs('akun-sales.*') || request()->routeIs('customer.*') || request()->routeIs('pics.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex cursor-pointer items-center rounded-lg p-2 text-base font-medium transition-all duration-200">

                        <svg width="28px" height="28px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                            fill="#000000"
                            class="{{ request()->routeIs('akun-sales.*') || request()->routeIs('customer.*') || request()->routeIs('pics.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill="currentColor"
                                    d="M13.098 8H6.902c-.751 0-1.172.754-.708 1.268L9.292 12.7c.36.399 1.055.399 1.416 0l3.098-3.433C14.27 8.754 13.849 8 13.098 8Z">
                                </path>
                            </g>
                        </svg>
                        <span
                            class="{{ request()->routeIs('akun-sales.*') || request()->routeIs('customer.*') || request()->routeIs('pics.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Manajemen
                            Entitas</span>
                    </summary>

                    <ul
                        class="before:left-4.5 relative flex flex-col items-end space-y-2 pt-2 before:absolute before:bottom-[.75rem] before:start-0 before:top-[.75rem] before:w-1 before:bg-black before:opacity-10 before:content-[''] dark:before:bg-white">

                        {{-- Akun Sales --}}
                        <li class="w-[75%]">
                            <a href="{{ route('akun-sales.index') }}"
                                class="{{ request()->routeIs('akun-sales.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="{{ request()->routeIs('akun-sales.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                                    <path
                                        d="M19.6436 13.8193C19.4355 13.7285 19.2275 13.6465 19.0166 13.5703C20.4756 12.3896 21.4072 10.5879 21.4072 8.57227C21.4072 5.02734 18.5244 2.14453 14.9795 2.14453C11.4346 2.14453 8.55176 5.02734 8.55176 8.57227C8.55176 10.5908 9.48633 12.3955 10.9453 13.5732C6.42773 15.2227 3.19336 19.5645 3.19336 24.6475H5.33496C5.33496 19.3301 9.66211 15.0059 14.9766 15.0059C16.3037 15.0059 17.5869 15.2695 18.791 15.7881L19.6436 13.8193ZM14.9795 4.28613C17.3438 4.28613 19.2656 6.20801 19.2656 8.57227C19.2656 10.9365 17.3438 12.8584 14.9795 12.8584C12.6152 12.8584 10.6934 10.9336 10.6934 8.57227C10.6934 6.21094 12.6152 4.28613 14.9795 4.28613ZM17.9443 18.6475C17.9443 18.9463 18.1084 19.2188 18.3721 19.3564L22.1221 21.3369C22.2393 21.3984 22.3682 21.4307 22.4971 21.4307C22.626 21.4307 22.7549 21.3984 22.8721 21.3369L26.6221 19.3564C26.8857 19.2158 27.0498 18.9434 27.0498 18.6475C27.0498 18.3516 26.8857 18.0762 26.6221 17.9385L22.8721 15.9551C22.6377 15.832 22.3564 15.832 22.1221 15.9551L18.3721 17.9385C18.1084 18.0762 17.9443 18.3486 17.9443 18.6475ZM22.4971 17.5752L24.5273 18.6475L22.4971 19.7197L20.4668 18.6475L22.4971 17.5752Z"
                                        fill="currentColor" />
                                    <path
                                        d="M22.4971 22.3652L18.1904 20.0889L17.4404 21.5098L22.4971 24.1816L27.5332 21.5215L26.7832 20.1006L22.4971 22.3652Z"
                                        fill="currentColor" />
                                    <path
                                        d="M22.4971 24.9434L18.1904 22.6699L17.4404 24.0879L22.4971 26.7627L27.5156 24.1084L26.7656 22.6875L22.4971 24.9434Z"
                                        fill="currentColor" />
                                </svg>

                                <span
                                    class="{{ request()->routeIs('akun-sales.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Akun
                                    Sales
                                </span>
                            </a>
                        </li>

                        {{-- Customer --}}
                        <li class="w-[75%]">
                            <a href="{{ route('customer.index') }}"
                                class="{{ request()->routeIs('customer.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="{{ request()->routeIs('customer.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                                    <path
                                        d="M23.3027 21.9639C23.8066 21.3252 24.1084 20.5195 24.1084 19.6406C24.1084 17.5693 22.4297 15.8906 20.3584 15.8906C18.2871 15.8906 16.6084 17.5693 16.6084 19.6406C16.6084 20.5166 16.9102 21.3252 17.4141 21.9639C15.3457 23.0332 13.9307 25.1924 13.9307 27.6768H16.0723C16.0723 25.3125 17.9941 23.3906 20.3584 23.3906C22.7227 23.3906 24.6445 25.3125 24.6445 27.6768H26.7861C26.7861 25.1953 25.3682 23.0361 23.3027 21.9639ZM20.3584 18.0352C21.2432 18.0352 21.9668 18.7559 21.9668 19.6436C21.9668 20.5283 21.2461 21.252 20.3584 21.252C19.4736 21.252 18.75 20.5312 18.75 19.6436C18.75 18.7559 19.4707 18.0352 20.3584 18.0352Z"
                                        fill="currentColor" />
                                    <path
                                        d="M3.21387 2.1416V27.8584H12.8584V25.7139H5.3584V4.28613H24.6445V15H26.7861V2.1416H3.21387Z"
                                        fill="currentColor" />
                                    <path
                                        d="M7.5 7.5H22.5V9.6416H7.5V7.5ZM7.5 11.7861H18.2139V13.9277H7.5V11.7861ZM7.5 16.0723H13.9277V18.2139H7.5V16.0723Z"
                                        fill="currentColor" />
                                </svg>

                                <span
                                    class="{{ request()->routeIs('customer.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Customer</span>
                            </a>
                        </li>

                        {{-- Pics --}}
                        <li class="w-[75%]">
                            <a href="{{ route('pics.index') }}"
                                class="{{ request()->routeIs('pics.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="{{ request()->routeIs('pics.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                                    <path
                                        d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                        fill="currentColor" />
                                    <path
                                        d="M12.0002 14.5C6.99016 14.5 2.91016 17.86 2.91016 22C2.91016 22.28 3.13016 22.5 3.41016 22.5H20.5902C20.8702 22.5 21.0902 22.28 21.0902 22C21.0902 17.86 17.0102 14.5 12.0002 14.5Z"
                                        fill="currentColor" />
                                </svg>

                                <span
                                    class="{{ request()->routeIs('pics.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Pics</span>
                            </a>
                        </li>
                    </ul>
                </details>

                {{-- History --}}
                <li>
                    <a href="{{ route('history.index') }}"
                        class="{{ request()->routeIs('history.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                        <svg width="28px" height="28px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="{{ request()->routeIs('history.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M3 5.67541V3C3 2.44772 2.55228 2 2 2C1.44772 2 1 2.44772 1 3V7C1 8.10457 1.89543 9 3 9H7C7.55229 9 8 8.55229 8 8C8 7.44772 7.55229 7 7 7H4.52186C4.54218 6.97505 4.56157 6.94914 4.57995 6.92229C5.621 5.40094 7.11009 4.22911 8.85191 3.57803C10.9074 2.80968 13.173 2.8196 15.2217 3.6059C17.2704 4.3922 18.9608 5.90061 19.9745 7.8469C20.9881 9.79319 21.2549 12.043 20.7247 14.1724C20.1945 16.3018 18.9039 18.1638 17.0959 19.4075C15.288 20.6513 13.0876 21.1909 10.9094 20.9247C8.73119 20.6586 6.72551 19.605 5.27028 17.9625C4.03713 16.5706 3.27139 14.8374 3.06527 13.0055C3.00352 12.4566 2.55674 12.0079 2.00446 12.0084C1.45217 12.0088 0.995668 12.4579 1.04626 13.0078C1.25994 15.3309 2.2082 17.5356 3.76666 19.2946C5.54703 21.3041 8.00084 22.5931 10.6657 22.9188C13.3306 23.2444 16.0226 22.5842 18.2345 21.0626C20.4464 19.541 22.0254 17.263 22.6741 14.6578C23.3228 12.0526 22.9963 9.30013 21.7562 6.91897C20.5161 4.53782 18.448 2.69239 15.9415 1.73041C13.4351 0.768419 10.6633 0.756291 8.14853 1.69631C6.06062 2.47676 4.26953 3.86881 3 5.67541Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M12 5C11.4477 5 11 5.44771 11 6V12.4667C11 12.4667 11 12.7274 11.1267 12.9235C11.2115 13.0898 11.3437 13.2344 11.5174 13.3346L16.1372 16.0019C16.6155 16.278 17.2271 16.1141 17.5032 15.6358C17.7793 15.1575 17.6155 14.546 17.1372 14.2698L13 11.8812V6C13 5.44772 12.5523 5 12 5Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                        <span
                            class="{{ request()->routeIs('history.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">History</span>
                    </a>
                </li>
            @endif

            {{-- Menu untuk Warehouse --}}
            @if (in_array(auth()->user()->role, ['Warehouse']))
                <details
                    {{ request()->routeIs('supply-orders.*') || request()->routeIs('delivery-orders.*') ? 'open' : 'close' }}
                    class="">
                    <summary
                        class="{{ request()->routeIs('supply-orders.*') || request()->routeIs('delivery-orders.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex cursor-pointer items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                        <svg width="28px" height="28px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                            fill="#000000"
                            class="{{ request()->routeIs('supply-orders.*') || request()->routeIs('delivery-orders.*') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 transition duration-75 group-hover:text-white">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill="currentColor"
                                    d="M13.098 8H6.902c-.751 0-1.172.754-.708 1.268L9.292 12.7c.36.399 1.055.399 1.416 0l3.098-3.433C14.27 8.754 13.849 8 13.098 8Z">
                                </path>
                            </g>
                        </svg>
                        <span
                            class="{{ request()->routeIs('supply-orders.*') || request()->routeIs('delivery-orders.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">
                            Orders</span>
                    </summary>

                    <ul
                        class="before:left-4.5 relative flex flex-col items-end space-y-2 pt-2 before:absolute before:bottom-[.75rem] before:start-0 before:top-[.75rem] before:w-1 before:bg-black before:opacity-10 before:content-[''] dark:before:bg-white">
                        {{-- Supply Orders --}}
                        <li class="w-[75%]">
                            <a href="{{ route('supply-orders.index') }}"
                                class="{{ request()->routeIs('supply-orders.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg class="{{ request()->routeIs('supply-orders.*') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 transition duration-75 group-hover:text-white"
                                    viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.5 0.20752L14.625 4.10463V11.8989L7.5 15.796L0.375 11.8989V4.10463L7.5 0.20752ZM5.12496 7.48198L5.125 12.7649L6.70833 13.631V8.34802L5.12496 7.48198ZM1.95833 5.75002V11.0328L3.54165 11.8989V6.61601L1.95833 5.75002ZM10.6169 3.64436L5.95673 6.205L7.5 7.04912L12.1708 4.49435L10.6169 3.64436ZM7.5 1.93957L2.82917 4.49435L4.37521 5.33995L9.03536 2.77934L7.5 1.93957Z"
                                        fill="currentColor" />
                                </svg>

                                <span
                                    class="{{ request()->routeIs('supply-orders.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Supply
                                    Orders</span>
                                @php
                                    $supplyOrderCount = \App\Models\Barang::where('status_barang', 'ditinjau')->count();
                                @endphp
                                <span id="supply-orders-notif-badge"
                                    class="{{ $supplyOrderCount > 0 ? '' : 'hidden' }} ml-2 rounded-full bg-red-500 px-2 py-1 text-xs text-white">{{ $supplyOrderCount }}</span>
                            </a>
                        </li>

                        {{-- Delivery Orders --}}
                        <li class="w-[75%]">
                            <a href="{{ route('delivery-orders.index') }}"
                                class="{{ request()->routeIs('delivery-orders.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg class="{{ request()->routeIs('delivery-orders.*') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 transition duration-75 group-hover:text-white"
                                    viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.6792 3.66661C12.4758 3.46392 12.1961 3.34427 11.9001 3.33328H11.3334V2.66661C11.3383 2.53404 11.3142 2.40192 11.2626 2.27849C11.211 2.15506 11.1329 2.04295 11.0332 1.94915C10.9335 1.85534 10.8144 1.78185 10.6833 1.73324C10.5521 1.68464 10.4118 1.66196 10.2709 1.66661H1.7709C1.63003 1.66196 1.48966 1.68464 1.35852 1.73324C1.22737 1.78185 1.10826 1.85534 1.00859 1.94915C0.908924 2.04295 0.830838 2.15506 0.779193 2.27849C0.727548 2.40192 0.703449 2.53404 0.708397 2.66661V11.6666C0.703449 11.7992 0.727548 11.9313 0.779193 12.0547C0.830838 12.1782 0.908924 12.2903 1.00859 12.3841C1.10826 12.4779 1.22737 12.5514 1.35852 12.6C1.48966 12.6486 1.63003 12.6713 1.7709 12.6666H2.58548C2.7396 13.146 3.05354 13.566 3.48086 13.8645C3.90819 14.163 4.42623 14.3242 4.9584 14.3242C5.49056 14.3242 6.0086 14.163 6.43593 13.8645C6.86326 13.566 7.17719 13.146 7.33131 12.6666H9.66881C9.82294 13.146 10.1369 13.566 10.5642 13.8645C10.9915 14.163 11.5096 14.3242 12.0417 14.3242C12.5739 14.3242 13.0919 14.163 13.5193 13.8645C13.9466 13.566 14.2605 13.146 14.4146 12.6666H15.2292C15.3701 12.6713 15.5105 12.6486 15.6416 12.6C15.7728 12.5514 15.8919 12.4779 15.9915 12.3841C16.0912 12.2903 16.1693 12.1782 16.2209 12.0547C16.2726 11.9313 16.2967 11.7992 16.2917 11.6666V7.39994L12.6792 3.66661ZM11.723 4.66661L14.3084 7.33328H11.3334V4.66661H11.723ZM2.12506 2.99994H9.91673V10.7999C9.81287 10.9683 9.7297 11.1472 9.66881 11.3333H7.33131C7.17719 10.8539 6.86326 10.4339 6.43593 10.1354C6.0086 9.83688 5.49056 9.67572 4.9584 9.67572C4.42623 9.67572 3.90819 9.83688 3.48086 10.1354C3.05354 10.4339 2.7396 10.8539 2.58548 11.3333H2.12506V2.99994ZM4.9584 12.9999C4.74825 12.9999 4.54283 12.9413 4.3681 12.8314C4.19338 12.7215 4.05719 12.5654 3.97677 12.3826C3.89636 12.1999 3.87532 11.9988 3.91631 11.8049C3.95731 11.6109 4.0585 11.4327 4.2071 11.2928C4.35569 11.153 4.54501 11.0577 4.75111 11.0192C4.95722 10.9806 5.17085 11.0004 5.365 11.0761C5.55914 11.1518 5.72508 11.2799 5.84183 11.4444C5.95858 11.6088 6.0209 11.8022 6.0209 11.9999C6.02584 12.1325 6.00175 12.2646 5.9501 12.3881C5.89846 12.5115 5.82037 12.6236 5.7207 12.7174C5.62104 12.8112 5.50192 12.8847 5.37078 12.9333C5.23963 12.9819 5.09926 13.0046 4.9584 12.9999ZM12.0417 12.9999C11.8316 12.9999 11.6262 12.9413 11.4514 12.8314C11.2767 12.7215 11.1405 12.5654 11.0601 12.3826C10.9797 12.1999 10.9586 11.9988 10.9996 11.8049C11.0406 11.6109 11.1418 11.4327 11.2904 11.2928C11.439 11.153 11.6283 11.0577 11.8344 11.0192C12.0406 10.9806 12.2542 11.0004 12.4483 11.0761C12.6425 11.1518 12.8084 11.2799 12.9252 11.4444C13.0419 11.6088 13.1042 11.8022 13.1042 11.9999C13.1092 12.1325 13.0851 12.2646 13.0334 12.3881C12.9818 12.5115 12.9037 12.6236 12.804 12.7174C12.7044 12.8112 12.5853 12.8847 12.4541 12.9333C12.323 12.9819 12.1826 13.0046 12.0417 12.9999ZM14.4146 11.3333C14.2622 10.8522 13.9491 10.4302 13.5216 10.1299C13.0941 9.82968 12.5751 9.6672 12.0417 9.66661C11.8015 9.6644 11.5624 9.69816 11.3334 9.76661V8.66661H14.8751V11.3333H14.4146Z"
                                        fill="currentColor" />
                                </svg>

                                <span
                                    class="{{ request()->routeIs('delivery-orders.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Delivery
                                    Orders</span>
                                <span id="delivery-orders-notif-badge"
                                    class="ml-2 hidden rounded-full bg-red-500 px-2 py-1 text-xs text-white">0</span>
                            </a>
                        </li>

                    </ul>
                </details>
            @endif

            {{-- Warehouse --}}
            <li>
                <a href="{{ route('warehouse.index') }}"
                    class="{{ request()->routeIs('warehouse.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="31" viewBox="0 0 26 31"
                        fill="none"
                        class="{{ request()->routeIs('warehouse.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                        <path
                            d="M6.21582 14.4103H10.0143V18.1884H6.21582V14.4103ZM6.21582 23.2138H10.0143V19.4356H6.21582V23.2138ZM6.21582 28.227H10.0143V24.4489H6.21582V28.227ZM11.1416 23.2138H14.9299V19.4356H11.1314V23.2138H11.1416ZM11.1416 28.227H14.9299V24.4489H11.1314V28.227H11.1416ZM16.0674 24.4489V28.227H19.8658V24.4489H16.0674ZM25.8986 9.71182L13.0307 2.49463L0.172852 9.73603L0.975195 11.7341L2.61035 10.8017V28.2392H4.45879V10.729H21.6229V28.2634H23.4713V10.8259L25.1064 11.7341L25.8986 9.71182Z"
                            fill="currentColor" />
                    </svg>
                    <span
                        class="{{ request()->routeIs('warehouse.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Warehouse</span>
                </a>
            </li>

            {{-- Menu untuk Sales --}}
            @if (in_array(auth()->user()->role, ['Sales']))
                <details
                    {{ request()->routeIs('sales.request-order.*') || request()->routeIs('sales.custom-penawaran.*') || request()->routeIs('customer.*') ? 'open' : 'close' }}
                    class="">
                    <summary
                        class="{{ request()->routeIs('sales.request-order.*') || request()->routeIs('sales.custom-penawaran.*') || request()->routeIs('customer.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex cursor-pointer items-center rounded-lg p-2 text-base font-medium transition-all duration-200">

                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="{{ request()->routeIs('sales.request-order.*') || request()->routeIs('sales.custom-penawaran.*') || request()->routeIs('customer.*') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 transition duration-75 group-hover:text-white">
                            <g clip-path="url(#clip0_919_580)">
                                <path
                                    d="M26.25 0.937592H18.75C18.0041 0.937592 17.2887 1.23391 16.7613 1.76135C16.2338 2.2888 15.9375 3.00417 15.9375 3.75009V11.2501C15.9375 11.996 16.2338 12.7114 16.7613 13.2388C17.2887 13.7663 18.0041 14.0626 18.75 14.0626H26.25C26.996 14.0626 27.7113 13.7663 28.2388 13.2388C28.7662 12.7114 29.0625 11.996 29.0625 11.2501V3.75009C29.0625 3.00417 28.7662 2.2888 28.2388 1.76135C27.7113 1.23391 26.996 0.937592 26.25 0.937592ZM27.1875 11.2501C27.1875 11.4987 27.0888 11.7372 26.9129 11.913C26.7371 12.0888 26.4987 12.1876 26.25 12.1876H18.75C18.5014 12.1876 18.2629 12.0888 18.0871 11.913C17.9113 11.7372 17.8125 11.4987 17.8125 11.2501V3.75009C17.8125 3.50145 17.9113 3.26299 18.0871 3.08718C18.2629 2.91136 18.5014 2.81259 18.75 2.81259H26.25C26.4987 2.81259 26.7371 2.91136 26.9129 3.08718C27.0888 3.26299 27.1875 3.50145 27.1875 3.75009V11.2501ZM1.87503 6.56259C1.99754 6.56111 2.11857 6.53563 2.23128 6.48759C2.34481 6.43995 2.44942 6.37338 2.54066 6.29072C2.67074 6.15888 2.75886 5.99147 2.7939 5.8096C2.82894 5.62774 2.80932 5.43957 2.73753 5.26884C2.69291 5.15376 2.62601 5.04863 2.54066 4.95947C2.43241 4.84659 2.29765 4.76258 2.14865 4.7151C1.99964 4.66761 1.84112 4.65816 1.68753 4.68759C1.62883 4.69799 1.57198 4.71694 1.51878 4.74384C1.45895 4.76415 1.40218 4.79254 1.35003 4.82822L1.20941 4.94072C1.12405 5.02988 1.05715 5.13501 1.01253 5.25009C0.96203 5.36861 0.936499 5.49627 0.93753 5.62509C0.93753 5.87373 1.0363 6.11219 1.21212 6.288C1.38793 6.46382 1.62639 6.56259 1.87503 6.56259ZM4.38753 3.75009C4.57131 3.74866 4.75062 3.69323 4.90316 3.59072C5.8967 2.93288 7.10349 2.67811 8.27815 2.87822C8.5268 2.91924 8.78155 2.85982 8.98637 2.71301C9.1912 2.5662 9.32932 2.34404 9.37034 2.0954C9.41137 1.84676 9.35194 1.59201 9.20514 1.38718C9.05833 1.18236 8.83617 1.04424 8.58753 1.00322C6.95458 0.740149 5.2823 1.09802 3.90003 2.00634C3.72268 2.11155 3.58496 2.2723 3.50821 2.4637C3.43146 2.6551 3.41995 2.86646 3.47548 3.06505C3.531 3.26365 3.65045 3.4384 3.81533 3.56224C3.98021 3.68609 4.18133 3.75211 4.38753 3.75009ZM11.0719 3.04697C10.887 3.2125 10.7752 3.44461 10.7612 3.6924C10.7471 3.9402 10.8319 4.18346 10.9969 4.36884C11.7659 5.2303 12.1899 6.34532 12.1875 7.50009V11.2501C12.1875 11.4987 12.0888 11.7372 11.9129 11.913C11.7371 12.0888 11.4987 12.1876 11.25 12.1876H7.50003C6.53171 12.187 5.58732 11.8866 4.79664 11.3276C4.00597 10.7686 3.40783 9.97843 3.0844 9.06572C2.99013 8.84702 2.81615 8.67239 2.5978 8.57731C2.37946 8.48222 2.1331 8.4738 1.90877 8.55374C1.68444 8.63369 1.49894 8.79602 1.38994 9.00777C1.28094 9.21952 1.25662 9.46481 1.32191 9.69384C1.77479 10.9707 2.61176 12.0761 3.71793 12.8583C4.82409 13.6405 6.14524 14.0612 7.50003 14.0626H11.25C11.996 14.0626 12.7113 13.7663 13.2388 13.2388C13.7662 12.7114 14.0625 11.996 14.0625 11.2501V7.50009C14.0639 5.8851 13.4698 4.32629 12.3938 3.12197C12.2282 2.93703 11.9961 2.82529 11.7483 2.81123C11.5006 2.79717 11.2573 2.88195 11.0719 3.04697ZM26.25 15.9376H18.75C18.0041 15.9376 17.2887 16.2339 16.7613 16.7614C16.2338 17.2888 15.9375 18.0042 15.9375 18.7501V26.2501C15.9375 26.996 16.2338 27.7114 16.7613 28.2388C17.2887 28.7663 18.0041 29.0626 18.75 29.0626H26.25C26.996 29.0626 27.7113 28.7663 28.2388 28.2388C28.7662 27.7114 29.0625 26.996 29.0625 26.2501V18.7501C29.0625 18.0042 28.7662 17.2888 28.2388 16.7614C27.7113 16.2339 26.996 15.9376 26.25 15.9376ZM27.1875 26.2501C27.1875 26.4987 27.0888 26.7372 26.9129 26.913C26.7371 27.0888 26.4987 27.1876 26.25 27.1876H18.75C18.5014 27.1876 18.2629 27.0888 18.0871 26.913C17.9113 26.7372 17.8125 26.4987 17.8125 26.2501V18.7501C17.8125 18.5015 17.9113 18.263 18.0871 18.0872C18.2629 17.9114 18.5014 17.8126 18.75 17.8126H26.25C26.4987 17.8126 26.7371 17.9114 26.9129 18.0872C27.0888 18.263 27.1875 18.5015 27.1875 18.7501V26.2501ZM11.25 15.9376H3.75003C3.00411 15.9376 2.28874 16.2339 1.76129 16.7614C1.23385 17.2888 0.93753 18.0042 0.93753 18.7501V26.2501C0.93753 26.996 1.23385 27.7114 1.76129 28.2388C2.28874 28.7663 3.00411 29.0626 3.75003 29.0626H11.25C11.996 29.0626 12.7113 28.7663 13.2388 28.2388C13.7662 27.7114 14.0625 26.996 14.0625 26.2501V18.7501C14.0625 18.0042 13.7662 17.2888 13.2388 16.7614C12.7113 16.2339 11.996 15.9376 11.25 15.9376ZM12.1875 26.2501C12.1875 26.4987 12.0888 26.7372 11.9129 26.913C11.7371 27.0888 11.4987 27.1876 11.25 27.1876H3.75003C3.50139 27.1876 3.26293 27.0888 3.08712 26.913C2.9113 26.7372 2.81253 26.4987 2.81253 26.2501V18.7501C2.81253 18.5015 2.9113 18.263 3.08712 18.0872C3.26293 17.9114 3.50139 17.8126 3.75003 17.8126H11.25C11.4987 17.8126 11.7371 17.9114 11.9129 18.0872C12.0888 18.263 12.1875 18.5015 12.1875 18.7501V26.2501Z"
                                    fill="currentColor" />
                            </g>
                            <defs>
                                <clipPath id="clip0_919_580">
                                    <rect width="30" height="30" fill="currentColor" />
                                </clipPath>
                            </defs>
                        </svg>

                        <span
                            class="{{ request()->routeIs('sales.request-order.*') || request()->routeIs('sales.sales-order.*') || request()->routeIs('sales.custom-penawaran.*') || request()->routeIs('customer.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">
                            Sales Module</span>
                    </summary>

                    <ul
                        class="before:left-4.5 relative flex flex-col items-end space-y-2 pt-2 before:absolute before:bottom-[.75rem] before:start-0 before:top-[.75rem] before:w-1 before:bg-black before:opacity-10 before:content-[''] dark:before:bg-white">
                        {{-- Request Order --}}
                        <li class="w-[75%]">
                            <a href="{{ route('sales.request-order.index') }}"
                                class="{{ request()->routeIs('sales.request-order.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_931_10)">
                                        <path
                                            d="M9.375 20.625C10.3696 20.625 11.3234 20.2299 12.0266 19.5266C12.7299 18.8234 13.125 17.8696 13.125 16.875C13.125 15.8804 12.7299 14.9266 12.0266 14.2233C11.3234 13.5201 10.3696 13.125 9.375 13.125C8.38044 13.125 7.42661 13.5201 6.72335 14.2233C6.02009 14.9266 5.625 15.8804 5.625 16.875C5.625 17.8696 6.02009 18.8234 6.72335 19.5266C7.42661 20.2299 8.38044 20.625 9.375 20.625ZM9.375 15C9.87228 15 10.3492 15.1975 10.7008 15.5492C11.0525 15.9008 11.25 16.3777 11.25 16.875C11.25 17.3723 11.0525 17.8492 10.7008 18.2008C10.3492 18.5525 9.87228 18.75 9.375 18.75C8.87772 18.75 8.4008 18.5525 8.04917 18.2008C7.69754 17.8492 7.5 17.3723 7.5 16.875C7.5 16.3777 7.69754 15.9008 8.04917 15.5492C8.4008 15.1975 8.87772 15 9.375 15ZM14.0625 22.5H4.6875C3.4443 22.5 2.25201 22.9939 1.37294 23.8729C0.49386 24.752 0 25.9443 0 27.1875C0 27.9334 0.296318 28.6488 0.823764 29.1762C1.35121 29.7037 2.06658 30 2.8125 30H15.9375C16.6834 30 17.3988 29.7037 17.9262 29.1762C18.4537 28.6488 18.75 27.9334 18.75 27.1875C18.75 25.9443 18.2561 24.752 17.3771 23.8729C16.498 22.9939 15.3057 22.5 14.0625 22.5ZM15.9375 28.125H2.8125C2.56386 28.125 2.3254 28.0262 2.14959 27.8504C1.97377 27.6746 1.875 27.4361 1.875 27.1875C1.875 26.4416 2.17132 25.7262 2.69876 25.1988C3.22621 24.6713 3.94158 24.375 4.6875 24.375H14.0625C14.8084 24.375 15.5238 24.6713 16.0512 25.1988C16.5787 25.7262 16.875 26.4416 16.875 27.1875C16.875 27.4361 16.7762 27.6746 16.6004 27.8504C16.4246 28.0262 16.1861 28.125 15.9375 28.125ZM30 4.6875V14.0625C30 15.3057 29.5061 16.498 28.6271 17.3771C27.748 18.2561 26.5557 18.75 25.3125 18.75H20.0756L16.6013 22.2263C16.514 22.3133 16.4105 22.3822 16.2966 22.4292C16.1827 22.4762 16.0607 22.5002 15.9375 22.5C15.8146 22.5003 15.6928 22.4761 15.5794 22.4287C15.4079 22.3579 15.2614 22.2377 15.1582 22.0835C15.0551 21.9293 15 21.748 15 21.5625V17.8125C15 17.5639 15.0988 17.3254 15.2746 17.1496C15.4504 16.9738 15.6889 16.875 15.9375 16.875C16.1861 16.875 16.4246 16.9738 16.6004 17.1496C16.7762 17.3254 16.875 17.5639 16.875 17.8125V19.2994L19.0237 17.1487C19.111 17.0617 19.2145 16.9928 19.3284 16.9458C19.4423 16.8988 19.5643 16.8748 19.6875 16.875H25.3125C26.0584 16.875 26.7738 16.5787 27.3012 16.0512C27.8287 15.5238 28.125 14.8084 28.125 14.0625V4.6875C28.125 3.94158 27.8287 3.22621 27.3012 2.69876C26.7738 2.17132 26.0584 1.875 25.3125 1.875H12.1875C11.4416 1.875 10.7262 2.17132 10.1988 2.69876C9.67132 3.22621 9.375 3.94158 9.375 4.6875V10.3125C9.375 10.5611 9.27623 10.7996 9.10041 10.9754C8.9246 11.1512 8.68614 11.25 8.4375 11.25C8.18886 11.25 7.9504 11.1512 7.77459 10.9754C7.59877 10.7996 7.5 10.5611 7.5 10.3125V4.6875C7.5 3.4443 7.99386 2.25201 8.87294 1.37294C9.75201 0.49386 10.9443 0 12.1875 0L25.3125 0C26.5557 0 27.748 0.49386 28.6271 1.37294C29.5061 2.25201 30 3.4443 30 4.6875ZM15.2737 11.5237L19.2994 7.5H15.9375C15.6889 7.5 15.4504 7.40123 15.2746 7.22541C15.0988 7.0496 15 6.81114 15 6.5625C15 6.31386 15.0988 6.0754 15.2746 5.89959C15.4504 5.72377 15.6889 5.625 15.9375 5.625H21.5625C21.6854 5.62505 21.8071 5.64926 21.9206 5.69625C22.1501 5.79245 22.3326 5.97493 22.4287 6.20438C22.4757 6.31793 22.5 6.43961 22.5 6.5625V12.1875C22.5 12.4361 22.4012 12.6746 22.2254 12.8504C22.0496 13.0262 21.8111 13.125 21.5625 13.125C21.3139 13.125 21.0754 13.0262 20.8996 12.8504C20.7238 12.6746 20.625 12.4361 20.625 12.1875V8.82562L16.6013 12.8513C16.514 12.9383 16.4105 13.0072 16.2966 13.0542C16.1827 13.1012 16.0607 13.1252 15.9375 13.125C15.8143 13.1252 15.6923 13.1012 15.5784 13.0542C15.4645 13.0072 15.361 12.9383 15.2737 12.8513C15.1864 12.7642 15.1172 12.6607 15.0699 12.5468C15.0227 12.4329 14.9983 12.3108 14.9983 12.1875C14.9983 12.0642 15.0227 11.9421 15.0699 11.8282C15.1172 11.7143 15.1864 11.6108 15.2737 11.5237Z"
                                            fill="currentColor" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_931_10">
                                            <rect width="30" height="30" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>

                                <span
                                    class="{{ request()->routeIs('sales.request-order.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Penawaran</span>
                            </a>
                        </li>

                        {{-- Custom Penawaran --}}
                        <li class="w-[75%]">
                            <a href="{{ route('sales.custom-penawaran.index') }}"
                                class="{{ request()->routeIs('sales.custom-penawaran.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="{{ request()->routeIs('sales.custom-penawaran.*') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 transition duration-75 group-hover:text-white">
                                    <path
                                        d="M15 2C8.37256 2 3 7.37256 3 14C3 20.6274 8.37256 26 15 26C21.6274 26 27 20.6274 27 14C27 7.37256 21.6274 2 15 2ZM15 4C20.5228 4 25 8.47715 25 14C25 19.5228 20.5228 24 15 24C9.47715 24 5 19.5228 5 14C5 8.47715 9.47715 4 15 4Z"
                                        fill="currentColor" />
                                    <path
                                        d="M13 10C13 9.44772 13.4477 9 14 9H16C16.5523 9 17 9.44772 17 10V14.5858L18.293 13.293C18.6835 12.9024 19.3166 12.9024 19.7071 13.293C20.0976 13.6835 20.0976 14.3166 19.7071 14.7071L15.707 18.707C15.3165 19.0975 14.6834 19.0975 14.2929 18.707L10.293 14.707C9.90245 14.3165 9.90245 13.6834 10.293 13.293C10.6835 12.9024 11.3166 12.9024 11.707 13.293L13 14.586V10Z"
                                        fill="currentColor" />
                                </svg>

                                <span
                                    class="{{ request()->routeIs('sales.custom-penawaran.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Penawaran
                                    Kustom</span>
                            </a>
                        </li>

                        {{-- Customer Management
                        <li class="w-[75%]">
                            <a href="{{ route('sales.customer.index') }}"
                                class="{{ request()->routeIs('sales.customer.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg class="{{ request()->routeIs('sales.customer.*') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 transition duration-75 group-hover:text-white"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                </svg>
                                <span class="{{ request()->routeIs('sales.customer.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Customer
                                    Management</span>
                            </a>
                        </li> --}}

                        {{-- Customer --}}
                        <li class="w-[75%]">
                            <a href="{{ route('customer.index') }}"
                                class="{{ request()->routeIs('customer.*') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="{{ request()->routeIs('customer.*') ? 'text-white' : 'text-black dark:text-white' }} group-hover:text-white">
                                    <path
                                        d="M23.3027 21.9639C23.8066 21.3252 24.1084 20.5195 24.1084 19.6406C24.1084 17.5693 22.4297 15.8906 20.3584 15.8906C18.2871 15.8906 16.6084 17.5693 16.6084 19.6406C16.6084 20.5166 16.9102 21.3252 17.4141 21.9639C15.3457 23.0332 13.9307 25.1924 13.9307 27.6768H16.0723C16.0723 25.3125 17.9941 23.3906 20.3584 23.3906C22.7227 23.3906 24.6445 25.3125 24.6445 27.6768H26.7861C26.7861 25.1953 25.3682 23.0361 23.3027 21.9639ZM20.3584 18.0352C21.2432 18.0352 21.9668 18.7559 21.9668 19.6436C21.9668 20.5283 21.2461 21.252 20.3584 21.252C19.4736 21.252 18.75 20.5312 18.75 19.6436C18.75 18.7559 19.4707 18.0352 20.3584 18.0352Z"
                                        fill="currentColor" />
                                    <path
                                        d="M3.21387 2.1416V27.8584H12.8584V25.7139H5.3584V4.28613H24.6445V15H26.7861V2.1416H3.21387Z"
                                        fill="currentColor" />
                                    <path
                                        d="M7.5 7.5H22.5V9.6416H7.5V7.5ZM7.5 11.7861H18.2139V13.9277H7.5V11.7861ZM7.5 16.0723H13.9277V18.2139H7.5V16.0723Z"
                                        fill="currentColor" />
                                </svg>

                                <span
                                    class="{{ request()->routeIs('customer.*') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Customer</span>
                            </a>
                        </li>

                    </ul>
                </details>
            @endif



            {{-- Menu untuk Supervisor --}}
            @if (in_array(auth()->user()->role, ['Supervisor']))
                {{-- (Incoming Orders removed) --}}
                {{-- Sent Penawaran (needs approval) --}}
                <li>
                    <a href="{{ route('admin.sent_penawaran') }}"
                        class="{{ request()->is('sent-penawaran') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                        <svg aria-hidden="true"
                            class="{{ request()->is('sent-penawaran') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3 2v6h10V7H5z">
                            </path>
                        </svg>
                        <span
                            class="{{ request()->is('sent-penawaran') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">Sent
                            Penawaran</span>
                    </a>
                </li>

                {{-- History Orders --}}
                <li>
                    <a href="{{ route('orders.history') }}"
                        class="{{ request()->routeIs('admin.orders.history') ? 'bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm' : 'bg-white text-black hover:bg-gradient-to-r hover:from-[#225A97] hover:to-[#0D223A] hover:text-white dark:bg-[#0D223A] dark:text-white dark:hover:bg-gradient-to-r dark:hover:from-[#225A97] dark:hover:to-[#0D223A]' }} group flex items-center rounded-lg p-2 text-base font-medium transition-all duration-200">
                        <svg aria-hidden="true"
                            class="{{ request()->routeIs('admin.orders.history') ? 'text-white' : 'text-black dark:text-white' }} h-6 w-6 transition duration-75 group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 4h10v2H5V4zM5 8h10v2H5V8zM5 12h6v2H5v-2z"></path>
                        </svg>
                        <span
                            class="{{ request()->routeIs('admin.orders.history') ? 'text-white' : 'text-black dark:text-white' }} ml-3 group-hover:text-white">History
                            Orders</span>
                    </a>
                </li>
        </ul>
    </div>
    @endif
</aside>
