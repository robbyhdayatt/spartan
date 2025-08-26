<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Jabatan;
use App\Http\Requests\StoreJabatanRequest;
use App\Http\Requests\UpdateJabatanRequest;

class JabatanController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['jabatans', 'read']); // Proteksi Read
        $jabatans = Jabatan::latest()->paginate(10);
        return view('master.jabatan.index', compact('jabatans'));
    }

    public function store(StoreJabatanRequest $request)
    {
        $this->authorize('access', ['jabatans', 'create']); // Proteksi Create
        Jabatan::create($request->validated());
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(UpdateJabatanRequest $request, Jabatan $jabatan)
    {
        $this->authorize('access', ['jabatans', 'update']); // Proteksi Update
        $jabatan->update($request->validated());
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $this->authorize('access', ['jabatans', 'delete']); // Proteksi Delete
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
