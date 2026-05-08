<?php

namespace App\Models\Obat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_obat extends Model
{
    use HasFactory;
    protected $table = 'master_obat';
    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'satuan',
        'kelompok_obat',
        'golongan_obat',
        'kategori_obat'
    ];

    public function laporanlplpo()
    {
        return $this->hasMany(laporanLplpo::class, 'kode_obat', 'kode_obat');
    }

}
