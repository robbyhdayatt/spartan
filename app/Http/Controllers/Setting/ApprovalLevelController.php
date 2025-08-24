<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\ApprovalLevel;
use App\Models\Master\Jabatan; // Import model Jabatan
use App\Http\Requests\StoreApprovalLevelRequest;
use App\Http\Requests\UpdateApprovalLevelRequest;

class ApprovalLevelController extends Controller
{
    /**
     * Menampilkan daftar aturan approval.
     */
    public function index()
    {
        $approvalLevels = ApprovalLevel::with('jabatan')->latest()->paginate(10);
        $jabatans = Jabatan::where('status_aktif', 1)->get(); // Data untuk dropdown di modal

        return view('setting.approval.index', compact('approvalLevels', 'jabatans'));
    }

    /**
     * Menyimpan aturan approval baru dari modal.
     */
    public function store(StoreApprovalLevelRequest $request)
    {
        ApprovalLevel::create($request->validated());
        return redirect()->route('approval-levels.index')->with('success', 'Aturan approval berhasil ditambahkan.');
    }

    /**
     * Memperbarui aturan approval dari modal.
     */
    public function update(UpdateApprovalLevelRequest $request, ApprovalLevel $approvalLevel)
    {
        $approvalLevel->update($request->validated());
        return redirect()->route('approval-levels.index')->with('success', 'Aturan approval berhasil diperbarui.');
    }

    /**
     * Menghapus aturan approval.
     */
    public function destroy(ApprovalLevel $approvalLevel)
    {
        $approvalLevel->delete();
        return redirect()->route('approval-levels.index')->with('success', 'Aturan approval berhasil dihapus.');
    }

    // Method create(), show(), dan edit() tidak kita gunakan karena sudah memakai modal.
}