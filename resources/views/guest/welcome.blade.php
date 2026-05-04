<x-front-layout>
    <div id="hero"
         class="hero-gradient duration-750 starting:opacity-0 flex min-h-[768px] w-full flex-col items-center justify-center opacity-100 transition-opacity lg:grow">
        <div class="max-w-(--breakpoint-xl) relative z-10 mx-auto grid w-full px-4 pb-8 md:grid-cols-12 lg:gap-12 lg:pb-16 xl:gap-0">
            <div class="reveal reveal-up md:order-0 er order-1 content-center justify-self-start md:col-span-7 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-white md:max-w-2xl md:text-5xl xl:text-6xl">PT INDONUSA JAYA BERSAMA
                </h1>
                <h2 class="mb-5 text-2xl font-bold leading-none tracking-tight text-white md:max-w-2xl md:text-3xl xl:text-4xl">
                    <span class="bg-black p-1 px-2">
                        ONE STOP SOLUTION
                    </span>
                </h2>
                <!-- Marketplace Logos -->
                <div class="container my-8 flex w-full flex-wrap items-center justify-center gap-8 lg:justify-start lg:gap-10">
                    <a href="https://www.tokopedia.com/ijb-teknik-solution"
                       class="group block transition-all"
                       target="_blank"
                       rel="noopener noreferrer">

                        <img src="{{ asset('images/tokopedia_logo.png') }}"
                             alt="tokopedia"
                             class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                    <a href="https://siplah.tokoladang.co.id/m/pt-indonusa-jaya-bersama.56746?page=1&etalase=-1"
                       class="group block transition-all"
                       target="_blank"
                       rel="noopener noreferrer">

                        <img src="{{ asset('images/siplah.png') }}"
                             alt="siplah"
                             class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                    <a href="https://padiumkm.id/store/668e31b0e383eae3fa79f723"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="group block transition-all">
                        <img src="{{ asset('images/padiumk_logo.png') }}"
                             alt="Padi UMK"
                             class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                    <a href="https://katalog.inaproc.id/indonusa-jaya-bersama"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="group block transition-all">
                        <img src="{{ asset('images/inaproc_logo.png') }}"
                             alt="Inaproc"
                             class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                    <a href="https://www.mbizmarket.co.id/p/wwwindonusajayabersamacom/catalog?page=1"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="group block transition-all">
                        <img src="{{ asset('images/mbizmarket_logo.png') }}"
                             alt="Mbizmarket"
                             class="h-12 w-auto object-contain transition-all duration-500 group-hover:scale-110 md:h-12 lg:h-14">
                    </a>
                </div>
            </div>
            <div class="reveal reveal-right order-0 md:order-1 md:col-span-5 md:mt-0 md:flex">
                <img src="{{ asset('images/transparent_1762594045_7576.svg') }}"
                     alt="Indonusa Jaya Bersama Warehouse Illustration">
            </div>
        </div>
    </div>



    <div class="reveal reveal-up duration-750 starting:opacity-0 flex min-h-[181px] w-full flex-col items-center justify-center bg-[#E5E7EB] opacity-100 transition-opacity lg:grow"
         id="products">
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
    <!-- BEGIN: AboutSection -->
    <section class="blob-bg bg-brand-surface duration-750 starting:opacity-0 relative flex min-h-screen w-full items-center justify-center overflow-hidden px-6 py-20 transition-opacity"
             data-purpose="about-us-container"
             id="about">
        <div class="mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-16 lg:grid-cols-2">
            <!-- BEGIN: ImageColumn -->
            <div class="reveal reveal-left relative"
                 data-purpose="image-column">
                <!-- Hero Industrial Image -->
                <div class="relative z-10 aspect-square overflow-hidden rounded-2xl">
                    <img alt="Warehouse Operations"
                         class="h-full w-full object-cover"
                         src="{{ asset('images/logo_hd.png') }}">
                </div>
                <!-- Floating Badge -->
                <div class="absolute -bottom-6 -right-6 z-20 flex items-center gap-4 rounded-2xl border border-gray-50 bg-white p-6 shadow-xl md:right-0"
                     data-purpose="experience-badge">
                    <div class="rounded-lg bg-orange-500 p-3">
                        <svg class="h-6 w-6 text-white"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-brand-navy text-2xl font-bold leading-none">5+ Years</div>
                        <div class="text-sm font-medium text-gray-500">of excellence</div>
                    </div>
                </div>
            </div>
            <!-- END: ImageColumn -->
            <!-- BEGIN: ContentColumn -->
            <div class="reveal reveal-right flex flex-col space-y-8"
                 data-purpose="content-column">
                <!-- Section Title -->
                <div class="relative">
                    <div class="flex items-center gap-3">
                        <div class="bg-brand-600 h-10 w-1.5 rounded-full"></div>
                        <span class="text-brand-600 text-xl font-bold uppercase tracking-widest">
                            About Us
                        </span>
                    </div>
                </div>
                <!-- Main Heading -->
                <div data-purpose="main-heading">
                    <h2 class="text-brand-950 text-3xl font-extrabold leading-tight md:text-5xl lg:text-5xl">
                        Your trusted <span class="bg-gradient-to-r from-blue-700 to-blue-500 bg-clip-text text-transparent">one-stop distributor</span> since 2020.
                    </h2>
                </div>
                <!-- Description Paragraphs -->
                <div class="space-y-4 text-lg leading-relaxed text-gray-600"
                     data-purpose="company-description">
                    <p>
                        Berdiri sejak tahun 2020 dengan kantor pusat di Surabaya, <strong class="text-brand-900">PT Indonusa Jaya Bersama</strong> merupakan distributor terkemuka yang berfokus pada penyediaan solusi laboratorium komprehensif. Kami mengkhususkan diri dalam pengadaan peralatan laboratorium, sistem pengujian material (material testing systems), instrumen kontrol kualitas, furnitur laboratorium (meubeller), Material Handling Equipment (MHE), serta produk kimia dan otomotif.
                    </p>
                    <p>
                        Didukung oleh tenaga teknisi ahli berpengalaman, kami berkomitmen untuk menghadirkan layanan pasokan dan pemeliharaan berkualitas tinggi dengan harga kompetitif guna mendukung operasional pelanggan di seluruh wilayah Indonesia.
                    </p>
                </div>
                <!-- Statistics Grid -->
                <div class="reveal reveal-up reveal-delay-200 grid grid-cols-2 gap-4 md:grid-cols-4"
                     data-purpose="statistics-grid">
                    <!-- Stat Item 1 -->
                    <div class="card-shadow rounded-2xl border border-gray-100 bg-white p-5 transition-transform duration-300 hover:scale-105">
                        <div class="text-brand-600 mb-2">
                            <svg class="h-6 w-6"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="text-brand-navy text-2xl font-bold">2020</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">ESTABLISHED</div>
                    </div>
                    <!-- Stat Item 2 -->
                    <div class="card-shadow rounded-2xl border border-gray-100 bg-white p-5 transition-transform duration-300 hover:scale-105">
                        <div class="mb-2 text-blue-600">
                            <svg class="h-6 w-6"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="text-brand-navy text-2xl font-bold">200+</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">TRUSTED CLIENTS</div>
                    </div>
                    <!-- Stat Item 3 -->
                    <div class="card-shadow rounded-2xl border border-gray-100 bg-white p-5 transition-transform duration-300 hover:scale-105">
                        <div class="mb-2 text-blue-600">
                            <svg class="h-6 w-6"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="text-brand-navy text-2xl font-bold">50+</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">BRAND PARTNERS</div>
                    </div>
                    <!-- Stat Item 4 -->
                    <div class="card-shadow rounded-2xl border border-gray-100 bg-white p-5 transition-transform duration-300 hover:scale-105">
                        <div class="mb-2 text-blue-600">
                            <svg class="h-6 w-6"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"></path>
                            </svg>
                        </div>
                        <div class="text-brand-navy text-2xl font-bold">24/7</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">DISTRIBUTION</div>
                    </div>
                </div>
            </div>
            <!-- END: ContentColumn -->
        </div>
    </section>
    <!-- END: AboutSection -->
    <!-- BEGIN: ServicesSection -->
    <section class="reveal bg-gradient-navy relative flex min-h-[768px] w-full items-center overflow-hidden py-24 lg:py-32"
             id="layanan"
             data-purpose="services-overview">
        <!-- Decorative background elements -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="bg-brand-600/20 absolute -left-[10%] -top-[20%] h-[50%] w-[50%] rounded-full blur-[120px]"></div>
            <div class="bg-brand-800/40 absolute -right-[10%] top-[40%] h-[60%] w-[40%] rounded-full blur-[150px]"></div>
        </div>
        <div class="container relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Section Header -->
            <div class="reveal reveal-up mx-auto mb-16 max-w-2xl text-center lg:mb-24">
                <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-white drop-shadow-sm md:text-5xl">
                    Layanan Kami
                </h2>
                <p class="text-brand-100 text-lg font-medium opacity-90 md:text-xl">
                    Antar barang kamu dengan cepat dan tepat
                </p>
            </div>
            <!-- Services Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-8">
                <!-- Card 1: Fast Delivery -->
                <div class="reveal reveal-up reveal-delay-100 glass-card group flex h-full flex-col rounded-2xl p-6"
                     data-purpose="service-card-fast-delivery">
                    <div class="icon-wrapper mb-4 flex h-16 w-16 items-center justify-center rounded-xl text-white shadow-inner">
                        <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"></path>
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold tracking-wide text-white">Fast Delivery</h3>
                    <p class="text-brand-200 mb-2 text-sm leading-relaxed">
                        Pengiriman kilat untuk paket mendesak. Prioritas utama dengan SLA tercepat di kelasnya.
                    </p>
                </div>
                <!-- Card 2: Logistics -->
                <div class="reveal reveal-up reveal-delay-200 glass-card group flex h-full flex-col rounded-2xl p-6"
                     data-purpose="service-card-logistics">
                    <div class="icon-wrapper mb-4 flex h-16 w-16 items-center justify-center rounded-xl text-white shadow-inner">
                        <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"></path>
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold tracking-wide text-white">Logistics</h3>
                    <p class="text-brand-200 mb-2 text-sm leading-relaxed">
                        Solusi rantai pasokan komprehensif. Manajemen pergudangan dan distribusi skala besar.
                    </p>
                </div>
                <!-- Card 3: Parcel Delivery -->
                <div class="reveal reveal-up reveal-delay-300 glass-card group flex h-full flex-col rounded-2xl p-6"
                     data-purpose="service-card-parcel">
                    <div class="icon-wrapper mb-4 flex h-16 w-16 items-center justify-center rounded-xl text-white shadow-inner">
                        <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"></path>
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold tracking-wide text-white">Parcel Delivery</h3>
                    <p class="text-brand-200 mb-2 text-sm leading-relaxed">
                        Pengiriman paket reguler dengan pelacakan real-time. Aman, terjangkau, dan dapat diandalkan.
                    </p>
                </div>
                <!-- Card 4: Transportation -->
                <div class="reveal reveal-up reveal-delay-400 glass-card group flex h-full flex-col rounded-2xl p-6"
                     data-purpose="service-card-transportation">
                    <div class="icon-wrapper mb-4 flex h-16 w-16 items-center justify-center rounded-xl text-white shadow-inner">
                        <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"></path>
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold tracking-wide text-white">Transportation</h3>
                    <p class="text-brand-200 mb-2 text-sm leading-relaxed">
                        Layanan armada darat komersial. Menyediakan berbagai kapasitas truk untuk kargo Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- END: ServicesSection -->
    <div class="duration-750 starting:opacity-0 flex min-h-[181px] w-full flex-col items-center justify-center bg-[#E5E7EB] opacity-100 transition-opacity lg:grow"
         id="clients">
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
