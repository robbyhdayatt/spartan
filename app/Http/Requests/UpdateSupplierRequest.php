<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Import Rule

class UpdateSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Ambil ID supplier dari route, contoh: suppliers/{supplier}
        $supplierId = $this->route('supplier')->id_supplier;

        return [
            // Saat update, kode_supplier harus unik KECUALI untuk dirinya sendiri
            'kode_supplier' => 'required|string|max:50|unique:supplier,kode_supplier,'.$supplierId.',id_supplier',
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'status_aktif' => 'required|boolean',
        ];
    }
}
