<x-app-layout>
    <div class="container-fluid px-4">
        

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
                            <th>No. Sales Order</th>
                            <th>Request Order</th>
                            <th>Tanggal</th>
                            <th>Nama Customer</th>
                            <th>Jumlah Item</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesOrders as $so)
                            @php
                                $statusClass = match($so->status) {
                                    'pending' => 'warning',
                                    'in_process' => 'info',
                                    'shipped' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                                $statusLabel = match($so->status) {
                                    'in_process' => 'Dalam Proses',
                                    'shipped' => 'Dikirim',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default => ucfirst(str_replace('_', ' ', $so->status))
                                };
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $so->sales_order_number }}</strong>
                                </td>
                                <td>
                                    @if($so->requestOrder)
                                        <a href="{{ route('sales.request-order.show', $so->requestOrder->id) }}" 
                                           class="text-decoration-none">
                                            {{ $so->requestOrder->request_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $so->created_at->format('d M Y') }}</td>
                                <td>{{ $so->customer_name }}</td>
                                <td>{{ $so->items->count() }} item(s)</td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('sales.sales-order.show', $so->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2">Belum ada Sales Order</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $salesOrders->links() }}
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</x-app-layout>
