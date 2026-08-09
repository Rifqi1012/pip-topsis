<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
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
            'nisn' => ['required', 'string', 'max:20', \Illuminate\Validation\Rule::unique('siswas', 'nisn')->whereNull('deleted_at')],
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'periode' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'status_data' => 'required|in:draft,submitted',
        ];
    }
}
