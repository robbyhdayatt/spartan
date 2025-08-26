@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Retur</h3>
            <div class="card-tools">
                @can('access', ['retur', 'create'])
                    <a href="#" id="btn-create-retur" class="btn btn-primary btn-sm">Buat Retur Baru</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> @endif
            @if (session('error')) <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> @endif
            
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
                        <td>
                            @can('access', ['retur', 'read'])
                                <a href="#" class="btn btn-info btn-sm btn-detail-retur" data-id="{{ $retur->id_retur }}">Detail</a>
                            @endcan
                        </td>
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

<div class="modal fade" id="createModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Form Retur Baru</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form id="createReturForm" action="{{ route('retur.store') }}" method="POST"> @csrf
<div class="modal-body">
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Tipe Retur</label><select name="tipe_retur" id="tipe_retur" class="form-control" required><option value="">-- Pilih Tipe --</option><option value="retur_jual">Retur Penjualan</option><option value="retur_beli">Retur Pembelian</option></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Dokumen Asal</label><select name="id_dokumen" id="id_dokumen" class="form-control" required disabled><option value="">-- Pilih Dokumen --</option></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Tanggal Retur</label><input type="date" name="tanggal_retur" class="form-control" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}"></div></div>
    </div>
    <div class="form-group"><label for="alasan">Alasan Retur</label><textarea name="alasan" id="alasan" rows="2" class="form-control" required></textarea></div><hr>
    <h6>Item yang Diretur</h6>
    <table class="table table-bordered table-sm"><thead><tr><th style="width:5%">Pilih</th><th style="width:40%">Part</th><th>Qty Retur</th><th>Kondisi</th></tr></thead><tbody id="details-container"></tbody></table>
</div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Retur</button></div>
</form></div></div></div>

<div class="modal fade" id="detailReturModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title" id="detailReturModalLabel">Detail Retur</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<div class="modal-body" id="detail-retur-content"></div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button></div>
</div></div></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // === LOGIC UNTUK MODAL CREATE ===
    $('#btn-create-retur').on('click', function(e) { e.preventDefault(); $('#createModal').modal('show'); });
    $('#createModal').on('hidden.bs.modal', function(){ $('#createReturForm')[0].reset(); $('#id_dokumen').html('<option value="">-- Pilih Dokumen --</option>').prop('disabled', true); $('#details-container').empty(); });

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
                if (items.length === 0) {
                    container.html('<tr><td colspan="4" class="text-center">Tidak ada item yang bisa diretur dari dokumen ini.</td></tr>');
                    return;
                }
                items.forEach(function(item, index) {
                    let row = `
                    <tr>
                        <td class="text-center align-middle"><input type="checkbox" class="item-checkbox" data-index="${index}"></td>
                        <td class="align-middle">
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
    
    $(document).on('change', '.item-checkbox', function(){
        let row = $(this).closest('tr');
        let isChecked = $(this).is(':checked');
        row.find('input, select').prop('disabled', !isChecked);
    });

    // === LOGIC UNTUK MODAL DETAIL RETUR (AJAX) ===
    $('.btn-detail-retur').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '{{ route("retur.details.json", ":id") }}'.replace(':id', id);

        $('#detailReturModalLabel').text('Memuat Detail...');
        $('#detail-retur-content').html('<p class="text-center">Memuat data...</p>');
        $('#detailReturModal').modal('show');

        $.ajax({
            url: url, type: 'GET',
            success: function(response) {
                $('#detailReturModalLabel').text('Detail Retur: ' + response.nomor_retur);
                let tgl = new Date(response.tanggal_retur).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                let pihak = response.tipe_retur == 'retur_jual' ? `<strong>Konsumen:</strong> ${response.konsumen.nama_konsumen}` : `<strong>Supplier:</strong> ${response.supplier.nama_supplier}`;
                
                let itemsHtml = '';
                response.details.forEach(function(detail, index) {
                    itemsHtml += `<tr><td>${index + 1}</td><td>${detail.part.nama_part}</td><td class="text-center">${detail.quantity}</td><td class="text-capitalize">${detail.kondisi_barang}</td></tr>`;
                });

                let detailHtml = `
                    <div class="row mb-3"><div class="col-md-6">${pihak}</div><div class="col-md-6 text-md-right"><p><strong>No. Retur:</strong> ${response.nomor_retur}</p><p><strong>Tanggal:</strong> ${tgl}</p></div></div>
                    <p><strong>Alasan:</strong> ${response.alasan}</p>
                    <table class="table table-sm table-bordered mt-3"><thead class="thead-light"><tr><th>No</th><th>Part</th><th class="text-center">Qty</th><th>Kondisi</th></tr></thead><tbody>${itemsHtml}</tbody></table>
                `;
                $('#detail-retur-content').html(detailHtml);
            },
            error: function() {
                $('#detail-retur-content').html('<p class="text-center text-danger">Gagal memuat data.</p>');
            }
        });
    });

    @if ($errors->any()) $('#createModal').modal('show'); @endif
});
</script>
@endpush