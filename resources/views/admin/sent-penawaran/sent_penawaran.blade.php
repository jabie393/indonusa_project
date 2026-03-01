<x-app-layout>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">

        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>

        {{-- Tampilkan error validasi jika ada --}}
        @if ($errors->any())
            <div class="m-4 rounded bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="selectCol px-4 py-3"></th>
                        <th class="px-4 py-3">No. Penawaran</th>
                        <th class="px-4 py-3">Sales</th>
                        <th class="px-4 py-3">To</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Diskon</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Sent At</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="w-fit px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penawarans as $index => $penawaran)
                        <tr class="group border-b border-gray-50 transition-colors hover:bg-gray-50/80 dark:border-gray-700/50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3">
                                <input type="checkbox" class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $penawaran->id }}" />
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $penawaran->request_number }}</span>
                                    <span class="mt-0.5 text-[10px] font-semibold uppercase tracking-widest text-amber-500">Request Order</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium">{{ optional($penawaran->sales)->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $penawaran->customer_name }}</td>
                            <td class="px-4 py-3">
                                <span class="inset-ring inline-flex items-center rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                    {{ $penawaran->items->count() }} Items
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $maxDiskon = $penawaran->items->max('diskon_percent') ?? 0;
                                    $itemsWithHighDiscount = $penawaran->items->where('diskon_percent', '>', 20);
                                @endphp
                                <div class="flex items-center">
                                    <span class="{{ $maxDiskon > 20 ? 'text-rose-600' : 'text-emerald-600' }} text-sm font-bold">{{ $maxDiskon }}%</span>
                                    @if ($maxDiskon > 20)
                                        <span class="ml-2 flex h-2 w-2 animate-pulse rounded-full bg-rose-500" title="High Discount"></span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($itemsWithHighDiscount->isNotEmpty())
                                    <div class="max-w-[200px] truncate text-xs italic text-gray-500 group-hover:overflow-visible group-hover:whitespace-normal">
                                        {{ $itemsWithHighDiscount->first()->keterangan ?? 'No reason provided' }}
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-400">{{ $penawaran->created_at ? $penawaran->created_at->format('d M Y') : '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $status = $penawaran->order?->status ?? $penawaran->status;
                                    $statusClass =
                                        [
                                            'sent_to_supervisor' => 'bg-sky-50 text-sky-700 ring-sky-700/20',
                                            'open' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'approved_supervisor' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'rejected_supervisor' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                            'sent_to_warehouse' => 'bg-indigo-50 text-indigo-700 ring-indigo-700/20',
                                        ][$status] ?? 'bg-gray-50 text-gray-600 ring-gray-600/20';
                                @endphp
                                <div class="flex items-center">
                                    <span class="{{ $statusClass }} badge inset-ring">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </div>

                            </td>
                            <td class="w-fit px-4 py-3">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-20 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Detail --}}
                                        <a href="{{ route('sales.request-order.show', $penawaran->id) }}" class="group/btn flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Detail</span>
                                        </a>

                                        {{-- Approve --}}
                                        <form action="{{ route('supervisor.request-order.approve', $penawaran->id) }}" method="POST" class="m-0 border-l border-white/20 p-0">
                                            @csrf
                                            <button type="submit" class="group/btn flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" title="Setujui">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Setujui</span>
                                            </button>
                                        </form>

                                        {{-- Reject --}}
                                        <button type="button" class="group/btn flex h-full cursor-pointer items-center justify-center border-l border-white/20 bg-red-600 p-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="openTolakModal('request_order', '{{ $penawaran->id }}', '{{ $penawaran->request_number }}')" title="Tolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle h-4 w-4">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="m15 9-6 6"></path>
                                                <path d="m9 9 6 6"></path>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Tolak</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $penawarans->links() }}
    </div>

    {{-- ===================================================
         MODAL TOLAK - Satu modal dipakai untuk semua baris
         ===================================================  --}}
    {{-- ===================================================
         MODAL TOLAK - Premium Redesign
         ===================================================  --}}
    <div id="modalTolakGlobal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="modalTolakTitle">
        <div class="animate-scale-up w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 dark:bg-gray-800">
            {{-- Header --}}
            <div class="relative bg-rose-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 id="modalTolakTitle" class="text-lg font-bold tracking-tight text-white">
                            Penolakan Penawaran
                        </h5>
                        <p class="mt-1 text-xs font-medium text-rose-100/80">Nomor: <span id="modalTolakNomor"></span></p>
                    </div>
                    <button type="button" onclick="closeTolakModal()" class="rounded-lg bg-white/10 p-2 text-white transition-colors hover:bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                {{-- Decorative SVG --}}
                <div class="pointer-events-none absolute bottom-0 right-0 opacity-10">
                    <svg width="100" height="100" viewBox="0 0 24 24" fill="white">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
            </div>

            {{-- Body --}}
            <form id="formTolakGlobal" method="POST" action="">
                @csrf
                <div class="px-7 py-6">
                    <div class="mb-5 rounded-lg border border-amber-100 bg-amber-50 p-3">
                        <div class="flex space-x-2">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-[11px] font-medium leading-relaxed text-amber-800">Harap sertakan alasan yang konstruktif agar tim sales dapat melakukan revisi dengan tepat.</p>
                        </div>
                    </div>

                    <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="modalTolakReason" name="reason" rows="4" required minlength="5" placeholder="Contoh: Diskon terlalu besar (max 15%), harap sesuaikan dengan margin target..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm transition-all focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-rose-500/10 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-rose-500"></textarea>

                    <div id="modalTolakError" class="mt-2 hidden items-center text-xs font-semibold text-rose-600">
                        <svg class="mr-1 h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Minimal 5 karakter wajib diisi.
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 px-7 py-5 dark:bg-gray-900/50">
                    <button type="button" onclick="closeTolakModal()" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-500 transition-all hover:bg-gray-100 hover:text-gray-700 active:scale-95 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="button" onclick="submitTolakModal()" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-600/20 transition-all hover:bg-rose-700 hover:shadow-rose-600/30 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        /**
         * Buka modal tolak.
         * @param {string} type  - 'request_order' atau 'custom'
         * @param {string} id    - ID record
         * @param {string} nomor - Nomor penawaran / request number (untuk label)
         */
        function openTolakModal(type, id, nomor) {
            // Tentukan action URL berdasarkan tipe
            let actionUrl = '';
            if (type === 'request_order') {
                // route: POST /request-order/{id}/reject  → supervisor.request-order.reject
                actionUrl = '/request-order/' + id + '/reject';
            } else if (type === 'custom') {
                // route: POST /supervisor/custom-penawaran/{id}/approval → admin.custom-penawaran.approval
                // We set actionUrl to include the ID, but for approval route we need to handle the 'reject' action
                actionUrl = '/supervisor/custom-penawaran/' + id + '/approval?action=reject';
            }

            document.getElementById('formTolakGlobal').action = actionUrl;
            document.getElementById('modalTolakNomor').textContent = nomor;
            document.getElementById('modalTolakReason').value = '';
            document.getElementById('modalTolakError').classList.add('hidden');
            document.getElementById('modalTolakGlobal').classList.remove('hidden');
            document.getElementById('modalTolakGlobal').classList.add('flex');
            document.getElementById('modalTolakReason').focus();
        }

        function closeTolakModal() {
            document.getElementById('modalTolakGlobal').classList.add('hidden');
            document.getElementById('modalTolakGlobal').classList.remove('flex');
        }

        function submitTolakModal() {
            const reason = document.getElementById('modalTolakReason').value.trim();
            if (reason.length < 5) {
                document.getElementById('modalTolakError').classList.remove('hidden');
                document.getElementById('modalTolakReason').focus();
                return;
            }
            document.getElementById('modalTolakError').classList.add('hidden');
            document.getElementById('formTolakGlobal').submit();
        }

        // Tutup modal jika klik di luar area modal
        document.getElementById('modalTolakGlobal').addEventListener('click', function(e) {
            if (e.target === this) closeTolakModal();
        });

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeTolakModal();
        });
    </script>

</x-app-layout>
