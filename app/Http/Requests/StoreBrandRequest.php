<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_brand' => 'required|string|max:255|unique:brand,nama_brand',
            'negara_asal' => 'nullable|string|max:100',
            'status_aktif' => 'required|boolean',
        ];
    }
}
