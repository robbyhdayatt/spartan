@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Karyawan</h3>
                    <div class="card-tools">
                        <a href="#" id="btn-create-karyawan" class="btn btn-primary btn-sm">Tambah Data</a>
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
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Gudang Asal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawans as $karyawan)
                            <tr>
                                <td>{{ $loop->iteration + $karyawans->firstItem() - 1 }}</td>
                                <td>{{ $karyawan->kode_karyawan }}</td>
                                <td>{{ $karyawan->nama_karyawan }}</td>
                                <td>{{ $karyawan->jabatan->nama_jabatan ?? 'N/A' }}</td>
                                <td>{{ $karyawan->gudang->nama_gudang ?? 'N/A' }}</td>
                                <td>{!! $karyawan->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm btn-edit-karyawan"
                                       data-id="{{ $karyawan->id_karyawan }}"
                                       data-kode_karyawan="{{ $karyawan->kode_karyawan }}"
                                       data-nama_karyawan="{{ $karyawan->nama_karyawan }}"
                                       data-id_jabatan="{{ $karyawan->id_jabatan }}"
                                       data-id_gudang_asal="{{ $karyawan->id_gudang_asal }}"
                                       data-telepon="{{ $karyawan->telepon }}"
                                       data-email="{{ $karyawan->email }}"
                                       data-alamat="{{ $karyawan->alamat }}"
                                       data-tanggal_masuk="{{ $karyawan->tanggal_masuk }}"
                                       data-status_aktif="{{ $karyawan->status_aktif }}">
                                       Edit
                                    </a>
                                    <form action="{{ route('karyawan.destroy', $karyawan->id_karyawan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
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
                    <div class="mt-3">{{ $karyawans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="karyawanModal" tabindex="-1" role="dialog" aria-labelledby="karyawanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="karyawanModalLabel">Form Karyawan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="karyawanForm" action="" method="POST">
            @csrf
            <div id="form-method-karyawan"></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kode_karyawan">Kode Karyawan</label>
                        <input type="text" name="kode_karyawan" id="kode_karyawan" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_karyawan">Nama Karyawan</label>
                        <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_jabatan">Jabatan</label>
                        <select name="id_jabatan" id="id_jabatan" class="form-control" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach ($jabatans as $jabatan)
                            <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_gudang_asal">Gudang Asal</label>
                        <select name="id_gudang_asal" id="id_gudang_asal" class="form-control">
                            <option value="">-- Pilih Gudang --</option>
                            @foreach ($gudangs as $gudang)
                            <option value="{{ $gudang->id_gudang }}">{{ $gudang->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" name="telepon" id="telepon" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status_aktif">Status</label>
                        <select name="status_aktif" id="status_aktif" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
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
    $('#btn-create-karyawan').on('click', function(e) {
        e.preventDefault();
        $('#karyawanForm')[0].reset();
        $('#form-method-karyawan').empty();
        $('#karyawanModalLabel').text('Tambah Karyawan Baru');
        $('#karyawanForm').attr('action', '{{ route("karyawan.store") }}');
        $('#karyawanModal').modal('show');
    });

    $('.btn-edit-karyawan').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#kode_karyawan').val($(this).data('kode_karyawan'));
        $('#nama_karyawan').val($(this).data('nama_karyawan'));
        $('#id_jabatan').val($(this).data('id_jabatan'));
        $('#id_gudang_asal').val($(this).data('id_gudang_asal'));
        $('#telepon').val($(this).data('telepon'));
        $('#email').val($(this).data('email'));
        $('#alamat').val($(this).data('alamat'));
        $('#tanggal_masuk').val($(this).data('tanggal_masuk'));
        $('#status_aktif').val($(this).data('status_aktif'));

        $('#karyawanModalLabel').text('Edit Karyawan: ' + $(this).data('nama_karyawan'));
        $('#form-method-karyawan').html('@method("PUT")');

        let updateUrl = '{{ route("karyawan.update", ":id") }}';
        updateUrl = updateUrl.replace(':id', id);
        $('#karyawanForm').attr('action', updateUrl);

        $('#karyawanModal').modal('show');
    });
});
</script>
@endpush
