<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        @if (session('success'))
            <div class="mb-6 flex items-center justify-between rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900 dark:text-green-300" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-green-100 p-1.5 text-green-500 hover:bg-green-200 focus:ring-2 focus:ring-green-400 dark:bg-green-800 dark:text-green-400 dark:hover:bg-green-700" data-bs-dismiss="alert" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700 dark:bg-red-900 dark:text-red-300" role="alert">
                <div class="mb-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="font-medium">Terdapat kesalahan:</span>
                </div>
                <ul class="ml-4 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="mt-2 text-sm underline hover:no-underline" data-bs-dismiss="alert" aria-label="Close">Tutup</button>
            </div>
        @endif
        <div class="space-y-3 p-6 md:space-x-4 md:space-y-0">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Left Column -->
                <div class="lg:col-span-2">
                    <!-- Main Details Card -->
                    <!-- Header -->
                    <div class="col-span-2 mb-6 flex items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detail Request Order</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No. {{ $requestOrder->request_number }}</p>
                        </div>
                    </div>
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm mb-6 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <h2 class="mr-3 font-semibold text-white">
                                Informasi Request Order
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="mb-6 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">No. Request</label>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $requestOrder->request_number }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">No. Penawaran</label>
                                    <p><span class="rounded bg-blue-100 px-2.5 py-0.5 text-xl font-semibold text-blue-800 dark:bg-blue-200 dark:text-blue-800">{{ $requestOrder->nomor_penawaran ?? '-' }}</span></p>
                                </div>
                                <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">No. Sales Order</label>
                                <p><span class="rounded bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800 dark:bg-green-200 dark:text-green-800">{{ $requestOrder->sales_order_number ?? '-' }}</span></p>
                            </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Dibuat</label>
                                    <p class="text-gray-900 dark:text-white">{{ $requestOrder->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Kategori Barang</label>
                                    <p><span class="rounded bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ $requestOrder->kategori_barang ?? '-' }}</span></p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Customer</label>
                                    <p class="text-gray-900 dark:text-white">{{ $requestOrder->customer_name }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">ID Customer</label>
                                    <p class="text-gray-900 dark:text-white">{{ $requestOrder->customer_id ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">PIC (Sales)</label>
                                    <p class="text-gray-900 dark:text-white">{{ $requestOrder->sales->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Subject</label>
                                    <p class="text-gray-900 dark:text-white">{{ $requestOrder->subject ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Kebutuhan</label>
                                    <p class="text-gray-900 dark:text-white">{{ $requestOrder->tanggal_kebutuhan_formatted }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                                    <div>
                                        @php
                                            $statusClass = match ($requestOrder->status) {
                                                'open', 'converted' => 'bg-blue-100 text-blue-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'expired' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        @php
                                            $statusLabel = match ($requestOrder->status) {
                                                'open', 'converted' => 'Open',
                                                'pending' => 'Menunggu',
                                                'approved' => 'Disetujui',
                                                'rejected' => 'Ditolak',
                                                'expired' => 'Kadaluarsa',
                                                default => ucfirst($requestOrder->status),
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                    </div>
                                </div>
                                <!-- Validity Period -->
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Masa Berlaku Mulai</label>
                                    <p class="text-gray-900 dark:text-white">{{ $requestOrder->tanggal_berlaku_formatted }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Masa Berlaku Berakhir</label>
                                    <p>
                                        @if ($requestOrder->expired_at)
                                            {{ $requestOrder->expired_at_formatted }}
                                            <br>
                                            @if ($requestOrder->isExpired())
                                                <small class="badge bg-danger">KADALUARSA</small>
                                            @else
                                                @php
                                                    try {
                                                        $expiry = is_string($requestOrder->expired_at) ? \Carbon\Carbon::parse($requestOrder->expired_at) : $requestOrder->expired_at;
                                                        $daysLeft = $expiry->diffInDays(now());
                                                    } catch (\Throwable $e) {
                                                        $daysLeft = null;
                                                    }
                                                @endphp
                                                <small class="badge bg-success">
                                                    @if ($daysLeft && $daysLeft > 0)
                                                        {{ $daysLeft }} hari dari sekarang
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                </div>
                            </div>

                            @if ($requestOrder->catatan_customer)
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-300">Catatan Customer</label>
                                    <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ $requestOrder->catatan_customer }}
                                    </div>
                                </div>
                            @endif

                            @if ($requestOrder->reason)
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-sm font-semibold text-red-600 dark:text-red-400">Alasan Penolakan</label>
                                    <div class="rounded-lg bg-red-50 p-3 text-sm text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                        {{ $requestOrder->reason }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Supporting Images Card -->
                    @if ($requestOrder->supporting_images && count($requestOrder->supporting_images) > 0)
                        <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                            <div class="flex items-center justify-between bg-green-600 p-4 text-white">
                                <h2 class="flex items-center gap-2 text-lg font-semibold">
                                    <i class="fas fa-images"></i> Gambar Pendukung
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                                    @foreach ($requestOrder->supporting_images as $image)
                                        <div class="group relative overflow-hidden rounded-lg border border-gray-200 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-700">
                                            <a href="{{ asset('storage/' . $image) }}" target="_blank" class="block aspect-square overflow-hidden bg-gray-100 dark:bg-gray-600">
                                                <img src="{{ asset('storage/' . $image) }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" alt="Supporting image">
                                            </a>
                                            <div class="truncate p-2 text-center text-xs text-gray-500 dark:text-gray-400">
                                                {{ basename($image) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Items Card -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="flex items-center justify-between bg-[#225A97] p-4 text-white">
                            <h2 class="flex items-center gap-2 text-lg font-semibold">
                                <i class="fas fa-box"></i> Detail Barang
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Kategori Barang</th>
                                        <th scope="col" class="px-6 py-3">Barang</th>
                                        <th scope="col" class="px-6 py-3">Diskon (%)</th>
                                        <th scope="col" class="px-6 py-3">Jumlah</th>
                                        <th scope="col" class="px-6 py-3">Harga Satuan</th>
                                        <th scope="col" class="px-6 py-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 border-b dark:divide-gray-700 dark:border-gray-700">
                                    @php $total = 0; @endphp
                                    @forelse($requestOrder->items as $item)
                                        @php
                                            // Show harga satuan directly from barang table (fallback to item's harga if barang missing)
                                            $displayHarga = optional($item->barang)->harga ?? ($item->harga ?? 0);
                                            $computedSubtotal = $displayHarga * $item->quantity;
                                            $total += $computedSubtotal;
                                        @endphp
                                        <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4">
                                                <span class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                    {{ $item->kategori_barang ?? ($item->barang->kategori ?? '-') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $item->barang->nama_barang ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Kode: {{ $item->barang->kode_barang ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4">{{ $item->diskon_percent ?? ($item->barang->diskon_percent ?? 0) }}%</td>
                                            <td class="px-6 py-4">{{ $item->quantity }} {{ $item->barang->satuan ?? 'pcs' }}</td>
                                            <td class="px-6 py-4">Rp {{ number_format($displayHarga, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">Rp {{ number_format($computedSubtotal, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center">Tidak ada item</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-50 font-semibold text-gray-900 dark:bg-gray-700 dark:text-white">
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-right">TOTAL:</td>
                                        <td class="px-6 py-4">Rp {{ number_format($total, 2, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="gap-2 lg:col-span-1">
                    @php
                        $subtotal = $requestOrder->items->sum(function ($item) {
                            $displayHarga = optional($item->barang)->harga ?? ($item->harga ?? 0);
                            return $displayHarga * $item->quantity;
                        });
                        $tax = 0; // Assuming tax is 0 for now, or calculate if needed
                        $grandTotal = $subtotal + $tax;
                    @endphp
                    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm sticky top-24 mb-6 overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
                            <div>
                                <h2 class="mr-3 font-semibold text-white">Ringkasan Request Order</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Sub Total</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Pajak/PPN</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($tax, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-4 dark:border-gray-700">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">Grand Total</span>
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">Rp
                                        {{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-gray-700">

                            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                <div>
                                    <p class="mb-1 font-semibold text-gray-700 dark:text-gray-300">Dibuat</p>
                                    <p>{{ $requestOrder->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="mb-1 font-semibold text-gray-700 dark:text-gray-300">Diperbarui</p>
                                    <p>{{ $requestOrder->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Status Card -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="flex items-center justify-between bg-cyan-600 p-4 text-white">
                            <h2 class="flex items-center gap-2 text-lg font-semibold">
                                <i class="fas fa-info-circle"></i> Info Status
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <span class="{{ $statusClass }} rounded px-2.5 py-1 text-sm font-semibold">
                                    {{ ucfirst($requestOrder->status) }}
                                </span>
                            </div>
                            <p class="text-muted small">
                                @if ($requestOrder->status === 'pending_approval')
                                    Request Order menunggu untuk disetujui.
                                @elseif($requestOrder->status === 'approved')
                                    Request Order telah disetujui dan siap untuk dikonversi.
                                @elseif($requestOrder->status === 'rejected')
                                    Request Order ditolak oleh supervisor.
                                @endif
                            </p>
                        </div>

                    </div>

                    <!-- Actions Card -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-md dark:bg-gray-800">
                        <div class="flex items-center justify-between bg-emerald-600 p-4 text-white">
                            <h2 class="flex items-center gap-2 text-lg font-semibold">
                                <i class="fas fa-cogs"></i> Aksi
                            </h2>
                        </div>
                        <div class="flex flex-col gap-3 p-6">
                            @if ($requestOrder->status === 'pending')
                                <a href="{{ route('sales.request-order.edit', $requestOrder->id) }}" class="inline-flex w-full items-center justify-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-900">
                                    <i class="fas fa-edit mr-2"></i> Edit Request Order
                                </a>
                            @endif

                            @php
                                $canDownloadPdf = false;
                                if (auth()->check() && in_array(auth()->user()->role, ['Supervisor', 'Admin'])) {
                                    $canDownloadPdf = true;
                                } elseif (auth()->check() && auth()->id() === $requestOrder->sales_id) {
                                    $canDownloadPdf = !in_array($requestOrder->status, ['pending_approval', 'rejected']);
                                }
                            @endphp

                            @if ($canDownloadPdf)
                                <button type="button" class="btn btn-secondary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#pdfNoteModal">
                                    <i class="fas fa-download"></i> Download PDF
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary w-100 mb-2" disabled title="PDF tidak tersedia sampai Supervisor menyetujui penawaran">
                                    <i class="fas fa-download"></i> Download PDF
                                </button>
                            @endif



                            {{-- Supervisor actions for pending approvals --}}
                            @if (auth()->check() && auth()->user()->role === 'Supervisor' && $requestOrder->status === 'pending_approval')
                                <form method="POST" action="{{ route('supervisor.request-order.approve', $requestOrder->id) }}" class="block w-full">
                                    @csrf
                                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800" onclick="return confirm('Setujui Request Order ini?')">
                                        <i class="fas fa-check mr-2"></i> Setujui Penawaran
                                    </button>
                                </form>

                                <!-- Reject with reason -->
                                <button type="button" class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-ban mr-2"></i> Tolak Penawaran
                                </button>
                            @endif

                            <a href="{{ route('sales.request-order.index') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-gray-500 px-4 py-2 text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                <i class="fas fa-list mr-2"></i> Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Reject Modal -->
    <!-- PDF Note Modal -->
    <div class="modal fade" id="pdfNoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Catatan untuk PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET" action="{{ route('sales.request-order.pdf', $requestOrder->id) }}" target="_blank">
                    <div class="modal-body">
                        @php
                            $defaultPdfNote = "Untuk memenuhi kebutuhan..., bersama ini kami sampaikan penawaran harga beserta spesifikasi produk sebagai berikut:\n\n";
                        @endphp
                        <div class="mb-3">
                            <label for="pdf_note" class="form-label">Catatan yang akan muncul di PDF</label>
                            <textarea id="pdf_note" name="pdf_note" rows="8" class="form-control">{{ old('pdf_note', $requestOrder->catatan_customer ?? $defaultPdfNote) }}</textarea>
                            <small class="text-muted">Teks ini akan dimasukkan ke bagian pembuka PDF. Anda dapat mengeditnya sebelum mengunduh.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Generate &amp; Download PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function supervisorBack() {
            try {
                var backBtn = document.getElementById('backBtn');
                var fallback = backBtn ? backBtn.dataset.fallback : '/sent-penawaran';

                // If referrer is the sent-penawaran page, just go back
                if (document.referrer && document.referrer.indexOf('/sent-penawaran') !== -1) {
                    history.back();
                    return;
                }

                // If there's history, try history.back() and fallback if nothing changes
                if (history.length > 1) {
                    // attempt history.back(), but also set a fallback timer
                    var navigated = false;
                    var onPop = function() {
                        navigated = true;
                        window.removeEventListener('popstate', onPop);
                    };
                    window.addEventListener('popstate', onPop);
                    history.back();
                    setTimeout(function() {
                        if (!navigated) {
                            window.location.href = fallback;
                        }
                    }, 250);
                    return;
                }

                // No useful history -> direct fallback
                window.location.href = fallback;
            } catch (e) {
                // If any error, go to fallback
                window.location.href = '{{ route('admin.sent_penawaran') }}';
            }
        }
    </script>

</x-app-layout>
