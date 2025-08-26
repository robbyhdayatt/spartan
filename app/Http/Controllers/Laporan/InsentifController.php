<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Setting\Insentif;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\RealisasiInsentif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InsentifController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('access', ['laporan.insentif', 'read']);

        $realisasis = collect();
        if ($request->has('bulan') && $request->has('tahun')) {
            $realisasis = RealisasiInsentif::with(['karyawan', 'insentif'])
                ->where('periode_bulan', $request->bulan)
                ->where('periode_tahun', $request->tahun)
                ->get();
        }

        return view('laporan.insentif.index', compact('realisasis'));
    }

    public function hitung(Request $request)
    {
        $this->authorize('access', ['laporan.insentif', 'create']);
        $request->validate(['bulan' => 'required|integer', 'tahun' => 'required|integer']);
        
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        DB::beginTransaction();
        try {
            // Hapus data realisasi lama untuk periode ini
            RealisasiInsentif::where('periode_bulan', $bulan)->where('periode_tahun', $tahun)->delete();

            // Tentukan tanggal awal dan akhir dari bulan yang dihitung
            $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth();

            // Ambil semua program insentif yang periodenya bersinggungan dengan bulan yang dihitung
            $programInsentifs = Insentif::where('status_aktif', 1)
                ->where('periode_awal', '<=', $endDate)
                ->where('periode_akhir', '>=', $startDate)
                ->get();

            foreach ($programInsentifs as $program) {
                // Ambil semua penjualan yang sesuai dengan kriteria program
                $penjualans = Penjualan::whereIn('status_penjualan', ['processed', 'delivered', 'completed'])
                    ->whereYear('tanggal_penjualan', $tahun)
                    ->whereMonth('tanggal_penjualan', $bulan)
                    ->whereHas('sales.jabatan', function ($q) use ($program) {
                        $q->where('id_jabatan', $program->id_jabatan);
                    })
                    ->with('details')
                    ->get();
                
                // Kelompokkan penjualan berdasarkan sales
                $salesPerformance = $penjualans->groupBy('id_sales');

                foreach ($salesPerformance as $salesId => $salesData) {
                    $totalRealisasi = $salesData->pluck('details')->flatten()
                                        // Gunakan "when" untuk filter part secara kondisional
                                        ->when($program->id_part, function ($query, $partId) {
                                            return $query->where('id_part', $partId);
                                        })
                                        ->sum('quantity');
                    
                    if ($totalRealisasi >= $program->minimum_target) {
                        $nilaiInsentif = 0;
                        if ($program->tipe_insentif == 'per_qty') {
                            $nilaiInsentif = $totalRealisasi * $program->nilai_insentif;
                        }
                        // Tambahkan logika untuk tipe insentif lain jika perlu

                        // Simpan hasil perhitungan
                        RealisasiInsentif::create([
                            'id_insentif' => $program->id_insentif,
                            'id_karyawan' => $salesId,
                            'periode_bulan' => $bulan,
                            'periode_tahun' => $tahun,
                            'realisasi_qty' => $totalRealisasi,
                            'nilai_insentif' => $nilaiInsentif,
                            'status_bayar' => 'pending',
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('laporan.insentif.index', ['bulan' => $bulan, 'tahun' => $tahun])
                             ->with('success', 'Perhitungan insentif berhasil diselesaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghitung insentif: ' . $e->getMessage());
        }
    }
    public function markAsPaid(RealisasiInsentif $realisasi)
    {
        $this->authorize('access', ['laporan.insentif', 'update']); // Hanya user dengan izin update

        $realisasi->status_bayar = 'paid';
        $realisasi->save();

        return redirect()->back()->with('success', 'Status insentif berhasil diubah menjadi LUNAS.');
    }
}