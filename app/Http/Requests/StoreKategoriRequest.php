<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
            'deskripsi' => 'nullable|string',
            // Pastikan parent_kategori yang dipilih ada di tabel kategori
            'parent_kategori' => 'nullable|integer|exists:kategori,id_kategori',
            'status_aktif' => 'required|boolean',
        ];
    }
}
