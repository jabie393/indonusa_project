<x-front-layout>
    <!-- Hero Section -->
    <div id="hero" class="hero-gradient relative duration-750 starting:opacity-0 flex min-h-[300px] w-full items-center justify-center opacity-100 transition-opacity lg:grow overflow-hidden">
        <img src="{{ asset('images/katalog_bg.png') }}"
             alt=""
             class="absolute inset-0 h-full w-full object-cover mix-blend-overlay">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-(--breakpoint-2xl) relative z-10 mx-auto flex w-full flex-wrap items-start justify-start px-4">
            <div class="reveal reveal-up md:order-0 order-1 content-center justify-self-start md:col-span-12 md:text-start">
                <h1 class="mb-4 text-4xl font-extrabold uppercase leading-none tracking-wider text-white md:text-5xl xl:text-6xl">KERANJANG</h1>
                <p class="text-xl font-medium italic text-white/90">Solusi Pengadaan Barang Terpercaya</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="reveal reveal-up reveal-delay-200 max-w-(--breakpoint-2xl) mx-auto w-full px-4 pt-6">
        <nav class="flex items-center gap-2 text-sm font-medium text-slate-400">
            <a href="{{ route('order') }}"
               class="transition-colors hover:text-[#225A97]">Daftar Order</a>
            <svg class="h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-700">Keranjang</span>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="max-w-(--breakpoint-2xl) mx-auto w-full px-4 py-10 pb-24">

        @if (empty($keranjang))
            <div class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white py-32 text-center shadow-sm">
                <svg class="mb-4 h-20 w-20 text-slate-200"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="mb-2 font-medium italic text-slate-400">Keranjang kamu masih kosong.</p>
                <p class="mb-6 text-sm text-slate-300">Yuk, mulai tambahkan produk ke keranjang!</p>
                <a href="{{ route('order') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#225A97] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition-all hover:bg-[#0D223A]">
                    <svg class="h-4 w-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Mulai Belanja
                </a>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "{{ route('order') }}";
                }, 3000);
            </script>
        @else
            <div class="flex flex-col items-start gap-8 lg:flex-row">

                <!-- Cart Items -->
                <div class="reveal reveal-left w-full space-y-4 lg:flex-1">
                    <h2 class="mb-6 text-xl font-bold text-slate-800">Item di Keranjang</h2>
                    @php $total = 0; @endphp
                    @foreach ($keranjang as $id => $item)
                        @php
                            $subtotal = $item['harga'] * $item['qty'];
                            $total += $subtotal;
                        @endphp

                        <div class="group flex flex-col gap-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-lg sm:flex-row sm:items-center">

                            <!-- Product Image -->
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-100 bg-slate-50">
                                @if (!empty($item['gambar'] ?? null))
                                    <img class="h-full w-full object-contain p-1"
                                         src="{{ url('files/' . $item['gambar']) }}"
                                         alt="{{ $item['nama'] ?? '' }}" />
                                @else
                                    <svg class="h-10 w-10 text-slate-200"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-bold text-slate-800">{{ $item['nama'] }}</p>

                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-2">
                                <form action="{{ route('keranjang.kurangi', $id) }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-600 transition-all duration-200 hover:border-transparent hover:bg-[#0D223A] hover:text-white">
                                        −
                                    </button>
                                </form>

                                <form action="{{ route('keranjang.tambah') }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    <input type="hidden"
                                           name="id"
                                           value="{{ $id }}">
                                    <input type="hidden"
                                           name="nama"
                                           value="{{ $item['nama'] }}">
                                    <input type="hidden"
                                           name="harga"
                                           value="{{ $item['harga'] }}">
                                    <input type="number"
                                           name="qty"
                                           value="{{ $item['qty'] }}"
                                           min="1"
                                           class="h-9 w-14 rounded-xl border border-slate-200 bg-slate-50 text-center text-sm font-bold text-slate-800 [appearance:textfield] focus:outline-none focus:ring-2 focus:ring-[#225A97] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                                           onchange="this.form.submit()">
                                </form>

                                <form action="{{ route('keranjang.tambah') }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    <input type="hidden"
                                           name="id"
                                           value="{{ $id }}">
                                    <input type="hidden"
                                           name="nama"
                                           value="{{ $item['nama'] }}">
                                    <input type="hidden"
                                           name="harga"
                                           value="{{ $item['harga'] }}">
                                    <button type="submit"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-600 transition-all duration-200 hover:border-transparent hover:bg-[#0D223A] hover:text-white">
                                        +
                                    </button>
                                </form>
                            </div>



                            <!-- Remove Button -->
                            <form action="{{ route('keranjang.hapus', $id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus barang ini dari keranjang?')">
                                @csrf
                                <button type="submit"
                                        class="rounded-xl p-2 text-slate-300 transition-all duration-200 hover:bg-red-50 hover:text-red-500"
                                        title="Hapus dari keranjang">
                                    <svg class="h-5 w-5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach

                    <!-- Continue Shopping -->
                    <a href="{{ route('order') }}"
                       class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-slate-400 transition-colors hover:text-[#225A97]">
                        <svg class="h-4 w-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Lanjut Belanja
                    </a>
                </div>

                <!-- Order Summary -->
                <div class="reveal reveal-right w-full shrink-0 lg:w-[360px]">
                    <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-xl lg:sticky lg:top-24">
                        <h2 class="mb-1 text-xl font-bold text-slate-900">Ringkasan Order</h2>
                        <p class="mb-8 text-[10px] font-black uppercase tracking-widest text-slate-400">ORDER SUMMARY</p>

                        <div class="mb-6 space-y-3 text-sm text-slate-600">
                            @foreach ($keranjang as $id => $item)
                                <div class="flex items-center gap-2">
                                    <span class="flex-1 truncate">{{ $item['nama'] }}</span>
                                    <span class="shrink-0 text-slate-400">×{{ $item['qty'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        @php
                            $waNumber = '6281234567890';
                            $pesan = "Halo, saya ingin memesan:\n";
                            foreach ($keranjang as $item) {
                                $pesan .= "- {$item['nama']} (Qty: {$item['qty']})\n";
                            }
                            $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($pesan);
                        @endphp

                        <form action="{{ route('keranjang.checkout') }}"
                              method="POST"
                              target="_blank"
                              style="display:inline;"
                              class="w-full">
                            @csrf
                            <input type="hidden"
                                   name="waUrl"
                                   value="{{ $waUrl }}">
                            <button type="submit"
                                    class="flex w-full items-center justify-center gap-3 rounded-xl bg-[#225A97] py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition-all duration-300 hover:bg-[#0D223A]">
                                <svg class="h-5 w-5"
                                     fill="currentColor"
                                     viewBox="0 0 24 24">
                                    <path
                                          d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                Checkout via WhatsApp
                            </button>
                        </form>

                        <script>
                            document.querySelector('form[action="{{ route('keranjang.checkout') }}"]').addEventListener('submit', function() {
                                setTimeout(function() {
                                    window.location.href = "{{ route('order') }}";
                                }, 500);
                            });
                        </script>
                    </div>
                </div>

            </div>
        @endif

    </div>
</x-front-layout>
