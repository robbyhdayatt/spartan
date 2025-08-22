<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePembelianRequest;
use App\Models\Master\Part;
use App\Models\Master\Supplier;
use App\Models\Transaksi\Pembelian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('supplier')->latest()->paginate(10);
        $suppliers = Supplier::where('status_aktif', 1)->get();
        $parts = Part::where('status_aktif', 1)->get();

        return view('transaksi.pembelian.index', compact('pembelians', 'suppliers', 'parts'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status_aktif', 1)->get();
        $parts = Part::where('status_aktif', 1)->get();
        return view('transaksi.pembelian.create', compact('suppliers', 'parts'));
    }

    public function store(StorePembelianRequest $request)
    {
        DB::beginTransaction();
        try {
            // 1. Hitung Total dari Details
            $subtotal = 0;
            foreach ($request->details as $detail) {
                $subtotal += $detail['quantity'] * $detail['harga_satuan'];
            }
            $ppnAmount = $subtotal * 0.11; // Asumsi PPN 11%
            $totalAmount = $subtotal + $ppnAmount;

            // 2. Simpan Data Header Pembelian
            $pembelian = Pembelian::create([
                'nomor_po' => 'PO-' . date('Ymd') . '-' . Str::random(4),
                'id_supplier' => $request->id_supplier,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'status_pembelian' => 'draft',
                'subtotal' => $subtotal,
                'ppn_amount' => $ppnAmount,
                'total_amount' => $totalAmount,
                'created_by' => auth()->id(),
            ]);

            // 3. Simpan Data Detail Pembelian
            foreach ($request->details as $detail) {
                $pembelian->details()->create([
                    'id_part' => $detail['id_part'],
                    'quantity' => $detail['quantity'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'subtotal' => $detail['quantity'] * $detail['harga_satuan'],
                ]);
            }

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Purchase Order berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Tampilkan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    public function show(Pembelian $pembelian)
    {
        // Eager load relasi untuk menghindari N+1 query problem
        $pembelian->load(['supplier', 'details.part']);

        return view('transaksi.pembelian.show', compact('pembelian'));
    }
    public function getDetailsJson(Pembelian $pembelian)
    {
        // Eager load semua relasi yang dibutuhkan
        $pembelian->load(['supplier', 'details.part']);

        // Kembalikan data dalam format JSON
        return response()->json($pembelian);
    }
}
