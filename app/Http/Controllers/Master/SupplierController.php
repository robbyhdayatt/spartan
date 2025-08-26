<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    public function index()
    {
        $this->authorize('access', ['suppliers', 'read']); // Proteksi Read
        $suppliers = Supplier::latest()->paginate(10);
        return view('master.supplier.index', compact('suppliers'));
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->authorize('access', ['suppliers', 'create']); // Proteksi Create
        Supplier::create($request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('access', ['suppliers', 'update']); // Proteksi Update
        $supplier->update($request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('access', ['suppliers', 'delete']); // Proteksi Delete
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}