@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Data Retur</h3><div class="card-tools"><a href="#" id="btn-create-retur" class="btn btn-primary btn-sm">Buat Retur Baru</a></div></div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            <table class="table table-bordered table-hover">
                <thead><tr><th>No</th><th>No. Retur</th><th>Tipe</th><th>Tanggal</th><th>Konsumen/Supplier</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($returs as $retur)
                    <tr>
                        <td>{{ $loop->iteration + $returs->firstItem() - 1 }}</td>
                        <td>{{ $retur->nomor_retur }}</td>
                        <td><span class="badge {{ $retur->tipe_retur == 'retur_jual' ? 'badge-primary' : 'badge-warning' }}">{{ $retur->tipe_retur == 'retur_jual' ? 'Retur Penjualan' : 'Retur Pembelian' }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d-m-Y') }}</td>
                        <td>{{ $retur->konsumen->nama_konsumen ?? $retur->supplier->nama_supplier ?? 'N/A' }}</td>
                        <td><span class="badge badge-success text-capitalize">{{ $retur->status_retur }}</span></td>
                        <td><a href="#" class="btn btn-info btn-sm">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $returs->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog"><div class="modal-dialog modal-xl" role="document"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Form Retur Baru</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form action="{{ route('retur.store') }}" method="POST"> @csrf
<div class="modal-body">
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Tipe Retur</label><select name="tipe_retur" id="tipe_retur" class="form-control" required><option value="">-- Pilih Tipe --</option><option value="retur_jual">Retur Penjualan</option><option value="retur_beli">Retur Pembelian</option></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Dokumen Asal</label><select name="id_dokumen" id="id_dokumen" class="form-control" required disabled><option value="">-- Pilih Dokumen --</option></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Tanggal Retur</label><input type="date" name="tanggal_retur" class="form-control" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}"></div></div>
    </div>
    <div class="form-group"><label for="alasan">Alasan Retur</label><textarea name="alasan" id="alasan" rows="2" class="form-control" required></textarea></div><hr>
    <h6>Item yang Diretur</h6>
    <table class="table table-bordered"><thead><tr><th style="width:5%">Pilih</th><th style="width:40%">Part</th><th>Qty Retur</th><th>Kondisi</th></tr></thead><tbody id="details-container"></tbody></table>
</div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Retur</button></div>
</form></div></div></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btn-create-retur').on('click', function(e) { e.preventDefault(); $('#createModal').modal('show'); });

    $('#tipe_retur').on('change', function(){
        let type = $(this).val();
        let selectDokumen = $('#id_dokumen');
        selectDokumen.html('<option value="">-- Pilih Dokumen --</option>').prop('disabled', true);
        $('#details-container').empty();

        if(type === 'retur_jual'){
            @foreach($penjualans as $doc)
                selectDokumen.append(`<option value="{{ $doc->id_penjualan }}">{{ $doc->nomor_invoice }} - {{ $doc->konsumen->nama_konsumen ?? '' }}</option>`);
            @endforeach
            selectDokumen.prop('disabled', false);
        } else if (type === 'retur_beli') {
            @foreach($pembelians as $doc)
                selectDokumen.append(`<option value="{{ $doc->id_pembelian }}">{{ $doc->nomor_po }} - {{ $doc->supplier->nama_supplier ?? '' }}</option>`);
            @endforeach
            selectDokumen.prop('disabled', false);
        }
    });

    $('#id_dokumen').on('change', function() {
        let id = $(this).val();
        let type = $('#tipe_retur').val();
        let container = $('#details-container');
        container.html('<tr><td colspan="4" class="text-center">Memuat item...</td></tr>');

        if(!id) { container.empty(); return; }

        $.ajax({
            url: '{{ route("retur.get-items") }}',
            data: { id: id, type: type },
            success: function(items) {
                container.empty();
                items.forEach(function(item, index) {
                    let row = `
                    <tr>
                        <td class="text-center"><input type="checkbox" class="item-checkbox" data-index="${index}"></td>
                        <td>
                            ${item.part.nama_part}
                            <input type="hidden" name="details[${index}][id_part]" value="${item.part.id_part}" disabled>
                        </td>
                        <td><input type="number" name="details[${index}][quantity]" class="form-control" value="1" min="1" max="${item.quantity}" disabled required></td>
                        <td><select name="details[${index}][kondisi_barang]" class="form-control" disabled required><option value="baik">Baik</option><option value="rusak">Rusak</option></select></td>
                    </tr>`;
                    container.append(row);
                });
            }
        });
    });

    // ===== PERBAIKAN DI SINI =====
    $(document).on('change', '.item-checkbox', function(){
        let row = $(this).closest('tr');
        let isChecked = $(this).is(':checked');
        // Aktifkan/nonaktifkan SEMUA input di baris tersebut, termasuk hidden input
        row.find('input, select').prop('disabled', !isChecked);
    });
});
</script>
@endpush