<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StorePembinaanFileRequest extends FormRequest {
 public function authorize(): bool { return (bool) $this->user()?->can('update',$this->route('pembinaan')); }
 public function rules(): array { return [
     'file'=>['required','file','max:20480','extensions:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,jpeg,png'],
     'jenis'=>['required',Rule::in(['laporan','materi'])],
     'visibilitas'=>['required',Rule::in(['Publikasi','Private'])]
 ]; }
}
