@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Stock Adjustment</h3>
            <div class="card-tools">
                <a href="#" id="btn-create-adjustment" class="btn btn-primary btn-sm">Buat Adjustment Baru</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            <table class="table table-bordered table-hover">
                <thead><tr><th>No</th><th>No. Dokumen</th><th>Gudang</th><th>Tanggal</th><th>Jenis</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($adjustments as $adj)
                    <tr>
                        <td>{{ $loop->iteration + $adjustments->firstItem() - 1 }}</td>
                        <td>{{ $adj->nomor_adjustment }}</td>
                        <td>{{ $adj->gudang->nama_gudang ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($adj->tanggal_adjustment)->format('d-m-Y') }}</td>
                        <td class="text-capitalize">{{ $adj->jenis_adjustment }}</td>
                        <td><span class="badge badge-success text-capitalize">{{ $adj->status_adjustment }}</span></td>
                        <td><a href="#" class="btn btn-info btn-sm">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $adjustments->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog"><div class="modal-dialog modal-xl" role="document"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Form Stock Adjustment</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form action="{{ route('adjustment.store') }}" method="POST"> @csrf
<div class="modal-body">
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Gudang</label><select name="id_gudang" id="select_gudang" class="form-control" required><option value="">-- Pilih Gudang --</option>@foreach($gudangs as $g)<option value="{{ $g->id_gudang }}">{{ $g->nama_gudang }}</option>@endforeach</select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Tanggal</label><input type="date" name="tanggal_adjustment" class="form-control" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Jenis</label><select name="jenis_adjustment" class="form-control" required><option value="opname">Stok Opname</option><option value="koreksi">Koreksi</option><option value="write_off">Write-Off</option></select></div></div>
    </div>
    <div class="form-group"><label for="keterangan">Keterangan</label><textarea name="keterangan" id="keterangan" rows="2" class="form-control"></textarea></div>
    <hr><h6>Detail Barang</h6>
    <table class="table table-bordered"><thead><tr><th style="width:40%">Part</th><th>Stok Sistem</th><th>Stok Fisik</th><th>Selisih</th><th>Aksi</th></tr></thead><tbody id="details-container"></tbody></table>
    <button type="button" id="btn-add-detail" class="btn btn-success mt-2">Tambah Part</button>
</div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Adjustment</button></div>
</form></div></div></div>

<template id="detail-row-template">
    <tr>
        <td><select name="details[__INDEX__][id_part]" class="form-control select-part" required><option value="">-- Pilih Part --</option>@foreach($parts as $part)<option value="{{ $part->id_part }}">{{ $part->nama_part }} ({{ $part->kode_part }})</option>@endforeach</select></td>
        <td><input type="number" class="form-control stok-sistem" readonly></td>
        <td><input type="number" name="details[__INDEX__][stok_fisik]" class="form-control stok-fisik" value="0" min="0" required></td>
        <td><input type="text" class="form-control selisih" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm btn-remove-detail">Hapus</button></td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let detailIndex = 0;

    $('#btn-create-adjustment').on('click', function(e) { e.preventDefault(); $('#createModal').modal('show'); });
    $('#createModal').on('hidden.bs.modal', function () { $('#details-container').empty(); detailIndex = 0; });

    $('#btn-add-detail').on('click', function() {
        let gudangId = $('#select_gudang').val();
        if (!gudangId) { alert('Harap pilih gudang terlebih dahulu!'); return; }
        let template = $('#detail-row-template').html().replace(/__INDEX__/g, detailIndex);
        $('#details-container').append(template);
        detailIndex++;
    });

    $(document).on('click', '.btn-remove-detail', function() { $(this).closest('tr').remove(); });

    $(document).on('change', '.select-part', function() {
        let row = $(this).closest('tr');
        let partId = $(this).val();
        let gudangId = $('#select_gudang').val();

        row.find('.stok-sistem, .stok-fisik, .selisih').val('');
        if (!partId || !gudangId) return;

        $.ajax({
            url: '{{ route("adjustment.get-stock") }}',
            type: 'GET',
            data: { part_id: partId, gudang_id: gudangId },
            success: function(response) {
                row.find('.stok-sistem').val(response.stok);
                updateSelisih(row);
            }
        });
    });

    $(document).on('input', '.stok-fisik', function() {
        updateSelisih($(this).closest('tr'));
    });

    function updateSelisih(row) {
        let stokSistem = parseInt(row.find('.stok-sistem').val()) || 0;
        let stokFisik = parseInt(row.find('.stok-fisik').val()) || 0;
        let selisih = stokFisik - stokSistem;

        let selisihText = selisih > 0 ? '+' + selisih : selisih;
        row.find('.selisih').val(selisihText);
    }
});
</script>
@endpush