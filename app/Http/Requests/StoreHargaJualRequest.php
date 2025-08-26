<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHargaJualRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'id_part' => 'required|integer|exists:part,id_part',
            'id_konsumen' => 'nullable|integer|exists:konsumen,id_konsumen',
            'hed' => 'required|numeric|min:0',
            'periode_awal' => 'nullable|date',
            'periode_akhir' => 'nullable|date|after_or_equal:periode_awal',
            'status_aktif' => 'required|boolean',
        ];
    }
}