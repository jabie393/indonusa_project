<x-app-layout>
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Edit Customer</h2>
                <p class="text-muted">{{ $customer->nama_customer }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('sales.customer.show', $customer->id) }}" class="btn btn-secondary">
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

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('sales.customer.update', $customer->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- Basic Info Section -->
                            <div class="card mb-4 bg-light">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-id-card"></i> Informasi Dasar</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="nama_customer" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_customer') is-invalid @enderror" 
                                               id="nama_customer" name="nama_customer" value="{{ old('nama_customer', $customer->nama_customer) }}" required>
                                        @error('nama_customer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" name="email" value="{{ old('email', $customer->email) }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="telepon" class="form-label">Telepon</label>
                                                <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                                       id="telepon" name="telepon" value="{{ old('telepon', $customer->telepon) }}">
                                                @error('telepon')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tipe_customer" class="form-label">Tipe Customer</label>
                                                <select name="tipe_customer" id="tipe_customer" class="form-select @error('tipe_customer') is-invalid @enderror">
                                                    <option value="retail" @selected(old('tipe_customer', $customer->tipe_customer) === 'retail')>Retail</option>
                                                    <option value="wholesale" @selected(old('tipe_customer', $customer->tipe_customer) === 'wholesale')>Wholesale</option>
                                                    <option value="distributor" @selected(old('tipe_customer', $customer->tipe_customer) === 'distributor')>Distributor</option>
                                                </select>
                                                @error('tipe_customer')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                                    <option value="active" @selected(old('status', $customer->status) === 'active')>Active</option>
                                                    <option value="inactive" @selected(old('status', $customer->status) === 'inactive')>Inactive</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Section -->
                            <div class="card mb-4 bg-light">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Alamat</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                                  id="alamat" name="alamat" rows="3">{{ old('alamat', $customer->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="kota" class="form-label">Kota</label>
                                                <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                                       id="kota" name="kota" value="{{ old('kota', $customer->kota) }}">
                                                @error('kota')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="provinsi" class="form-label">Provinsi</label>
                                                <input type="text" class="form-control @error('provinsi') is-invalid @enderror" 
                                                       id="provinsi" name="provinsi" value="{{ old('provinsi', $customer->provinsi) }}">
                                                @error('provinsi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="kode_pos" class="form-label">Kode Pos</label>
                                        <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
                                               id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $customer->kode_pos) }}">
                                        @error('kode_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('sales.customer.show', $customer->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-info-circle"></i> Informasi</h5>
                        <p class="card-text small">
                            Update informasi customer sesuai dengan data terbaru.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
