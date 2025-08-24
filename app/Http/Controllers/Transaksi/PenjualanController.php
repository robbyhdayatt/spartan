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

class PenjualanController extends Controller
{
    public function index()
    {
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
        DB::beginTransaction();
        try {
            // Asumsi penjualan dilakukan dari gudang utama (ID 1), sesuaikan jika perlu
            $gudangId = 1; 

            // 1. Validasi Stok
            foreach ($request->details as $detail) {
                $stok = StokLokasi::where('id_part', $detail['id_part'])->where('id_gudang', $gudangId)->first();
                if (!$stok || $stok->quantity < $detail['quantity']) {
                    $part = Part::find($detail['id_part']);
                    throw new \Exception("Stok untuk part '{$part->nama_part}' tidak mencukupi di gudang utama.");
                }
            }

            // 2. Hitung Total
            $subtotal = 0;
            foreach ($request->details as $detail) {
                $subtotal += $detail['quantity'] * $detail['harga_satuan'];
            }
            $ppnAmount = $subtotal * 0.11;
            $totalAmount = $subtotal + $ppnAmount;

            // 3. Simpan Header Penjualan
            $penjualan = Penjualan::create([
                'nomor_invoice' => 'INV-' . date('Ymd') . '-' . Str::random(4),
                'id_konsumen' => $request->id_konsumen,
                'id_sales' => $request->id_sales,
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'jenis_penjualan' => $request->jenis_penjualan,
                'status_penjualan' => 'processed', // Anggap langsung diproses
                'status_pembayaran' => 'unpaid',
                'subtotal' => $subtotal,
                'ppn_amount' => $ppnAmount,
                'total_amount' => $totalAmount,
                'created_by' => auth()->id(),
            ]);

            // 4. Simpan Detail & Kurangi Stok
            foreach ($request->details as $detail) {
                // Hitung subtotal untuk baris ini
                $subtotal_detail = $detail['quantity'] * $detail['harga_satuan'];

                // Simpan detail, gabungkan data dari form dengan subtotal yang dihitung
                $penjualan->details()->create(array_merge($detail, [
                    'subtotal' => $subtotal_detail,
                    'total_after_diskon' => $subtotal_detail // Asumsi belum ada diskon
                ]));

                // Kurangi Stok Lokasi
                $stokLokasi = StokLokasi::where('id_part', $detail['id_part'])->where('id_gudang', $gudangId)->first();
                $stokLokasi->quantity -= $detail['quantity'];
                $stokLokasi->save();

                $this->_updateStockSummary($detail['id_part']);

                // Catat di Kartu Stok
                KartuStok::create([
                    'id_part' => $detail['id_part'],
                    'id_gudang' => $gudangId,
                    'jenis_transaksi' => 'penjualan',
                    'referensi_dokumen' => $penjualan->nomor_invoice,
                    'referensi_id' => $penjualan->id_penjualan,
                    'keluar' => $detail['quantity'],
                    'kondisi_stok' => 'baik',
                    'created_by' => auth()->id(),
                ]);
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
        $penjualan->load(['konsumen', 'sales', 'details.part']);
        return response()->json($penjualan);
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