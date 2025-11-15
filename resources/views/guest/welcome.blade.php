<x-front-layout>
    <div id="hero" class="duration-750 starting:opacity-0 bg-linear-to-r flex min-h-[768px] w-full items-center justify-center from-[#225A97] to-[#0D223A] opacity-100 transition-opacity lg:grow">
        <div class="max-w-(--breakpoint-xl) mx-auto grid px-4 pb-8 md:grid-cols-12 lg:gap-12 lg:pb-16 xl:gap-0">
            <div class="md:order-0 er order-1 content-center justify-self-start md:col-span-7 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-white md:max-w-2xl md:text-5xl xl:text-6xl">PT INDONUSA JAYA BERSAMA
                </h1>
                <h1 class="mb-5 text-2xl font-bold leading-none tracking-tight text-white md:max-w-2xl md:text-3xl xl:text-4xl">
                    <span class="bg-black p-1 px-2">
                        ONE STOP SOLUTION
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
            <div class="order-0 md:order-1 md:col-span-5 md:mt-0 md:flex">
                <img src="{{ asset('images/transparent_1762594045_7576.svg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="duration-750 starting:opacity-0 flex min-h-[181px] w-full flex-col items-center justify-center bg-[#E5E7EB] opacity-100 transition-opacity lg:grow" id="about">
        <div class="mt-7">
            <h1 class="mb-4 text-2xl font-bold leading-none tracking-tight text-black md:max-w-2xl md:text-3xl xl:text-4xl">
                OUR PRODUCTS
            </h1>
        </div>
        <div class="carousel w-full max-w-full">
            <div class="logos"></div>
            <div class="mask"></div>
        </div>
    </div>
    <div class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center gap-10 bg-white opacity-100 transition-opacity lg:grow" id="about">
        <div class="max-w-(--breakpoint-xl) mx-auto flex flex-col items-center gap-4 px-4 md:flex-row md:gap-12">
            <div>
                <img src="{{ asset('images/components.png') }}" alt="">
            </div>
            <div>
                <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-white md:max-w-2xl md:text-5xl xl:text-6xl">
                    <span class="bg-black px-2">
                        About Us
                    </span>
                </h1>
                <p class="mb-3 max-w-2xl text-justify text-black md:mb-12 md:text-lg lg:mb-5 lg:text-xl">
                    PT Indonusa Jaya Bersama didirikan pada tahun 2020 dengan kantor pusat di Surabaya,Distribuutor yang mengkhususkan diri dalam menyediakan produk berkualitas
                    <br>
                    Mulai dari peralatan laboratorium, laboratorium biologi, Sistem Pengujian Material, Alat uji kualitas, Meubeller, MHE , Kimia dan Otomotif
                    <br>
                    Berkantor pusat di Surabaya untuk melayani pelanggan dan di didukung oleh teknisi ahlli. Kami menyediakan pasokan dan perbaikan dengan kualitas dan harga yang komperatif
                </p>
            </div>
        </div>
    </div>
    <div class="duration-750 starting:opacity-0 bg-linear-to-r flex min-h-[768px] w-full items-center justify-center gap-10 from-[#225A97] to-[#0D223A] opacity-100 transition-opacity lg:grow"
        id="layanan">
        <div class="max-w-(--breakpoint-xl) mx-auto flex min-h-[600px] flex-col items-center px-4 py-9 lg:px-6">
            <div class="max-w-(--breakpoint-md) mb-8 text-center lg:mb-16">
                <h1 class="mb-4 text-4xl font-bold tracking-tight text-white md:text-6xl">Layanan Kami</h1>
                <p class="font-light text-white sm:text-xl">
                    Antar barang kamu dengan cepat dan tepat
                </p>
            </div>
            <div class="grid grid-cols-2 gap-12 space-y-0 lg:grid-cols-4">
                <div class="bg-linear-to-b flex max-h-72 max-w-72 items-center justify-center from-[#FFFFFF] to-[#D9D9D9A6] p-10">
                    <img src="{{ asset('images/layanan/Fast_delivery.png') }}" alt="">
                </div>
                <div class="bg-linear-to-b flex max-h-72 max-w-72 items-center justify-center from-[#FFFFFF] to-[#D9D9D9A6] p-10">
                    <img src="{{ asset('images/layanan/Location.png') }}" alt="">

                </div>
                <div class="bg-linear-to-b flex max-h-72 max-w-72 items-center justify-center from-[#FFFFFF] to-[#D9D9D9A6] p-10">
                    <img src="{{ asset('images/layanan/parcel_delivery.png') }}" alt="">

                </div>
                <div class="bg-linear-to-b flex max-h-72 max-w-72 items-center justify-center from-[#FFFFFF] to-[#D9D9D9A6] p-10">
                    <img src="{{ asset('images/layanan/Transporation.png') }}" alt="">

                </div>
            </div>
        </div>
    </div>
    <div class="duration-750 starting:opacity-0 flex min-h-[181px] w-full flex-col items-center justify-center bg-[#E5E7EB] opacity-100 transition-opacity lg:grow" id="about">
        <div class="mt-7">
            <h1 class="mb-4 text-2xl font-bold leading-none tracking-tight text-black md:max-w-2xl md:text-3xl xl:text-4xl">
                OUR CLIENTS
            </h1>
        </div>
        <div class="carousel w-full max-w-full">
            <div class="logos2"></div>
            <div class="mask"></div>
        </div>
        <div class="carousel w-full max-w-full">
            <div class="logos3"></div>
            <div class="mask"></div>
        </div>
        <div class="carousel w-full max-w-full">
            <div class="logos4"></div>
            <div class="mask"></div>
        </div>
    </div>
</x-front-layout>
