<x-app-layout>
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Detail Sales Order</h2>
                <p class="text-muted">No. {{ $salesOrder->sales_order_number }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('sales.sales-order.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- Main Details Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-contract"></i> Informasi Sales Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. Sales Order</label>
                                <p>{{ $salesOrder->sales_order_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Request Order</label>
                                <p>
                                    @if($salesOrder->requestOrder)
                                        <a href="{{ route('sales.request-order.show', $salesOrder->requestOrder->id) }}">
                                            {{ $salesOrder->requestOrder->request_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Dibuat</label>
                                <p>{{ $salesOrder->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sales</label>
                                <p>{{ $salesOrder->sales->name ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Customer</label>
                                <p>{{ $salesOrder->customer_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ID Customer</label>
                                <p>{{ $salesOrder->customer_id ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Kebutuhan</label>
                                <p>{{ $salesOrder->tanggal_kebutuhan ? $salesOrder->tanggal_kebutuhan->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <p>
                                    @php
                                        $statusClass = match($salesOrder->status) {
                                            'pending' => 'warning',
                                            'in_process' => 'info',
                                            'shipped' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                        $statusLabel = match($salesOrder->status) {
                                            'in_process' => 'Dalam Proses',
                                            'shipped' => 'Dikirim',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                            default => ucfirst(str_replace('_', ' ', $salesOrder->status))
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                </p>
                            </div>
                        </div>

                        @if($salesOrder->catatan_customer)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan Customer</label>
                                <p>{{ $salesOrder->catatan_customer }}</p>
                            </div>
                        @endif

                        @if($salesOrder->reason)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alasan Pembatalan</label>
                                <p class="text-danger">{{ $salesOrder->reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items & Delivery Tracking Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Detail Barang & Tracking Pengiriman</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th width="100">Diminta</th>
                                    <th width="100">Terkirim</th>
                                    <th width="100">Sisa</th>
                                    <th width="110">Harga</th>
                                    <th width="130">Subtotal</th>
                                    <th width="100">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @forelse($salesOrder->items as $item)
                                    @php 
                                        $total += $item->subtotal;
                                        $remaining = $item->quantity - $item->delivered_quantity;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $item->barang->nama_barang ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">Kode: {{ $item->barang->kode_barang ?? '-' }}</small>
                                        </td>
                                        <td>{{ $item->quantity }} {{ $item->barang->satuan ?? 'pcs' }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $item->delivered_quantity }}</span>
                                        </td>
                                        <td>
                                            @if($remaining > 0)
                                                <span class="badge bg-warning">{{ $remaining }}</span>
                                            @else
                                                <span class="badge bg-secondary">0</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($item->harga, 2, ',', '.') }}</td>
                                        <td><strong>Rp {{ number_format($item->subtotal, 2, ',', '.') }}</strong></td>
                                        <td>
                                            @php
                                                $itemStatusClass = match($item->status_item) {
                                                    'pending' => 'warning',
                                                    'partial' => 'info',
                                                    'completed' => 'success',
                                                    default => 'secondary'
                                                };
                                                $itemStatusLabel = match($item->status_item) {
                                                    'pending' => 'Menunggu',
                                                    'partial' => 'Sebagian',
                                                    'completed' => 'Selesai',
                                                    default => ucfirst($item->status_item)
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $itemStatusClass }}">{{ $itemStatusLabel }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3">Tidak ada item</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="5" class="text-end">TOTAL:</td>
                                    <td>Rp {{ number_format($total, 2, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Timeline/History Card -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Sales Order Dibuat</h6>
                                    <p class="text-muted small">{{ $salesOrder->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if($salesOrder->approved_at)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Disetujui oleh {{ $salesOrder->approvedBy->name ?? 'Admin' }}</h6>
                                        <p class="text-muted small">{{ $salesOrder->approved_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($salesOrder->status === 'shipped')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Sedang Dikirim</h6>
                                        <p class="text-muted small">Status terkini</p>
                                    </div>
                                </div>
                            @endif

                            @if($salesOrder->status === 'completed')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success">
                                        <i class="fas fa-flag-checkered"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Pesanan Selesai</h6>
                                        <p class="text-muted small">Status terkini</p>
                                    </div>
                                </div>
                            @endif

                            @if($salesOrder->status === 'cancelled')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Pesanan Dibatalkan</h6>
                                        <p class="text-muted small">{{ $salesOrder->reason }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-{{ $statusClass }} fs-6">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        @php
                            $totalItems = $salesOrder->items->count();
                            $completedItems = $salesOrder->items->where('status_item', 'completed')->count();
                            $progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
                        @endphp
                        <div class="mb-3">
                            <label class="form-label small">Progress Pengiriman</label>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $progress }}%" 
                                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $progress }}%
                                </div>
                            </div>
                            <small class="text-muted">{{ $completedItems }} dari {{ $totalItems }} item selesai</small>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-cogs"></i> Aksi</h5>
                    </div>
                    <div class="card-body">
                        @if($salesOrder->status !== 'completed' && $salesOrder->status !== 'cancelled')
                            <!-- Update Status Modal Button -->
                            <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="fas fa-sync-alt"></i> Update Status
                            </button>

                            @if($salesOrder->status !== 'cancelled')
                                <!-- Cancel Modal Button -->
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    <i class="fas fa-ban"></i> Batalkan Pesanan
                                </button>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <small>
                                    @if($salesOrder->status === 'completed')
                                        ✓ Pesanan ini sudah selesai
                                    @else
                                        ✗ Pesanan ini sudah dibatalkan
                                    @endif
                                </small>
                            </div>
                        @endif

                        <hr>
                        <a href="{{ route('sales.sales-order.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-list"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-sum"></i> Ringkasan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Total Item:</small>
                            <p>{{ $salesOrder->items->count() }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Total Nilai:</small>
                            <p><strong>Rp {{ number_format($total, 2, ',', '.') }}</strong></p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Terkirim:</small>
                            <p>{{ $salesOrder->items->sum('delivered_quantity') }} {{ $salesOrder->items->first()?->barang?->satuan ?? 'pcs' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Sales Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('sales.sales-order.status', $salesOrder->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Baru</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="pending" @selected($salesOrder->status === 'pending')>Pending</option>
                                <option value="in_process" @selected($salesOrder->status === 'in_process')>Dalam Proses</option>
                                <option value="shipped" @selected($salesOrder->status === 'shipped')>Dikirim</option>
                                <option value="completed" @selected($salesOrder->status === 'completed')>Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Catatan (Opsional)</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Catatan perubahan status..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Sales Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('sales.sales-order.cancel', $salesOrder->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Anda akan membatalkan Sales Order ini. Tindakan ini tidak dapat dibatalkan.
                        </div>
                        <div class="mb-3">
                            <label for="cancel_reason" class="form-label">Alasan Pembatalan <span class="text-danger">*</span></label>
                            <textarea name="reason" id="cancel_reason" class="form-control" rows="4" 
                                      placeholder="Jelaskan alasan pembatalan..." required></textarea>
                            <small class="text-muted">Minimal 10 karakter</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin akan membatalkan pesanan ini?')">
                            <i class="fas fa-trash"></i> Batalkan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card-header {
            padding: 1rem;
        }
        .form-label {
            margin-bottom: 0.25rem;
            color: #666;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding: 0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 2rem;
            position: relative;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 19px;
            top: 50px;
            width: 2px;
            height: calc(100% + 30px);
            background: #dee2e6;
        }

        .timeline-marker {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            margin-right: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .timeline-content h6 {
            margin: 0 0 0.5rem 0;
            font-weight: 600;
        }

        .timeline-content p {
            margin: 0;
        }
    </style>
</x-app-layout>
