<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJabatanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_jabatan' => 'required|string|max:255|unique:jabatan,nama_jabatan',
            'deskripsi' => 'nullable|string',
            'level_jabatan' => 'nullable|integer',
            'status_aktif' => 'required|boolean',
        ];
    }
}
