<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockAdjustmentRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'id_gudang' => 'required|integer|exists:gudang,id_gudang',
            'tanggal_adjustment' => 'required|date',
            'jenis_adjustment' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.id_part' => 'required|integer|exists:part,id_part',
            'details.*.stok_fisik' => 'required|integer|min:0',
        ];
    }
}