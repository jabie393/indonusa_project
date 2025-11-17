<x-app-layout>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            
            <a href="{{ route('sales.request-order.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Request Order Baru
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
                            <th>No. Request</th>
                            <th>No. Penawaran</th>
                            <th>Tanggal</th>
                            <th>Nama Customer</th>
                            <th>Jumlah Item</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Berlaku Sampai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requestOrders as $ro)
                            @php
                                $total = $ro->items->sum('subtotal');
                                $statusClass = match($ro->status) {
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'converted' => 'info',
                                    'expired' => 'secondary',
                                    default => 'secondary'
                                };
                                $statusLabel = match($ro->status) {
                                    'expired' => 'Kadaluarsa',
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'converted' => 'Dikonversi',
                                    default => ucfirst($ro->status)
                                };
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $ro->request_number }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $ro->nomor_penawaran ?? '-' }}</span>
                                </td>
                                <td>{{ $ro->created_at->format('d M Y') }}</td>
                                <td>{{ $ro->customer_name }}</td>
                                <td>{{ $ro->items->count() }} item(s)</td>
                                <td>Rp {{ number_format($total, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    @if($ro->expired_at)
                                        {{ $ro->expired_at->format('d M Y') }}
                                        <br>
                                        <small>
                                            @if($ro->isExpired())
                                                <span class="badge bg-danger">EXPIRED</span>
                                            @else
                                                <span class="text-success">{{ $ro->expired_at->diffForHumans() }}</span>
                                            @endif
                                        </small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('sales.request-order.show', $ro->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($ro->status === 'pending')
                                        <a href="{{ route('sales.request-order.edit', $ro->id) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2">Belum ada Request Order</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $requestOrders->links() }}
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</x-app-layout>
