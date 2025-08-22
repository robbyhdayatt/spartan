<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Gudang;
use App\Models\Master\Karyawan; // Import model Karyawan
use App\Http\Requests\StoreGudangRequest;
use App\Http\Requests\UpdateGudangRequest;

class GudangController extends Controller
{
    public function index()
    {
        $gudangs = Gudang::with('pic')->latest()->paginate(10);
        // Ambil data karyawan untuk dropdown PIC
        $karyawans = Karyawan::where('status_aktif', 1)->get();
        return view('master.gudang.index', compact('gudangs', 'karyawans'));
    }

    public function store(StoreGudangRequest $request)
    {
        Gudang::create($request->validated());
        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function update(UpdateGudangRequest $request, Gudang $gudang)
    {
        $gudang->update($request->validated());
        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Gudang $gudang)
    {
        $gudang->delete();
        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil dihapus.');
    }
}
