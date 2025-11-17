<x-app-layout>
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Detail Request Order</h2>
                <p class="text-muted">No. {{ $requestOrder->request_number }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('sales.request-order.index') }}" class="btn btn-secondary">
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
                        <h5 class="mb-0"><i class="fas fa-file-alt"></i> Informasi Request Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. Request</label>
                                <p>{{ $requestOrder->request_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. Penawaran</label>
                                <p><span class="badge bg-info">{{ $requestOrder->nomor_penawaran ?? '-' }}</span></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Dibuat</label>
                                <p>{{ $requestOrder->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kategori Barang</label>
                                <p><span class="badge bg-secondary">{{ $requestOrder->kategori_barang ?? '-' }}</span></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Customer</label>
                                <p>{{ $requestOrder->customer_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ID Customer</label>
                                <p>{{ $requestOrder->customer_id ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Kebutuhan</label>
                                <p>{{ $requestOrder->tanggal_kebutuhan ? $requestOrder->tanggal_kebutuhan->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <p>
                                    @php
                                        $statusClass = match($requestOrder->status) {
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'converted' => 'info',
                                            'expired' => 'secondary',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($requestOrder->status) }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Validity Period -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Masa Berlaku Mulai</label>
                                <p>{{ $requestOrder->tanggal_berlaku ? $requestOrder->tanggal_berlaku->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Masa Berlaku Berakhir</label>
                                <p>
                                    @if($requestOrder->expired_at)
                                        {{ $requestOrder->expired_at->format('d M Y') }}
                                        <br>
                                        @if($requestOrder->isExpired())
                                            <small class="badge bg-danger">KADALUARSA</small>
                                        @else
                                            <small class="badge bg-success">{{ $requestOrder->expired_at->diffForHumans() }}</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($requestOrder->catatan_customer)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan Customer</label>
                                <p>{{ $requestOrder->catatan_customer }}</p>
                            </div>
                        @endif

                        @if($requestOrder->reason)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alasan Penolakan</label>
                                <p class="text-danger">{{ $requestOrder->reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Supporting Images Card -->
                @if($requestOrder->supporting_images && count($requestOrder->supporting_images) > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-images"></i> Gambar Pendukung</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($requestOrder->supporting_images as $image)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card">
                                            <a href="{{ asset('storage/' . $image) }}" target="_blank" data-bs-toggle="tooltip" title="Klik untuk memperbesar">
                                                <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="Supporting image" style="height: 200px; object-fit: cover; cursor: pointer;">
                                            </a>
                                            <div class="card-body p-2">
                                                <small class="text-muted">{{ basename($image) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Items Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Detail Barang</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th width="100">Jumlah</th>
                                    <th width="120">Harga Satuan</th>
                                    <th width="120">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @forelse($requestOrder->items as $item)
                                    @php $total += $item->subtotal; @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $item->barang->nama_barang ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">Kode: {{ $item->barang->kode_barang ?? '-' }}</small>
                                        </td>
                                        <td>{{ $item->quantity }} {{ $item->barang->satuan ?? 'pcs' }}</td>
                                        <td>Rp {{ number_format($item->harga, 2, ',', '.') }}</td>
                                        <td><strong>Rp {{ number_format($item->subtotal, 2, ',', '.') }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">Tidak ada item</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="3" class="text-end">TOTAL:</td>
                                    <td>Rp {{ number_format($total, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Info Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-{{ $statusClass }} fs-6">
                                {{ ucfirst($requestOrder->status) }}
                            </span>
                        </div>
                        <p class="text-muted small">
                            @if($requestOrder->status === 'pending')
                                Request Order menunggu untuk disetujui
                            @elseif($requestOrder->status === 'approved')
                                Request Order telah disetujui dan siap untuk dikonversi
                            @elseif($requestOrder->status === 'rejected')
                                Request Order ditolak oleh supervisor
                            @elseif($requestOrder->status === 'converted')
                                Request Order telah dikonversi menjadi Sales Order
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-cogs"></i> Aksi</h5>
                    </div>
                    <div class="card-body">
                        @if($requestOrder->status === 'pending')
                            <a href="{{ route('sales.request-order.edit', $requestOrder->id) }}" class="btn btn-warning w-100 mb-2">
                                <i class="fas fa-edit"></i> Edit Request Order
                            </a>
                        @endif

                        @if($requestOrder->status === 'approved' && !$requestOrder->salesOrder)
                            <form method="POST" action="{{ route('sales.request-order.convert', $requestOrder->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2" 
                                        onclick="return confirm('Konversi Request Order ke Sales Order?')">
                                    <i class="fas fa-arrow-right"></i> Konversi ke Sales Order
                                </button>
                            </form>
                        @endif

                        @if($requestOrder->salesOrder)
                            <div class="alert alert-info">
                                <small>
                                    <strong>✓ Sudah dikonversi</strong><br>
                                    Sales Order: <a href="{{ route('sales.sales-order.show', $requestOrder->salesOrder->id) }}">
                                        {{ $requestOrder->salesOrder->sales_order_number }}
                                    </a>
                                </small>
                            </div>
                        @endif

                        <a href="{{ route('sales.request-order.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-list"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
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
    </style>
</x-app-layout>
