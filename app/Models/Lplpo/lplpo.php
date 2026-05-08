<?php

namespace App\Models\Lplpo;

use Illuminate\Database\Eloquent\Model;

class Lplpo extends Model
{
    protected $table = 'lplpo';

    protected $fillable = [
        'nama_obat',
        'kode_faskes',
        'bulan',
        'tahun',
        'stok_awal_pkd',
        'stok_awal_program',
        'penerimaan_pkd',
        'penerimaan_program',
        'persediaan_pkd',
        'persediaan_program',
        'pemakaian_pkd',
        'pemakaian_program',
        'kadaluarsa',
        'pengembalian',
        'stok_akhir_pkd',
        'stok_akhir_program',
        'rko',
        'stok_optimum',
        'permintaan'
    ];
}
