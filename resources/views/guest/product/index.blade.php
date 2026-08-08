<x-front-layout>
    <!-- Hero Section -->
    <div id="hero"
         class="hero-gradient duration-750 starting:opacity-0 relative flex min-h-[300px] w-full items-center justify-center overflow-hidden opacity-100 transition-opacity lg:grow">
        <img src="{{ asset('images/katalog_bg.png') }}"
             alt=""
             class="absolute inset-0 h-full w-full object-cover mix-blend-overlay">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-(--breakpoint-2xl) relative z-10 mx-auto flex w-full flex-wrap items-start justify-start px-4">
            <div class="reveal reveal-up md:order-0 order-1 content-center justify-self-start md:col-span-12 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold uppercase leading-none tracking-tight tracking-wider text-white md:text-5xl xl:text-6xl">DAFTAR PRODUK</h1>
                <p class="text-xl font-medium italic text-white/90">Solusi Pengadaan Barang Terpercaya</p>
            </div>
        </div>
    </div>


    <div class="max-w-(--breakpoint-2xl) container mx-auto flex w-full flex-col gap-10 px-4 py-8 lg:flex-row">
        <!-- Sidebar Filter (Kategori) -->
        <div class="reveal reveal-left w-full shrink-0 lg:w-[20%]">
            <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-xl lg:sticky lg:top-24">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-900">Kategori</h2>
                    <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400">PRODUCT CATEGORIES</p>
                </div>

                <div class="scrollbar-none flex max-h-[calc(100vh-320px)] flex-row gap-2 overflow-x-auto pb-4 lg:flex-col lg:overflow-y-auto lg:pb-0">
                    <a href="{{ route('product.index') }}"
                       class="{{ !request('category') ? 'bg-[#0D223A] text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }} whitespace-nowrap rounded-xl px-5 py-3 text-sm font-bold transition-all duration-300">
                        Semua Produk
                    </a>

                    @foreach ($allCategories as $kategori)
                        <a href="{{ route('product.index', ['category' => $kategori]) }}"
                           class="{{ request('category') == $kategori ? 'bg-[#0D223A] text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }} rounded-xl px-5 py-3 text-sm font-bold transition-all duration-300">
                            {{ $kategori }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid w-full grid-cols-1 gap-6 pb-20 sm:grid-cols-2 lg:w-[80%] xl:grid-cols-3">
            @forelse ($goods as $product)
                <a href="{{ route('product.show', $product->id) }}"
                   class="reveal reveal-up group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                    <!-- Image Box -->
                    <div class="relative aspect-square overflow-hidden bg-slate-50">
                        @if (!empty($product->image))
                            <img src="{{ url('files/' . $product->image) }}"
                                 alt="{{ $product->goods_name }}"
                                 class="h-full w-full object-contain p-4 transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="flex h-full items-center justify-center">
                                <svg class="h-20 w-20 text-slate-200"
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

                        <!-- Overlay Labels -->
                        <div class="absolute right-4 top-4 rounded-full bg-[#0D223A] px-3 py-1 text-[10px] font-bold text-white shadow-lg">
                            {{ $product->stock > 0 ? 'READY STOCK' : 'PRE-ORDER' }}
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex grow flex-col p-8">
                        <span class="mb-2 text-[10px] font-black uppercase tracking-widest text-primary-600">{{ $product->category ?? 'Uncategorized' }}</span>
                        <h3 class="mb-4 line-clamp-2 h-12 text-xl font-bold leading-tight text-slate-800">{{ $product->goods_name }}</h3>

                        <div class="mt-auto flex items-center justify-between border-t border-slate-50 pt-6">
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
            @empty
                <div class="col-span-full rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 py-24 text-center">
                    <p class="font-medium italic text-slate-400">Belum ada produk di kategori ini.</p>
                </div>
            @endforelse
        </div>
    </div>


</x-front-layout>
