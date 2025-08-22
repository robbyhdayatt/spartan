<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Izinkan semua user yang terotentikasi
    }

    public function rules()
    {
        return [
            'kode_supplier' => 'required|string|max:50|unique:supplier,kode_supplier',
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'status_aktif' => 'required|boolean',
        ];
    }
}
