<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Karyawan;
use App\Models\Master\Jabatan;  // Import Jabatan
use App\Models\Master\Gudang;   // Import Gudang
use App\Http\Requests\StoreKaryawanRequest;
use App\Http\Requests\UpdateKaryawanRequest;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with(['jabatan', 'gudang'])->latest()->paginate(10);
        $jabatans = Jabatan::where('status_aktif', 1)->get();
        $gudangs = Gudang::where('status_aktif', 1)->get();

        return view('master.karyawan.index', compact('karyawans', 'jabatans', 'gudangs'));
    }

    public function store(StoreKaryawanRequest $request)
    {
        Karyawan::create($request->validated());
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function update(UpdateKaryawanRequest $request, Karyawan $karyawan)
    {
        $karyawan->update($request->validated());
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
