<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class MasterTpmb extends Model {
 use HasUuids;
 protected $table = 'master_tpmb';
 protected $keyType = 'string';
 public $incrementing = false;
 protected $fillable = ['fhir_id','kode_tpmb','kodeFaskes','nama_tpmb','alamat_tpmb','status_tpmb'];
}
