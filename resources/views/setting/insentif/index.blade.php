@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Manajemen Insentif</h3><div class="card-tools">@can('access', ['settings.insentif', 'create'])<a href="#" id="btn-create" class="btn btn-primary btn-sm">Buat Program Baru</a>@endcan</div></div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <table class="table table-bordered table-hover">
                <thead><tr><th>No</th><th>Nama Program</th><th>Jabatan</th><th>Tipe</th><th>Nilai</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($insentifs as $insentif)
                    <tr>
                        <td>{{ $loop->iteration + $insentifs->firstItem() - 1 }}</td>
                        <td>{{ $insentif->nama_program }}</td>
                        <td>{{ $insentif->jabatan->nama_jabatan ?? 'N/A' }}</td>
                        <td class="text-capitalize">{{ str_replace('_', ' ', $insentif->tipe_insentif) }}</td>
                        <td class="text-right">{{ $insentif->tipe_insentif == 'percentage' ? $insentif->nilai_insentif.' %' : 'Rp '.number_format($insentif->nilai_insentif, 0, ',', '.') }}</td>
                        <td>{!! $insentif->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                        <td>
                            @can('access', ['settings.insentif', 'update'])
                            <a href="#" class="btn btn-warning btn-sm btn-edit" data-id="{{ $insentif->id_insentif }}" data-json="{{ json_encode($insentif) }}">Edit</a>
                            @endcan
                            @can('access', ['settings.insentif', 'delete'])
                            <form action="{{ route('insentif.destroy', $insentif->id_insentif) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus program ini?');"> @csrf @method('DELETE') <button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Belum ada program insentif yang dibuat.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $insentifs->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title" id="formModalLabel">Form Insentif</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form id="form" action="" method="POST">
    @csrf <div id="form-method"></div>
    <div class="modal-body">
        <div class="form-group"><label>Nama Program</label><input type="text" name="nama_program" id="nama_program" class="form-control" required></div>
        <div class="row">
            <div class="col-md-6"><div class="form-group"><label>Untuk Jabatan</label><select name="id_jabatan" id="id_jabatan" class="form-control" required><option value="">-- Pilih Jabatan --</option>@foreach($jabatans as $jabatan)<option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama_jabatan }}</option>@endforeach</select></div></div>
            <div class="col-md-6"><div class="form-group"><label>Untuk Part (Opsional)</label><select name="id_part" id="id_part" class="form-control"><option value="">-- Berlaku untuk Semua Part --</option>@foreach($parts as $part)<option value="{{ $part->id_part }}">{{ $part->nama_part }}</option>@endforeach</select></div></div>
        </div>
        <div class="row">
            <div class="col-md-6"><div class="form-group"><label>Tipe Insentif</label><select name="tipe_insentif" id="tipe_insentif" class="form-control" required><option value="per_qty">Per Qty</option><option value="per_value">Per Nilai (Rp)</option><option value="percentage">Persentase (%)</option></select></div></div>
            <div class="col-md-6"><div class="form-group"><label>Nilai Insentif</label><input type="number" name="nilai_insentif" id="nilai_insentif" class="form-control" required></div></div>
        </div>
        <div class="form-group"><label>Minimum Target (Qty/Rupiah)</label><input type="number" name="minimum_target" id="minimum_target" class="form-control" required></div>
        <div class="row">
            <div class="col-md-6"><div class="form-group"><label>Periode Awal</label><input type="date" name="periode_awal" id="periode_awal" class="form-control" required></div></div>
            <div class="col-md-6"><div class="form-group"><label>Periode Akhir</label><input type="date" name="periode_akhir" id="periode_akhir" class="form-control" required></div></div>
        </div>
        <div class="form-group"><label>Status</label><select name="status_aktif" id="status_aktif" class="form-control" required><option value="1">Aktif</option><option value="0">Tidak Aktif</option></select></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
</form>
</div></div></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btn-create').on('click', function(e) { e.preventDefault(); $('#form')[0].reset(); $('#form-method').empty(); $('#formModalLabel').text('Buat Program Insentif Baru'); $('#form').attr('action', '{{ route("insentif.store") }}'); $('#formModal').modal('show'); });
    $('.btn-edit').on('click', function(e) {
        e.preventDefault();
        $('#form-method').html('@method("PUT")');
        $('#formModalLabel').text('Edit Program Insentif');
        let data = $(this).data('json');
        $('#nama_program').val(data.nama_program);
        $('#id_jabatan').val(data.id_jabatan);
        $('#id_part').val(data.id_part);
        $('#tipe_insentif').val(data.tipe_insentif);
        $('#nilai_insentif').val(data.nilai_insentif);
        $('#minimum_target').val(data.minimum_target);
        $('#periode_awal').val(data.periode_awal);
        $('#periode_akhir').val(data.periode_akhir);
        $('#status_aktif').val(data.status_aktif);
        let url = '{{ route("insentif.update", ":id") }}'.replace(':id', data.id_insentif);
        $('#form').attr('action', url);
        $('#formModal').modal('show');
    });
});
</script>
@endpush