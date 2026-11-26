<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class MasterTpmbResource extends JsonResource {
 public function toArray($request): array {
  return $this->only(['id','fhir_id','kode_tpmb','kodeFaskes','nama_tpmb','alamat_tpmb','status_tpmb','created_at','updated_at']);
 }
}
