<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'nama_campaign' => 'required|string|max:255',
            'jenis_campaign' => 'required|string|in:purchase,sales',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status_aktif' => 'required|boolean',
        ];
    }
}