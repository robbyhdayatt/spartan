<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKaryawanRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        $karyawanId = $this->route('karyawan')->id_karyawan;
        return [
            'kode_karyawan' => 'required|string|max:50|unique:karyawan,kode_karyawan,' . $karyawanId . ',id_karyawan',
            'nama_karyawan' => 'required|string|max:255',
            'id_jabatan' => 'required|integer|exists:jabatan,id_jabatan',
            'id_gudang_asal' => 'nullable|integer|exists:gudang,id_gudang',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'tanggal_masuk' => 'nullable|date',
            'status_aktif' => 'required|boolean',
        ];
    }
}
