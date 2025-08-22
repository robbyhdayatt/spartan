<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembelianRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'id_supplier' => 'required|integer|exists:supplier,id_supplier',
            'tanggal_pembelian' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.id_part' => 'required|integer|exists:part,id_part',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'details.required' => 'Minimal harus ada 1 item barang dalam pembelian.',
            'details.*.id_part.required' => 'Part harus dipilih.',
            'details.*.quantity.required' => 'Jumlah (Qty) harus diisi.',
            'details.*.harga_satuan.required' => 'Harga harus diisi.',
        ];
    }
}
