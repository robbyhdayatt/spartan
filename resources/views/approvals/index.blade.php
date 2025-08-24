@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Persetujuan (Approval Inbox)</h3>
                </div>
                <div class="card-body">
                    <p>Berikut adalah daftar semua dokumen yang membutuhkan persetujuan Anda.</p>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tipe Dokumen</th>
                                <th>Nomor Dokumen</th>
                                <th>Tanggal</th>
                                <th class="text-right">Total Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingApprovals as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->document_type }}</td>
                                <td>{{ $item->nomor_po }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d-m-Y') }}</td>
                                <td class="text-right">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    {{-- Tombol ini akan mengarahkan ke halaman daftar PO, lalu user bisa klik detail --}}
                                    <a href="{{ $item->detail_url }}" class="btn btn-primary btn-sm">
                                        Lihat & Proses
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada dokumen yang menunggu persetujuan Anda.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection