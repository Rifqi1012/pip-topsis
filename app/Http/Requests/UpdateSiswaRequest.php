<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'status_data' => 'required|in:draft,submitted',
            'c1_id' => 'required|exists:sub_kriterias,id',
            'c2_id' => 'required|exists:sub_kriterias,id',
            'c3_id' => 'required|exists:sub_kriterias,id',
            'c4_id' => 'required|exists:sub_kriterias,id',
            'c5_id' => 'required|exists:sub_kriterias,id',
            'c6_id' => 'required|exists:sub_kriterias,id',
        ];
    }
}
