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
        'obat_napza',
        'kelompok_obat',
        'golongan_obat',
        'kategori_obat',
    ];

    protected $casts = [
        'obat_napza' => 'string',
    ];

    public function stokMinimal()
    {
        return $this->hasMany(
            StokMinimalObat::class,
            'kode_obat',
            'kode_obat'
        );
    }
}
