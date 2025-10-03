<!-- Sidebar -->
<aside class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full pt-14 transition-transform dark:bg-[#0B1D31] md:translate-x-0" aria-label="Sidenav" id="drawer-navigation">
    <div class="h-full overflow-y-auto bg-gradient-to-r from-[#225A97] to-[#1F5188] px-3 py-5 dark:bg-gradient-to-r dark:from-[#0B1D31] dark:to-[#0E243D]">
        <form action="#" method="GET" class="mb-2 md:hidden">
            <label for="sidebar-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                        </path>
                    </svg>
                </div>
                <input type="text" name="search" id="sidebar-search"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                    placeholder="Search" />
            </div>
        </form>

        <ul class="space-y-2">
            {{-- Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg aria-hidden="true"
                        class="{{ request()->routeIs('dashboard') ? 'text-black dark:text-white' : 'text-white drak:text-black' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span
                        class="{{ request()->routeIs('dashboard') ? 'text-black dark:text-white' : 'text-white drak:text-black' }} ml-3 group-hover:text-black dark:group-hover:text-white">Dashboard</span>
                </a>
            </li>

            {{-- Menu untuk admin_supply --}}
            @if (in_array(auth()->user()->role, ['admin_supply']))
                {{-- Goods In --}}
                <li>
                    <a href="{{ route('goods-in.index') }}"
                        class="{{ request()->routeIs('goods-in.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg aria-hidden="true"
                            class="{{ request()->routeIs('goods-in.*') ? 'text-black dark:text-white' : 'text-white' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4h12v12H4z"></path>
                        </svg>
                        <span class="{{ request()->routeIs('goods-in.*') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black dark:group-hover:text-white">Goods In</span>
                    </a>
                    {{-- Add Stock --}}
                    <ul>
                        @if (request()->routeIs('add-stock.*'))
                            <li class="flex items-center justify-end">
                                <svg width="64px" class="h-6 w-6 text-white transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white" height="64px" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M3 4C3 3.44772 3.44771 3 4 3C4.55229 3 5 3.44772 5 4L5 11C5 11.7956 5.31607 12.5587 5.87868 13.1213C6.44129 13.6839 7.20435 14 8 14H17.5858L14.2929 10.7071C13.9024 10.3166 13.9024 9.68342 14.2929 9.29289C14.6834 8.90237 15.3166 8.90237 15.7071 9.29289L20.7071 14.2929C21.0976 14.6834 21.0976 15.3166 20.7071 15.7071L15.7071 20.7071C15.3166 21.0976 14.6834 21.0976 14.2929 20.7071C13.9024 20.3166 13.9024 19.6834 14.2929 19.2929L17.5858 16H8C6.67392 16 5.40215 15.4732 4.46447 14.5355C3.52678 13.5979 3 12.3261 3 11V4Z"
                                            fill="white">
                                        </path>
                                    </g>
                                </svg>
                                <a href="{{ route('add-stock.index') }}"
                                    class="group ml-2 flex w-[179px] items-center rounded-lg bg-gray-200 p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-700">

                                    <span class="">Add Stock</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>



                {{-- Goods In Status --}}
                <li>
                    <a href="{{ route('goods-in-status.index') }}"
                        class="{{ request()->routeIs('goods-in-status.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg aria-hidden="true"
                            class="{{ request()->routeIs('goods-in-status.*') ? 'text-black dark:text-white' : 'text-white' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4h12v12H4z"></path>
                        </svg>
                        <span
                            class="{{ request()->routeIs('goods-in-status.*') ? 'text-black dark:text-white' : 'text-white dark:text-black' }} ml-3 group-hover:text-black dark:text-white dark:group-hover:text-white">Goods
                            In Status</span>
                    </a>
                </li>
            @endif

            {{-- Menu untuk admin_warehouse --}}
            @if (in_array(auth()->user()->role, ['admin_warehouse']))
                {{-- Supply Orders --}}
                <li>
                    <a href="{{ route('supply-orders.index') }}"
                        class="{{ request()->routeIs('supply-orders.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg aria-hidden="true"
                            class="{{ request()->routeIs('supply-orders.*') ? 'text-black dark:text-white' : 'text-white' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4h12v12H4z"></path>
                        </svg>
                        <span class="{{ request()->routeIs('supply-orders.*') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black">Supply Orders</span>
                    </a>
                </li>
            @endif

            {{-- Warehouse --}}
            <li>
                <a href="{{ route('warehouse.index') }}"
                    class="{{ request()->routeIs('warehouse.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                    <svg aria-hidden="true"
                        class="{{ request()->routeIs('warehouse.*') ? 'text-black dark:text-white' : 'text-white' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4h12v12H4z"></path>
                    </svg>
                    <span class="{{ request()->routeIs('warehouse.*') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black dark:group-hover:text-white">Warehouse</span>
                </a>
            </li>

            {{-- Menu untuk admin_supply --}}
            @if (in_array(auth()->user()->role, ['admin_PT']))
                {{-- Incoming Orders --}}
                <li>
                    <a href="{{ route('orders.incoming') }}"
                        class="{{ request()->routeIs('orders.incoming') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg aria-hidden="true" class="h-6 w-6 {{ request()->routeIs('orders.incoming') ? 'text-black dark:text-white' : 'text-white' }}  transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M3 3h14v2H3V3zm0 4h14v10H3V7z"></path>
                        </svg>
                        <span class="ml-3 {{ request()->routeIs('orders.incoming') ? 'text-black dark:text-white' : 'text-white' }} group-hover:text-black dark:group-hover:text-white">Incoming Orders</span>
                    </a>
                </li>


                {{-- History Orders --}}
                <li>
                    <a href="{{ route('orders.history') }}"
                        class="{{ request()->routeIs('admin.orders.history') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg aria-hidden="true" class="h-6 w-6 text-white transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M5 4h10v2H5V4zM5 8h10v2H5V8zM5 12h6v2H5v-2z"></path>
                        </svg>
                        <span class="ml-3 ">History Orders</span>
                    </a>
                </li>
        </ul>
    </div>
    @endif
</aside>
