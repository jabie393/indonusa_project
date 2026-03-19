<x-front-layout>
    <div id="hero" class="duration-750 starting:opacity-0 bg-linear-to-r relative flex min-h-[300px] w-full items-center justify-center overflow-hidden from-[#225A97] to-[#0D223A] opacity-100 transition-opacity lg:grow">
        <img src="{{ asset('images/katalog_bg.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover mix-blend-overlay">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-(--breakpoint-2xl) relative z-10 mx-auto flex w-full flex-wrap items-start justify-start px-4">
            <div class="md:order-0 order-1 content-center justify-self-start md:col-span-12 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold uppercase leading-none tracking-tight tracking-wider text-white md:text-5xl xl:text-6xl">KATALOG</h1>
                <p class="text-xl font-medium italic text-white/90">Temukan katalog produk kami</p>
            </div>
        </div>
    </div>
    <div class="max-w-(--breakpoint-2xl) container mx-auto my-8 flex flex-wrap items-center justify-center gap-8 px-6 lg:justify-between lg:gap-10 lg:px-10">
        <a href="https://snapon.com.sg/catalog" class="group block transition-all" target="_blank">
            <img src="{{ asset('images/snapon.png') }}" alt="Snap-on" class="h-8 w-auto object-contain opacity-60 grayscale transition-all duration-500 group-hover:scale-110 group-hover:opacity-100 group-hover:grayscale-0 md:h-10 lg:h-12">
        </a>
        <a href="https://padiumkm.id/store/668e31b0e383eae3fa79f723" target="_blank" class="group block transition-all">
            <img src="{{ asset('images/padiumk.png') }}" alt="Padi UMK" class="h-8 w-auto object-contain opacity-60 grayscale transition-all duration-500 group-hover:scale-110 group-hover:opacity-100 group-hover:grayscale-0 md:h-10 lg:h-12">
        </a>
        <a href="https://katalog.inaproc.id/indonusa-jaya-bersama" target="_blank" class="group block transition-all">
            <img src="{{ asset('images/inaproc.png') }}" alt="Inaproc" class="h-8 w-auto object-contain opacity-60 grayscale transition-all duration-500 group-hover:scale-110 group-hover:opacity-100 group-hover:grayscale-0 md:h-10 lg:h-12">
        </a>
        <a href="https://www.mbizmarket.co.id/p/wwwindonusajayabersamacom" target="_blank" class="group block transition-all">
            <img src="{{ asset('images/mbizmarket.png') }}" alt="Mbizmarket" class="h-8 w-auto object-contain opacity-60 grayscale transition-all duration-500 group-hover:scale-110 group-hover:opacity-100 group-hover:grayscale-0 md:h-10 lg:h-12">
        </a>
    </div>
    <div class="max-w-(--breakpoint-2xl) container mx-auto flex w-full flex-col gap-8 px-4 py-8 lg:flex-row">
        <!-- Sidebar Filter -->
        <div class="w-full shrink-0 lg:w-[20%]">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-xl lg:sticky lg:top-24">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Filter by brands</h2>
                    <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400">BROWSE OUR PARTNERS</p>
                </div>

                <div class="scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent flex max-h-[calc(100vh-300px)] flex-row gap-1 overflow-x-auto pb-4 lg:flex-col lg:overflow-y-auto lg:pb-0">
                    <a href="{{ route('catalogs') }}" class="{{ !request('brand') ? 'bg-[#0D223A] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} group flex items-center whitespace-nowrap rounded-lg px-4 py-2.5 text-sm font-semibold transition-all duration-200">
                        All
                    </a>

                    @foreach ($allBrands as $brand)
                        <a href="{{ route('catalogs', ['brand' => $brand]) }}" class="{{ request('brand') == $brand ? 'bg-[#0D223A] text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }} group flex items-center whitespace-nowrap rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200">
                            {{ $brand }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Catalog Grid -->
        <div class="grid w-full grid-cols-1 gap-6 pb-20 sm:grid-cols-2 lg:w-[80%] xl:grid-cols-3">
            @forelse ($catalogs as $catalog)
                <div class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:shadow-xl">
                    <!-- Cover Image -->
                    <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                        <img src="{{ asset('files/' . $catalog->catalog_cover) }}" alt="{{ $catalog->catalog_name }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center gap-3 bg-[#0D223A]/40 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <a href="{{ asset('files/' . $catalog->catalog_file) }}" target="_blank" class="rounded-full bg-white p-3 text-[#0D223A] shadow-lg transition-colors hover:bg-[#0D223A] hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex grow flex-col p-6">
                        <span class="mb-1 text-[10px] font-bold uppercase tracking-widest text-primary-600">{{ $catalog->brand_name }}</span>
                        <h3 class="mb-5 line-clamp-2 text-lg font-bold leading-snug text-slate-800">{{ $catalog->catalog_name }}</h3>

                        <div class="mt-auto flex items-center justify-between border-t border-slate-50 pt-4 text-slate-400">
                            <a href="{{ asset('files/' . $catalog->catalog_file) }}" download target="_blank" class="flex items-center gap-2 text-xs font-black tracking-wider text-[#0D223A] hover:opacity-70">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                            <span class="text-[10px] font-bold">PDF</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 py-20 text-center">
                    <p class="font-medium italic text-slate-400">No catalogs found for this brand yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-front-layout>
