<x-app-layout>
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Edit Request Order</h2>
                <p class="text-muted">No. {{ $requestOrder->request_number }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('sales.request-order.show', $requestOrder->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <strong>Gagal:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('sales.request-order.update', $requestOrder->id) }}" id="requestOrderForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Customer Info Section -->
                    <div class="card mb-4 bg-light">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Customer</h5>
                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                <i class="fas fa-plus"></i> Tambah Customer Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="customer_id" class="form-label">Pilih Customer <span class="text-danger">*</span></label>
                                        <select class="form-select @error('customer_id') is-invalid @enderror" 
                                                id="customer_id" name="customer_id" required onchange="populateCustomerData(this.value)">
                                            <option value="">-- Pilih Customer --</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}" data-email="{{ $c->email }}" data-telepon="{{ $c->telepon }}" data-kota="{{ $c->kota }}"
                                                    @selected(old('customer_id', $requestOrder->customer_id) == $c->id)>
                                                    {{ $c->nama_customer }} 
                                                    @if($c->email)
                                                        ({{ $c->email }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Pilih dari daftar customer yang sudah terdaftar</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Nama Customer</label>
                                        <input type="text" class="form-control" 
                                               id="customer_name" name="customer_name" value="{{ old('customer_name', $requestOrder->customer_name) }}" readonly>
                                        <small class="text-muted">Auto-filled dari customer yang dipilih</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" 
                                               id="customer_email" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_telepon" class="form-label">Telepon</label>
                                        <input type="text" class="form-control" 
                                               id="customer_telepon" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_kota" class="form-label">Kota</label>
                                        <input type="text" class="form-control" 
                                               id="customer_kota" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_kebutuhan" class="form-label">Tanggal Kebutuhan</label>
                                        <input type="date" class="form-control @error('tanggal_kebutuhan') is-invalid @enderror" 
                                               id="tanggal_kebutuhan" name="tanggal_kebutuhan" value="{{ old('tanggal_kebutuhan', $requestOrder->tanggal_kebutuhan) }}">
                                        @error('tanggal_kebutuhan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="catatan_customer" class="form-label">Catatan</label>
                                        <textarea class="form-control @error('catatan_customer') is-invalid @enderror" 
                                                  id="catatan_customer" name="catatan_customer" rows="1">{{ old('catatan_customer', $requestOrder->catatan_customer) }}</textarea>
                                        @error('catatan_customer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection Section (REQUIRED FIRST) -->
                    <div class="card mb-4 bg-warning bg-opacity-10">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-tag"></i> Kategori Barang <span class="badge bg-danger ms-2">WAJIB DIPILIH TERLEBIH DAHULU</span></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kategori_barang" class="form-label">Pilih Kategori Barang <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kategori_barang') is-invalid @enderror" 
                                                id="kategori_barang" name="kategori_barang" required onchange="filterBarangByCategory(this.value)">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat }}" @selected(old('kategori_barang', $requestOrder->kategori_barang) == $cat)>
                                                    {{ $cat }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_barang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Kategori harus dipilih sebelum memilih barang</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Informasi Periode Berlaku</label>
                                        <div class="alert alert-info mb-0">
                                            <strong>Dimulai:</strong> <span>{{ $requestOrder->tanggal_berlaku_formatted }}</span><br>
                                            <strong>Berakhir:</strong> <span>{{ $requestOrder->expired_at_formatted }}</span><br>
                                            <strong>Status:</strong> 
                                            @if($requestOrder->expired_at)
                                                @if($requestOrder->isExpired())
                                                    <span class="badge bg-danger">KADALUARSA</span>
                                                @else
                                                    <span class="badge bg-success">BERLAKU</span> 
                                                    <small>
                                                        @if(is_string($requestOrder->expired_at))
                                                            ({{ \Carbon\Carbon::parse($requestOrder->expired_at)->diffForHumans() }})
                                                        @else
                                                            ({{ $requestOrder->expired_at->diffForHumans() }})
                                                        @endif
                                                    </small>
                                                @endif
                                            @else
                                                <span class="badge bg-warning">TBD</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="card mb-4" id="barangSection">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-box"></i> Detail Barang</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead class="table-light">
                                        <tr>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th width="100">Diskon (%)</th>
                                                    <th width="120">Jumlah</th>
                                                    <th width="150">Harga Satuan</th>
                                                    <th width="150">Gambar</th>
                                                    <th width="150">Subtotal</th>
                                                    <th width="80">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemRows">
                                        @forelse($requestOrder->items as $item)
                                            <tr class="item-row">
                                                <td>
                                                    <select name="barang_id[]" class="form-control barang-select @error('barang_id.*') is-invalid @enderror" required>
                                                        <option value="">-- Pilih Barang --</option>
                                                        @foreach($barangs as $b)
                                                                <option value="{{ $b->id }}"
                                                                        data-kode="{{ $b->kode_barang }}"
                                                                        data-nama="{{ $b->nama_barang }}"
                                                                        data-kategori="{{ $b->kategori }}"
                                                                        data-stok="{{ $b->stok }}"
                                                                        data-harga="{{ $b->harga ?? 0 }}"
                                                                        data-diskon="{{ $b->diskon_percent ?? 0 }}"
                                                                        @selected($item->barang_id === $b->id)>
                                                                    {{ $b->kode_barang }}
                                                                </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control barang-nama-display" readonly style="background-color: #f0f0f0;" value="{{ $item->barang->nama_barang ?? '' }}">
                                                </td>
                                                    <td>
                                                        <input type="text" class="form-control diskon-display" readonly style="background-color: #f8f9fa;" value="{{ $item->barang->diskon_percent ?? 0 }}%">
                                                    </td>
                                                <td>
                                                    <input type="number" name="quantity[]" class="form-control quantity-input @error('quantity.*') is-invalid @enderror" 
                                                           min="1" value="{{ old('quantity', $item->quantity) }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="harga[]" class="form-control harga-input @error('harga.*') is-invalid @enderror" 
                                                           min="0" step="0.01" value="{{ old('harga', $item->harga) }}">
                                                </td>
                                                <td>
                                                    <input type="file" name="item_images[{{ $loop->index }}][]" class="item-images-input block w-full rounded-lg" multiple accept="image/*">
                                                    <div class="item-images-preview mt-2 row g-2">
                                                        @if($item->item_images && count($item->item_images) > 0)
                                                            @foreach($item->item_images as $img)
                                                                <div class="col-auto">
                                                                    <div class="card" style="width: 90px; height: 90px; overflow: hidden;">
                                                                        <img src="{{ asset('storage/' . $img) }}" class="card-img-top" alt="Image" style="height: 100%; width: 100%; object-fit: cover;">
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control subtotal-display" readonly style="background-color: #f0f0f0;">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-row" style="display:none;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="item-row">
                                                <td>
                                                    <select name="barang_id[]" class="form-control barang-select @error('barang_id.*') is-invalid @enderror" required>
                                                        <option value="">-- Pilih Barang --</option>
                                                        @foreach($barangs as $b)
                                                            <option value="{{ $b->id }}" data-kode="{{ $b->kode_barang }}" data-nama="{{ $b->nama_barang }}" data-kategori="{{ $b->kategori }}" data-stok="{{ $b->stok }}" data-harga="{{ $b->harga ?? 0 }}" style="display: none;">
                                                                {{ $b->kode_barang }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control barang-nama-display" readonly style="background-color: #f0f0f0;">
                                                </td>
                                                <td>
                                                    <input type="number" name="quantity[]" class="form-control quantity-input @error('quantity.*') is-invalid @enderror" 
                                                           min="1" value="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="harga[]" class="form-control harga-input @error('harga.*') is-invalid @enderror" 
                                                           min="0" step="0.01" value="0">
                                                </td>
                                                <td>
                                                    <input type="file" name="item_images[0][]" class="item-images-input block w-full rounded-lg" multiple accept="image/*">
                                                    <div class="item-images-preview mt-2 row g-2"></div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control subtotal-display" readonly style="background-color: #f0f0f0;">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-row" style="display:none;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">TOTAL:</th>
                                            <th>
                                                <strong id="totalAmount">Rp 0</strong>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <button type="button" id="addRow" class="btn btn-secondary">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </button>
                        </div>
                    </div>

                    <!-- Supporting Images Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-images"></i> Gambar Pendukung Penawaran</h5>
                        </div>
                        <div class="card-body">
                            @if($requestOrder->supporting_images && count($requestOrder->supporting_images) > 0)
                                <div class="mb-4">
                                    <h6>Gambar Saat Ini</h6>
                                    <div class="row g-2">
                                        @foreach($requestOrder->supporting_images as $image)
                                            <div class="col-md-3 col-sm-4 col-6">
                                                <div class="card">
                                                    <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="Existing image" style="height: 150px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <small class="text-truncate d-block">{{ basename($image) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="supporting_images" class="form-label">Unggah Gambar Baru <span class="text-muted">(Foto barang, contoh produk, desain, dll)</span></label>
                                <div class="input-group">
                                    <input type="file" class="form-control @error('supporting_images.*') is-invalid @enderror" 
                                           id="supporting_images" name="supporting_images[]" multiple accept="image/*">
                                    <small class="text-muted d-block mt-2">Format: JPG, PNG, GIF | Ukuran maksimal: 5MB per gambar</small>
                                </div>
                                @error('supporting_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="imagePreview" class="row g-2">
                                <!-- Preview images will be displayed here -->
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('sales.request-order.show', $requestOrder->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Customer Baru -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Tambah Customer Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCustomerForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Nama Customer -->
                        <div class="mb-3">
                            <label for="modalNamaCustomer" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modalNamaCustomer" name="nama_customer" required>
                            <small class="text-muted">Nama lengkap pelanggan</small>
                            <div class="invalid-feedback" id="error-nama_customer"></div>
                        </div>

                        <!-- Email & Telepon -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modalEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="modalEmail" name="email">
                                    <div class="invalid-feedback" id="error-email"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modalTelepon" class="form-label">Telepon</label>
                                    <input type="tel" class="form-control" id="modalTelepon" name="telepon">
                                    <div class="invalid-feedback" id="error-telepon"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Tipe Customer -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modalTipeCustomer" class="form-label">Tipe Customer <span class="text-danger">*</span></label>
                                    <select class="form-select" id="modalTipeCustomer" name="tipe_customer" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="retail">Retail</option>
                                        <option value="wholesale">Wholesale</option>
                                        <option value="distributor">Distributor</option>
                                    </select>
                                    <div class="invalid-feedback" id="error-tipe_customer"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modalStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="modalStatus" name="status" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </select>
                                    <div class="invalid-feedback" id="error-status"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="modalAlamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="modalAlamat" name="alamat" rows="2"></textarea>
                            <div class="invalid-feedback" id="error-alamat"></div>
                        </div>

                        <!-- Kota, Provinsi, Kode Pos -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modalKota" class="form-label">Kota</label>
                                    <input type="text" class="form-control" id="modalKota" name="kota">
                                    <div class="invalid-feedback" id="error-kota"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="modalProvinsi" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" id="modalProvinsi" name="provinsi">
                                    <div class="invalid-feedback" id="error-provinsi"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="modalKodePos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" id="modalKodePos" name="kode_pos">
                                    <div class="invalid-feedback" id="error-kode_pos"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Populate customer data from select dropdown
        function populateCustomerData(customerId) {
            const customerSelect = document.getElementById('customer_id');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            
            if (!customerId) {
                document.getElementById('customer_name').value = '';
                document.getElementById('customer_email').value = '';
                document.getElementById('customer_telepon').value = '';
                document.getElementById('customer_kota').value = '';
                return;
            }
            
            document.getElementById('customer_name').value = selectedOption.textContent.split('(')[0].trim();
            document.getElementById('customer_email').value = selectedOption.dataset.email || '';
            document.getElementById('customer_telepon').value = selectedOption.dataset.telepon || '';
            document.getElementById('customer_kota').value = selectedOption.dataset.kota || '';
        }

        // Handle Add Customer Form Submission
        document.addEventListener('DOMContentLoaded', function() {
            const addCustomerForm = document.getElementById('addCustomerForm');
            const addCustomerModal = new bootstrap.Modal(document.getElementById('addCustomerModal'));

            // Populate customer data on page load
            const customerId = document.getElementById('customer_id').value;
            if (customerId) {
                populateCustomerData(customerId);
            }

            addCustomerForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Clear previous errors
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                    el.previousElementSibling.classList.remove('is-invalid');
                });

                const formData = new FormData(this);

                try {
                    const response = await fetch('{{ route("sales.customer.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Add new customer to dropdown
                        const customerSelect = document.getElementById('customer_id');
                        const newOption = document.createElement('option');
                        newOption.value = data.customer.id;
                        newOption.textContent = data.customer.nama_customer + (data.customer.email ? ' (' + data.customer.email + ')' : '');
                        newOption.dataset.email = data.customer.email || '';
                        newOption.dataset.telepon = data.customer.telepon || '';
                        newOption.dataset.kota = data.customer.kota || '';
                        newOption.selected = true;
                        customerSelect.appendChild(newOption);

                        // Populate fields with new customer data
                        populateCustomerData(data.customer.id);

                        // Reset form and close modal
                        addCustomerForm.reset();
                        addCustomerModal.hide();

                        // Show success message
                        showAlert('success', 'Customer berhasil ditambahkan!');
                    } else {
                        showAlert('danger', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                } catch (error) {
                    if (error.response) {
                        // Handle validation errors
                        const errors = error.response.data.errors || {};
                        Object.keys(errors).forEach(field => {
                            const errorElement = document.getElementById('error-' + field);
                            const inputElement = document.getElementById('modal' + capitalizeFirst(field));
                            
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                                if (inputElement) {
                                    inputElement.classList.add('is-invalid');
                                }
                            }
                        });
                    } else {
                        showAlert('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                    }
                }
            });

            // Helper function to capitalize field names
            function capitalizeFirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1).replace(/_(.)/g, (match, letter) => letter.toUpperCase());
            }

            // Helper function to show alert
            function showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const kategoriSelect = document.getElementById('kategori_barang');
            const barangSection = document.getElementById('barangSection');
            const addRowBtn = document.getElementById('addRow');
            const itemRows = document.getElementById('itemRows');
            const supportingImagesInput = document.getElementById('supporting_images');
            const imagePreview = document.getElementById('imagePreview');

            // Filter barang by selected kategori
            window.filterBarangByCategory = function(kategoriValue) {
                const barangSelects = document.querySelectorAll('.barang-select');
                let hasVisibleOptions = false;

                barangSelects.forEach(select => {
                    const options = select.querySelectorAll('option');
                    options.forEach(option => {
                        if (option.value === '') {
                            option.style.display = 'block'; // Always show placeholder
                        } else if (option.dataset.kategori === kategoriValue) {
                            option.style.display = 'block';
                            hasVisibleOptions = true;
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });
            };

            
            function handleBarangChange(select) {
                const option = select.options[select.selectedIndex];
                const row = select.closest('.item-row');
                const namaDisplay = row.querySelector('.barang-nama-display');
                const diskonDisplay = row.querySelector('.diskon-display');
                const hargaInput = row.querySelector('.harga-input');
                
                if (option.value) {
                    namaDisplay.value = option.dataset.nama || '';
                    if (diskonDisplay) diskonDisplay.value = (option.dataset.diskon || '0') + '%';
                    // Set jual price = base price from Barang + 30%
                    const baseHarga = parseFloat(option.dataset.harga || 0) || 0;
                    const hargaJual = +(baseHarga * 1.3).toFixed(2);
                    if (hargaInput) hargaInput.value = hargaJual;
                } else {
                    namaDisplay.value = '';
                    if (diskonDisplay) diskonDisplay.value = '';
                }
                calculateTotals();
            }

            function getBarangOptionsHTML() {
                const firstSelect = document.querySelector('.barang-select');
                return firstSelect.innerHTML;
            }

            function calculateTotals() {
                let total = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
                    const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
                    const subtotal = qty * harga;
                    
                    row.querySelector('.subtotal-display').value = subtotal > 0 
                        ? 'Rp ' + subtotal.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})
                        : '0';
                    
                    total += subtotal;
                });

                document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }

            addRowBtn.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.innerHTML = `
                    <td>
                        <select name="barang_id[]" class="form-control barang-select" required>
                            ${getBarangOptionsHTML()}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control barang-nama-display" readonly style="background-color: #f0f0f0;">
                    </td>
                    <td>
                        <input type="text" class="form-control diskon-display" readonly style="background-color: #f8f9fa;">
                    </td>
                    <td>
                        <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="number" name="harga[]" class="form-control harga-input" min="0" step="0.01" value="0">
                    </td>
                    <td>
                        <input type="file" name="item_images[0][]" class="item-images-input block w-full rounded-lg" multiple accept="image/*">
                        <div class="item-images-preview mt-2 row g-2"></div>
                    </td>
                    <td>
                        <input type="text" class="form-control subtotal-display" readonly style="background-color: #f0f0f0;">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                itemRows.appendChild(newRow);
                attachRowEvents(newRow);
                updateRemoveButtons();
                calculateTotals();
                // Reindex file input names after adding a row
                reindexItemImageInputs();

                // Re-apply kategori filter to new row
                const selectedKategori = kategoriSelect.value;
                if (selectedKategori) {
                    filterBarangByCategory(selectedKategori);
                }
            });

            function attachRowEvents(row) {
                const barangSelect = row.querySelector('.barang-select');
                barangSelect.addEventListener('change', function() {
                    handleBarangChange(this);
                });
                row.querySelector('.quantity-input').addEventListener('change', calculateTotals);
                row.querySelector('.harga-input').addEventListener('change', calculateTotals);
                row.querySelector('.remove-row').addEventListener('click', function() {
                    row.remove();
                    updateRemoveButtons();
                    calculateTotals();
                    reindexItemImageInputs();
                });
                // Setup preview handler
                handleItemImagePreview(row);
            }

            // Preview handler for per-item images
            function handleItemImagePreview(row) {
                const fileInput = row.querySelector('.item-images-input');
                const preview = row.querySelector('.item-images-preview');
                if (!fileInput || !preview) return;

                fileInput.addEventListener('change', function() {
                    const newPreviewContainer = document.createElement('div');
                    newPreviewContainer.className = 'space-y-1';

                    Array.from(this.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-auto';
                            col.innerHTML = `
                                <div class="card" style="width: 90px; height: 90px; overflow: hidden;">
                                    <img src="${e.target.result}" class="card-img-top" alt="Preview ${index + 1}" style="height: 100%; width: 100%; object-fit: cover;">
                                </div>
                            `;
                            newPreviewContainer.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    });

                    if (this.files.length > 0) {
                        preview.appendChild(newPreviewContainer);
                    }
                });
            }

            function updateRemoveButtons() {
                const rows = document.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    row.querySelector('.remove-row').style.display = rows.length > 1 ? 'inline-block' : 'none';
                });
            }

            // Reindex all item_images input names to maintain sequence
            function reindexItemImageInputs() {
                document.querySelectorAll('.item-row').forEach((row, i) => {
                    const fileInput = row.querySelector('.item-images-input');
                    if (fileInput) fileInput.name = `item_images[${i}][]`;
                });
            }

            // Handle supporting images upload and preview
            supportingImagesInput.addEventListener('change', function() {
                imagePreview.innerHTML = ''; // Clear previous previews
                const files = this.files;

                if (files.length === 0) {
                    return;
                }

                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" alt="Preview ${index + 1}" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="text-truncate d-block">${file.name}</small>
                                    <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                                </div>
                            </div>
                        `;
                        imagePreview.appendChild(col);
                    };

                    reader.readAsDataURL(file);
                });
            });

            // Initialize on load - apply category filter if one was selected
            const selectedKategori = kategoriSelect.value;
            if (selectedKategori) {
                filterBarangByCategory(selectedKategori);
            }

            document.querySelectorAll('.item-row').forEach(row => attachRowEvents(row));
            // Initialize harga for rows that have a selected barang
            document.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('.barang-select');
                if (select && select.value) {
                    handleBarangChange(select);
                }
            });
            updateRemoveButtons();
            calculateTotals();
        });
    </script>

    <style>
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .card-header {
            padding: 1rem;
        }
    </style>
</x-app-layout>
