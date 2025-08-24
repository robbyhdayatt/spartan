<?php

namespace App\Http\Controllers;

use App\Models\Setting\ApprovalLevel;
use App\Models\Transaksi\Pembelian;
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

        // Ambil semua PO yang menunggu persetujuan
        $allPendingPO = Pembelian::with('supplier')
                          ->where('status_pembelian', 'pending_approval')
                          ->get();

        // Filter satu per satu, mana yang menjadi hak user ini
        foreach ($allPendingPO as $po) {
            // Untuk setiap PO, cari aturan yang paling pas berdasarkan nominalnya
            $correctRule = ApprovalLevel::where('jenis_dokumen', 'pembelian')
                                        ->where('minimum_amount', '<=', $po->total_amount)
                                        ->orderBy('minimum_amount', 'desc')
                                        ->first();

            // Jika aturan yang pas itu membutuhkan jabatan user ini, tampilkan PO di inbox
            if ($correctRule && $correctRule->id_jabatan_required == $userJabatanId) {
                $po->document_type = 'Purchase Order';
                $po->detail_url = route('pembelian.index'); // Link ke halaman daftar PO
                $pendingApprovals->push($po);
            }
        }

        return view('approvals.index', compact('pendingApprovals'));
    }
}