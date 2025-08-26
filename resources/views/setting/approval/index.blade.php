@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pengaturan Aturan Persetujuan (Approval)</h3>
            <div class="card-tools">
                <a href="#" id="btn-create" class="btn btn-primary btn-sm">Buat Aturan Baru</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Dokumen</th>
                        <th>Nama Level</th>
                        <th>Jabatan Wajib</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($approvalLevels as $rule)
                    <tr>
                        <td>{{ $loop->iteration + $approvalLevels->firstItem() - 1 }}</td>
                        <td class="text-capitalize">{{ $rule->jenis_dokumen }}</td>
                        <td>{{ $rule->nama_level }}</td>
                        <td>{{ $rule->jabatan->nama_jabatan ?? 'N/A' }}</td>
                        <td>{{ $rule->level_sequence }}</td>
                        <td>{!! $rule->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                        <td>
                            @can('manage-master-data')
                            <a href="#" class="btn btn-warning btn-sm btn-edit"
                               data-id="{{ $rule->id_approval_level }}"
                               data-jenis_dokumen="{{ $rule->jenis_dokumen }}"
                               data-nama_level="{{ $rule->nama_level }}"
                               data-id_jabatan_required="{{ $rule->id_jabatan_required }}"
                               data-level_sequence="{{ $rule->level_sequence }}"
                               data-minimum_amount="{{ $rule->minimum_amount }}"
                               data-status_aktif="{{ $rule->status_aktif }}">
                               Edit
                            </a>
                            @endcan
                            
                            @can('delete-data')
                            <form action="{{ route('approval-levels.destroy', $rule->id_approval_level) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus aturan ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Belum ada aturan approval yang dibuat.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $approvalLevels->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title" id="formModalLabel">Form Aturan Approval</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form id="form" action="" method="POST">
    @csrf
    <div id="form-method"></div>
    <div class="modal-body">
        <div class="form-group">
            <label>Jenis Dokumen</label>
            <select name="jenis_dokumen" id="jenis_dokumen" class="form-control" required>
                <option value="pembelian">Pembelian (PO)</option>
                <option value="penjualan">Penjualan</option>
                <option value="retur">Retur</option>
                <option value="adjustment">Stock Adjustment</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nama Level/Aturan</label>
            <input type="text" name="nama_level" id="nama_level" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Jabatan yang Wajib Menyetujui</label>
            <select name="id_jabatan_required" id="id_jabatan_required" class="form-control" required>
                <option value="">-- Pilih Jabatan --</option>
                @foreach($jabatans as $jabatan)
                <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
         <div class="row">
            <div class="col-md-6"><div class="form-group">
                <label>Urutan Persetujuan</label>
                <input type="number" name="level_sequence" id="level_sequence" value="1" class="form-control" required>
            </div></div>
            <div class="col-md-6"><div class="form-group">
                <label>Berlaku Untuk Nominal Minimum</label>
                <input type="number" name="minimum_amount" id="minimum_amount" value="0" class="form-control" required>
            </div></div>
        </div>
        <div class="form-group">
            <label>Status</label>
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
</div></div></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btn-create').on('click', function(e) {
        e.preventDefault();
        $('#form')[0].reset();
        $('#form-method').empty();
        $('#formModalLabel').text('Buat Aturan Approval Baru');
        $('#form').attr('action', '{{ route("approval-levels.store") }}');
        $('#formModal').modal('show');
    });

    $('.btn-edit').on('click', function(e) {
        e.preventDefault();
        $('#form-method').html('@method("PUT")');
        $('#formModalLabel').text('Edit Aturan Approval');
        
        let data = $(this).data();
        $('#jenis_dokumen').val(data.jenis_dokumen);
        $('#nama_level').val(data.nama_level);
        $('#id_jabatan_required').val(data.id_jabatan_required);
        $('#level_sequence').val(data.level_sequence);
        $('#minimum_amount').val(data.minimum_amount);
        $('#status_aktif').val(data.status_aktif);

        let url = '{{ route("approval-levels.update", ":id") }}'.replace(':id', data.id);
        $('#form').attr('action', url);
        $('#formModal').modal('show');
    });
});
</script>
@endpush