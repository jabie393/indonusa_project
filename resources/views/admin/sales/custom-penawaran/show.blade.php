<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Detail Penawaran</h1>
            <div class="flex gap-2">
                <a href="{{ route('sales.custom-penawaran.edit', $customPenawaran->id) }}" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold">
                    Edit
                </a>
                @if($customPenawaran->status === 'approved')
                    <a href="{{ route('sales.custom-penawaran.pdf', $customPenawaran->id) }}" target="_blank"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
                        Generate PDF
                    </a>
                @endif
                @if($customPenawaran->status === 'sent' && auth()->user()->role === 'Supervisor')
                    <form action="{{ route('sales.custom-penawaran.approval', $customPenawaran->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" name="action" value="approve" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Approve</button>
                        <button type="submit" name="action" value="reject" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold ml-2">Reject</button>
                    </form>
                @endif
                <a href="{{ route('sales.custom-penawaran.index') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Header Info -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">No Penawaran</p>
                            <p class="text-xl font-bold text-gray-900">{{ $customPenawaran->penawaran_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Our Ref</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $customPenawaran->our_ref }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($customPenawaran->date)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @php
                                $statusClass = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'sent' => 'bg-blue-100 text-blue-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800'
                                ][$customPenawaran->status] ?? 'bg-gray-100 text-gray-800';
                                $statusLabel = [
                                    'draft' => 'Draft',
                                    'sent' => 'Terkirim',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak'
                                ][$customPenawaran->status] ?? $customPenawaran->status;
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }} mt-1">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Kepada (To)</p>
                            <p class="text-gray-900 font-semibold">{{ $customPenawaran->to }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Attn (Up)</p>
                            <p class="text-gray-900 font-semibold">{{ $customPenawaran->up ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Email</p>
                            <p class="text-gray-900 font-semibold">{{ $customPenawaran->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Subject</p>
                            <p class="text-gray-900 font-semibold">{{ $customPenawaran->subject }}</p>
                        </div>
                    </div>
                </div>

                <!-- Intro Text -->
                @if($customPenawaran->intro_text)
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Teks Pembuka</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $customPenawaran->intro_text }}</p>
                    </div>
                @endif

                <!-- Items Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Detail Barang</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-300">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Barang</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Qty</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Satuan</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Harga (Rp)</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total (Rp)</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Gambar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customPenawaran->items as $index => $item)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 font-semibold">{{ $item->nama_barang }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700">{{ $item->qty }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->satuan }}</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-700">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($item->images && count($item->images) > 0)
                                                <div class="flex gap-3 justify-center flex-wrap">
                                                    @foreach($item->images as $image)
                                                        @php
                                                            if (is_null($image) || $image === '') {
                                                                $imgUrl = null;
                                                            } elseif (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                                                                $imgUrl = $image;
                                                            } elseif (str_starts_with($image, 'public/')) {
                                                                $imgUrl = asset('storage/' . ltrim(substr($image, 7), '/'));
                                                            } else {
                                                                $imgUrl = asset('storage/' . ltrim($image, '/'));
                                                            }
                                                        @endphp

                                                        @if($imgUrl)
                                                            <button type="button" class="custom-penawaran-thumb inline-block" data-full="{{ $imgUrl }}" aria-label="Lihat gambar">
                                                                <img src="{{ $imgUrl }}" alt="Gambar" class="w-20 h-20 sm:w-28 sm:h-28 object-cover rounded border border-gray-300 hover:shadow-lg transition">
                                                            </button>
                                                        @else
                                                            <span class="text-gray-400 text-sm">-</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Tidak ada barang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Penawaran</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sub Total</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($customPenawaran->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pajak/PPN</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($customPenawaran->tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4 flex justify-between">
                            <span class="text-lg font-bold text-gray-900">Grand Total</span>
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($customPenawaran->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div class="space-y-3 text-sm text-gray-600">
                        <div>
                            <p class="font-semibold text-gray-700 mb-1">Dibuat</p>
                            <p>{{ $customPenawaran->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700 mb-1">Diperbarui</p>
                            <p>{{ $customPenawaran->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <!-- Image modal (lightbox) -->
            <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
                <button id="image-modal-close" class="absolute top-6 right-6 text-white text-3xl leading-none">&times;</button>
                <img id="image-modal-img" src="" alt="Gambar" class="max-w-[95%] max-h-[90%] rounded shadow-lg">
            </div>

            <script>
                (function(){
                    const modal = document.getElementById('image-modal');
                    const modalImg = document.getElementById('image-modal-img');
                    const closeBtn = document.getElementById('image-modal-close');

                    function openModal(src){
                        modalImg.src = src;
                        modal.classList.remove('hidden');
                    }

                    function closeModal(){
                        modalImg.src = '';
                        modal.classList.add('hidden');
                    }

                    document.querySelectorAll('.custom-penawaran-thumb').forEach(btn => {
                        btn.addEventListener('click', function(e){
                            const src = this.getAttribute('data-full');
                            if(src) openModal(src);
                        });
                    });

                    closeBtn.addEventListener('click', closeModal);
                    modal.addEventListener('click', function(e){
                        if(e.target === modal) closeModal();
                    });
                    document.addEventListener('keydown', function(e){
                        if(e.key === 'Escape') closeModal();
                    });
                })();
            </script>
        </x-app-layout>
