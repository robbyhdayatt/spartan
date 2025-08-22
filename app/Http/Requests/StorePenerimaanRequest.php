<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenerimaanRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'id_pembelian' => 'required|integer|exists:pembelian,id_pembelian',
            'id_gudang_tujuan' => 'required|integer|exists:gudang,id_gudang',
            'tanggal_penerimaan' => 'required|date',
            'nomor_surat_jalan' => 'required|string|max:100',
            'details' => 'required|array|min:1',
            'details.*.id_detail_pembelian' => 'required|integer|exists:detail_pembelian,id_detail_pembelian',
            'details.*.id_part' => 'required|integer|exists:part,id_part',
            'details.*.qty_diterima' => 'required|integer|min:0',
        ];
    }
}
