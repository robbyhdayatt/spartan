<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Models\Master\Gudang;
use App\Models\Master\Part;
use App\Models\Master\StokLokasi;
use App\Models\Master\StokSummary;
use App\Models\Setting\ApprovalLevel;
use App\Models\Transaksi\ApprovalHistory;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['adjustment', 'read']);
        $adjustments = StockAdjustment::with('gudang')->latest()->paginate(10);
        $gudangs = Gudang::where('status_aktif', 1)->get();
        $parts = Part::where('status_aktif', 1)->get();

        $isApprover = false;
        if (auth()->check() && auth()->user()->karyawan) {
            $userJabatanId = auth()->user()->karyawan->id_jabatan;
            $approvalRule = ApprovalLevel::where('jenis_dokumen', 'adjustment')->first();
            if ($approvalRule && $userJabatanId == $approvalRule->id_jabatan_required) {
                $isApprover = true;
            }
        }

        return view('transaksi.adjustment.index', compact('adjustments', 'gudangs', 'parts', 'isApprover'));
    }

    public function store(StoreStockAdjustmentRequest $request)
    {
        $this->authorize('access', ['adjustment', 'create']);
      
        DB::beginTransaction();
        try {
            // Method store sekarang hanya membuat dokumen DRAFT, TIDAK mengubah stok
            $adj = StockAdjustment::create([
                'nomor_adjustment' => 'ADJ-' . date('Ymd') . '-' . Str::random(4),
                'id_gudang' => $request->id_gudang,
                'tanggal_adjustment' => $request->tanggal_adjustment,
                'jenis_adjustment' => $request->jenis_adjustment,
                'status_adjustment' => 'draft', // <-- STATUS AWAL DIUBAH MENJADI DRAFT
                'keterangan' => $request->keterangan,
                'created_by' => auth()->id(),

        foreach ($request->details as $item) {
            $part = Part::find($item['id_part']);
            $stokLokasi = StokLokasi::firstOrCreate(
                ['id_part' => $item['id_part'], 'id_gudang' => $request->id_gudang],
                ['quantity' => 0]
            );
            $adj->details()->create([
                'id_part' => $item['id_part'],
                'stok_sistem' => $stokLokasi->quantity,
                'stok_fisik' => $item['stok_fisik'],
                'harga_satuan' => $part->harga_pokok ?? 0,
            ]);
        }
        return redirect()->route('adjustment.index')->with('success', 'Dokumen adjustment berhasil dibuat & menunggu diajukan.');
    }

    public function submitApproval(StockAdjustment $adjustment)
    {
        $this->authorize('access', ['adjustment', 'update']);
        $adjustment->status_adjustment = 'pending_approval';
        $adjustment->save();
        return redirect()->route('adjustment.index')->with('success', 'Adjustment berhasil diajukan untuk persetujuan.');
    }
            foreach ($request->details as $item) {
                $part = Part::find($item['id_part']);
                $stokLokasi = StokLokasi::firstOrCreate(
                    ['id_part' => $item['id_part'], 'id_gudang' => $request->id_gudang],
                    ['quantity' => 0]
                );
                $adj->details()->create([
                    'id_part' => $item['id_part'],
                    'stok_sistem' => $stokLokasi->quantity,
                    'stok_fisik' => $item['stok_fisik'],
                    'harga_satuan' => $part->harga_pokok ?? 0,
                ]);
            }
            DB::commit();
            return redirect()->route('adjustment.index')->with('success', 'Dokumen adjustment berhasil dibuat & menunggu diajukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function submitApproval(StockAdjustment $adjustment)
    {
        $this->authorize('access', ['adjustment', 'update']);
        $adjustment->status_adjustment = 'pending_approval';
        $adjustment->save();
        return redirect()->route('adjustment.index')->with('success', 'Adjustment berhasil diajukan untuk persetujuan.');
    }

    public function approve(StockAdjustment $adjustment)
    {
        $this->authorize('access', ['adjustment', 'update']);
        $rule = ApprovalLevel::where('jenis_dokumen', 'adjustment')->first();
        if (!$rule || auth()->user()->karyawan->id_jabatan != $rule->id_jabatan_required) {
            return redirect()->back()->with('error', 'Anda tidak berhak menyetujui dokumen ini.');
        }

        DB::beginTransaction();
        try {
            // LOGIKA PERUBAHAN STOK DIPINDAHKAN KE SINI
            foreach($adjustment->details as $detail) {
                $selisih = $detail->stok_fisik - $detail->stok_sistem;
                $stokLokasi = StokLokasi::where('id_part', $detail->id_part)->where('id_gudang', $adjustment->id_gudang)->first();
                $stokLokasi->quantity = $detail->stok_fisik;
                $stokLokasi->save();
                
                $this->_updateStockSummary($detail->id_part);

                KartuStok::create(['id_part' => $detail->id_part, 'id_gudang' => $adjustment->id_gudang, 'jenis_transaksi' => 'adjustment', 'referensi_dokumen' => $adjustment->nomor_adjustment, 'referensi_id' => $adjustment->id_adjustment, 'masuk' => $selisih > 0 ? $selisih : 0, 'keluar' => $selisih < 0 ? abs($selisih) : 0, 'kondisi_stok' => 'baik', 'created_by' => auth()->id()]);
            }

            $adjustment->status_adjustment = 'completed';
            $adjustment->approved_by = auth()->id();
            $adjustment->save();

            ApprovalHistory::create(['jenis_dokumen' => 'adjustment', 'id_dokumen' => $adjustment->id_adjustment, 'id_approver' => auth()->id(), 'status_approval' => 'approved', 'keterangan' => 'Disetujui', 'tanggal_approval' => now()]);
            
            DB::commit();
            return redirect()->route('adjustment.index')->with('success', 'Stock Adjustment berhasil disetujui dan stok telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function getDetailsJson(StockAdjustment $adjustment)
    {
        $this->authorize('access', ['adjustment', 'read']);
        $adjustment->load(['gudang', 'details.part']);
        $rule = ApprovalLevel::where('jenis_dokumen', 'adjustment')->first();
        $adjustment->id_jabatan_required = $rule ? $rule->id_jabatan_required : null;
        return response()->json($adjustment);
    }

    public function getStockSistem(Request $request)
    {
        $this->authorize('access', ['adjustment', 'read']);
        $request->validate(['part_id' => 'required|integer', 'gudang_id' => 'required|integer']);
        $stok = StokLokasi::where('id_part', $request->part_id)->where('id_gudang', $request->gudang_id)->first();
        return response()->json(['stok' => $stok->quantity ?? 0]);
    }

    private function _updateStockSummary(int $partId)
    {
        $totalStok = StokLokasi::where('id_part', $partId)->sum('quantity');
        $totalStokRusak = StokLokasi::where('id_part', $partId)->sum('quantity_rusak');
        $totalStokKarantina = StokLokasi::where('id_part', $partId)->sum('quantity_quarantine');

        StokSummary::updateOrCreate(
            ['id_part' => $partId],
            [
                'stok_tersedia' => $totalStok,
                'stok_rusak' => $totalStokRusak,
                'stok_quarantine' => $totalStokKarantina,
                'stok_total' => $totalStok + $totalStokRusak + $totalStokKarantina,
                'last_updated' => now()
            ]
        );
    }
}