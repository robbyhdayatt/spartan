<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Supplier; // Path model yang benar
use App\Http\Requests\StoreSupplierRequest; // Gunakan request yang sudah dibuat
use App\Http\Requests\UpdateSupplierRequest; // Gunakan request yang sudah dibuat

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('master.supplier.index', compact('suppliers'));
    }

    public function store(StoreSupplierRequest $request)
    {
        Supplier::create($request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        // Biasanya tidak digunakan untuk master data, bisa dilewati
        return view('master.supplier.show', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete(); // Ini akan menjalankan Soft Delete
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
