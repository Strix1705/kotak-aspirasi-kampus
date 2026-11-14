<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        // Sanitasi XSS: buang seluruh tag HTML sebelum validasi
        $this->merge([
            'name' => trim(strip_tags((string) $this->input('name'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[^<>]*$/'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Nama tidak boleh mengandung karakter < atau >.',
            'name.required' => 'Nama wajib diisi.',
        ];
    }
}
