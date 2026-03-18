<x-front-layout>
    <div id="hero" class="duration-750 starting:opacity-0 bg-linear-to-r relative flex min-h-[300px] w-full items-center justify-center overflow-hidden from-[#225A97] to-[#0D223A] opacity-100 transition-opacity lg:grow">
        <img src="{{ asset('images/katalog_bg.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover mix-blend-overlay">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-(--breakpoint-xl) relative z-10 mx-auto flex w-full flex-wrap items-start justify-start px-4">
            <div class="md:order-0 order-1 content-center justify-self-start md:col-span-12 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold uppercase leading-none tracking-tight text-white md:text-5xl xl:text-6xl">KATALOG</h1>
                <p class="text-xl font-medium text-white/90">Temukan katalog produk kami</p>
            </div>
        </div>
    </div>
    <div class="max-w-(--breakpoint-2xl) mx-auto container px-6 lg:px-10 flex flex-wrap justify-center lg:justify-between items-center gap-8 lg:gap-10 my-8">
        <a href="#" class="group block transition-all">
            <img src="{{ asset('images/snapon.png') }}" alt="Snap-on" class="h-8 md:h-10 lg:h-12 w-auto object-contain grayscale opacity-60 transition-all duration-500 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110">
        </a>
        <a href="https://padiumk.id" target="_blank" class="group block transition-all">
            <img src="{{ asset('images/padiumk.png') }}" alt="Padi UMK" class="h-8 md:h-10 lg:h-12 w-auto object-contain grayscale opacity-60 transition-all duration-500 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110">
        </a>
        <a href="https://www.inaproc.id" target="_blank" class="group block transition-all">
            <img src="{{ asset('images/inaproc.png') }}" alt="Inaproc" class="h-8 md:h-10 lg:h-12 w-auto object-contain grayscale opacity-60 transition-all duration-500 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110">
        </a>
        <a href="https://www.mbizmarket.co.id" target="_blank" class="group block transition-all">
            <img src="{{ asset('images/mbizmarket.png') }}" alt="Mbizmarket" class="h-8 md:h-10 lg:h-12 w-auto object-contain grayscale opacity-60 transition-all duration-500 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110">
        </a>
    </div>
    <div class="max-w-(--breakpoint-2xl) w-full mx-auto container px-4 py-8 flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filter -->
        <div class="w-full lg:w-[20%] shrink-0">
            <div class="lg:sticky lg:top-24 bg-white rounded-2xl border border-slate-100 shadow-xl p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Filter by brands</h2>
                    <p class="text-[10px] font-black tracking-widest text-slate-400 uppercase mt-1">BROWSE OUR PARTNERS</p>
                </div>

                <div class="flex flex-row lg:flex-col gap-1 overflow-x-auto lg:overflow-y-auto max-h-[calc(100vh-300px)] pb-4 lg:pb-0 scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
                    <a href="{{ route('catalogs') }}" 
                       class="whitespace-nowrap group flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ !request('brand') ? 'bg-[#0D223A] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        All
                    </a>

                    @foreach ($allBrands as $brand)
                        <a href="{{ route('catalogs', ['brand' => $brand]) }}" 
                           class="whitespace-nowrap group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request('brand') == $brand ? 'bg-[#0D223A] text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            {{ $brand }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Catalog Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 w-full lg:w-[80%] pb-20">
            @forelse ($catalogs as $catalog)
                <div class="group flex flex-col overflow-hidden transition-all duration-300 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-xl">
                    <!-- Cover Image -->
                    <div class="relative overflow-hidden aspect-[3/4] bg-slate-100">
                        <img src="{{ asset('files/' . $catalog->catalog_cover) }}" alt="{{ $catalog->catalog_name }}" 
                             class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110">
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-[#0D223A]/40 opacity-0 transition-opacity duration-300 group-hover:opacity-100 flex items-center justify-center gap-3">
                             <a href="{{ asset('files/' . $catalog->catalog_file) }}" target="_blank" class="p-3 bg-white rounded-full text-[#0D223A] hover:bg-[#0D223A] hover:text-white transition-colors shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                             </a>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex flex-col p-6 grow">
                        <span class="text-[10px] font-bold text-primary-600 uppercase tracking-widest mb-1">{{ $catalog->brand_name }}</span>
                        <h3 class="mb-5 text-lg font-bold leading-snug text-slate-800 line-clamp-2">{{ $catalog->catalog_name }}</h3>
                        
                        <div class="flex items-center justify-between pt-4 mt-auto border-t border-slate-50 text-slate-400">
                            <a href="{{ asset('files/' . $catalog->catalog_file) }}" download target="_blank" class="text-xs font-black text-[#0D223A] tracking-wider hover:opacity-70 flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download
                            </a>
                            <span class="text-[10px] font-bold">PDF</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-medium italic">No catalogs found for this brand yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-front-layout>
