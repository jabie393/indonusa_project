<x-front-layout>
    <!-- Hero Section -->
    <div id="hero" class="hero-gradient relative duration-750 starting:opacity-0 flex min-h-[300px] w-full items-center justify-center opacity-100 transition-opacity lg:grow overflow-hidden">
        <img src="{{ asset('images/katalog_bg.png') }}"
             alt=""
             class="absolute inset-0 h-full w-full object-cover mix-blend-overlay">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-(--breakpoint-2xl) relative z-10 mx-auto flex w-full flex-wrap items-start justify-start px-4">
            <div class="reveal reveal-up md:order-0 order-1 content-center justify-self-start md:col-span-12 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold uppercase leading-none tracking-wider text-white md:text-5xl xl:text-6xl">DETAIL PRODUK</h1>
                <p class="text-xl font-medium italic text-white/90">Solusi Pengadaan Barang Terpercaya</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="reveal reveal-up reveal-delay-200 max-w-(--breakpoint-2xl) mx-auto w-full px-4 pt-6">
        <nav class="flex items-center gap-2 text-sm font-medium text-slate-400">
            <a href="{{ route('product.index') }}"
               class="transition-colors hover:text-[#225A97]">Daftar Produk</a>
            <svg class="h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-700">{{ $product->goods_name ?? 'Detail Produk' }}</span>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="max-w-(--breakpoint-2xl) mx-auto w-full px-4 py-10 pb-24">
        @if (empty($product))
            <div class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white py-32 text-center shadow-sm">
                <svg class="mb-4 h-16 w-16 text-slate-200"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium italic text-slate-400">Barang tidak ditemukan.</p>
                <a href="{{ route('product.index') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#225A97] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition-all hover:bg-[#0D223A]">
                    <svg class="h-4 w-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 xl:gap-16">

                <!-- Image Panel -->
                <div class="reveal reveal-left group relative flex aspect-square items-center justify-center overflow-hidden rounded-3xl border border-slate-100 bg-white p-8 shadow-xl">
                    <!-- Badge -->
                    <div class="absolute right-5 top-5 z-10 rounded-full bg-[#0D223A] px-3 py-1.5 text-[10px] font-bold text-white shadow-lg">
                        {{ $product->stock > 0 ? 'READY STOCK' : 'PRE-ORDER' }}
                    </div>

                    @if (!empty($product->image))
                        <img src="{{ url('files/' . $product->image) }}"
                             alt="{{ $product->goods_name }}"
                             class="h-full w-full object-contain transition-transform duration-700 group-hover:scale-105" />
                    @else
                        <div class="flex flex-col items-center justify-center gap-3 text-slate-200">
                            <svg class="h-24 w-24"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs font-bold uppercase tracking-widest">Tanpa Gambar</p>
                        </div>
                    @endif
                </div>

                <!-- Info Panel -->
                <div class="reveal reveal-right flex flex-col justify-center">
                    <!-- Category Tag -->
                    <span class="mb-3 text-[10px] font-black uppercase tracking-widest text-[#225A97]">{{ $product->category ?? 'Uncategorized' }}</span>

                    <!-- Product Name -->
                    <h2 class="mb-4 text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">
                        {{ $product->goods_name }}
                    </h2>


                    <!-- Divider -->
                    <hr class="mb-6 border-slate-100">

                    <!-- Description -->
                    <p class="mb-8 leading-relaxed text-slate-500">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>



                    <!-- Back Link -->
                    <a href="{{ route('product.index') }}"
                       class="mt-6 inline-flex items-center gap-2 text-sm font-medium text-slate-400 transition-colors hover:text-[#225A97]">
                        <svg class="h-4 w-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar Produk
                    </a>
                </div>

            </div>
        @endif

        {{-- Related Products --}}
        @if (!empty($product) && isset($relatedGoods) && $relatedGoods->isNotEmpty())
            <div class="mt-20">
                <!-- Section Header -->
                <div class="reveal reveal-up mb-8 flex items-end justify-between">
                    <div>
                        <p class="mb-1 text-[10px] font-black uppercase tracking-widest text-[#225A97]">PRODUK SERUPA</p>
                        <h2 class="text-2xl font-extrabold text-slate-900">Kategori {{ $product->category }}</h2>
                    </div>
                    <a href="{{ route('product.index', ['category' => $product->category]) }}"
                       class="flex items-center gap-1 text-sm font-bold text-slate-400 transition-colors hover:text-[#225A97]">
                        Lihat Semua
                        <svg class="h-4 w-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($relatedGoods as $related)
                        <a href="{{ route('product.show', $related->id) }}"
                           class="reveal reveal-up group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">

                            <!-- Image Box -->
                            <div class="relative aspect-square overflow-hidden bg-slate-50">
                                @if (!empty($related->image))
                                    <img src="{{ url('files/' . $related->image) }}"
                                         alt="{{ $related->goods_name }}"
                                         class="h-full w-full object-contain p-4 transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <svg class="h-16 w-16 text-slate-200"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="1"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <!-- Badge -->
                                <div class="absolute right-3 top-3 rounded-full bg-[#0D223A] px-3 py-1 text-[10px] font-bold text-white shadow">
                                    {{ $related->stock > 0 ? 'READY' : 'PRE-ORDER' }}
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="flex grow flex-col p-6">
                                <span class="mb-2 text-[10px] font-black uppercase tracking-widest text-[#225A97]">{{ $related->category ?? 'Uncategorized' }}</span>
                                <h3 class="mb-4 line-clamp-2 text-base font-bold leading-snug text-slate-800">{{ $related->goods_name }}</h3>
                                <div class="mt-auto flex items-center justify-between border-t border-slate-50 pt-4">
                                    <span class="text-xs font-bold text-[#225A97] group-hover:underline">Lihat Detail</span>
                                    <svg class="h-4 w-4 text-[#225A97] transition-transform duration-300 group-hover:translate-x-1"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-front-layout>
