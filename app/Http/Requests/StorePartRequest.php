<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'kode_part' => 'required|string|max:100|unique:part,kode_part',
            'nama_part' => 'required|string|max:255',
            'id_kategori' => 'required|integer|exists:kategori,id_kategori',
            'id_brand' => 'required|integer|exists:brand,id_brand',
            'spesifikasi' => 'nullable|string',
            'satuan' => 'nullable|string|max:50',
            'minimum_stok' => 'required|integer|min:0',
            'harga_pokok' => 'nullable|numeric|min:0',
            'require_qc' => 'required|boolean',
            'status_aktif' => 'required|boolean',
        ];
    }
}
