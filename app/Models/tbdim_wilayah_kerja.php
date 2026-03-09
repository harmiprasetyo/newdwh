<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbdim_wilayah_kerja extends Model
{
    use HasFactory;
    protected $table = "tbdim_wilayah_kerja";
    protected $fillable = [
        "kode_desa",
        "kode_puskesmas"
    ];
}
