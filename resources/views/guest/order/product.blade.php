<x-front-layout>
    <!-- Hero Section -->
    <div id="hero" class="relative duration-750 starting:opacity-0 bg-linear-to-r flex min-h-[300px] w-full items-center justify-center from-[#225A97] to-[#0D223A] opacity-100 transition-opacity lg:grow overflow-hidden">
        <img src="{{ asset('images/katalog_bg.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 max-w-(--breakpoint-2xl) mx-auto px-4 flex flex-wrap items-start justify-start w-full">
            <div class="md:order-0 order-1 content-center justify-self-start md:col-span-12 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-wider text-white md:text-5xl xl:text-6xl uppercase">DETAIL PRODUK</h1>
                <p class="text-xl font-medium text-white/90 italic">Solusi Pengadaan Barang Terpercaya</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="max-w-(--breakpoint-2xl) w-full mx-auto px-4 pt-6">
        <nav class="flex items-center gap-2 text-sm text-slate-400 font-medium">
            <a href="{{ route('order') }}" class="hover:text-[#225A97] transition-colors">Daftar Order</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-700">{{ $barang->nama_barang ?? 'Detail Produk' }}</span>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="max-w-(--breakpoint-2xl) w-full mx-auto px-4 py-10 pb-24">
        @if (empty($barang))
            <div class="flex flex-col items-center justify-center py-32 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 shadow-sm">
                <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-slate-400 font-medium italic">Barang tidak ditemukan.</p>
                <a href="{{ route('order') }}" class="mt-6 inline-flex items-center gap-2 bg-[#225A97] text-white text-sm font-bold px-6 py-3 rounded-xl hover:bg-[#0D223A] transition-all shadow-lg shadow-blue-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-16">

                <!-- Image Panel -->
                <div class="group relative bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden aspect-square flex items-center justify-center p-8">
                    <!-- Badge -->
                    <div class="absolute top-5 right-5 bg-[#0D223A] text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg z-10">
                        {{ $barang->stok > 0 ? 'READY STOCK' : 'PRE-ORDER' }}
                    </div>

                    @if (!empty($barang->gambar))
                        <img src="{{ url('files/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}"
                             class="object-contain w-full h-full transition-transform duration-700 group-hover:scale-105" />
                    @else
                        <div class="flex flex-col items-center justify-center gap-3 text-slate-200">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs font-bold uppercase tracking-widest">Tanpa Gambar</p>
                        </div>
                    @endif
                </div>

                <!-- Info Panel -->
                <div class="flex flex-col justify-center">
                    <!-- Category Tag -->
                    <span class="text-[10px] font-black text-[#225A97] uppercase tracking-widest mb-3">{{ $barang->kategori ?? 'Uncategorized' }}</span>

                    <!-- Product Name -->
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                        {{ $barang->nama_barang }}
                    </h2>


                    <!-- Divider -->
                    <hr class="border-slate-100 mb-6">

                    <!-- Description -->
                    <p class="text-slate-500 leading-relaxed mb-8">
                        {{ $barang->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-4">
                        <!-- WhatsApp / Pesan Button -->
                        <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya ingin memesan ' . $barang->nama_barang . '. Apakah masih tersedia?') }}"
                           target="_blank"
                           class="flex-1 flex items-center justify-center gap-2 bg-[#225A97] text-white text-sm font-bold py-4 px-6 rounded-xl shadow-lg shadow-blue-200 hover:bg-[#0D223A] transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            PESAN SEKARANG
                        </a>

                        <!-- Add to Cart Button -->
                        <form action="{{ route('keranjang.tambah') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $barang->id }}">
                            <input type="hidden" name="nama" value="{{ $barang->nama_barang }}">
                            <input type="hidden" name="harga" value="{{ $barang->harga }}">
                            <input type="hidden" name="gambar" value="{{ $barang->gambar }}">
                            <button type="submit"
                                class="p-4 bg-green-50 text-green-700 rounded-xl hover:bg-green-700 hover:text-white transition-all duration-300 shadow-sm border border-green-200 hover:border-transparent hover:shadow-lg hover:shadow-green-200"
                                title="Tambah ke Keranjang">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </button>
                        </form>
                    </div>

                    <!-- Back Link -->
                    <a href="{{ route('order') }}" class="mt-6 inline-flex items-center gap-2 text-sm text-slate-400 font-medium hover:text-[#225A97] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Daftar Produk
                    </a>
                </div>

            </div>
        @endif

        {{-- Related Products --}}
        @if (!empty($barang) && isset($relatedGoods) && $relatedGoods->isNotEmpty())
            <div class="mt-20">
                <!-- Section Header -->
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <p class="text-[10px] font-black tracking-widest text-[#225A97] uppercase mb-1">PRODUK SERUPA</p>
                        <h2 class="text-2xl font-extrabold text-slate-900">Kategori {{ $barang->kategori }}</h2>
                    </div>
                    <a href="{{ route('order', ['category' => $barang->kategori]) }}"
                       class="text-sm font-bold text-slate-400 hover:text-[#225A97] transition-colors flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($relatedGoods as $related)
                        <a href="{{ route('product.barang', $related->id) }}"
                           class="group flex flex-col overflow-hidden transition-all duration-500 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-2xl hover:-translate-y-2">

                            <!-- Image Box -->
                            <div class="relative overflow-hidden aspect-square bg-slate-50">
                                @if (!empty($related->gambar))
                                    <img src="{{ url('files/' . $related->gambar) }}" alt="{{ $related->nama_barang }}"
                                         class="object-contain w-full h-full p-4 transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <svg class="w-16 h-16 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <!-- Badge -->
                                <div class="absolute top-3 right-3 bg-[#0D223A] text-white text-[10px] font-bold px-3 py-1 rounded-full shadow">
                                    {{ $related->stok > 0 ? 'READY' : 'PRE-ORDER' }}
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="flex flex-col p-6 grow">
                                <span class="text-[10px] font-black text-[#225A97] uppercase tracking-widest mb-2">{{ $related->kategori ?? 'Uncategorized' }}</span>
                                <h3 class="text-base font-bold leading-snug text-slate-800 line-clamp-2 mb-4">{{ $related->nama_barang }}</h3>
                                <div class="mt-auto flex items-center justify-between border-t border-slate-50 pt-4">
                                    <!-- Cart Button -->
                                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="inline" onclick="event.stopPropagation()">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $related->id }}">
                                        <input type="hidden" name="nama" value="{{ $related->nama_barang }}">
                                        <input type="hidden" name="gambar" value="{{ $related->gambar }}">
                                        <button type="submit"
                                            class="p-2.5 bg-green-50 text-green-700 rounded-xl hover:bg-green-700 hover:text-white transition-all duration-300 border border-green-200 hover:border-transparent">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-front-layout>
