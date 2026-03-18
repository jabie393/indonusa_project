<x-front-layout>
    <!-- Hero Section -->
    <div id="hero" class="relative duration-750 starting:opacity-0 bg-linear-to-r flex min-h-[300px] w-full items-center justify-center from-[#225A97] to-[#0D223A] opacity-100 transition-opacity lg:grow overflow-hidden">
        <img src="{{ asset('images/katalog_bg.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 max-w-(--breakpoint-2xl) mx-auto px-4 flex flex-wrap items-start justify-start w-full">
            <div class="md:order-0 order-1 content-center justify-self-start md:col-span-12 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-white md:text-5xl xl:text-6xl uppercase tracking-wider">DAFTAR ORDER</h1>
                <p class="text-xl font-medium text-white/90 italic">Solusi Pengadaan Barang Terpercaya</p>
            </div>
        </div>
    </div>

    <!-- Marketplace Logos -->
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

    <div class="max-w-(--breakpoint-2xl) w-full mx-auto container px-4 py-8 flex flex-col lg:flex-row gap-10">
        <!-- Sidebar Filter (Kategori) -->
        <div class="w-full lg:w-[20%] shrink-0">
            <div class="lg:sticky lg:top-24 bg-white rounded-3xl border border-slate-100 shadow-xl p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-900">Kategori</h2>
                    <p class="text-[10px] font-black tracking-widest text-slate-400 uppercase mt-1">PRODUCT CATEGORIES</p>
                </div>

                <div class="flex flex-row lg:flex-col gap-2 overflow-x-auto lg:overflow-y-auto max-h-[calc(100vh-320px)] pb-4 lg:pb-0 scrollbar-none">
                    <a href="{{ route('order') }}" 
                       class="whitespace-nowrap px-5 py-3 rounded-xl text-sm font-bold transition-all duration-300 {{ !request('category') ? 'bg-[#0D223A] text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        Semua Produk
                    </a>

                    @foreach ($allCategories as $kategori)
                        <a href="{{ route('order', ['category' => $kategori]) }}" 
                           class=" px-5 py-3 rounded-xl text-sm font-bold transition-all duration-300 {{ request('category') == $kategori ? 'bg-[#0D223A] text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            {{ $kategori }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 w-full lg:w-[80%] pb-20">
            @forelse ($goods as $barang)
                <div class="group flex flex-col overflow-hidden transition-all duration-500 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-2xl hover:-translate-y-2">
                    <a href="{{ route('product.barang', $barang->id) }}">
                    <!-- Image Box -->
                    <div class="relative overflow-hidden aspect-square bg-slate-50">
                        @if (!empty($barang->gambar))
                            <img src="{{ url('files/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" 
                                 class="object-contain w-full h-full p-4 transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="w-20 h-20 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <!-- Overlay Labels -->
                        <div class="absolute top-4 right-4 bg-[#0D223A] text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">
                            {{ $barang->stok > 0 ? 'READY STOCK' : 'PRE-ORDER' }}
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex flex-col p-8 grow">
                        <span class="text-[10px] font-black text-primary-600 uppercase tracking-widest mb-2">{{ $barang->kategori ?? 'Uncategorized' }}</span>
                        <h3 class="mb-4 text-xl font-bold leading-tight text-slate-800 line-clamp-2 h-12">{{ $barang->nama_barang }}</h3>
                        
                        <div class="flex items-center gap-3 mt-auto pt-6 border-t border-slate-50">
                            <!-- WhatsApp Button -->
                            <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya ingin memesan ' . $barang->nama_barang . '. Apakah masih tersedia?') }}"
                               target="_blank"
                               class="flex-1 flex items-center justify-center gap-2 bg-[#225A97] text-white text-xs font-bold py-4 rounded-xl shadow-lg shadow-blue-200 hover:bg-[#0D223A] transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                PESAN
                            </a>
                            
                            <!-- Cart Button -->
                            <form action="{{ route('keranjang.tambah') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $barang->id }}">
                                <input type="hidden" name="nama" value="{{ $barang->nama_barang }}">
                                <input type="hidden" name="harga" value="{{ $barang->harga }}">
                                <input type="hidden" name="gambar" value="{{ $barang->gambar }}">
                                <button type="submit"
                                    class="p-4 bg-green-50 text-green-700 rounded-xl hover:bg-green-700 hover:text-white transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-medium italic">Belum ada barang di kategori ini.</p>
                </div>
            @endforelse
        </div>
    </div>


</x-front-layout>
