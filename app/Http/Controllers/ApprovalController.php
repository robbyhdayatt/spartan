<?php

namespace App\Http\Controllers;

use App\Models\Setting\ApprovalLevel;
use App\Models\Transaksi\Pembelian;
use App\Models\Transaksi\StockAdjustment; // <-- Pastikan ini ada
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pendingApprovals = collect();

        if (!$user->karyawan || !$user->karyawan->id_jabatan) {
            return view('approvals.index', ['pendingApprovals' => $pendingApprovals]);
        }

        $userJabatanId = $user->karyawan->id_jabatan;

        // --- LOGIKA UNTUK PURCHASE ORDER (PO) ---
        $allPendingPO = Pembelian::with('supplier')
                          ->where('status_pembelian', 'pending_approval')
                          ->get();

        foreach ($allPendingPO as $po) {
            $correctRule = ApprovalLevel::where('jenis_dokumen', 'pembelian')
                                        ->where('minimum_amount', '<=', $po->total_amount)
                                        ->orderBy('minimum_amount', 'desc')
                                        ->first();

            if ($correctRule && $correctRule->id_jabatan_required == $userJabatanId) {
                // Menyeragamkan properti untuk ditampilkan di view
                $po->document_type = 'Purchase Order';
                $po->detail_url = route('pembelian.index');
                $po->nomor_dokumen = $po->nomor_po;
                $po->tanggal_dokumen = $po->tanggal_pembelian;
                $po->nilai_dokumen = $po->total_amount;
                $pendingApprovals->push($po);
            }
        }

        // =======================================================
        // === LOGIKA BARU UNTUK STOCK ADJUSTMENT ===
        // =======================================================
        $allPendingAdjustment = StockAdjustment::with('gudang')
                                    ->where('status_adjustment', 'pending_approval')
                                    ->get();
        
        foreach ($allPendingAdjustment as $adj) {
            // Cari aturan untuk adjustment
            $correctRule = ApprovalLevel::where('jenis_dokumen', 'adjustment')->first();

            // Jika aturan ditemukan dan jabatan user sesuai, tambahkan ke daftar
            if ($correctRule && $correctRule->id_jabatan_required == $userJabatanId) {
                // Menyeragamkan properti untuk ditampilkan di view
                $adj->document_type = 'Stock Adjustment';
                $adj->detail_url = route('adjustment.index');
                $adj->nomor_dokumen = $adj->nomor_adjustment;
                $adj->tanggal_dokumen = $adj->tanggal_adjustment;
                $adj->nilai_dokumen = $adj->total_selisih_value;
                $pendingApprovals->push($adj);
            }
        }
        
        return view('approvals.index', compact('pendingApprovals'));
    }
}