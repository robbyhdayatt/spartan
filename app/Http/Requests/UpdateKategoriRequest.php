<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $kategoriId = $this->route('category')->id_kategori;
        return [
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $kategoriId . ',id_kategori',
            'deskripsi' => 'nullable|string',
            'parent_kategori' => 'nullable|integer|exists:kategori,id_kategori',
            'status_aktif' => 'required|boolean',
        ];
    }
}
