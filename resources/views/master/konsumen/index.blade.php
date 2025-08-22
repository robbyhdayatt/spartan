@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Konsumen</h3>
                    <div class="card-tools">
                        <a href="#" id="btn-create-konsumen" class="btn btn-primary btn-sm">Tambah Data</a>
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
                                <th>Nama Konsumen</th>
                                <th>Telepon</th>
                                <th>Limit Kredit</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($konsumens as $konsumen)
                            <tr>
                                <td>{{ $loop->iteration + $konsumens->firstItem() - 1 }}</td>
                                <td>{{ $konsumen->kode_konsumen }}</td>
                                <td>{{ $konsumen->nama_konsumen }}</td>
                                <td>{{ $konsumen->telepon }}</td>
                                <td>Rp {{ number_format($konsumen->limit_kredit, 0, ',', '.') }}</td>
                                <td>{!! $konsumen->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm btn-edit-konsumen"
                                       data-id="{{ $konsumen->id_konsumen }}"
                                       data-kode_konsumen="{{ $konsumen->kode_konsumen }}"
                                       data-nama_konsumen="{{ $konsumen->nama_konsumen }}"
                                       data-alamat="{{ $konsumen->alamat }}"
                                       data-telepon="{{ $konsumen->telepon }}"
                                       data-email="{{ $konsumen->email }}"
                                       data-limit_kredit="{{ $konsumen->limit_kredit }}"
                                       data-term_pembayaran="{{ $konsumen->term_pembayaran }}"
                                       data-status_aktif="{{ $konsumen->status_aktif }}">
                                       Edit
                                    </a>
                                    <form action="{{ route('konsumen.destroy', $konsumen->id_konsumen) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
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
                    <div class="mt-3">{{ $konsumens->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="konsumenModal" tabindex="-1" role="dialog" aria-labelledby="konsumenModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="konsumenModalLabel">Form Konsumen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="konsumenForm" action="" method="POST">
            @csrf
            <div id="form-method-konsumen"></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kode_konsumen">Kode Konsumen</label>
                        <input type="text" name="kode_konsumen" id="kode_konsumen" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_konsumen">Nama Konsumen</label>
                        <input type="text" name="nama_konsumen" id="nama_konsumen" class="form-control" required>
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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="limit_kredit">Limit Kredit</label>
                        <input type="number" name="limit_kredit" id="limit_kredit" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="term_pembayaran">Termin Pembayaran (Hari)</label>
                        <input type="number" name="term_pembayaran" id="term_pembayaran" class="form-control">
                    </div>
                </div>
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
    $('#btn-create-konsumen').on('click', function(e) {
        e.preventDefault();
        $('#konsumenForm')[0].reset();
        $('#form-method-konsumen').empty();
        $('#konsumenModalLabel').text('Tambah Konsumen Baru');
        $('#konsumenForm').attr('action', '{{ route("konsumen.store") }}');
        $('#konsumenModal').modal('show');
    });

    $('.btn-edit-konsumen').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#kode_konsumen').val($(this).data('kode_konsumen'));
        $('#nama_konsumen').val($(this).data('nama_konsumen'));
        $('#alamat').val($(this).data('alamat'));
        $('#telepon').val($(this).data('telepon'));
        $('#email').val($(this).data('email'));
        $('#limit_kredit').val($(this).data('limit_kredit'));
        $('#term_pembayaran').val($(this).data('term_pembayaran'));
        $('#status_aktif').val($(this).data('status_aktif'));

        $('#konsumenModalLabel').text('Edit Konsumen: ' + $(this).data('nama_konsumen'));
        $('#form-method-konsumen').html('@method("PUT")');

        let updateUrl = '{{ route("konsumen.update", ":id") }}';
        updateUrl = updateUrl.replace(':id', id);
        $('#konsumenForm').attr('action', updateUrl);

        $('#konsumenModal').modal('show');
    });
});
</script>
@endpush
