<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'kepala_sekolah';
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('nama'))) {
            $this->merge(['nama' => strtoupper(trim($this->input('nama')))]);
        }
    }

    public function rules(): array
    {
        return ['nama' => ['required', 'string', 'max:50', Rule::unique('kelas', 'nama')->ignore($this->route('kelas'))]];
    }

    public function messages(): array
    {
        return ['nama.required' => 'Nama kelas wajib diisi.', 'nama.unique' => 'Nama kelas sudah ada.'];
    }
}
