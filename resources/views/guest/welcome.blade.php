<x-front-layout>
    <div id="hero" class="duration-750 starting:opacity-0 bg-linear-to-r flex min-h-[768px] w-full flex-col items-center justify-center from-[#225A97] to-[#0D223A] opacity-100 transition-opacity lg:grow">
        <div class="max-w-(--breakpoint-xl) mx-auto grid w-full px-4 pb-8 md:grid-cols-12 lg:gap-12 lg:pb-16 xl:gap-0">
            <div class="md:order-0 er order-1 content-center justify-self-start md:col-span-7 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-white md:max-w-2xl md:text-5xl xl:text-6xl">PT INDONUSA JAYA BERSAMA
                </h1>
                <h2 class="mb-5 text-2xl font-bold leading-none tracking-tight text-white md:max-w-2xl md:text-3xl xl:text-4xl">
                    <span class="bg-black p-1 px-2">
                        ONE STOP SOLUTION
                    </span>
                </h2>
                <!-- Marketplace Logos -->
                <div class="container my-8 flex w-full flex-wrap items-center justify-center gap-8 lg:justify-start lg:gap-10">
                    <a href="https://www.tokopedia.com/ijb-teknik-solution" class="group block transition-all" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('images/snapon_logo.png') }}" alt="Snap-on" class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                    <a href="https://padiumkm.id/store/668e31b0e383eae3fa79f723" target="_blank" rel="noopener noreferrer" class="group block transition-all">
                        <img src="{{ asset('images/padiumk_logo.png') }}" alt="Padi UMK" class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                    <a href="https://katalog.inaproc.id/indonusa-jaya-bersama" target="_blank" rel="noopener noreferrer" class="group block transition-all">
                        <img src="{{ asset('images/inaproc_logo.png') }}" alt="Inaproc" class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                    <a href="https://www.mbizmarket.co.id/p/wwwindonusajayabersamacom/catalog?page=1" target="_blank" rel="noopener noreferrer" class="group block transition-all">
                        <img src="{{ asset('images/mbizmarket_logo.png') }}" alt="Mbizmarket" class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                </div>
            </div>
            <div class="order-0 md:order-1 md:col-span-5 md:mt-0 md:flex">
                <img src="{{ asset('images/transparent_1762594045_7576.svg') }}" alt="Indonusa Jaya Bersama Warehouse Illustration">
            </div>
        </div>


    </div>



    <div class="duration-750 starting:opacity-0 flex min-h-[181px] w-full flex-col items-center justify-center bg-[#E5E7EB] opacity-100 transition-opacity lg:grow" id="about">
        <div class="mt-7">
            <h2 class="mb-4 text-2xl font-bold leading-none tracking-tight text-black md:max-w-2xl md:text-3xl xl:text-4xl">
                OUR PRODUCTS
            </h2>
        </div>
        <div class="carousel w-full max-w-full">
            <div class="logos"></div>
            <div class="mask"></div>
        </div>
    </div>
    <div class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center gap-10 bg-white opacity-100 transition-opacity lg:grow" id="about">
        <div class="max-w-(--breakpoint-xl) mx-auto flex flex-col items-center gap-4 px-4 md:flex-row md:gap-12">
            <div>
                <img class="w-100" src="{{ asset('images/Logo_transparent.png') }}" alt="PT Indonusa Jaya Bersama Logo" loading="lazy">
            </div>
            <div>
                <h2 class="mb-4 text-4xl font-bold leading-none tracking-tight text-white md:max-w-2xl md:text-5xl xl:text-6xl">
                    <span class="bg-black px-2">
                        About Us
                    </span>
                </h2>
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
    <div class="duration-750 starting:opacity-0 bg-linear-to-r flex min-h-[768px] w-full items-center justify-center gap-10 from-[#225A97] to-[#0D223A] opacity-100 transition-opacity lg:grow" id="layanan">
        <div class="max-w-(--breakpoint-xl) mx-auto flex min-h-[600px] flex-col items-center px-4 py-9 lg:px-6">
            <div class="max-w-(--breakpoint-md) mb-8 text-center lg:mb-16">
                <h2 class="mb-4 text-4xl font-bold tracking-tight text-white md:text-6xl">Layanan Kami</h2>
                <p class="font-light text-white sm:text-xl">
                    Antar barang kamu dengan cepat dan tepat
                </p>
            </div>
            <div class="grid grid-cols-2 gap-12 space-y-0 lg:grid-cols-4">
                <div class="bg-linear-to-b flex max-h-72 max-w-72 items-center justify-center from-[#FFFFFF] to-[#D9D9D9A6] p-10">
                    <img src="{{ asset('images/layanan/Fast_delivery.png') }}" alt="Fast Delivery Service" loading="lazy">
                </div>
                <div class="bg-linear-to-b flex max-h-72 max-w-72 items-center justify-center from-[#FFFFFF] to-[#D9D9D9A6] p-10">
                    <img src="{{ asset('images/layanan/Location.png') }}" alt="Location Service" loading="lazy">

                </div>
                <div class="bg-linear-to-b flex max-h-72 max-w-72 items-center justify-center from-[#FFFFFF] to-[#D9D9D9A6] p-10">
                    <img src="{{ asset('images/layanan/parcel_delivery.png') }}" alt="Parcel Delivery Service" loading="lazy">

                </div>
                <div class="bg-linear-to-b flex max-h-72 max-w-72 items-center justify-center from-[#FFFFFF] to-[#D9D9D9A6] p-10">
                    <img src="{{ asset('images/layanan/Transporation.png') }}" alt="Transportation Service" loading="lazy">

                </div>
            </div>
        </div>
    </div>
    <div class="duration-750 starting:opacity-0 flex min-h-[181px] w-full flex-col items-center justify-center bg-[#E5E7EB] opacity-100 transition-opacity lg:grow" id="about">
        <div class="mt-7">
            <h2 class="mb-4 text-2xl font-bold leading-none tracking-tight text-black md:max-w-2xl md:text-3xl xl:text-4xl">
                OUR CLIENTS
            </h2>
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
