<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenerimaanRequest;
use App\Models\Master\Gudang;
use App\Models\Transaksi\Pembelian;
use App\Models\Transaksi\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenerimaanController extends Controller
{
    public function index()
    {
        $penerimaans = Penerimaan::with(['pembelian', 'supplier', 'gudang'])->latest()->paginate(10);
        return view('transaksi.penerimaan.index', compact('penerimaans'));
    }

    public function create(Request $request)
    {
        $pembelian = Pembelian::with('details.part')->findOrFail($request->po_id);
        $gudangs = Gudang::where('status_aktif', 1)->get();

        return view('transaksi.penerimaan.create', compact('pembelian', 'gudangs'));
    }

    public function store(StorePenerimaanRequest $request)
    {
        DB::beginTransaction();
        try {
            $pembelian = Pembelian::with('details')->findOrFail($request->id_pembelian);

            // 1. Buat Header Penerimaan
            $penerimaan = Penerimaan::create([
                'nomor_penerimaan' => 'RCV-' . date('Ymd') . '-' . Str::random(4),
                'id_pembelian' => $pembelian->id_pembelian,
                'id_supplier' => $pembelian->id_supplier,
                'tanggal_penerimaan' => $request->tanggal_penerimaan,
                'id_gudang_tujuan' => $request->id_gudang_tujuan,
                'nomor_surat_jalan' => $request->nomor_surat_jalan,
                'status_penerimaan' => 'checking', // Status awal
                'pic_penerima' => auth()->user()->id_karyawan,
            ]);

            // 2. Simpan Detail Penerimaan & Update Qty di Detail PO
            foreach ($request->details as $item) {
                if($item['qty_diterima'] > 0) { // Hanya proses item yang diterima
                    $penerimaan->details()->create($item);

                    // Update qty_received di detail pembelian
                    $detailPembelian = $pembelian->details()->find($item['id_detail_pembelian']);
                    $detailPembelian->qty_received += $item['qty_diterima'];
                    $detailPembelian->save();
                }
            }

            // 3. Update Status Header PO
            $totalDipesan = $pembelian->details->sum('quantity');
            $totalDiterima = $pembelian->details->sum('qty_received');

            if ($totalDiterima >= $totalDipesan) {
                $pembelian->status_pembelian = 'received';
            } else {
                $pembelian->status_pembelian = 'partial_received';
            }
            $pembelian->save();

            DB::commit();
            return redirect()->route('penerimaan.index')->with('success', 'Data penerimaan barang berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    public function getDetailsJson(Penerimaan $penerimaan)
    {
        $penerimaan->load(['supplier', 'pembelian', 'gudang', 'details.part']);
        return response()->json($penerimaan);
    }
}
