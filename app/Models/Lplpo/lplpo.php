<?php

namespace App\Models\Lplpo;

use Illuminate\Database\Eloquent\Model;

class Lplpo extends Model
{
    protected $table = 'lplpo_temp';

    protected $fillable = [
        'nama_obat',
        'kode_faskes',
        'bulan',
        'tahun',
        'satuan',
        'kode_obat',
        'stok_awal_field1',
        'stok_awal_field2',
        'stok_awal_field3',
        'penerimaan_field1',
        'penerimaan_field2',
        'persediaan_field1',
        'persediaan_field2',
        'pemakaian_field1',
        'pemakaian_field2',
        'pemakaian_field3',
        'kadaluarsa',
        'pengembalian',
        'stok_akhir_field1',
        'stok_akhir_field2',
        'stok_akhir_field3',
        'rko',
        'stok_optimum',
        'permintaan'
    ];
}
