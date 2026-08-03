<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterDataObat extends Model
{
    use HasFactory;

    protected $table = 'master_obat';

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'satuan',
        'stok_minimum',
        'stok_optimum'
    ];

    protected $casts = [
        'stok_minimum' => 'integer',
        'stok_optimum' => 'integer',
    ];
}
