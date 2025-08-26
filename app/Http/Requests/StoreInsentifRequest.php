<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreInsentifRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return [
            'nama_program' => 'required|string|max:255',
            'id_jabatan' => 'required|integer|exists:jabatan,id_jabatan',
            'id_part' => 'nullable|integer|exists:part,id_part',
            'tipe_insentif' => 'required|string',
            'nilai_insentif' => 'required|numeric|min:0',
            'minimum_target' => 'required|numeric|min:0',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
            'status_aktif' => 'required|boolean',
        ];
    }
}