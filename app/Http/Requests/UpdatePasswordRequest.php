<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password' => 'password lama',
            'password' => 'password baru',
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.mixed' => 'Password baru harus mengandung huruf besar dan huruf kecil.',
            'password.numbers' => 'Password baru harus mengandung setidaknya satu angka.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ];
    }
}
