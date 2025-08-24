@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Purchase Order (PO)</h3>
            <div class="card-tools">
                <a href="#" id="btn-create-po" class="btn btn-primary btn-sm">Buat PO Baru</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor PO</th>
                        <th>Supplier</th>
                        <th>Tanggal PO</th>
                        <th class="text-right">Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembelians as $pembelian)
                    <tr>
                        <td>{{ $loop->iteration + $pembelians->firstItem() - 1 }}</td>
                        <td>{{ $pembelian->nomor_po }}</td>
                        <td>{{ $pembelian->supplier->nama_supplier ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->format('d-m-Y') }}</td>
                        <td class="text-right">Rp {{ number_format($pembelian->total_amount, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusClass = [
                                    'draft' => 'secondary', 'pending_approval' => 'warning', 'approved' => 'info',
                                    'ordered' => 'primary', 'partial_received' => 'info', 'received' => 'success',
                                    'completed' => 'success', 'cancelled' => 'danger', 'rejected' => 'danger',
                                ][$pembelian->status_pembelian] ?? 'light';
                            @endphp
                            <span class="badge badge-{{ $statusClass }} text-capitalize">{{ str_replace('_', ' ', $pembelian->status_pembelian) }}</span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm btn-detail" data-id="{{ $pembelian->id_pembelian }}">Detail</a>
                            
                            @if($pembelian->status_pembelian == 'draft')
                                <form action="{{ route('pembelian.submit', $pembelian->id_pembelian) }}" method="POST" class="d-inline" onsubmit="return confirm('Ajukan PO ini untuk persetujuan?');">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Ajukan</button>
                                </form>
                            @endif
                            
                            @if(in_array($pembelian->status_pembelian, ['approved', 'ordered', 'partial_received']))
                                <a href="{{ route('penerimaan.create', ['po_id' => $pembelian->id_pembelian]) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-box-open"></i> Terima
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Data Purchase Order tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $pembelians->links() }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Buat Purchase Order Baru</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form action="{{ route('pembelian.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6"><div class="form-group">
                <label for="id_supplier">Supplier</label>
                <select name="id_supplier" id="id_supplier" class="form-control @error('id_supplier') is-invalid @enderror" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supplier)<option value="{{ $supplier->id_supplier }}" {{ old('id_supplier') == $supplier->id_supplier ? 'selected' : '' }}>{{ $supplier->nama_supplier }}</option>@endforeach
                </select>
                @error('id_supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div></div>
            <div class="col-md-6"><div class="form-group">
                <label for="tanggal_pembelian">Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" class="form-control @error('tanggal_pembelian') is-invalid @enderror" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
                @error('tanggal_pembelian') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div></div>
        </div>
        <hr><h6>Detail Barang</h6>
        @error('details') <div class="alert alert-danger">{{ $message }}</div> @enderror
        <table class="table table-bordered"><thead><tr><th>Part</th><th>Qty</th><th>Harga Satuan</th><th>Subtotal</th><th>Aksi</th></tr></thead><tbody id="details-container"></tbody></table>
        <button type="button" id="btn-add-detail" class="btn btn-success mt-2">Tambah Part</button>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button><button type="submit" class="btn btn-primary">Simpan PO Baru</button></div>
</form>
</div></div></div>

<div class="modal fade" id="detailModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title" id="detailModalLabel">Detail Purchase Order</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<div class="modal-body">
    <div class="row mb-3">
        <div class="col-md-6"><strong>Supplier:</strong> <p id="detail-supplier-nama"></p></div>
        <div class="col-md-6 text-md-right"><strong>Nomor PO:</strong> <p id="detail-nomor-po"></p><strong>Tanggal:</strong> <p id="detail-tanggal-po"></p></div>
    </div>
    <table class="table table-bordered">
        <thead class="thead-light"><tr><th>No</th><th>Part</th><th>Qty</th><th class="text-right">Harga</th><th class="text-right">Subtotal</th></tr></thead>
        <tbody id="detail-items-table"></tbody>
        <tfoot style="background-color: #f8f9fa;">
            <tr><td colspan="4" class="text-right"><strong>Subtotal</strong></td><td class="text-right" id="detail-subtotal"></td></tr>
            <tr><td colspan="4" class="text-right"><strong>PPN (11%)</strong></td><td class="text-right" id="detail-ppn"></td></tr>
            <tr><td colspan="4" class="text-right font-weight-bold"><strong>Total</strong></td><td class="text-right font-weight-bold" id="detail-total"></td></tr>
        </tfoot>
    </table>
</div>
<div class="modal-footer">
    <div id="approval-actions" style="display: none;" class="mr-auto">
        <form id="approveForm" action="" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Approve</button>
        </form>
        <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal" id="btn-reject">Reject</a>
    </div>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>
</div></div></div>

<div class="modal fade" id="rejectModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Alasan Penolakan</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
<form id="rejectForm" action="" method="POST">
    @csrf
    <div class="modal-body">
        <div class="form-group"><label for="keterangan">Mohon berikan alasan penolakan:</label><textarea name="keterangan" id="keterangan" rows="3" class="form-control" required></textarea></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Tolak PO</button></div>
</form>
</div></div></div>

{{-- Template untuk baris detail (disembunyikan) --}}
<template id="detail-row-template">
    <tr>
        <td><select name="details[__INDEX__][id_part]" class="form-control select-part" required><option value="">-- Pilih Part --</option>@foreach($parts as $part)<option value="{{ $part->id_part }}" data-harga="{{ $part->harga_pokok }}">{{ $part->nama_part }} ({{ $part->kode_part }})</option>@endforeach</select></td>
        <td><input type="number" name="details[__INDEX__][quantity]" class="form-control input-quantity" value="1" min="1" required></td>
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
    $('#btn-create-po').on('click', function(e) { e.preventDefault(); $('#createModal').modal('show'); });
    $('#createModal').on('hidden.bs.modal', function () { $('#details-container').empty(); detailIndex = 0; });
    $('#btn-add-detail').on('click', function() { let template = $('#detail-row-template').html().replace(/__INDEX__/g, detailIndex); $('#details-container').append(template); detailIndex++; });
    $(document).on('click', '.btn-remove-detail', function() { $(this).closest('tr').remove(); });
    $(document).on('change', '.select-part', function() { let harga = $(this).find('option:selected').data('harga') || 0; $(this).closest('tr').find('.input-harga').val(harga); updateRowSubtotal($(this).closest('tr')); });
    $(document).on('input', '.input-quantity, .input-harga', function() { updateRowSubtotal($(this).closest('tr')); });
    function updateRowSubtotal(row) { let quantity = parseFloat(row.find('.input-quantity').val()) || 0; let harga = parseFloat(row.find('.input-harga').val()) || 0; row.find('.subtotal').val('Rp ' + (quantity * harga).toLocaleString('id-ID')); }

    // === LOGIC UNTUK MODAL DETAIL & APPROVAL (AJAX) ===
    $('.btn-detail').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '{{ route("pembelian.details.json", ":id") }}'.replace(':id', id);
        
        $('#approval-actions').hide();
        $('#detailModalLabel').text('Memuat Detail...');
        $('#detail-items-table').html('<tr><td colspan="5" class="text-center">Memuat data...</td></tr>');
        $('#detailModal').modal('show');

        $.ajax({
            url: url, type: 'GET',
            success: function(response) {
                $('#detailModalLabel').text('Detail Purchase Order: ' + response.nomor_po);
                $('#detail-nomor-po').text(response.nomor_po);
                $('#detail-tanggal-po').text(new Date(response.tanggal_pembelian).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}));
                $('#detail-supplier-nama').text(response.supplier.nama_supplier);

                let itemsHtml = '';
                response.details.forEach(function(detail, index) {
                    let harga = parseFloat(detail.harga_satuan); let subtotal = parseFloat(detail.subtotal);
                    itemsHtml += `<tr><td>${index + 1}</td><td>${detail.part.nama_part} (${detail.part.kode_part})</td><td>${detail.quantity}</td><td class="text-right">Rp ${harga.toLocaleString('id-ID')}</td><td class="text-right">Rp ${subtotal.toLocaleString('id-ID')}</td></tr>`;
                });
                $('#detail-items-table').html(itemsHtml);

                $('#detail-subtotal').text('Rp ' + parseFloat(response.subtotal).toLocaleString('id-ID'));
                $('#detail-ppn').text('Rp ' + parseFloat(response.ppn_amount).toLocaleString('id-ID'));
                $('#detail-total').text('Rp ' + parseFloat(response.total_amount).toLocaleString('id-ID'));
                
                // Cek apakah jabatan user saat ini cocok dengan yang dibutuhkan oleh PO ini
                let userJabatanId = {{ auth()->user()->karyawan->id_jabatan ?? 'null' }};
                if (response.status_pembelian === 'pending_approval' && userJabatanId == response.id_jabatan_required) {
                    let approveUrl = '{{ route("pembelian.approve", ":id") }}'.replace(':id', response.id_pembelian);
                    $('#approveForm').attr('action', approveUrl);
                    $('#approval-actions').show();
                }
            },
            error: function() { $('#detail-items-table').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>'); }
        });
    });

    $('#detailModal').on('click', '#btn-reject', function(e) {
        let approveAction = $('#approveForm').attr('action');
        if(approveAction) {
            let rejectUrl = approveAction.replace('/approve', '/reject');
            $('#rejectForm').attr('action', rejectUrl);
        }
    });

    @if ($errors->any())
        $('#createModal').modal('show');
    @endif
});
</script>
@endpush