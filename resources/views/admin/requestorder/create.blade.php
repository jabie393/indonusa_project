<x-app-layout>
<div class="container">
    <h2>Buat Request Barang</h2>

    @if($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('requestorder.store') }}">
        @csrf

        <div class="mb-3">
            <label>Nama Customer</label>
            <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}">
        </div>

        <div id="rows">
            <div class="row mb-2 item-row">
                <div class="col-md-6">
                    <select name="barang_id[]" class="form-control" required>
                        <option value="">-- pilih barang --</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}" {{ $b->stok == 0 ? 'disabled' : '' }}>
                                {{ $b->nama_barang }} (Stok: {{ $b->stok }} {{ $b->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="quantity[]" class="form-control" min="1" value="1">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-row" style="display:none;">Hapus</button>
                </div>
            </div>
        </div>

        <button type="button" id="addRow" class="btn btn-secondary btn-sm">+ Tambah Barang</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
document.getElementById('addRow').addEventListener('click', function(){
    let rows = document.getElementById('rows');
    let clone = rows.querySelector('.item-row').cloneNode(true);
    clone.querySelector('select').value = '';
    clone.querySelector('input').value = 1;
    clone.querySelector('.remove-row').style.display = 'inline-block';
    rows.appendChild(clone);
});
document.getElementById('rows').addEventListener('click', function(e){
    if(e.target.classList.contains('remove-row')){
        e.target.closest('.item-row').remove();
    }
});
</script>
</x-app-layout>
