<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGudangRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        $gudangId = $this->route('gudang')->id_gudang;
        return [
            'kode_gudang' => 'required|string|max:50|unique:gudang,kode_gudang,' . $gudangId . ',id_gudang',
            'nama_gudang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'id_pic_gudang' => 'nullable|integer|exists:karyawan,id_karyawan',
            'telepon' => 'nullable|string|max:20',
            'jenis_gudang' => 'required|string|in:utama,transit,retur,quarantine',
            'status_aktif' => 'required|boolean',
        ];
    }
}
