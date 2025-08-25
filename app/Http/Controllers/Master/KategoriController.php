<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Kategori;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;

class KategoriController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['kategoris', 'read']); // Proteksi Read
        $kategoris = Kategori::with('parent')->latest()->paginate(10);
        $parentKategoris = Kategori::where('status_aktif', 1)->get(); // Data untuk dropdown
        return view('master.kategori.index', compact('kategoris', 'parentKategoris'));
    }

    public function store(StoreKategoriRequest $request)
    {
        $this->authorize('access', ['kategoris', 'create']); // Proteksi Create
        Kategori::create($request->validated());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(UpdateKategoriRequest $request, Kategori $category)
    {
        $this->authorize('access', ['kategoris', 'update']); // Proteksi Update
        $category->update($request->validated());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $category)
    {
        $this->authorize('access', ['kategoris', 'delete']); // Proteksi Delete
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
