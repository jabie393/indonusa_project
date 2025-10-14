<!-- Sidebar -->
<aside class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full pt-14 transition-transform dark:bg-[#0B1D31] md:translate-x-0" aria-label="Sidenav" id="drawer-navigation">
    <div class="h-full overflow-y-auto bg-gradient-to-r from-[#225A97] to-[#1F5188] px-3 py-5 dark:bg-gradient-to-r dark:from-[#0B1D31] dark:to-[#0E243D]">

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

            {{-- Menu untuk Supply --}}
            @if (in_array(auth()->user()->role, ['Supply']))
                {{-- Goods In --}}
                <li>
                    <a href="{{ route('goods-in.index') }}"
                        class="{{ request()->routeIs('goods-in.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none"
                            class="{{ request()->routeIs('goods-in.*') ? 'text-black dark:text-white' : 'text-white' }} group-hover:text-black dark:group-hover:text-white">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M13.5 0.0795898L26.625 6.79129V20.2147L13.5 26.9264L0.375 20.2147V6.79129L13.5 0.0795898ZM9.12493 12.6078L9.125 21.7063L12.0417 23.1978V14.0993L9.12493 12.6078ZM3.29167 9.62501V18.7232L6.20831 20.2148V11.1164L3.29167 9.62501ZM19.2416 5.99859L10.6571 10.4086L13.5 11.8623L22.1042 7.46246L19.2416 5.99859ZM13.5 3.06257L4.89583 7.46246L7.7438 8.91877L16.3283 4.50884L13.5 3.06257Z"
                                fill="currentColor" />
                        </svg>
                        <span class="{{ request()->routeIs('goods-in.*') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black dark:group-hover:text-white">Goods
                            In</span>
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
                        class="{{ request()->routeIs('goods-in-status.*') ? 'bg-gray-200 dark:bg-gray-700 ' : '' }} group flex items-center rounded-lg p-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="29" viewBox="0 0 28 29" fill="currentColor"
                            class="{{ request()->routeIs('goods-in-status.*') ? 'text-black dark:text-white' : 'text-white dark:text-black' }} group-hover:text-black dark:text-white dark:group-hover:text-white">
                            <path d="M8.20312 13.0952H19.8187V14.4546H8.20312V13.0952ZM8.20312 19.0765H15.3125V20.4358H8.20312V19.0765Z" fill="currentColor" />
                            <path
                                d="M22.3781 27.7992H5.62188C5.27188 27.7992 4.94375 27.7313 4.61563 27.5953C4.30938 27.4594 4.025 27.2781 3.80625 27.0289C3.56562 26.7797 3.39062 26.5078 3.25937 26.1906C3.12812 25.8508 3.0625 25.5109 3.0625 25.1484V7.09141C3.0625 6.72891 3.12812 6.38906 3.25937 6.04922C3.39062 5.73203 3.56562 5.4375 3.80625 5.21094C4.04687 4.96172 4.30938 4.78047 4.61563 4.64453C4.94375 4.50859 5.27188 4.44063 5.62188 4.44063H8.15937V5.77734H5.62188C4.92188 5.77734 4.33125 6.36641 4.33125 7.11406V25.1711C4.33125 25.8961 4.9 26.5078 5.62188 26.5078H22.3562C23.0562 26.5078 23.6469 25.9188 23.6469 25.1711V7.09141C23.6469 6.36641 23.0781 5.75469 22.3562 5.75469H19.8406V4.41797H22.3781C22.7281 4.41797 23.0562 4.48594 23.3844 4.62187C23.6906 4.75781 23.975 4.93906 24.1938 5.18828C24.4344 5.4375 24.6094 5.70938 24.7406 6.02656C24.8719 6.36641 24.9375 6.70625 24.9375 7.06875V25.1258C24.9375 25.4883 24.8719 25.8281 24.7406 26.168C24.6094 26.4852 24.4344 26.7797 24.1938 27.0063C23.9531 27.2555 23.6906 27.4367 23.3844 27.5727C23.0562 27.7313 22.7281 27.7992 22.3781 27.7992Z"
                                fill="currentColor" />
                            <path
                                d="M20.4313 8.42822H7.56885V4.07822C7.56885 3.14932 8.29072 2.40166 9.1876 2.40166H10.8501C11.4407 1.17822 12.6657 0.385254 14.022 0.385254C15.3782 0.385254 16.6032 1.17822 17.1938 2.40166H18.8563C19.7532 2.40166 20.4751 3.14932 20.4751 4.07822L20.4313 8.42822ZM8.85947 7.0915H19.1626V4.07822C19.1626 3.89697 19.0095 3.73838 18.8345 3.73838H16.2751L16.122 3.28525C15.8157 2.35635 14.9626 1.72197 14.0001 1.72197C13.0376 1.72197 12.1845 2.35635 11.8782 3.28525L11.7251 3.73838H9.16572C8.99072 3.73838 8.8376 3.89697 8.8376 4.07822V7.0915H8.85947Z"
                                fill="currentColor" />
                        </svg>
                        <span
                            class="{{ request()->routeIs('goods-in-status.*') ? 'text-black dark:text-white' : 'text-white dark:text-black' }} ml-3 group-hover:text-black dark:text-white dark:group-hover:text-white">Goods
                            In Status</span>
                    </a>
                </li>
                {{-- History --}}
                <li>
                    <a href="{{ route('history.index') }}"
                        class="{{ request()->routeIs('history.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg width="28px" height="28px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="{{ request()->routeIs('history.*') ? 'text-black dark:text-white' : 'text-white dark:text-black' }} group-hover:text-black dark:text-white dark:group-hover:text-white">
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
                            class="{{ request()->routeIs('history.*') ? 'text-black dark:text-white' : 'text-white dark:text-black' }} ml-3 group-hover:text-black dark:text-white dark:group-hover:text-white">History</span>
                    </a>
                </li>
            @endif

            {{-- Menu untuk Warehouse --}}
            @if (in_array(auth()->user()->role, ['Warehouse']))
                <details {{ request()->routeIs('supply-orders.*') || request()->routeIs('delivery-orders.*') ? 'open' : 'close' }} class="">
                    <summary
                        class="{{ request()->routeIs('supply-orders.*') || request()->routeIs('delivery-orders.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex cursor-pointer items-center rounded-lg p-2 text-base font-medium hover:bg-gray-100 hover:text-black dark:text-white dark:hover:bg-gray-700">
                        <svg width="28px" height="28px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="#000000"
                            class="{{ request()->routeIs('supply-orders.*') || request()->routeIs('delivery-orders.*') ? 'text-black dark:text-white' : 'text-white' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill="currentColor" d="M13.098 8H6.902c-.751 0-1.172.754-.708 1.268L9.292 12.7c.36.399 1.055.399 1.416 0l3.098-3.433C14.27 8.754 13.849 8 13.098 8Z"></path>
                            </g>
                        </svg>
                        <span class="{{ request()->routeIs('supply-orders.*') || request()->routeIs('delivery-orders.*') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black">
                            Orders</span>
                    </summary>

                    <ul
                        class="before:left-4.5 relative flex flex-col items-end space-y-2 pt-2 before:absolute before:bottom-[.75rem] before:start-0 before:top-[.75rem] before:w-1 before:bg-white before:opacity-10 before:content-['']">
                        {{-- Supply Orders --}}
                        <li class="w-[179px]">
                            <a href="{{ route('supply-orders.index') }}"
                                class="{{ request()->routeIs('supply-orders.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                <svg class="{{ request()->routeIs('supply-orders.*') ? 'text-black dark:text-white' : 'text-white' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                                    viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.5 0.20752L14.625 4.10463V11.8989L7.5 15.796L0.375 11.8989V4.10463L7.5 0.20752ZM5.12496 7.48198L5.125 12.7649L6.70833 13.631V8.34802L5.12496 7.48198ZM1.95833 5.75002V11.0328L3.54165 11.8989V6.61601L1.95833 5.75002ZM10.6169 3.64436L5.95673 6.205L7.5 7.04912L12.1708 4.49435L10.6169 3.64436ZM7.5 1.93957L2.82917 4.49435L4.37521 5.33995L9.03536 2.77934L7.5 1.93957Z"
                                        fill="currentColor" />
                                </svg>

                                <span class="{{ request()->routeIs('supply-orders.*') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black">Supply
                                    Orders</span>
                            </a>
                        </li>

                        {{-- Delivery Orders --}}
                        <li class="w-[179px]">
                            <a href="{{ route('delivery-orders.index') }}"
                                class="{{ request()->routeIs('delivery-orders.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                <svg class="{{ request()->routeIs('delivery-orders.*') ? 'text-black dark:text-white' : 'text-white' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.6792 3.66661C12.4758 3.46392 12.1961 3.34427 11.9001 3.33328H11.3334V2.66661C11.3383 2.53404 11.3142 2.40192 11.2626 2.27849C11.211 2.15506 11.1329 2.04295 11.0332 1.94915C10.9335 1.85534 10.8144 1.78185 10.6833 1.73324C10.5521 1.68464 10.4118 1.66196 10.2709 1.66661H1.7709C1.63003 1.66196 1.48966 1.68464 1.35852 1.73324C1.22737 1.78185 1.10826 1.85534 1.00859 1.94915C0.908924 2.04295 0.830838 2.15506 0.779193 2.27849C0.727548 2.40192 0.703449 2.53404 0.708397 2.66661V11.6666C0.703449 11.7992 0.727548 11.9313 0.779193 12.0547C0.830838 12.1782 0.908924 12.2903 1.00859 12.3841C1.10826 12.4779 1.22737 12.5514 1.35852 12.6C1.48966 12.6486 1.63003 12.6713 1.7709 12.6666H2.58548C2.7396 13.146 3.05354 13.566 3.48086 13.8645C3.90819 14.163 4.42623 14.3242 4.9584 14.3242C5.49056 14.3242 6.0086 14.163 6.43593 13.8645C6.86326 13.566 7.17719 13.146 7.33131 12.6666H9.66881C9.82294 13.146 10.1369 13.566 10.5642 13.8645C10.9915 14.163 11.5096 14.3242 12.0417 14.3242C12.5739 14.3242 13.0919 14.163 13.5193 13.8645C13.9466 13.566 14.2605 13.146 14.4146 12.6666H15.2292C15.3701 12.6713 15.5105 12.6486 15.6416 12.6C15.7728 12.5514 15.8919 12.4779 15.9915 12.3841C16.0912 12.2903 16.1693 12.1782 16.2209 12.0547C16.2726 11.9313 16.2967 11.7992 16.2917 11.6666V7.39994L12.6792 3.66661ZM11.723 4.66661L14.3084 7.33328H11.3334V4.66661H11.723ZM2.12506 2.99994H9.91673V10.7999C9.81287 10.9683 9.7297 11.1472 9.66881 11.3333H7.33131C7.17719 10.8539 6.86326 10.4339 6.43593 10.1354C6.0086 9.83688 5.49056 9.67572 4.9584 9.67572C4.42623 9.67572 3.90819 9.83688 3.48086 10.1354C3.05354 10.4339 2.7396 10.8539 2.58548 11.3333H2.12506V2.99994ZM4.9584 12.9999C4.74825 12.9999 4.54283 12.9413 4.3681 12.8314C4.19338 12.7215 4.05719 12.5654 3.97677 12.3826C3.89636 12.1999 3.87532 11.9988 3.91631 11.8049C3.95731 11.6109 4.0585 11.4327 4.2071 11.2928C4.35569 11.153 4.54501 11.0577 4.75111 11.0192C4.95722 10.9806 5.17085 11.0004 5.365 11.0761C5.55914 11.1518 5.72508 11.2799 5.84183 11.4444C5.95858 11.6088 6.0209 11.8022 6.0209 11.9999C6.02584 12.1325 6.00175 12.2646 5.9501 12.3881C5.89846 12.5115 5.82037 12.6236 5.7207 12.7174C5.62104 12.8112 5.50192 12.8847 5.37078 12.9333C5.23963 12.9819 5.09926 13.0046 4.9584 12.9999ZM12.0417 12.9999C11.8316 12.9999 11.6262 12.9413 11.4514 12.8314C11.2767 12.7215 11.1405 12.5654 11.0601 12.3826C10.9797 12.1999 10.9586 11.9988 10.9996 11.8049C11.0406 11.6109 11.1418 11.4327 11.2904 11.2928C11.439 11.153 11.6283 11.0577 11.8344 11.0192C12.0406 10.9806 12.2542 11.0004 12.4483 11.0761C12.6425 11.1518 12.8084 11.2799 12.9252 11.4444C13.0419 11.6088 13.1042 11.8022 13.1042 11.9999C13.1092 12.1325 13.0851 12.2646 13.0334 12.3881C12.9818 12.5115 12.9037 12.6236 12.804 12.7174C12.7044 12.8112 12.5853 12.8847 12.4541 12.9333C12.323 12.9819 12.1826 13.0046 12.0417 12.9999ZM14.4146 11.3333C14.2622 10.8522 13.9491 10.4302 13.5216 10.1299C13.0941 9.82968 12.5751 9.6672 12.0417 9.66661C11.8015 9.6644 11.5624 9.69816 11.3334 9.76661V8.66661H14.8751V11.3333H14.4146Z"
                                        fill="currentColor" />
                                </svg>

                                <span class="{{ request()->routeIs('delivery-orders.*') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black">Delivery
                                    Orders</span>
                            </a>
                        </li>

                    </ul>
                </details>
            @endif

            {{-- Warehouse --}}
            <li>
                <a href="{{ route('warehouse.index') }}"
                    class="{{ request()->routeIs('warehouse.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="31" viewBox="0 0 26 31" fill="none"
                        class="{{ request()->routeIs('warehouse.*') ? 'text-black dark:text-white' : 'text-white' }} group-hover:text-black dark:group-hover:text-white">
                        <path
                            d="M6.21582 14.4103H10.0143V18.1884H6.21582V14.4103ZM6.21582 23.2138H10.0143V19.4356H6.21582V23.2138ZM6.21582 28.227H10.0143V24.4489H6.21582V28.227ZM11.1416 23.2138H14.9299V19.4356H11.1314V23.2138H11.1416ZM11.1416 28.227H14.9299V24.4489H11.1314V28.227H11.1416ZM16.0674 24.4489V28.227H19.8658V24.4489H16.0674ZM25.8986 9.71182L13.0307 2.49463L0.172852 9.73603L0.975195 11.7341L2.61035 10.8017V28.2392H4.45879V10.729H21.6229V28.2634H23.4713V10.8259L25.1064 11.7341L25.8986 9.71182Z"
                            fill="currentColor" />
                    </svg>
                    <span class="{{ request()->routeIs('warehouse.*') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black dark:group-hover:text-white">Warehouse</span>
                </a>
            </li>

            {{-- Menu untuk Sales --}}
            {{-- Menu untuk Sales --}}
            {{-- Menu untuk Sales --}}
            @if (in_array(auth()->user()->role, ['Sales']))
                <li>
                    <a href="{{ route('requestorder.create') }}"
                        class="{{ request()->routeIs('requestorder.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }} ajax-link group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg aria-hidden="true" class="h-6 w-6 text-blue-500 transition duration-75 group-hover:text-blue-700 dark:text-blue-400 dark:group-hover:text-blue-300" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M3 10a1 1 0 011-1h2V7a1 1 0 112 0v2h2a1 1 0 110 2h-2v2a1 1 0 11-2 0v-2H4a1 1 0 01-1-1z" />
                        </svg>
                        <span class="ml-3">Request Order</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales.order') }}"
                        class="{{ request()->routeIs('sales.order') ? 'bg-gray-200 dark:bg-gray-700' : '' }} ajax-link group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg aria-hidden="true" class="h-6 w-6 text-green-500 transition duration-75 group-hover:text-green-700 dark:text-green-400 dark:group-hover:text-green-300"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 3h14v2H3V3zm0 4h14v10H3V7z"></path>
                        </svg>
                        <span class="ml-3">Sales Order</span>
                    </a>
                </li>
            @endif



            {{-- Menu untuk Supply --}}
            @if (in_array(auth()->user()->role, ['Supervisor']))
                {{-- Incoming Orders --}}
                <li>
                    <a href="{{ url('/incoming') }}"
                        class="{{ request()->is('incoming') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg aria-hidden="true"
                            class="{{ request()->is('incoming') ? 'text-black dark:text-white' : 'text-white' }} h-6 w-6 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 3h14v2H3V3zm0 4h14v10H3V7z"></path>
                        </svg>
                        <span class="{{ request()->is('incoming') ? 'text-black dark:text-white' : 'text-white' }} ml-3 group-hover:text-black dark:group-hover:text-white">Incoming
                            Orders</span>
                    </a>
                </li>
                {{-- Approved Orders --}}
                <li>
                    <a href="{{ route('admin.approved') }}"
                        class="{{ request()->is('approved-orders') ? 'bg-gray-200 dark:bg-gray-700' : '' }} group flex items-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg aria-hidden="true" class="h-6 w-6 text-white transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3 2v6h10V7H5z"></path>
                        </svg>
                        <span class="ml-3">Approved Orders</span>
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
                        <span class="ml-3">History Orders</span>
                    </a>
                </li>
        </ul>
    </div>
    @endif
</aside>
