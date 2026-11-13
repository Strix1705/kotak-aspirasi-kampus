<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pendaftaran terbuka untuk guest
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'nim' => strtoupper(trim((string) $this->input('nim'))),
            'email' => strtolower(trim((string) $this->input('email'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'nim' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[A-Za-z0-9\-]+$/', 'unique:users,nim'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'nim' => 'NIM',
            'email' => 'email',
            'password' => 'password',
        ];
    }

    public function messages(): array
    {
        return [
            'nim.regex' => 'NIM hanya boleh berisi huruf, angka, dan tanda hubung.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
            'password.numbers' => 'Password harus mengandung setidaknya satu angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.',
        ];
    }
}
