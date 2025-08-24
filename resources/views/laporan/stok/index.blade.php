@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Stok Real-Time</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{-- Form Pencarian --}}
                            <form action="{{ route('laporan.stok.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Kode atau Nama Part..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Cari</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode Part</th>
                                    <th>Nama Part</th>
                                    <th class="text-center">Stok Tersedia</th>
                                    <th class="text-center">Stok Rusak</th>
                                    <th class="text-center">Stok Karantina</th>
                                    <th class="text-center">Total Stok</th>
                                    <th class="text-center">Min. Stok</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stoks as $stok)
                                    @php
                                        $rowClass = '';
                                        if ($stok->status_stok == 'OUT_OF_STOCK') {
                                            $rowClass = 'table-danger';
                                        } elseif ($stok->status_stok == 'LOW_STOCK') {
                                            $rowClass = 'table-warning';
                                        }
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>{{ $stok->kode_part }}</td>
                                        <td>{{ $stok->nama_part }}</td>
                                        <td class="text-center font-weight-bold">{{ $stok->stok_tersedia }}</td>
                                        <td class="text-center">{{ $stok->stok_rusak }}</td>
                                        <td class="text-center">{{ $stok->stok_quarantine }}</td>
                                        <td class="text-center">{{ $stok->stok_total }}</td>
                                        <td class="text-center">{{ $stok->minimum_stok }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $rowClass == 'table-danger' ? 'danger' : ($rowClass == 'table-warning' ? 'warning' : 'success') }}">
                                                {{ str_replace('_', ' ', $stok->status_stok) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Data stok tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $stoks->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection