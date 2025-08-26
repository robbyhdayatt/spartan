<?php
namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Insentif;
use App\Models\Master\Jabatan;
use App\Models\Master\Part;
use App\Http\Requests\StoreInsentifRequest;

class InsentifController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['settings.insentif', 'read']);
        $insentifs = Insentif::with(['part', 'jabatan'])->latest()->paginate(15);
        $jabatans = Jabatan::where('status_aktif', 1)->get();
        $parts = Part::where('status_aktif', 1)->get();
        return view('setting.insentif.index', compact('insentifs', 'jabatans', 'parts'));
    }

    public function store(StoreInsentifRequest $request)
    {
        $this->authorize('access', ['settings.insentif', 'create']);
        Insentif::create($request->validated());
        return redirect()->route('insentif.index')->with('success', 'Program insentif berhasil ditambahkan.');
    }

    public function update(StoreInsentifRequest $request, Insentif $insentif)
    {
        $this->authorize('access', ['settings.insentif', 'update']);
        $insentif->update($request->validated());
        return redirect()->route('insentif.index')->with('success', 'Program insentif berhasil diperbarui.');
    }

    public function destroy(Insentif $insentif)
    {
        $this->authorize('access', ['settings.insentif', 'delete']);
        $insentif->delete();
        return redirect()->route('insentif.index')->with('success', 'Program insentif berhasil dihapus.');
    }
}