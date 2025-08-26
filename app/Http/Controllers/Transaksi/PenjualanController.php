<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenjualanRequest;
use App\Models\Master\Konsumen;
use App\Models\Master\Karyawan;
use App\Models\Master\Part;
use App\Models\Master\StokLokasi;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Master\StokSummary;
use Illuminate\Http\Request; 
use App\Models\Setting\HargaJual;

class PenjualanController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['penjualan', 'read']);
        $penjualans = Penjualan::with(['konsumen', 'sales'])->latest()->paginate(10);
        $konsumens = Konsumen::where('status_aktif', 1)->get();
        $salespersons = Karyawan::where('status_aktif', 1)->whereHas('jabatan', function($q){
            $q->where('nama_jabatan', 'like', '%Sales%');
        })->get();
        $parts = Part::with('stok_summary')->where('status_aktif', 1)->get();

        return view('transaksi.penjualan.index', compact('penjualans', 'konsumens', 'salespersons', 'parts'));
    }

    public function store(StorePenjualanRequest $request)
    {
        $this->authorize('access', ['penjualan', 'create']);
        DB::beginTransaction();
        try {
            $gudangId = 1;

            foreach ($request->details as $detail) {
                $stok = StokLokasi::where('id_part', $detail['id_part'])->where('id_gudang', $gudangId)->first();
                if (!$stok || $stok->quantity < $detail['quantity']) {
                    $part = Part::find($detail['id_part']);
                    throw new \Exception("Stok untuk part '{$part->nama_part}' tidak mencukupi di gudang utama.");
                }
            }

            $subtotal = 0;
            foreach ($request->details as $detail) {
                $subtotal += $detail['quantity'] * $detail['harga_satuan'];
            }
            $ppnAmount = $subtotal * 0.11;
            $totalAmount = $subtotal + $ppnAmount;

            $penjualan = Penjualan::create([
                'nomor_invoice' => 'INV-' . date('Ymd') . '-' . Str::random(4),
                'id_konsumen' => $request->id_konsumen,
                'id_sales' => $request->id_sales,
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'jenis_penjualan' => $request->jenis_penjualan,
                'status_penjualan' => 'processed',
                'status_pembayaran' => 'unpaid',
                'subtotal' => $subtotal,
                'ppn_amount' => $ppnAmount,
                'total_amount' => $totalAmount,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->details as $detail) {
                // PERBAIKAN TYPO ADA DI SINI
                $subtotal_detail = $detail['quantity'] * $detail['harga_satuan']; 

                $penjualan->details()->create(array_merge($detail, [
                    'subtotal' => $subtotal_detail,
                    'total_after_diskon' => $subtotal_detail
                ]));
                
                $stokLokasi = StokLokasi::where('id_part', $detail['id_part'])->where('id_gudang', $gudangId)->first();
                $stokLokasi->quantity -= $detail['quantity'];
                $stokLokasi->save();

                $this->_updateStockSummary($detail['id_part']);

                KartuStok::create(['id_part' => $detail['id_part'], 'id_gudang' => $gudangId, 'jenis_transaksi' => 'penjualan', 'referensi_dokumen' => $penjualan->nomor_invoice, 'referensi_id' => $penjualan->id_penjualan, 'keluar' => $detail['quantity'], 'kondisi_stok' => 'baik', 'created_by' => auth()->id()]);
            }

            DB::commit();
            return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penjualan: ' . $e->getMessage())->withInput();
        }
    }

    public function getDetailsJson(Penjualan $penjualan)
    {
        $this->authorize('access', ['penjualan', 'read']);
        $penjualan->load(['konsumen', 'sales', 'details.part']);
        return response()->json($penjualan);
    }
    
    public function getHargaPart(Request $request)
    {
        $request->validate([
            'part_id' => 'required|integer|exists:part,id_part',
            'konsumen_id' => 'required|integer|exists:konsumen,id_konsumen'
        ]);

        $partId = $request->part_id;
        $konsumenId = $request->konsumen_id;

        // 1. Cari harga spesifik untuk konsumen & part ini
        $hargaSpesifik = HargaJual::where('id_part', $partId)
                                  ->where('id_konsumen', $konsumenId)
                                  ->where('status_aktif', 1)
                                  ->first();

        if ($hargaSpesifik) {
            return response()->json(['harga' => $hargaSpesifik->hed]);
        }

        // 2. Jika tidak ada, cari harga umum untuk part ini (tanpa konsumen spesifik)
        $hargaUmum = HargaJual::where('id_part', $partId)
                              ->whereNull('id_konsumen')
                              ->where('status_aktif', 1)
                              ->first();

        if ($hargaUmum) {
            return response()->json(['harga' => $hargaUmum->hed]);
        }

        // 3. Jika tidak ada harga yang diatur sama sekali, kembalikan harga pokok
        $part = \App\Models\Master\Part::find($partId);
        return response()->json(['harga' => $part->harga_pokok ?? 0]);
    }

    public function markAsDelivered(Penjualan $penjualan)
    {
        $this->authorize('access', ['penjualan', 'update']);
        if ($penjualan->status_penjualan == 'processed') {
            $penjualan->status_penjualan = 'delivered';
            $penjualan->save();
            return redirect()->back()->with('success', 'Status penjualan berhasil diubah menjadi Terkirim.');
        }
        return redirect()->back()->with('error', 'Hanya penjualan berstatus Processed yang bisa diubah menjadi Terkirim.');
    }

    public function markAsCompleted(Penjualan $penjualan)
    {
        $this->authorize('access', ['penjualan', 'update']);
        if (in_array($penjualan->status_penjualan, ['processed', 'delivered'])) {
            $penjualan->status_penjualan = 'completed';
            $penjualan->status_pembayaran = 'paid'; // Asumsi lunas saat completed
            $penjualan->save();
            return redirect()->back()->with('success', 'Status penjualan berhasil diubah menjadi Selesai.');
        }
        return redirect()->back()->with('error', 'Hanya penjualan berstatus Processed atau Delivered yang bisa diselesaikan.');
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