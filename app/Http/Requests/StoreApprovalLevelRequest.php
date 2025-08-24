<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreApprovalLevelRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return [
            'jenis_dokumen' => 'required|string',
            'nama_level' => 'required|string|max:255',
            'id_jabatan_required' => 'required|integer|exists:jabatan,id_jabatan',
            'level_sequence' => 'required|integer',
            'minimum_amount' => 'required|numeric|min:0',
            'status_aktif' => 'required|boolean',
        ];
    }
}