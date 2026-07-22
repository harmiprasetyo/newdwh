<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MasterObat extends Model
{
    protected $table = 'master_obat';

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'satuan',
        'kelompok_obat',
        'golongan_obat',
        'kategori_obat',
        'stok_minimal'
    ];
}
