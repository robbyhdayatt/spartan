<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKonsumenRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        $konsumenId = $this->route('konsuman')->id_konsumen; // 'konsuman' adalah nama parameter route
        return [
            'kode_konsumen' => 'required|string|max:50|unique:konsumen,kode_konsumen,' . $konsumenId . ',id_konsumen',
            'nama_konsumen' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'limit_kredit' => 'nullable|numeric',
            'term_pembayaran' => 'nullable|integer',
            'status_aktif' => 'required|boolean',
        ];
    }
}
