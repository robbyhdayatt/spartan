<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Konsumen;
use App\Http\Requests\StoreKonsumenRequest;
use App\Http\Requests\UpdateKonsumenRequest;

class KonsumenController extends Controller
{
    public function index()
    {
        $konsumens = Konsumen::latest()->paginate(10);
        return view('master.konsumen.index', compact('konsumens'));
    }

    public function store(StoreKonsumenRequest $request)
    {
        Konsumen::create($request->validated());
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil ditambahkan.');
    }

    public function update(UpdateKonsumenRequest $request, Konsumen $konsuman)
    {
        $konsuman->update($request->validated());
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil diperbarui.');
    }

    public function destroy(Konsumen $konsuman)
    {
        $konsuman->delete();
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil dihapus.');
    }
}
