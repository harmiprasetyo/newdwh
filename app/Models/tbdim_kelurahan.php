<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbdim_kelurahan extends Model
{
    use HasFactory;
    protected $table = "tbdim_kelurahan";
    protected $fillable = [
        "kode_desa",
        "kode_kecamatan",
        "kode_kabupaten",
        "kode_propinsi",
        "nama_desa"
    ];
}
