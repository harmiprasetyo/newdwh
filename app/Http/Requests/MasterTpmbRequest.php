<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class MasterTpmbRequest extends FormRequest {
 public function authorize(): bool { return true; } // Otorisasi melalui middleware route.
 public function rules(): array {
  $required = $this->isMethod('PATCH') ? 'sometimes|required' : 'required';
  $unique = Rule::unique('master_tpmb','kode_tpmb');
  if ($this->route('tpmb')) $unique->ignore($this->route('tpmb')->getKey());
  return [
   'fhir_id'=>['sometimes','nullable','string','max:255'],
   'kode_tpmb'=>[$required,'string','max:50',$unique],
   'kodeFaskes'=>[$required,'string','max:50',Rule::exists('master_faskes','kodeFaskes')],
   'nama_tpmb'=>[$required,'string','max:255'],
   'alamat_tpmb'=>[$required,'string'],
   'status_tpmb'=>['sometimes','required',Rule::in(['aktif','tidak'])],
  ];
 }
}
