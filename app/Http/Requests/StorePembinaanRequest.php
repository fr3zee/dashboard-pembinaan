<?php

namespace App\Http\Requests;

use App\Models\Pembinaan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePembinaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->isMethod('post')
            ? (bool) $this->user()?->can('create', Pembinaan::class)
            : (bool) $this->user()?->can('update', $this->route('pembinaan'));
    }

    public function rules(): array
    {
        return [
            'nama_kegiatan' => ['nullable', 'string', 'max:255'],
            'jenis_pembinaan' => ['nullable', Rule::in(['Sosialisasi Srikandi', 'Integrasi'])],
            'instansi_id' => ['required', 'integer', 'exists:instansi,id'],
            'kategori_instansi' => ['nullable', 'string', 'max:100'],
            'satker' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'metode_pelaksanaan' => ['required', 'string', 'max:255'],
            'sumber_kegiatan' => ['required', Rule::in(['Permohonan Eksternal', 'Tamu/Kunjungan'])],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:255'],
            'layanan' => ['required', Rule::in(['Sosialisasi', 'Sosialisasi Srikandi', 'Integrasi', 'Pendampingan Teknis', 'Troubleshooting', 'Audit/Evaluasi', 'Bimbingan Teknis'])],
            'integrasi_aplikasi' => ['nullable', 'string', 'max:255'],
            'catatan_hasil' => ['nullable', 'string'],
            'status_dokumen' => ['required', Rule::in(['Belum Menggunakan', 'Tahap Uji Coba', 'Tahap Ujicoba', 'Belum Implementasi', 'Implementasi', 'Live', 'Tahap Persiapan', 'Selesai'])],
            'pegawai' => ['nullable', 'array'],
            'pegawai.*' => ['integer', 'exists:pegawai,id'],
            'peran' => ['nullable', 'array'],
            'peran.*' => ['nullable', Rule::in(['Narasumber', 'Tim'])],
            'laporan_file' => ['nullable', 'file', 'max:20480', 'mimes:pdf'],
            'materi_file' => ['nullable', 'file', 'max:20480', 'extensions:pdf,doc,docx,ppt,pptx,zip,rar,jpg,jpeg,png'],
            'materi_visibilitas' => ['nullable', Rule::in(['Publikasi', 'Private'])],
        ];
    }
}




