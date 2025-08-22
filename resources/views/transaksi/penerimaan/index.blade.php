@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Penerimaan Barang</h3>
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
                                <th>No. Penerimaan</th>
                                <th>No. PO Terkait</th>
                                <th>Supplier</th>
                                <th>Gudang Tujuan</th>
                                <th>Tanggal Terima</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penerimaans as $penerimaan)
                            <tr>
                                <td>{{ $loop->iteration + $penerimaans->firstItem() - 1 }}</td>
                                <td>{{ $penerimaan->nomor_penerimaan }}</td>
                                <td>{{ $penerimaan->pembelian->nomor_po ?? 'N/A' }}</td>
                                <td>{{ $penerimaan->supplier->nama_supplier ?? 'N/A' }}</td>
                                <td>{{ $penerimaan->gudang->nama_gudang ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($penerimaan->tanggal_penerimaan)->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'draft' => 'secondary', 'checking' => 'info', 'qc_pending' => 'warning',
                                            'partial_approved' => 'primary', 'completed' => 'success', 'rejected' => 'danger',
                                        ][$penerimaan->status_penerimaan] ?? 'light';
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }} text-capitalize">{{ str_replace('_', ' ', $penerimaan->status_penerimaan) }}</span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-info btn-sm btn-detail-penerimaan" data-id="{{ $penerimaan->id_penerimaan }}">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Data Penerimaan Barang tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $penerimaans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailPenerimaanModal" tabindex="-1" role="dialog" aria-labelledby="detailPenerimaanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailPenerimaanModalLabel">Detail Penerimaan Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Supplier:</strong> <span id="detail-supplier"></span></p>
                <p><strong>Gudang Tujuan:</strong> <span id="detail-gudang"></span></p>
            </div>
            <div class="col-md-6 text-md-right">
                <p><strong>No. Penerimaan:</strong> <span id="detail-nomor-penerimaan"></span></p>
                <p><strong>No. PO Terkait:</strong> <span id="detail-nomor-po"></span></p>
                <p><strong>Tanggal Terima:</strong> <span id="detail-tanggal"></span></p>
            </div>
        </div>
        <table class="table table-bordered">
            <thead class="thead-light"><tr><th>No</th><th>Part</th><th class="text-center">Qty Dipesan</th><th class="text-center">Qty Diterima</th></tr></thead>
            <tbody id="detail-penerimaan-items"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('.btn-detail-penerimaan').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '{{ route("penerimaan.details.json", ":id") }}'.replace(':id', id);

        $('#detailPenerimaanModalLabel').text('Memuat Detail...');
        $('#detail-penerimaan-items').html('<tr><td colspan="4" class="text-center">Memuat data...</td></tr>');
        $('#detailPenerimaanModal').modal('show');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#detailPenerimaanModalLabel').text('Detail Penerimaan: ' + response.nomor_penerimaan);
                $('#detail-nomor-penerimaan').text(response.nomor_penerimaan);
                $('#detail-nomor-po').text(response.pembelian ? response.pembelian.nomor_po : 'N/A');
                $('#detail-supplier').text(response.supplier ? response.supplier.nama_supplier : 'N/A');
                $('#detail-gudang').text(response.gudang ? response.gudang.nama_gudang : 'N/A');
                $('#detail-tanggal').text(new Date(response.tanggal_penerimaan).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}));

                let itemsHtml = '';
                response.details.forEach(function(detail, index) {
                    itemsHtml += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${detail.part ? detail.part.nama_part : 'Part Dihapus'}</td>
                            <td class="text-center">${detail.qty_dipesan}</td>
                            <td class="text-center">${detail.qty_diterima}</td>
                        </tr>
                    `;
                });
                $('#detail-penerimaan-items').html(itemsHtml);
            },
            error: function() {
                 $('#detail-penerimaan-items').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data.</td></tr>');
            }
        });
    });
});
</script>
@endpush
