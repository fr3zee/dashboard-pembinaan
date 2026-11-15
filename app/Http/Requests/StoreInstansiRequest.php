<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreInstansiRequest extends FormRequest {
 public function authorize(): bool { return $this->user()?->role === 'admin'; }
 public function rules(): array { return ['kategori_instansi_id'=>['required','integer','exists:kategori_instansi,id'], 'nama'=>['required','string','max:255'], 'status_aktif'=>['sometimes','boolean']]; }
}
