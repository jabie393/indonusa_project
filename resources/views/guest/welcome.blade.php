<x-front-layout>
    <div id="hero" class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center bg-gradient-to-r from-[#225A97] to-[#0B1D31] opacity-100 transition-opacity lg:grow">
        <div class="mx-auto grid max-w-screen-xl px-4 pb-8 md:grid-cols-12 lg:gap-12 lg:pb-16 xl:gap-0">
            <div class="content-cent er justify-self-start md:col-span-7 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-white md:max-w-2xl md:text-5xl xl:text-6xl">Get Your Items
                </h1>
                <p class="mb-3 mb-4 max-w-2xl text-zinc-100 dark:text-gray-400 md:mb-12 md:text-lg lg:mb-5 lg:text-xl">Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus quos magni
                    delectus nobis perspiciatis modi dolores. Itaque architecto commodi velit quod et accusamus eum eaque facere, voluptas placeat doloribus qui?
                </p>
                <h1 class="mb-5 text-2xl font-bold leading-none tracking-tight text-white md:max-w-2xl md:text-3xl xl:text-4xl">
                    <span class="bg-black p-1 px-2">
                        delivered with ease
                    </span>
                </h1>
                <svg width="151" height="74" viewBox="0 0 151 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="39" cy="37" rx="39" ry="37" fill="#D9D9D9" fill-opacity="0.65" />
                    <ellipse cx="112" cy="37" rx="39" ry="37" fill="black" />
                    <path
                        d="M118.707 37.7071C119.098 37.3166 119.098 36.6834 118.707 36.2929L112.343 29.9289C111.953 29.5384 111.319 29.5384 110.929 29.9289C110.538 30.3195 110.538 30.9526 110.929 31.3431L116.586 37L110.929 42.6569C110.538 43.0474 110.538 43.6805 110.929 44.0711C111.319 44.4616 111.953 44.4616 112.343 44.0711L118.707 37.7071ZM37 37V38H118V37V36H37V37Z"
                        fill="white" />
                </svg>

            </div>
            <div class="hidden md:col-span-5 md:mt-0 md:flex">
                <img class="dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/girl-shopping-list.svg" alt="shopping illustration" />
                <img class="hidden dark:block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/girl-shopping-list-dark.svg" alt="shopping illustration" />
            </div>
        </div>
    </div>
    <div class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center gap-10 bg-white opacity-100 transition-opacity lg:grow" id="about">
        <div class="mx-auto flex max-w-screen-xl flex-col items-center gap-4 px-4 md:flex-row md:gap-12">
            <div>
                <img src="{{ asset('images/components.png') }}" alt="">
            </div>
            <div>
                <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-white md:max-w-2xl md:text-5xl xl:text-6xl">
                    <span class="bg-black px-2">
                        About Us
                    </span>
                </h1>
                <p class="mb-3 max-w-2xl text-black md:mb-12 md:text-lg lg:mb-5 lg:text-xl">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus quos magni
                    delectus nobis perspiciatis modi dolores. Itaque architecto commodi velit quod et accusamus eum eaque facere, voluptas placeat doloribus qui?
                </p>
            </div>
        </div>
    </div>
    <div class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center gap-10 bg-gradient-to-r from-[#225A97] to-[#0B1D31] opacity-100 transition-opacity lg:grow"
        id="layanan">
        <div class="mx-auto flex min-h-[600px] max-w-screen-xl flex-col items-center px-4 lg:px-6">
            <div class="mb-8 max-w-screen-md text-center lg:mb-16">
                <h1 class="mb-4 text-4xl font-bold tracking-tight text-white md:text-6xl">Layanan Kami</h1>
                <p class="font-light text-white sm:text-xl">
                    Antar barang kamu dengan cepat dan tepat
                </p>
            </div>
            <div class="space-y-8 md:grid md:grid-cols-2 md:gap-12 md:space-y-0 lg:grid-cols-4">
                <div class="bg-gradient-to-b from-[#FFFFFF] to-[#D9D9D9A6] h-72 w-72 flex items-center justify-center">
                    <img src="{{ asset('images/layanan/Fast_delivery.png') }}" alt="">
                </div>
                <div class="bg-gradient-to-b from-[#FFFFFF] to-[#D9D9D9A6] h-72 w-72 flex items-center justify-center ">
                    <img src="{{ asset('images/layanan/Location.png') }}" alt="">

                </div>
                <div class="bg-gradient-to-b from-[#FFFFFF] to-[#D9D9D9A6] h-72 w-72 flex items-center justify-center">
                    <img src="{{ asset('images/layanan/parcel_delivery.png') }}" alt="">

                </div>
                <div class="bg-gradient-to-b from-[#FFFFFF] to-[#D9D9D9A6] h-72 w-72 flex items-center justify-center ">
                    <img src="{{ asset('images/layanan/Transporation.png') }}" alt="">

                </div>
            </div>
        </div>
    </div>
</x-front-layout>
