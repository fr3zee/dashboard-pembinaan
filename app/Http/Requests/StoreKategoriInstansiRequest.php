<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreKategoriInstansiRequest extends FormRequest {
 public function authorize(): bool { return $this->user()?->role === 'admin'; }
 public function rules(): array { return ['nama'=>['required','string','max:255','unique:kategori_instansi,nama,'.$this->route('kategori_instansi')?->id], 'status_aktif'=>['sometimes','boolean']]; }
}
