<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJabatanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $jabatanId = $this->route('jabatan')->id_jabatan;
        return [
            'nama_jabatan' => 'required|string|max:255|unique:jabatan,nama_jabatan,' . $jabatanId . ',id_jabatan',
            'deskripsi' => 'nullable|string',
            'level_jabatan' => 'nullable|integer',
            'status_aktif' => 'required|boolean',
        ];
    }
}
