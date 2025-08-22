@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Supplier</h3>
                    <div class="card-tools">
                        {{-- Tombol diubah untuk memicu modal --}}
                        <a href="#" id="btn-create-supplier" class="btn btn-primary btn-sm">Tambah Data</a>
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
                                <th>Kode Supplier</th>
                                <th>Nama Supplier</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration + $suppliers->firstItem() - 1 }}</td>
                                <td>{{ $supplier->kode_supplier }}</td>
                                <td>{{ $supplier->nama_supplier }}</td>
                                <td>{{ $supplier->telepon }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{!! $supplier->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                <td>
                                    {{-- Tombol Edit diubah untuk menyimpan data dan memicu modal --}}
                                    <a href="#"
                                       class="btn btn-warning btn-sm btn-edit-supplier"
                                       data-id="{{ $supplier->id_supplier }}"
                                       data-kode_supplier="{{ $supplier->kode_supplier }}"
                                       data-nama_supplier="{{ $supplier->nama_supplier }}"
                                       data-alamat="{{ $supplier->alamat }}"
                                       data-telepon="{{ $supplier->telepon }}"
                                       data-email="{{ $supplier->email }}"
                                       data-status_aktif="{{ $supplier->status_aktif }}">
                                       Edit
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id_supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                    <div class="mt-3">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="supplierModalLabel">Form Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="supplierForm" action="" method="POST">
            @csrf
            <div id="form-method"></div> <div class="form-group">
                <label for="kode_supplier">Kode Supplier</label>
                <input type="text" name="kode_supplier" id="kode_supplier" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nama_supplier">Nama Supplier</label>
                <input type="text" name="nama_supplier" id="nama_supplier" class="form-control" required>
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
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control">
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
    $('#btn-create-supplier').on('click', function(e) {
        e.preventDefault();
        // Reset form
        $('#supplierForm')[0].reset();
        $('#form-method').empty();

        // Ubah judul modal
        $('#supplierModalLabel').text('Tambah Supplier Baru');

        // Ubah action form ke route 'store'
        $('#supplierForm').attr('action', '{{ route("suppliers.store") }}');

        // Hapus pesan error sebelumnya (jika ada)
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Tampilkan modal
        $('#supplierModal').modal('show');
    });

    // === Logic untuk Tombol Edit ===
    $('.btn-edit-supplier').on('click', function(e) {
        e.preventDefault();
        // Ambil data dari atribut data-*
        let id = $(this).data('id');
        let kode = $(this).data('kode_supplier');
        let nama = $(this).data('nama_supplier');
        let alamat = $(this).data('alamat');
        let telepon = $(this).data('telepon');
        let email = $(this).data('email');
        let status = $(this).data('status_aktif');

        // Isi form di dalam modal dengan data yang diambil
        $('#kode_supplier').val(kode);
        $('#nama_supplier').val(nama);
        $('#alamat').val(alamat);
        $('#telepon').val(telepon);
        $('#email').val(email);
        $('#status_aktif').val(status);

        // Ubah judul modal
        $('#supplierModalLabel').text('Edit Supplier: ' + nama);

        // Tambahkan method PUT untuk proses update
        $('#form-method').html('@method("PUT")');

        // Ubah action form ke route 'update'
        let updateUrl = '{{ route("suppliers.update", ":id") }}';
        updateUrl = updateUrl.replace(':id', id);
        $('#supplierForm').attr('action', updateUrl);

        // Hapus pesan error sebelumnya (jika ada)
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Tampilkan modal
        $('#supplierModal').modal('show');
    });

    // Menangani error validasi saat form disubmit via AJAX (opsional, tapi bagus untuk UX)
    // Jika tidak menggunakan AJAX, error akan tampil setelah halaman refresh.
    // Kode ini menangani jika validasi gagal, form tidak akan menutup modal.
    @if ($errors->any())
        @if (old('_method') == 'PUT') // Jika error terjadi pada saat update
            // Kita perlu cara untuk membuka kembali modal edit dengan data dan errornya
            // Ini adalah bagian yang lebih kompleks, untuk saat ini kita biarkan refresh halaman.
        @else // Jika error terjadi pada saat create
            $('#supplierModal').modal('show');
        @endif
    @endif
});
</script>
@endpush
