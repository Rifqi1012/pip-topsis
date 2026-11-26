<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'kepala_sekolah';
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('kode'))) {
            $this->merge(['kode' => strtoupper(trim($this->input('kode')))]);
        }
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:10', Rule::unique('kriterias', 'kode')->ignore($this->route('kriteria'))],
            'nama_kriteria' => 'required|string|max:255',
            'atribut' => 'required|in:Cost,Benefit',
            'bobot' => 'required|integer|min:1|max:5',
            'urutan' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return ['kode.unique' => 'Kode kriteria sudah digunakan.'];
    }
}
