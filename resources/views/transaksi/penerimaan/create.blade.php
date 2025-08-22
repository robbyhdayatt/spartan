@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('penerimaan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_pembelian" value="{{ $pembelian->id_pembelian }}">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Form Penerimaan Barang</h3></div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Supplier</label>
                            <input type="text" class="form-control" value="{{ $pembelian->supplier->nama_supplier }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nomor PO</label>
                            <input type="text" class="form-control" value="{{ $pembelian->nomor_po }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nomor_surat_jalan">Nomor Surat Jalan</label>
                            <input type="text" name="nomor_surat_jalan" id="nomor_surat_jalan" class="form-control @error('nomor_surat_jalan') is-invalid @enderror" value="{{ old('nomor_surat_jalan') }}" required>
                            @error('nomor_surat_jalan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal_penerimaan">Tanggal Penerimaan</label>
                            <input type="date" name="tanggal_penerimaan" id="tanggal_penerimaan" class="form-control @error('tanggal_penerimaan') is-invalid @enderror" value="{{ old('tanggal_penerimaan', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
                            @error('tanggal_penerimaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                            <label for="id_gudang_tujuan">Gudang Tujuan</label>
                            <select name="id_gudang_tujuan" id="id_gudang_tujuan" class="form-control @error('id_gudang_tujuan') is-invalid @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($gudangs as $gudang)
                                    <option value="{{ $gudang->id_gudang }}" {{ old('id_gudang_tujuan') == $gudang->id_gudang ? 'selected' : '' }}>{{ $gudang->nama_gudang }}</option>
                                @endforeach
                            </select>
                            @error('id_gudang_tujuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Detail Barang Diterima</h3></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Part</th>
                                <th class="text-center">Qty Dipesan</th>
                                <th class="text-center">Qty Sudah Diterima</th>
                                <th class="text-center" style="width: 20%;">Qty Diterima Sekarang</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($pembelian->details as $index => $detail)
                               @php
                                   $qtySisa = $detail->quantity - $detail->qty_received;
                               @endphp
                               {{-- Hanya tampilkan item yang masih ada sisa untuk diterima --}}
                               @if($qtySisa > 0)
                               <tr>
                                   <td>
                                       <input type="hidden" name="details[{{ $index }}][id_detail_pembelian]" value="{{ $detail->id_detail_pembelian }}">
                                       <input type="hidden" name="details[{{ $index }}][id_part]" value="{{ $detail->id_part }}">
                                       <input type="hidden" name="details[{{ $index }}][qty_dipesan]" value="{{ $detail->quantity }}">
                                       {{ $detail->part->nama_part }} ({{ $detail->part->kode_part }})
                                   </td>
                                   <td class="text-center">{{ $detail->quantity }}</td>
                                   <td class="text-center">{{ $detail->qty_received }}</td>
                                   <td>
                                       <input type="number" name="details[{{ $index }}][qty_diterima]" class="form-control @error('details.'.$index.'.qty_diterima') is-invalid @enderror" value="{{ old('details.'.$index.'.qty_diterima', $qtySisa) }}" min="0" max="{{ $qtySisa }}">
                                       @error('details.'.$index.'.qty_diterima') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                   </td>
                               </tr>
                               @endif
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-right">
                 <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
                 <button type="submit" class="btn btn-primary">Simpan Penerimaan</button>
            </div>
        </div>
    </form>
</div>
@endsection
