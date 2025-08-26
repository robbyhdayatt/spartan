@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Penjualan</h3>
            <div class="card-tools">
                @can('access', ['penjualan', 'create'])
                    <a href="#" id="btn-create-penjualan" class="btn btn-primary btn-sm">Buat Penjualan Baru</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> @endif
            @if (session('error')) <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> @endif
            
            <table class="table table-bordered table-hover">
                <thead><tr><th>No</th><th>No. Invoice</th><th>Konsumen</th><th>Sales</th><th>Tanggal</th><th class="text-right">Total</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($penjualans as $penjualan)
                    <tr>
                        <td>{{ $loop->iteration + $penjualans->firstItem() - 1 }}</td>
                        <td>{{ $penjualan->nomor_invoice }}</td>
                        <td>{{ $penjualan->konsumen->nama_konsumen ?? 'N/A' }}</td>
                        <td>{{ $penjualan->sales->nama_karyawan ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d-m-Y') }}</td>
                        <td class="text-right">Rp {{ number_format($penjualan->total_amount, 0, ',', '.') }}</td>
                        <td><span class="badge badge-info text-capitalize">{{ $penjualan->status_penjualan }}</span></td>
                        <td><a href="#" class="btn btn-info btn-sm btn-detail" data-id="{{ $penjualan->id_penjualan }}">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">Data penjualan tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $penjualans->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Form Penjualan Baru</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form action="{{ route('penjualan.store') }}" method="POST"> @csrf
<div class="modal-body">
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Konsumen</label><select name="id_konsumen" id="create_id_konsumen" class="form-control" required><option value="">-- Pilih Konsumen --</option>@foreach($konsumens as $k)<option value="{{ $k->id_konsumen }}">{{ $k->nama_konsumen }}</option>@endforeach</select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Sales</label><select name="id_sales" class="form-control" required><option value="">-- Pilih Sales --</option>@foreach($salespersons as $s)<option value="{{ $s->id_karyawan }}">{{ $s->nama_karyawan }}</option>@endforeach</select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Tanggal Penjualan</label><input type="date" name="tanggal_penjualan" class="form-control" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Jenis Penjualan</label><select name="jenis_penjualan" class="form-control" required><option value="credit">Credit</option><option value="cash">Cash</option></select></div></div>
    </div>
    <hr><h6>Detail Barang</h6>
    @error('details') <div class="alert alert-danger">{{ $message }}</div> @enderror
    <table class="table table-bordered"><thead><tr><th>Part</th><th style="width: 20%">Qty</th><th style="width: 20%">Harga Satuan</th><th style="width: 20%">Subtotal</th><th style="width: 5%">Aksi</th></tr></thead><tbody id="details-container"></tbody></table>
    <button type="button" id="btn-add-detail" class="btn btn-success mt-2">Tambah Part</button>
</div>
<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Penjualan</button></div>
</form></div></div></div>

<div class="modal fade" id="detailModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title" id="detailModalLabel">Detail Penjualan</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<div class="modal-body"><div id="detail-content"></div></div>
<div class="modal-footer justify-content-between">
    <div>
        {{-- Tombol Aksi akan muncul di sini --}}
        <div id="penjualan-actions" class="d-inline"></div>
    </div>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>
</div></div>

<template id="detail-row-template">
    <tr>
        <td><select name="details[__INDEX__][id_part]" class="form-control select-part" required><option value="">-- Pilih Part --</option>@foreach($parts as $part)<option value="{{ $part->id_part }}" data-harga="{{ $part->harga_jual ?? $part->harga_pokok }}" data-stok="{{ $part->stok_summary->stok_tersedia ?? 0 }}">{{ $part->nama_part }}</option>@endforeach</select></td>
        <td><input type="number" name="details[__INDEX__][quantity]" class="form-control input-quantity" value="1" min="1" required><small class="form-text text-muted stock-display"></small></td>
        <td><input type="number" name="details[__INDEX__][harga_satuan]" class="form-control input-harga" required></td>
        <td><input type="text" class="form-control subtotal" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm btn-remove-detail">Hapus</button></td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let detailIndex = 0;

    // === LOGIC UNTUK MODAL CREATE ===
    $('#btn-create-penjualan').on('click', function(e) {
        e.preventDefault();
        $('#createModal').modal('show');
    });

    $('#createModal').on('hidden.bs.modal', function () {
        $('#details-container').empty();
        detailIndex = 0;
        $('#createModal form')[0].reset();
    });

    $('#btn-add-detail').on('click', function() {
        let template = $('#detail-row-template').html().replace(/__INDEX__/g, detailIndex);
        $('#details-container').append(template);
        detailIndex++;
    });

    $(document).on('click', '.btn-remove-detail', function() { $(this).closest('tr').remove(); });
    $(document).on('input', '.input-quantity, .input-harga', function() { updateRowSubtotal($(this).closest('tr')); });

    function updateRowSubtotal(row) {
        let quantity = parseFloat(row.find('.input-quantity').val()) || 0;
        let harga = parseFloat(row.find('.input-harga').val()) || 0;
        row.find('.subtotal').val('Rp ' + (quantity * harga).toLocaleString('id-ID'));
    }

    function getHargaDanStok(row) {
        let selectedOption = row.find('.select-part option:selected');
        let partId = row.find('.select-part').val();
        let konsumenId = $('#create_id_konsumen').val();

        let stok = selectedOption.data('stok') || 0;
        row.find('.input-quantity').attr('max', stok);
        row.find('.stock-display').text('Stok tersedia: ' + stok);
        row.find('.input-harga').val('');
        row.find('.subtotal').val('');

        if (!partId || !konsumenId) { return; }

        $.ajax({
            url: '{{ route("penjualan.get-harga") }}', type: 'GET',
            data: { part_id: partId, konsumen_id: konsumenId },
            success: function(response) {
                row.find('.input-harga').val(response.harga);
                updateRowSubtotal(row);
            },
            error: function() {
                console.error('Gagal mengambil data harga.');
                let hargaDefault = selectedOption.data('harga') || 0;
                row.find('.input-harga').val(hargaDefault);
                updateRowSubtotal(row);
            }
        });
    }

    $(document).on('change', '.select-part', function() { getHargaDanStok($(this).closest('tr')); });
    $('#createModal').on('change', '#create_id_konsumen', function() {
        $('#details-container tr').each(function() { getHargaDanStok($(this)); });
    });

    // === LOGIC UNTUK MODAL DETAIL (AJAX) - DENGAN PERBAIKAN ===
$('.btn-detail').on('click', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    let url = '{{ route("penjualan.details.json", ":id") }}'.replace(':id', id);

    $('#detailModalLabel').text('Memuat Detail...');
    $('#detail-content').html('<p class="text-center">Memuat data...</p>');
    $('#detailModal').modal('show'); // Tampilkan modal SEBELUM ajax

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            $('#detailModalLabel').text('Detail Penjualan: ' + response.nomor_invoice);

            // Pengecekan data null (ini perbaikannya)
            let konsumenName = response.konsumen ? response.konsumen.nama_konsumen : 'N/A';
            let salesName = response.sales ? response.sales.nama_karyawan : 'N/A';
            let tgl = new Date(response.tanggal_penjualan).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
            
            let itemsHtml = '';
            response.details.forEach(function(detail, index) {
                let harga = parseFloat(detail.harga_satuan);
                let subtotal = parseFloat(detail.subtotal);
                itemsHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${detail.part ? detail.part.nama_part : 'Part Dihapus'}</td>
                        <td class="text-center">${detail.quantity}</td>
                        <td class="text-right">Rp ${harga.toLocaleString('id-ID')}</td>
                        <td class="text-right">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
            
            let detailHtml = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Konsumen:</strong> ${konsumenName}</p>
                        <p><strong>Sales:</strong> ${salesName}</p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <p><strong>No. Invoice:</strong> ${response.nomor_invoice}</p>
                        <p><strong>Tanggal:</strong> ${tgl}</p>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead class="thead-light"><tr><th>No</th><th>Part</th><th class="text-center">Qty</th><th class="text-right">Harga</th><th class="text-right">Subtotal</th></tr></thead>
                    <tbody>${itemsHtml}</tbody>
                    <tfoot style="background-color: #f8f9fa;">
                        <tr><td colspan="4" class="text-right"><strong>Subtotal</strong></td><td class="text-right"><strong>Rp ${parseFloat(response.subtotal).toLocaleString('id-ID')}</strong></td></tr>
                        <tr><td colspan="4" class="text-right"><strong>PPN (11%)</strong></td><td class="text-right"><strong>Rp ${parseFloat(response.ppn_amount).toLocaleString('id-ID')}</strong></td></tr>
                        <tr><td colspan="4" class="text-right font-weight-bold"><strong>Total</strong></td><td class="text-right font-weight-bold"><h5>Rp ${parseFloat(response.total_amount).toLocaleString('id-ID')}</h5></td></tr>
                    </tfoot>
                </table>
            `;
             // Tambahkan tombol aksi setelah detail
            let actionsHtml = '';
            let csrfToken = '@csrf';
            if (response.status_penjualan === 'processed') {
                let deliveredUrl = '{{ route("penjualan.delivered", ":id") }}'.replace(':id', response.id_penjualan);
                actionsHtml += `<form action="${deliveredUrl}" method="POST" class="d-inline"> ${csrfToken} <button type="submit" class="btn btn-info">Tandai Terkirim</button></form>`;
            }
            if (['processed', 'delivered'].includes(response.status_penjualan)) {
                let completedUrl = '{{ route("penjualan.completed", ":id") }}'.replace(':id', response.id_penjualan);
                actionsHtml += `<form action="${completedUrl}" method="POST" class="d-inline ml-2"> ${csrfToken} <button type="submit" class="btn btn-success">Tandai Selesai & Lunas</button></form>`;
            }
            
            // Gabungkan detail dengan tombol aksi
            let finalHtml = detailHtml + `<div class="mt-4 pt-3 border-top">${actionsHtml}</div>`;
            $('#detail-content').html(finalHtml);
        },
        error: function() {
            $('#detail-content').html('<p class="text-center text-danger">Gagal memuat data.</p>');
        }
    });
});

    @if ($errors->any())
        $('#createModal').modal('show');
    @endif
});
</script>
@endpush