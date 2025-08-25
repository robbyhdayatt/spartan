@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Kategori</h3>
                    <div class="card-tools">
                        @can('manage-master-data')
                        <a href="#" id="btn-create-kategori" class="btn btn-primary btn-sm">Tambah Data</a>
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
                                <th>Nama Kategori</th>
                                <th>Parent Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategoris as $kategori)
                            <tr>
                                <td>{{ $loop->iteration + $kategoris->firstItem() - 1 }}</td>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>{{ $kategori->parent->nama_kategori ?? 'Tidak Ada' }}</td>
                                <td>{!! $kategori->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                <td>
                                    @can('manage-master-data')
                                    <a href="#"
                                       class="btn btn-warning btn-sm btn-edit-kategori"
                                       data-id="{{ $kategori->id_kategori }}"
                                       data-nama_kategori="{{ $kategori->nama_kategori }}"
                                       data-deskripsi="{{ $kategori->deskripsi }}"
                                       data-parent_kategori="{{ $kategori->parent_kategori }}"
                                       data-status_aktif="{{ $kategori->status_aktif }}">
                                       Edit
                                    </a>
                                    @endcan

                                    @can('delete-data')
                                    <form action="{{ route('categories.destroy', $kategori->id_kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                        {{ $kategoris->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kategoriModal" tabindex="-1" role="dialog" aria-labelledby="kategoriModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="kategoriModalLabel">Form Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="kategoriForm" action="" method="POST">
            @csrf
            <div id="form-method-kategori"></div>

            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="parent_kategori">Parent Kategori</label>
                <select name="parent_kategori" id="parent_kategori" class="form-control">
                    <option value="">Tidak Ada</option>
                    @foreach ($parentKategoris as $parent)
                        <option value="{{ $parent->id_kategori }}">{{ $parent->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
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
    $('#btn-create-kategori').on('click', function(e) {
        e.preventDefault();
        $('#kategoriForm')[0].reset();
        $('#form-method-kategori').empty();
        $('#kategoriModalLabel').text('Tambah Kategori Baru');
        $('#kategoriForm').attr('action', '{{ route("categories.store") }}');
        $('#kategoriModal').modal('show');
    });

    // === Logic untuk Tombol Edit ===
    $('.btn-edit-kategori').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let nama_kategori = $(this).data('nama_kategori');
        let deskripsi = $(this).data('deskripsi');
        let parent_kategori = $(this).data('parent_kategori');
        let status_aktif = $(this).data('status_aktif');

        $('#nama_kategori').val(nama_kategori);
        $('#deskripsi').val(deskripsi);
        $('#parent_kategori').val(parent_kategori);
        $('#status_aktif').val(status_aktif);

        $('#kategoriModalLabel').text('Edit Kategori: ' + nama_kategori);
        $('#form-method-kategori').html('@method("PUT")');

        let updateUrl = '{{ route("categories.update", ":id") }}';
        updateUrl = updateUrl.replace(':id', id);
        $('#kategoriForm').attr('action', updateUrl);

        $('#kategoriModal').modal('show');
    });
});
</script>
@endpush
