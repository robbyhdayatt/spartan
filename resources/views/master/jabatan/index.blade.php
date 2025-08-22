@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Jabatan</h3>
                    <div class="card-tools">
                        <a href="#" id="btn-create-jabatan" class="btn btn-primary btn-sm">Tambah Data</a>
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
                                <th>Nama Jabatan</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jabatans as $jabatan)
                            <tr>
                                <td>{{ $loop->iteration + $jabatans->firstItem() - 1 }}</td>
                                <td>{{ $jabatan->nama_jabatan }}</td>
                                <td>{{ $jabatan->level_jabatan }}</td>
                                <td>{!! $jabatan->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                <td>
                                    <a href="#"
                                       class="btn btn-warning btn-sm btn-edit-jabatan"
                                       data-id="{{ $jabatan->id_jabatan }}"
                                       data-nama_jabatan="{{ $jabatan->nama_jabatan }}"
                                       data-deskripsi="{{ $jabatan->deskripsi }}"
                                       data-level_jabatan="{{ $jabatan->level_jabatan }}"
                                       data-status_aktif="{{ $jabatan->status_aktif }}">
                                       Edit
                                    </a>
                                    <form action="{{ route('jabatan.destroy', $jabatan->id_jabatan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
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
                        {{ $jabatans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="jabatanModal" tabindex="-1" role="dialog" aria-labelledby="jabatanModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jabatanModalLabel">Form Jabatan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="jabatanForm" action="" method="POST">
            @csrf
            <div id="form-method-jabatan"></div>

            <div class="form-group">
                <label for="nama_jabatan">Nama Jabatan</label>
                <input type="text" name="nama_jabatan" id="nama_jabatan" class="form-control" required>
            </div>
             <div class="form-group">
                <label for="level_jabatan">Level Jabatan</label>
                <input type="number" name="level_jabatan" id="level_jabatan" class="form-control">
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
    $('#btn-create-jabatan').on('click', function(e) {
        e.preventDefault();
        $('#jabatanForm')[0].reset();
        $('#form-method-jabatan').empty();
        $('#jabatanModalLabel').text('Tambah Jabatan Baru');
        $('#jabatanForm').attr('action', '{{ route("jabatan.store") }}');
        $('#jabatanModal').modal('show');
    });

    // === Logic untuk Tombol Edit ===
    $('.btn-edit-jabatan').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let nama_jabatan = $(this).data('nama_jabatan');
        let deskripsi = $(this).data('deskripsi');
        let level_jabatan = $(this).data('level_jabatan');
        let status_aktif = $(this).data('status_aktif');

        $('#nama_jabatan').val(nama_jabatan);
        $('#deskripsi').val(deskripsi);
        $('#level_jabatan').val(level_jabatan);
        $('#status_aktif').val(status_aktif);

        $('#jabatanModalLabel').text('Edit Jabatan: ' + nama_jabatan);
        $('#form-method-jabatan').html('@method("PUT")');

        let updateUrl = '{{ route("jabatan.update", ":id") }}';
        updateUrl = updateUrl.replace(':id', id);
        $('#jabatanForm').attr('action', updateUrl);

        $('#jabatanModal').modal('show');
    });
});
</script>
@endpush
