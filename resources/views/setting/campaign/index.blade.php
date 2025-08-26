@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Manajemen Kampanye/Promosi</h3><div class="card-tools">@can('access', ['settings.campaign', 'create'])<a href="#" id="btn-create" class="btn btn-primary btn-sm">Buat Kampanye Baru</a>@endcan</div></div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <table class="table table-bordered table-hover">
                <thead><tr><th>No</th><th>Kode</th><th>Nama Kampanye</th><th>Jenis</th><th>Periode</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($campaigns as $campaign)
                    <tr>
                        <td>{{ $loop->iteration + $campaigns->firstItem() - 1 }}</td>
                        <td>{{ $campaign->kode_campaign }}</td>
                        <td>{{ $campaign->nama_campaign }}</td>
                        <td class="text-capitalize">{{ $campaign->jenis_campaign }}</td>
                        <td>{{ \Carbon\Carbon::parse($campaign->tanggal_mulai)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($campaign->tanggal_selesai)->format('d/m/y') }}</td>
                        <td>{!! $campaign->status_aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                        <td>
                            @can('access', ['settings.campaign', 'update'])
                            <a href="#" class="btn btn-warning btn-sm btn-edit" data-id="{{ $campaign->id_campaign }}" data-json="{{ json_encode($campaign) }}">Edit</a>
                            @endcan
                            @can('access', ['settings.campaign', 'delete'])
                            <form action="{{ route('campaign.destroy', $campaign->id_campaign) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kampanye ini?');"> @csrf @method('DELETE') <button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Belum ada kampanye yang dibuat.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $campaigns->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title" id="formModalLabel">Form Kampanye</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form id="form" action="" method="POST">
    @csrf <div id="form-method"></div>
    <div class="modal-body">
        <div class="form-group"><label>Nama Kampanye</label><input type="text" name="nama_campaign" id="nama_campaign" class="form-control" required></div>
        <div class="form-group"><label>Jenis Kampanye</label><select name="jenis_campaign" id="jenis_campaign" class="form-control" required><option value="sales">Penjualan (Sales)</option><option value="purchase">Pembelian (Purchase)</option></select></div>
        <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea></div>
        <div class="row">
            <div class="col-md-6"><div class="form-group"><label>Tanggal Mulai</label><input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required></div></div>
            <div class="col-md-6"><div class="form-group"><label>Tanggal Selesai</label><input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required></div></div>
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
    $('#btn-create').on('click', function(e) { e.preventDefault(); $('#form')[0].reset(); $('#form-method').empty(); $('#formModalLabel').text('Buat Kampanye Baru'); $('#form').attr('action', '{{ route("campaign.store") }}'); $('#formModal').modal('show'); });
    $('.btn-edit').on('click', function(e) {
        e.preventDefault();
        $('#form-method').html('@method("PUT")');
        $('#formModalLabel').text('Edit Kampanye');
        let data = $(this).data('json');
        $('#nama_campaign').val(data.nama_campaign);
        $('#jenis_campaign').val(data.jenis_campaign);
        $('#deskripsi').val(data.deskripsi);
        $('#tanggal_mulai').val(data.tanggal_mulai);
        $('#tanggal_selesai').val(data.tanggal_selesai);
        $('#status_aktif').val(data.status_aktif);
        let url = '{{ route("campaign.update", ":id") }}'.replace(':id', data.id_campaign);
        $('#form').attr('action', url);
        $('#formModal').modal('show');
    });
});
</script>
@endpush