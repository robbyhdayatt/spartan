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
        $this->authorize('access', ['approval-levels', 'read']);
        $approvalLevels = ApprovalLevel::with('jabatan')->latest()->paginate(10);
        $jabatans = Jabatan::where('status_aktif', 1)->get(); // Data untuk dropdown di modal

        return view('setting.approval.index', compact('approvalLevels', 'jabatans'));
    }

    /**
     * Menyimpan aturan approval baru dari modal.
     */
    public function store(StoreApprovalLevelRequest $request)
    {
        $this->authorize('access', ['approval-levels', 'create']);
        ApprovalLevel::create($request->validated());
        return redirect()->route('approval-levels.index')->with('success', 'Aturan approval berhasil ditambahkan.');
    }

    /**
     * Memperbarui aturan approval dari modal.
     */
    public function update(UpdateApprovalLevelRequest $request, ApprovalLevel $approvalLevel)
    {
        $this->authorize('access', ['approval-levels', 'update']);
        $approvalLevel->update($request->validated());
        return redirect()->route('approval-levels.index')->with('success', 'Aturan approval berhasil diperbarui.');
    }

    /**
     * Menghapus aturan approval.
     */
    public function destroy(ApprovalLevel $approvalLevel)
    {
        $this->authorize('access', ['approval-levels', 'delete']);
        $approvalLevel->delete();
        return redirect()->route('approval-levels.index')->with('success', 'Aturan approval berhasil dihapus.');
    }
}