@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Manajemen Harga Jual</h3><div class="card-tools">@can('access', ['settings.harga-jual', 'create'])<a href="#" id="btn-create" class="btn btn-primary btn-sm">Buat Aturan Baru</a>@endcan</div></div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <table class="table table-bordered table-hover">
                <thead><tr><th>No</th><th>Part</th><th>Konsumen</th><th class="text-right">Harga (HED)</th><th>Periode</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($hargaJuals as $harga)
                    <tr>
                        <td>{{ $loop->iteration + $hargaJuals->firstItem() - 1 }}</td>
                        <td>{{ $harga->part->nama_part ?? 'N/A' }}</td>
                        <td>{{ $harga->konsumen->nama_konsumen ?? 'Semua Konsumen' }}</td>
                        <td class="text-right">Rp {{ number_format($harga->hed, 0, ',', '.') }}</td>
                        <td>{{ $harga->periode_awal ? \Carbon\Carbon::parse($harga->periode_awal)->format('d/m/y') : '-' }} s/d {{ $harga->periode_akhir ? \Carbon\Carbon::parse($harga->periode_akhir)->format('d/m/y') : '-' }}</td>
                        <td>{!! $harga->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                        <td>
                            @can('access', ['settings.harga-jual', 'update'])
                            <a href="#" class="btn btn-warning btn-sm btn-edit" data-id="{{ $harga->id_harga_jual }}" data-part_id="{{ $harga->id_part }}" data-konsumen_id="{{ $harga->id_konsumen }}" data-hed="{{ $harga->hed }}" data-periode_awal="{{ $harga->periode_awal }}" data-periode_akhir="{{ $harga->periode_akhir }}" data-status_aktif="{{ $harga->status_aktif }}">Edit</a>
                            @endcan
                            @can('access', ['settings.harga-jual', 'delete'])
                            <form action="{{ route('harga-jual.destroy', $harga->id_harga_jual) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus aturan ini?');"> @csrf @method('DELETE') <button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Belum ada aturan harga jual yang dibuat.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $hargaJuals->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title" id="formModalLabel">Form Harga Jual</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form id="form" action="" method="POST">
    @csrf <div id="form-method"></div>
    <div class="modal-body">
        <div class="form-group"><label>Part</label><select name="id_part" id="id_part" class="form-control" required><option value="">-- Pilih Part --</option>@foreach($parts as $part)<option value="{{ $part->id_part }}">{{ $part->nama_part }}</option>@endforeach</select></div>
        <div class="form-group"><label>Konsumen</label><select name="id_konsumen" id="id_konsumen" class="form-control"><option value="">-- Berlaku untuk Semua Konsumen --</option>@foreach($konsumens as $konsumen)<option value="{{ $konsumen->id_konsumen }}">{{ $konsumen->nama_konsumen }}</option>@endforeach</select></div>
        <div class="form-group"><label>Harga Jual (HED)</label><input type="number" name="hed" id="hed" class="form-control" required></div>
        <div class="row">
            <div class="col-md-6"><div class="form-group"><label>Periode Awal</label><input type="date" name="periode_awal" id="periode_awal" class="form-control"></div></div>
            <div class="col-md-6"><div class="form-group"><label>Periode Akhir</label><input type="date" name="periode_akhir" id="periode_akhir" class="form-control"></div></div>
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
    $('#btn-create').on('click', function(e) { e.preventDefault(); $('#form')[0].reset(); $('#form-method').empty(); $('#formModalLabel').text('Buat Aturan Harga Baru'); $('#form').attr('action', '{{ route("harga-jual.store") }}'); $('#formModal').modal('show'); });
    $('.btn-edit').on('click', function(e) {
        e.preventDefault();
        $('#form-method').html('@method("PUT")');
        $('#formModalLabel').text('Edit Aturan Harga');
        let data = $(this).data();
        $('#id_part').val(data.part_id);
        $('#id_konsumen').val(data.konsumen_id);
        $('#hed').val(data.hed);
        $('#periode_awal').val(data.periode_awal);
        $('#periode_akhir').val(data.periode_akhir);
        $('#status_aktif').val(data.status_aktif);
        let url = '{{ route("harga-jual.update", ":id") }}'.replace(':id', data.id);
        $('#form').attr('action', url);
        $('#formModal').modal('show');
    });
});
</script>
@endpush