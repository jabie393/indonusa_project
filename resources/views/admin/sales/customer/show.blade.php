<x-app-layout>
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Detail Customer</h2>
                <p class="text-muted">{{ $customer->nama_customer }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('sales.customer.index') }}" class="btn btn-secondary">
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

        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Info Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-id-card"></i> Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Customer</label>
                                <p>{{ $customer->nama_customer }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipe Customer</label>
                                <p>
                                    @php
                                        $typeLabel = match($customer->tipe_customer) {
                                            'retail' => 'Retail',
                                            'wholesale' => 'Wholesale',
                                            'distributor' => 'Distributor',
                                            default => ucfirst($customer->tipe_customer)
                                        };
                                    @endphp
                                    <span class="badge bg-info">{{ $typeLabel }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <p>{{ $customer->email ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Telepon</label>
                                <p>{{ $customer->telepon ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <p>
                                    <span class="badge bg-{{ $customer->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Alamat</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <p>{{ $customer->alamat ?? '-' }}</p>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Kota</label>
                                <p>{{ $customer->kota ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Provinsi</label>
                                <p>{{ $customer->provinsi ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Kode Pos</label>
                                <p>{{ $customer->kode_pos ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Card -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dibuat oleh</label>
                            <p>{{ $customer->createdBy?->name ?? '-' }} pada {{ $customer->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Diubah terakhir oleh</label>
                            <p>{{ $customer->updatedBy?->name ?? '-' }} pada {{ $customer->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-cogs"></i> Aksi</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('sales.customer.edit', $customer->id) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit"></i> Edit Customer
                        </a>
                        <form method="POST" action="{{ route('sales.customer.destroy', $customer->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Hapus customer ini? (Hanya bisa jika tidak ada pesanan)');">
                                <i class="fas fa-trash"></i> Hapus Customer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistik</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Request Order</label>
                            <p class="h4">{{ $customer->requestOrders->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-info-circle"></i> Info</h5>
                        <p class="card-text small">
                            Customer ini memiliki {{ $customer->requestOrders->count() }} Request Order.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-label {
            margin-bottom: 0.25rem;
            color: #666;
        }
        .card-header {
            padding: 1rem;
        }
    </style>
</x-app-layout>
