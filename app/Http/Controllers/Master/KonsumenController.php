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
        $this->authorize('access', ['konsumen', 'read']); // Proteksi Read
        $konsumens = Konsumen::latest()->paginate(10);
        return view('master.konsumen.index', compact('konsumens'));
    }

    public function store(StoreKonsumenRequest $request)
    {
        $this->authorize('access', ['konsumen', 'create']); // Proteksi Create
        Konsumen::create($request->validated());
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil ditambahkan.');
    }

    public function update(UpdateKonsumenRequest $request, Konsumen $konsuman)
    {
        $this->authorize('access', ['konsumen', 'update']); // Proteksi Update
        $konsuman->update($request->validated());
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil diperbarui.');
    }

    public function destroy(Konsumen $konsuman)
    {
        $this->authorize('access', ['konsumen', 'delete']); // Proteksi Delete
        $konsuman->delete();
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil dihapus.');
    }
}
