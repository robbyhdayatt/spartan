<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'tipe_retur' => 'required|in:retur_jual,retur_beli',
            'id_dokumen' => 'required|integer', // ID Penjualan atau Pembelian
            'tanggal_retur' => 'required|date',
            'alasan' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.id_part' => 'required|integer|exists:part,id_part',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.kondisi_barang' => 'required|string',
        ];
    }
}