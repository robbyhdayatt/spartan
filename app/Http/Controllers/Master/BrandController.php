<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;

class BrandController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['brands', 'read']); // Proteksi Read
        $brands = Brand::latest()->paginate(10);
        return view('master.brand.index', compact('brands'));
    }

    public function store(StoreBrandRequest $request)
    {
        $this->authorize('access', ['brands', 'create']); // Proteksi Create
        Brand::create($request->validated());
        return redirect()->route('brands.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $this->authorize('access', ['brands', 'update']); // Proteksi Update
        $brand->update($request->validated());
        return redirect()->route('brands.index')->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy(Brand $brand)
    {
        $this->authorize('access', ['brands', 'delete']); // Proteksi Delete
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand berhasil dihapus.');
    }
}
