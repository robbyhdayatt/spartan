@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Brand</h3>
                    <div class="card-tools">
                        @can('manage-master-data')
                        <a href="#" id="btn-create-brand" class="btn btn-primary btn-sm">Tambah Data</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Brand</th>
                                <th>Negara Asal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($brands as $brand)
                            <tr>
                                <td>{{ $loop->iteration + $brands->firstItem() - 1 }}</td>
                                <td>{{ $brand->nama_brand }}</td>
                                <td>{{ $brand->negara_asal }}</td>
                                <td>{!! $brand->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                <td>
                                    @can('manage-master-data')
                                    <a href="#"
                                       class="btn btn-warning btn-sm btn-edit-brand"
                                       data-id="{{ $brand->id_brand }}"
                                       data-nama_brand="{{ $brand->nama_brand }}"
                                       data-negara_asal="{{ $brand->negara_asal }}"
                                       data-status_aktif="{{ $brand->status_aktif }}">
                                       Edit
                                    </a>
                                    @endcan

                                    @can('delete-data')
                                    <form action="{{ route('brands.destroy', $brand->id_brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $brands->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="brandModalLabel">Form Brand</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="brandForm" action="" method="POST">
            @csrf
            <div id="form-method-brand"></div>

            <div class="form-group">
                <label for="nama_brand">Nama Brand</label>
                <input type="text" name="nama_brand" id="nama_brand" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="negara_asal">Negara Asal</label>
                <input type="text" name="negara_asal" id="negara_asal" class="form-control">
            </div>
            <div class="form-group">
                <label for="status_aktif">Status</label>
                <select name="status_aktif" id="status_aktif" class="form-control" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // === Logic untuk Tombol Tambah Data ===
    $('#btn-create-brand').on('click', function(e) {
        e.preventDefault();
        $('#brandForm')[0].reset();
        $('#form-method-brand').empty();
        $('#brandModalLabel').text('Tambah Brand Baru');
        $('#brandForm').attr('action', '{{ route("brands.store") }}');
        $('#brandModal').modal('show');
    });

    // === Logic untuk Tombol Edit ===
    $('.btn-edit-brand').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let nama_brand = $(this).data('nama_brand');
        let negara_asal = $(this).data('negara_asal');
        let status_aktif = $(this).data('status_aktif');

        $('#nama_brand').val(nama_brand);
        $('#negara_asal').val(negara_asal);
        $('#status_aktif').val(status_aktif);

        $('#brandModalLabel').text('Edit Brand: ' + nama_brand);
        $('#form-method-brand').html('@method("PUT")');

        let updateUrl = '{{ route("brands.update", ":id") }}';
        updateUrl = updateUrl.replace(':id', id);
        $('#brandForm').attr('action', updateUrl);

        $('#brandModal').modal('show');
    });
});
</script>
@endpush
