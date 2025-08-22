@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Part</h3>
                    <div class="card-tools">
                        <a href="#" id="btn-create-part" class="btn btn-primary btn-sm">Tambah Data</a>
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
                                <th>Kode Part</th>
                                <th>Nama Part</th>
                                <th>Kategori</th>
                                <th>Brand</th>
                                <th>Min. Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($parts as $part)
                            <tr>
                                <td>{{ $loop->iteration + $parts->firstItem() - 1 }}</td>
                                <td>{{ $part->kode_part }}</td>
                                <td>{{ $part->nama_part }}</td>
                                <td>{{ $part->kategori->nama_kategori ?? 'N/A' }}</td>
                                <td>{{ $part->brand->nama_brand ?? 'N/A' }}</td>
                                <td>{{ $part->minimum_stok }}</td>
                                <td>{!! $part->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm btn-edit-part"
                                       data-id="{{ $part->id_part }}"
                                       data-kode_part="{{ $part->kode_part }}"
                                       data-nama_part="{{ $part->nama_part }}"
                                       data-id_kategori="{{ $part->id_kategori }}"
                                       data-id_brand="{{ $part->id_brand }}"
                                       data-spesifikasi="{{ $part->spesifikasi }}"
                                       data-satuan="{{ $part->satuan }}"
                                       data-minimum_stok="{{ $part->minimum_stok }}"
                                       data-harga_pokok="{{ $part->harga_pokok }}"
                                       data-require_qc="{{ $part->require_qc }}"
                                       data-status_aktif="{{ $part->status_aktif }}">
                                       Edit
                                    </a>
                                    <form action="{{ route('parts.destroy', $part->id_part) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $parts->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="partModal" tabindex="-1" role="dialog" aria-labelledby="partModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="partModalLabel">Form Part</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="partForm" action="" method="POST">
            @csrf
            <div id="form-method-part"></div>
            <div class="row">
                <div class="col-md-6"><div class="form-group">
                    <label for="kode_part">Kode Part</label>
                    <input type="text" name="kode_part" id="kode_part" class="form-control" required>
                </div></div>
                <div class="col-md-6"><div class="form-group">
                    <label for="nama_part">Nama Part</label>
                    <input type="text" name="nama_part" id="nama_part" class="form-control" required>
                </div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select name="id_kategori" id="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div></div>
                <div class="col-md-6"><div class="form-group">
                    <label for="id_brand">Brand</label>
                    <select name="id_brand" id="id_brand" class="form-control" required>
                        <option value="">-- Pilih Brand --</option>
                        @foreach ($brands as $brand)
                        <option value="{{ $brand->id_brand }}">{{ $brand->nama_brand }}</option>
                        @endforeach
                    </select>
                </div></div>
            </div>
            <div class="form-group">
                <label for="spesifikasi">Spesifikasi</label>
                <textarea name="spesifikasi" id="spesifikasi" class="form-control"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" id="satuan" class="form-control">
                </div></div>
                <div class="col-md-6"><div class="form-group">
                    <label for="minimum_stok">Minimum Stok</label>
                    <input type="number" name="minimum_stok" id="minimum_stok" class="form-control" required>
                </div></div>
            </div>
             <div class="row">
                <div class="col-md-6"><div class="form-group">
                    <label for="harga_pokok">Harga Pokok</label>
                    <input type="number" name="harga_pokok" id="harga_pokok" class="form-control">
                </div></div>
                <div class="col-md-6"><div class="form-group">
                    <label for="require_qc">Membutuhkan QC</label>
                    <select name="require_qc" id="require_qc" class="form-control" required>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div></div>
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
    $('#btn-create-part').on('click', function(e) {
        e.preventDefault();
        $('#partForm')[0].reset();
        $('#form-method-part').empty();
        $('#partModalLabel').text('Tambah Part Baru');
        $('#partForm').attr('action', '{{ route("parts.store") }}');
        $('#partModal').modal('show');
    });

    $('.btn-edit-part').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#kode_part').val($(this).data('kode_part'));
        $('#nama_part').val($(this).data('nama_part'));
        $('#id_kategori').val($(this).data('id_kategori'));
        $('#id_brand').val($(this).data('id_brand'));
        $('#spesifikasi').val($(this).data('spesifikasi'));
        $('#satuan').val($(this).data('satuan'));
        $('#minimum_stok').val($(this).data('minimum_stok'));
        $('#harga_pokok').val($(this).data('harga_pokok'));
        $('#require_qc').val($(this).data('require_qc'));
        $('#status_aktif').val($(this).data('status_aktif'));

        $('#partModalLabel').text('Edit Part: ' + $(this).data('nama_part'));
        $('#form-method-part').html('@method("PUT")');

        let updateUrl = '{{ route("parts.update", ":id") }}';
        updateUrl = updateUrl.replace(':id', id);
        $('#partForm').attr('action', updateUrl);

        $('#partModal').modal('show');
    });
});
</script>
@endpush
