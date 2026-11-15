<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:255', 'unique:pegawai,nip,'.$this->route('pegawai')?->id],
            'email' => ['nullable', 'email', 'max:255'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'status_aktif' => ['sometimes', 'boolean'],
        ];
    }
}
