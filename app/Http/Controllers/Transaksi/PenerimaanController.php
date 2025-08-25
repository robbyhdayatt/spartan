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
use App\Models\Master\StokLokasi;
use App\Models\Transaksi\KartuStok;
use App\Models\Master\StokSummary;

class PenerimaanController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['penerimaan', 'read']);
        $penerimaans = Penerimaan::with(['pembelian', 'supplier', 'gudang'])->latest()->paginate(10);
        return view('transaksi.penerimaan.index', compact('penerimaans'));
    }

    public function create(Request $request)
    {
        $this->authorize('access', ['penerimaan', 'create']);
        $pembelian = Pembelian::with('details.part')->findOrFail($request->po_id);
        $gudangs = Gudang::where('status_aktif', 1)->get();

        return view('transaksi.penerimaan.create', compact('pembelian', 'gudangs'));
    }

    public function store(StorePenerimaanRequest $request)
    {
        $this->authorize('access', ['penerimaan', 'create']);
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
    public function showQcForm(Penerimaan $penerimaan)
    {
        $this->authorize('access', ['penerimaan', 'update']);
        // Hanya proses dokumen yang statusnya 'checking'
        if ($penerimaan->status_penerimaan !== 'checking') {
            return redirect()->route('penerimaan.index')->with('error', 'Dokumen penerimaan ini sudah diproses atau dibatalkan.');
        }

        $penerimaan->load('details.part');
        return view('transaksi.penerimaan.qc', compact('penerimaan'));
    }

    public function processQc(Request $request, Penerimaan $penerimaan)
    {
        $this->authorize('access', ['penerimaan', 'update']);
        // Validasi sederhana
        $request->validate([
            'details' => 'required|array',
            'details.*.qty_approved' => 'required|integer|min:0',
            'details.*.qty_rejected' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalApproved = 0;
            $totalRejected = 0;

            foreach ($request->details as $id_detail => $data) {
                $detail = $penerimaan->details()->findOrFail($id_detail);

                if (($data['qty_approved'] + $data['qty_rejected']) > $detail->qty_diterima) {
                    throw new \Exception("Jumlah approved & rejected untuk part {$detail->part->nama_part} melebihi jumlah diterima.");
                }

                // 1. Update Detail Penerimaan (kode ini tetap sama)
                $detail->qty_approved = $data['qty_approved'];
                $detail->qty_rejected = $data['qty_rejected'];
                $detail->qc_notes = $data['qc_notes'];
                $detail->status_qc = 'passed';
                $detail->save();

                $totalApproved += $detail->qty_approved;
                $totalRejected += $detail->qty_rejected;

                // 2. Update Stok & Kartu Stok (kode ini tetap sama)
                if ($detail->qty_approved > 0) {
                    $stokLokasi = StokLokasi::firstOrNew(['id_part' => $detail->id_part, 'id_gudang' => $penerimaan->id_gudang_tujuan]);
                    $stokLokasi->quantity += $detail->qty_approved;
                    $stokLokasi->save();
                    KartuStok::create([ 'id_part' => $detail->id_part, 'id_gudang' => $penerimaan->id_gudang_tujuan, 'jenis_transaksi' => 'qc_approved', 'referensi_dokumen' => $penerimaan->nomor_penerimaan, 'referensi_id' => $penerimaan->id_penerimaan, 'masuk' => $detail->qty_approved, 'kondisi_stok' => 'baik', 'created_by' => auth()->id(), ]);
                }
                if ($detail->qty_rejected > 0) {
                    $stokLokasi = StokLokasi::firstOrNew(['id_part' => $detail->id_part, 'id_gudang' => $penerimaan->id_gudang_tujuan]);
                    $stokLokasi->quantity_rusak += $detail->qty_rejected;
                    $stokLokasi->save();
                }
                $this->_updateStockSummary($detail->id_part);
            }

            // 3. Update Header Penerimaan (kode ini tetap sama)
            $penerimaan->total_qty_approved = $totalApproved;
            $penerimaan->total_qty_rejected = $totalRejected;
            $penerimaan->status_penerimaan = 'completed';
            $penerimaan->qc_by = auth()->user()->id_karyawan;
            $penerimaan->qc_date = now();
            $penerimaan->save();

            $pembelian = $penerimaan->pembelian()->with('details')->first();
            if ($pembelian) {
                $totalDipesan = $pembelian->details->sum('quantity');
                $totalDiterima = $pembelian->details->sum('qty_received');

                if ($totalDiterima >= $totalDipesan) {
                    $pembelian->status_pembelian = 'received'; // Atau 'completed' jika ada logika pembayaran
                } else {
                    $pembelian->status_pembelian = 'partial_received';
                }
                $pembelian->save();
            }

            DB::commit();
            return redirect()->route('penerimaan.index')->with('success', 'Proses QC & Finalisasi Penerimaan berhasil.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function _updateStockSummary(int $partId)
    {
        // Hitung total dari semua gudang untuk part ini
        $totalStok = StokLokasi::where('id_part', $partId)->sum('quantity');
        $totalStokRusak = StokLokasi::where('id_part', $partId)->sum('quantity_rusak');
        $totalStokKarantina = StokLokasi::where('id_part', $partId)->sum('quantity_quarantine');

        // Gunakan updateOrCreate untuk membuat atau memperbarui record
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
    public function getDetailsJson(Penerimaan $penerimaan)
    {
        $this->authorize('access', ['penerimaan', 'read']);
        $penerimaan->load(['supplier', 'pembelian', 'gudang', 'details.part']);
        return response()->json($penerimaan);
    }
}
