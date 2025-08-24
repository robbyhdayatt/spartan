<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Models\Master\Gudang;
use App\Models\Master\Part;
use App\Models\Master\StokLokasi;
use App\Models\Master\StokSummary;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = StockAdjustment::with('gudang')->latest()->paginate(10);
        $gudangs = Gudang::where('status_aktif', 1)->get();
        $parts = Part::where('status_aktif', 1)->get();
        return view('transaksi.adjustment.index', compact('adjustments', 'gudangs', 'parts'));
    }

    public function store(StoreStockAdjustmentRequest $request)
    {
        DB::beginTransaction();
        try {
            // Buat header adjustment
            $adj = StockAdjustment::create([
                'nomor_adjustment' => 'ADJ-' . date('Ymd') . '-' . Str::random(4),
                'id_gudang' => $request->id_gudang,
                'tanggal_adjustment' => $request->tanggal_adjustment,
                'jenis_adjustment' => $request->jenis_adjustment,
                'status_adjustment' => 'completed', // Langsung complete untuk saat ini
                'keterangan' => $request->keterangan,
                'created_by' => auth()->id(),
            ]);

            // Loop detail, simpan, dan update stok
            foreach ($request->details as $item) {
                $part = Part::find($item['id_part']);
                $stokLokasi = StokLokasi::firstOrCreate(
                    ['id_part' => $item['id_part'], 'id_gudang' => $request->id_gudang],
                    ['quantity' => 0]
                );

                $selisih = $item['stok_fisik'] - $stokLokasi->quantity;

                // Simpan detail adjustment
                $adj->details()->create([
                    'id_part' => $item['id_part'],
                    'stok_sistem' => $stokLokasi->quantity,
                    'stok_fisik' => $item['stok_fisik'],
                    'harga_satuan' => $part->harga_pokok ?? 0,
                ]);

                // Update Stok Lokasi
                $stokLokasi->quantity = $item['stok_fisik']; // Langsung set ke stok fisik
                $stokLokasi->save();

                // Update Summary
                $this->_updateStockSummary($item['id_part']);

                // Catat di Kartu Stok
                KartuStok::create([
                    'id_part' => $item['id_part'],
                    'id_gudang' => $request->id_gudang,
                    'jenis_transaksi' => 'adjustment',
                    'referensi_dokumen' => $adj->nomor_adjustment,
                    'referensi_id' => $adj->id_adjustment,
                    'masuk' => $selisih > 0 ? $selisih : 0,
                    'keluar' => $selisih < 0 ? abs($selisih) : 0,
                    'kondisi_stok' => 'baik',
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();
            return redirect()->route('adjustment.index')->with('success', 'Stock Adjustment berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    // Endpoint untuk AJAX
    public function getStockSistem(Request $request)
    {
        $request->validate(['part_id' => 'required|integer', 'gudang_id' => 'required|integer']);

        $stok = StokLokasi::where('id_part', $request->part_id)
                          ->where('id_gudang', $request->gudang_id)
                          ->first();

        return response()->json(['stok' => $stok->quantity ?? 0]);
    }

    // Fungsi helper untuk update summary
    private function _updateStockSummary(int $partId)
    {
        $totalStok = StokLokasi::where('id_part', $partId)->sum('quantity');
        $totalStokRusak = StokLokasi::where('id_part', $partId)->sum('quantity_rusak');
        StokSummary::updateOrCreate(['id_part' => $partId], ['stok_tersedia' => $totalStok, 'stok_rusak' => $totalStokRusak, 'stok_total' => $totalStok + $totalStokRusak, 'last_updated' => now()]);
    }
}