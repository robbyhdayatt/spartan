@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('penerimaan.processQc', $penerimaan->id_penerimaan) }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><h3 class="card-title">Form Quality Control - {{ $penerimaan->nomor_penerimaan }}</h3></div>
            <div class="card-body">
                @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                 <div class="row">
                    <div class="col-md-4"><p><strong>Supplier:</strong> {{ $penerimaan->supplier->nama_supplier }}</p></div>
                    <div class="col-md-4"><p><strong>No. PO:</strong> {{ $penerimaan->pembelian->nomor_po }}</p></div>
                    <div class="col-md-4"><p><strong>Tanggal Terima:</strong> {{ \Carbon\Carbon::parse($penerimaan->tanggal_penerimaan)->format('d F Y') }}</p></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Detail Item untuk Diperiksa</h3></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Part</th>
                                <th class="text-center">Qty Diterima</th>
                                <th class="text-center" style="width: 15%;">Qty Approved</th>
                                <th class="text-center" style="width: 15%;">Qty Rejected</th>
                                <th>Catatan QC</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($penerimaan->details as $detail)
                               <tr>
                                   <td>{{ $detail->part->nama_part }}</td>
                                   <td class="text-center align-middle"><strong>{{ $detail->qty_diterima }}</strong></td>
                                   <td>
                                       <input type="number" name="details[{{ $detail->id_detail_penerimaan }}][qty_approved]" class="form-control" value="{{ old('details.'.$detail->id_detail_penerimaan.'.qty_approved', $detail->qty_diterima) }}" min="0" max="{{ $detail->qty_diterima }}" required>
                                   </td>
                                   <td>
                                       <input type="number" name="details[{{ $detail->id_detail_penerimaan }}][qty_rejected]" class="form-control" value="{{ old('details.'.$detail->id_detail_penerimaan.'.qty_rejected', 0) }}" min="0" max="{{ $detail->qty_diterima }}" required>
                                   </td>
                                   <td>
                                       <input type="text" name="details[{{ $detail->id_detail_penerimaan }}][qc_notes]" class="form-control" placeholder="Opsional">
                                   </td>
                               </tr>
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-right">
                 <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary">Batal</a>
                 <button type="submit" class="btn btn-primary">Simpan & Selesaikan QC</button>
            </div>
        </div>
    </form>
</div>
@endsection