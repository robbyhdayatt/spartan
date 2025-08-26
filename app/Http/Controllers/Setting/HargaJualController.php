<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\HargaJual;
use App\Models\Master\Part;
use App\Models\Master\Konsumen;
use App\Http\Requests\StoreHargaJualRequest;
use App\Http\Requests\UpdateHargaJualRequest;

class HargaJualController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['settings.harga-jual', 'read']);
        $hargaJuals = HargaJual::with(['part', 'konsumen'])->latest()->paginate(15);
        $parts = Part::where('status_aktif', 1)->get();
        $konsumens = Konsumen::where('status_aktif', 1)->get();

        return view('setting.harga_jual.index', compact('hargaJuals', 'parts', 'konsumens'));
    }

    public function store(StoreHargaJualRequest $request)
    {
        $this->authorize('access', ['settings.harga-jual', 'create']);
        HargaJual::create($request->validated());
        return redirect()->route('harga-jual.index')->with('success', 'Aturan harga jual berhasil ditambahkan.');
    }

    public function update(UpdateHargaJualRequest $request, HargaJual $hargaJual)
    {
        $this->authorize('access', ['settings.harga-jual', 'update']);
        $hargaJual->update($request->validated());
        return redirect()->route('harga-jual.index')->with('success', 'Aturan harga jual berhasil diperbarui.');
    }

    public function destroy(HargaJual $hargaJual)
    {
        $this->authorize('access', ['settings.harga-jual', 'delete']);
        $hargaJual->delete();
        return redirect()->route('harga-jual.index')->with('success', 'Aturan harga jual berhasil dihapus.');
    }
}