<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table='new_lplpo_itemlist';

    protected $fillable=[

        'report_id',
        'program_id',

        'kode_obat',
        'nama_obat',
        'satuan',

        'stok_awal_progam_pkd',
        'stok_awal_jkn',

        'penerimaan_program_pkd',
        'penerimaan_jkn',

        'persediaan_program_pkd',
        'persediaan_jkn',

        'pemakaian_program_pkd',
        'pemakaian_jkn',

        'item_expired',

        'stok_akhir_program_pkd',
        'stok_akhir_jkn',

        'stok_minimum',
        'stok_optimum',

        'permintaan',
        'pemberian_program_pkd',
        'pemberian_jkn',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class,'report_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class,'program_id');
    }

}
