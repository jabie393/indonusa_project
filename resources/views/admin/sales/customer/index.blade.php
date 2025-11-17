<x-app-layout>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Daftar Customer</h2>
                <p class="text-muted small">Kelola data customer untuk Request Order dan Sales Order</p>
            </div>
            <a href="{{ route('sales.customer.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Customer
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Customer</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Kota</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Dibuat oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            @php
                                $statusClass = $customer->status === 'active' ? 'success' : 'danger';
                                $typeLabel = match($customer->tipe_customer) {
                                    'retail' => 'Retail',
                                    'wholesale' => 'Wholesale',
                                    'distributor' => 'Distributor',
                                    default => ucfirst($customer->tipe_customer)
                                };
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $customer->nama_customer }}</strong>
                                </td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>{{ $customer->telepon ?? '-' }}</td>
                                <td>{{ $customer->kota ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $typeLabel }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $customer->createdBy?->name ?? '-' }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('sales.customer.show', $customer->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('sales.customer.edit', $customer->id) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('sales.customer.destroy', $customer->id) }}" 
                                          style="display:inline;" onsubmit="return confirm('Hapus customer ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-users text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2">Belum ada customer</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</x-app-layout>
