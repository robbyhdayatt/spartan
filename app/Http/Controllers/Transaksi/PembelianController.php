<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePembelianRequest;
use App\Models\Master\Part;
use App\Models\Master\Supplier;
use App\Models\Setting\ApprovalLevel;
use App\Models\Transaksi\ApprovalHistory;
use App\Models\Transaksi\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembelianController extends Controller
{
    
    public function index()
    {
        $this->authorize('access', ['pembelian', 'read']);
        $pembelians = Pembelian::with('supplier')->latest()->paginate(10);
        $suppliers = Supplier::where('status_aktif', 1)->get();
        $parts = Part::where('status_aktif', 1)->get();
        
        return view('transaksi.pembelian.index', compact('pembelians', 'suppliers', 'parts'));
    }

    public function store(StorePembelianRequest $request)
    {
        $this->authorize('access', ['pembelian', 'create']);
        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->details as $detail) {
                $subtotal += $detail['quantity'] * $detail['harga_satuan'];
            }
            $ppnAmount = $subtotal * 0.11;
            $totalAmount = $subtotal + $ppnAmount;

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
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function submitApproval(Pembelian $pembelian)
    {
        $this->authorize('access', ['pembelian', 'update']);
        if ($pembelian->status_pembelian !== 'draft') {
            return redirect()->back()->with('error', 'Hanya PO berstatus DRAFT yang bisa diajukan.');
        }

        $pembelian->status_pembelian = 'pending_approval';
        $pembelian->save();

        return redirect()->route('pembelian.index')->with('success', 'PO berhasil diajukan untuk persetujuan.');
    }

    private function getApprovalRule(Pembelian $pembelian)
    {
        return ApprovalLevel::where('jenis_dokumen', 'pembelian')
                            ->where('minimum_amount', '<=', $pembelian->total_amount)
                            ->orderBy('minimum_amount', 'desc')
                            ->first();
    }

    public function approve(Pembelian $pembelian)
    {
        $this->authorize('access', ['pembelian', 'update']);
        $correctRule = $this->getApprovalRule($pembelian);
        $userJabatanId = auth()->user()->karyawan->id_jabatan;

        if (!$correctRule || $userJabatanId != $correctRule->id_jabatan_required || $pembelian->status_pembelian !== 'pending_approval') {
            return redirect()->route('pembelian.index')->with('error', 'Anda tidak memiliki hak untuk menyetujui dokumen ini.');
        }

        DB::beginTransaction();
        try {
            $pembelian->status_pembelian = 'approved';
            $pembelian->status_approval = 'approved';
            $pembelian->approved_by = auth()->id();
            $pembelian->save();
            
            ApprovalHistory::create(['jenis_dokumen' => 'pembelian', 'id_dokumen' => $pembelian->id_pembelian, 'id_approver' => auth()->id(), 'status_approval' => 'approved', 'keterangan' => 'Disetujui', 'tanggal_approval' => now()]);
            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'PO berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pembelian.index')->with('error', 'Gagal menyetujui PO: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Pembelian $pembelian)
    {
        $this->authorize('access', ['pembelian', 'update']);
        $request->validate(['keterangan' => 'required|string|max:255']);
        $correctRule = $this->getApprovalRule($pembelian);
        $userJabatanId = auth()->user()->karyawan->id_jabatan;

        if (!$correctRule || $userJabatanId != $correctRule->id_jabatan_required || $pembelian->status_pembelian !== 'pending_approval') {
            return redirect()->route('pembelian.index')->with('error', 'Anda tidak memiliki hak untuk menolak dokumen ini.');
        }
        
        DB::beginTransaction();
        try {
            $pembelian->status_pembelian = 'rejected';
            $pembelian->status_approval = 'rejected';
            $pembelian->save();
            
            ApprovalHistory::create(['jenis_dokumen' => 'pembelian', 'id_dokumen' => $pembelian->id_pembelian, 'id_approver' => auth()->id(), 'status_approval' => 'rejected', 'keterangan' => $request->keterangan, 'tanggal_approval' => now()]);
            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'PO berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pembelian.index')->with('error', 'Gagal menolak PO: ' . $e->getMessage());
        }
    }

    public function getDetailsJson(Pembelian $pembelian)
    {
        $this->authorize('access', ['pembelian', 'read']);
        $pembelian->load(['supplier', 'details.part']);
        $correctRule = $this->getApprovalRule($pembelian);
        $pembelian->id_jabatan_required = $correctRule ? $correctRule->id_jabatan_required : null;
        return response()->json($pembelian);
    }

}