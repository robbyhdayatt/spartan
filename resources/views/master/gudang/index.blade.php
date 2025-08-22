@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Gudang</h3>
                    <div class="card-tools">
                        <a href="#" id="btn-create-gudang" class="btn btn-primary btn-sm">Tambah Data</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Gudang</th>
                                <th>PIC</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gudangs as $gudang)
                            <tr>
                                <td>{{ $loop->iteration + $gudangs->firstItem() - 1 }}</td>
                                <td>{{ $gudang->kode_gudang }}</td>
                                <td>{{ $gudang->nama_gudang }}</td>
                                <td>{{ $gudang->pic->nama_karyawan ?? 'N/A' }}</td>
                                <td class="text-capitalize">{{ $gudang->jenis_gudang }}</td>
                                <td>{!! $gudang->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm btn-edit-gudang"
                                       data-id="{{ $gudang->id_gudang }}"
                                       data-kode_gudang="{{ $gudang->kode_gudang }}"
                                       data-nama_gudang="{{ $gudang->nama_gudang }}"
                                       data-alamat="{{ $gudang->alamat }}"
                                       data-id_pic_gudang="{{ $gudang->id_pic_gudang }}"
                                       data-telepon="{{ $gudang->telepon }}"
                                       data-jenis_gudang="{{ $gudang->jenis_gudang }}"
                                       data-status_aktif="{{ $gudang->status_aktif }}">
                                       Edit
                                    </a>
                                    <form action="{{ route('gudang.destroy', $gudang->id_gudang) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $gudangs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="gudangModal" tabindex="-1" role="dialog" aria-labelledby="gudangModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="gudangModalLabel">Form Gudang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="gudangForm" action="" method="POST">
            @csrf
            <div id="form-method-gudang"></div>

            <div class="form-group">
                <label for="kode_gudang">Kode Gudang</label>
                <input type="text" name="kode_gudang" id="kode_gudang" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nama_gudang">Nama Gudang</label>
                <input type="text" name="nama_gudang" id="nama_gudang" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jenis_gudang">Jenis Gudang</label>
                <select name="jenis_gudang" id="jenis_gudang" class="form-control" required>
                    <option value="utama">Utama</option>
                    <option value="transit">Transit</option>
                    <option value="retur">Retur</option>
                    <option value="quarantine">Quarantine</option>
                </select>
            </div>
             <div class="form-group">
                <label for="id_pic_gudang">PIC Gudang</label>
                <select name="id_pic_gudang" id="id_pic_gudang" class="form-control">
                    <option value="">-- Pilih PIC --</option>
                    @foreach ($karyawans as $karyawan)
                    <option value="{{ $karyawan->id_karyawan }}">{{ $karyawan->nama_karyawan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="telepon">Telepon</label>
                <input type="text" name="telepon" id="telepon" class="form-control">
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
    $('#btn-create-gudang').on('click', function(e) {
        e.preventDefault();
        $('#gudangForm')[0].reset();
        $('#form-method-gudang').empty();
        $('#gudangModalLabel').text('Tambah Gudang Baru');
        $('#gudangForm').attr('action', '{{ route("gudang.store") }}');
        $('#gudangModal').modal('show');
    });

    $('.btn-edit-gudang').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#kode_gudang').val($(this).data('kode_gudang'));
        $('#nama_gudang').val($(this).data('nama_gudang'));
        $('#alamat').val($(this).data('alamat'));
        $('#id_pic_gudang').val($(this).data('id_pic_gudang'));
        $('#telepon').val($(this).data('telepon'));
        $('#jenis_gudang').val($(this).data('jenis_gudang'));
        $('#status_aktif').val($(this).data('status_aktif'));

        $('#gudangModalLabel').text('Edit Gudang: ' + $(this).data('nama_gudang'));
        $('#form-method-gudang').html('@method("PUT")');

        let updateUrl = '{{ route("gudang.update", ":id") }}';
        updateUrl = updateUrl.replace(':id', id);
        $('#gudangForm').attr('action', updateUrl);

        $('#gudangModal').modal('show');
    });
});
</script>
@endpush
