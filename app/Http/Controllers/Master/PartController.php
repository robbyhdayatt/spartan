<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Part;
use App\Models\Master\Kategori; // Import Kategori
use App\Models\Master\Brand;    // Import Brand
use App\Http\Requests\StorePartRequest;
use App\Http\Requests\UpdatePartRequest;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with(['kategori', 'brand'])->latest()->paginate(10);
        $kategoris = Kategori::where('status_aktif', 1)->get();
        $brands = Brand::where('status_aktif', 1)->get();

        return view('master.part.index', compact('parts', 'kategoris', 'brands'));
    }

    public function store(StorePartRequest $request)
    {
        Part::create($request->validated());
        return redirect()->route('parts.index')->with('success', 'Part berhasil ditambahkan.');
    }

    public function update(UpdatePartRequest $request, Part $part)
    {
        $part->update($request->validated());
        return redirect()->route('parts.index')->with('success', 'Part berhasil diperbarui.');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part berhasil dihapus.');
    }
}
