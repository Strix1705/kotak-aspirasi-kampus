<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAspirationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya mahasiswa terautentikasi yang boleh submit
        return $this->user() !== null && $this->user()->isMahasiswa();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim((string) $this->input('title')),
            'description' => trim((string) $this->input('description')),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:150'],
            'category' => ['required', 'in:Fasilitas,Akademik,Layanan'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'category' => 'kategori',
            'description' => 'deskripsi',
        ];
    }
}
