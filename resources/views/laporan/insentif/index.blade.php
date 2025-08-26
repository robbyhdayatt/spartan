@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Realisasi Insentif</h3>
        </div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

            {{-- Form Filter dan Tombol Hitung --}}
            <form action="{{ route('laporan.insentif.index') }}" method="GET" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <label for="bulan" class="mr-2">Periode:</label>
                    <select name="bulan" id="bulan" class="form-control" required>
                        @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan', date('m')) == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group mr-2">
                    <select name="tahun" id="tahun" class="form-control" required>
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
            </form>

            @can('access', ['laporan.insentif', 'create'])
            <form action="{{ route('laporan.insentif.hitung') }}" method="POST" class="d-inline" onsubmit="return confirm('Proses ini akan menghapus dan menghitung ulang data insentif pada periode yang dipilih. Lanjutkan?');">
                @csrf
                <input type="hidden" name="bulan" value="{{ request('bulan', date('m')) }}">
                <input type="hidden" name="tahun" value="{{ request('tahun', date('Y')) }}">
                <button type="submit" class="btn btn-success mb-3">
                    <i class="fas fa-calculator"></i> Hitung Ulang Insentif
                </button>
            </form>
            @endcan

            <table class="table table-bordered table-hover">
                <thead><tr><th>No</th><th>Nama Sales</th><th>Program Insentif</th><th>Realisasi (Qty)</th><th class="text-right">Nilai Insentif</th><th>Status Bayar</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse ($realisasis as $realisasi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $realisasi->karyawan->nama_karyawan ?? 'N/A' }}</td>
                            <td>{{ $realisasi->insentif->nama_program ?? 'N/A' }}</td>
                            <td>{{ $realisasi->realisasi_qty }}</td>
                            <td class="text-right">Rp {{ number_format($realisasi->nilai_insentif, 0, ',', '.') }}</td>
                            <td><span class="badge badge-{{ $realisasi->status_bayar == 'paid' ? 'success' : 'warning' }} text-capitalize">{{ $realisasi->status_bayar }}</span></td>
                            <td>
                                @if ($realisasi->status_bayar == 'pending')
                                    @can('access', ['laporan.insentif', 'update'])
                                    <form action="{{ route('laporan.insentif.paid', $realisasi->id_realisasi) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menandai insentif ini sebagai LUNAS?');">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-success">Tandai Lunas</button>
                                    </form>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">Pilih periode dan klik "Tampilkan" untuk melihat data, atau "Hitung Ulang" untuk memproses.</td></tr>
                        @endforelse
                    </tbody>
            </table>
        </div>
    </div>
</div>
@endsection