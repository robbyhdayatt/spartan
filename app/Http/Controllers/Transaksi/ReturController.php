<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturRequest;
use App\Models\Master\StokLokasi;
use App\Models\Master\StokSummary;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\Pembelian;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReturController extends Controller
{
    public function index()
    {
        $returs = Retur::with(['konsumen', 'supplier'])->latest()->paginate(10);
        $penjualans = Penjualan::whereIn('status_penjualan', ['processed', 'delivered', 'completed'])->get();
        $pembelians = Pembelian::whereIn('status_pembelian', ['received', 'partial_received'])->get();
        return view('transaksi.retur.index', compact('returs', 'penjualans', 'pembelians'));
    }

    public function store(StoreReturRequest $request)
    {
        DB::beginTransaction();
        try {
            $nomorRetur = 'RET-' . date('Ymd') . '-' . Str::random(4);
            $gudangId = 1; // Asumsi retur dari/ke gudang utama

            // LOGIKA UNTUK RETUR PENJUALAN
            if ($request->tipe_retur == 'retur_jual') {
                $penjualan = Penjualan::findOrFail($request->id_dokumen);
                $retur = Retur::create([
                    'nomor_retur' => $nomorRetur, 'tipe_retur' => 'retur_jual', 'id_konsumen' => $penjualan->id_konsumen,
                    'id_penjualan' => $penjualan->id_penjualan, 'tanggal_retur' => $request->tanggal_retur,
                    'alasan' => $request->alasan, 'status_retur' => 'completed', 'created_by' => auth()->id(),
                ]);

                foreach ($request->details as $item) {
                    $detailPenjualan = $penjualan->details()->where('id_part', $item['id_part'])->firstOrFail();
                    $retur->details()->create(['id_part' => $item['id_part'], 'quantity' => $item['quantity'], 'harga_satuan' => $detailPenjualan->harga_satuan, 'subtotal' => $item['quantity'] * $detailPenjualan->harga_satuan, 'kondisi_barang' => $item['kondisi_barang'],]);
                    // Tambah stok kembali
                    $stokLokasi = StokLokasi::firstOrCreate(['id_part' => $item['id_part'], 'id_gudang' => $gudangId]);
                    if ($item['kondisi_barang'] == 'baik') {
                        $stokLokasi->quantity += $item['quantity'];
                    } else {
                        $stokLokasi->quantity_rusak += $item['quantity'];
                    }
                    $stokLokasi->save();
                    $this->_updateStockSummary($item['id_part']);
                    KartuStok::create(['id_part' => $item['id_part'], 'id_gudang' => $gudangId, 'jenis_transaksi' => 'retur_jual', 'referensi_dokumen' => $nomorRetur, 'masuk' => $item['quantity'], 'kondisi_stok' => $item['kondisi_barang']]);
                }
            }

            // LOGIKA UNTUK RETUR PEMBELIAN
            if ($request->tipe_retur == 'retur_beli') {
                $pembelian = Pembelian::findOrFail($request->id_dokumen);
                $retur = Retur::create([
                    'nomor_retur' => $nomorRetur, 'tipe_retur' => 'retur_beli', 'id_supplier' => $pembelian->id_supplier,
                    'id_pembelian' => $pembelian->id_pembelian, 'tanggal_retur' => $request->tanggal_retur,
                    'alasan' => $request->alasan, 'status_retur' => 'completed', 'created_by' => auth()->id(),
                ]);

                foreach ($request->details as $item) {
                    $detailPembelian = $pembelian->details()->where('id_part', $item['id_part'])->firstOrFail();
                    $retur->details()->create(['id_part' => $item['id_part'], 'quantity' => $item['quantity'], 'harga_satuan' => $detailPembelian->harga_satuan, 'subtotal' => $item['quantity'] * $detailPembelian->harga_satuan, 'kondisi_barang' => $item['kondisi_barang'],]);

                    // Kurangi stok (asumsi retur ke supplier adalah dari stok rusak hasil QC)
                    $stokLokasi = StokLokasi::where('id_part', $item['id_part'])->where('id_gudang', $gudangId)->first();
                    if(!$stokLokasi || $stokLokasi->quantity_rusak < $item['quantity']) {
                        $part = \App\Models\Master\Part::find($item['id_part']);
                        throw new \Exception("Stok rusak untuk part '{$part->nama_part}' tidak mencukupi untuk diretur.");
                    }
                    $stokLokasi->quantity_rusak -= $item['quantity'];
                    $stokLokasi->save();
                    $this->_updateStockSummary($item['id_part']);
                    KartuStok::create(['id_part' => $item['id_part'], 'id_gudang' => $gudangId, 'jenis_transaksi' => 'retur_beli', 'referensi_dokumen' => $nomorRetur, 'keluar' => $item['quantity'], 'kondisi_stok' => 'rusak']);
                }
            }

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Data retur berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    // Endpoint AJAX untuk mengambil item dari dokumen penjualan/pembelian
    public function getItemsForReturn(Request $request)
    {
        $request->validate(['id' => 'required|integer', 'type' => 'required|string']);
        if ($request->type == 'penjualan') {
            $doc = Penjualan::with('details.part')->find($request->id);
        } else {
            $doc = Pembelian::with('details.part')->find($request->id);
        }
        return response()->json($doc ? $doc->details : []);
    }
    public function getDetailsJson(Retur $retur)
    {
        $retur->load(['konsumen', 'supplier', 'details.part']);
        return response()->json($retur);
    }

    // Fungsi helper update summary
    private function _updateStockSummary(int $partId)
    {
        $stokTersedia = StokLokasi::where('id_part', $partId)->sum('quantity');
        $stokRusak = StokLokasi::where('id_part', $partId)->sum('quantity_rusak');
        StokSummary::updateOrCreate(['id_part' => $partId], ['stok_tersedia' => $stokTersedia, 'stok_rusak' => $stokRusak, 'stok_total' => $stokTersedia + $stokRusak, 'last_updated' => now()]);
    }
}