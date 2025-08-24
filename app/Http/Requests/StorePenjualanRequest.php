<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenjualanRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'id_konsumen' => 'required|integer|exists:konsumen,id_konsumen',
            'id_sales' => 'required|integer|exists:karyawan,id_karyawan',
            'tanggal_penjualan' => 'required|date',
            'jenis_penjualan' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.id_part' => 'required|integer|exists:part,id_part',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [ 'details.required' => 'Minimal harus ada 1 item barang dalam penjualan.' ];
    }
}