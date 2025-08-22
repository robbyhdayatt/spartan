<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGudangRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'kode_gudang' => 'required|string|max:50|unique:gudang,kode_gudang',
            'nama_gudang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'id_pic_gudang' => 'nullable|integer|exists:karyawan,id_karyawan',
            'telepon' => 'nullable|string|max:20',
            'jenis_gudang' => 'required|string|in:utama,transit,retur,quarantine', // Validasi ENUM
            'status_aktif' => 'required|boolean',
        ];
    }
}
