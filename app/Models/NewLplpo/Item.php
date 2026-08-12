<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table = 'new_lplpo_itemlist';

    protected $fillable = [

        'report_id',
        'program_id',
        'program_name',

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

        'item_expired_pkd',
        'item_expired_jkn',

        'stok_akhir_program_pkd',
        'stok_akhir_jkn',

        'stok_minimum',
        'stok_optimum',

        'permintaan',

        'pemberian_program_pkd',
        'pemberian_jkn',
    ];

    protected $casts = [

        'stok_awal_progam_pkd'      => 'integer',
        'stok_awal_jkn'             => 'integer',

        'penerimaan_program_pkd'    => 'integer',
        'penerimaan_jkn'            => 'integer',

        'persediaan_program_pkd'    => 'integer',
        'persediaan_jkn'            => 'integer',

        'pemakaian_program_pkd'     => 'integer',
        'pemakaian_jkn'             => 'integer',

        'item_expired_pkd'          => 'integer',
        'item_expired_jkn'          => 'integer',

        'stok_akhir_program_pkd'    => 'integer',
        'stok_akhir_jkn'            => 'integer',

        'stok_minimum'              => 'integer',
        'stok_optimum'              => 'integer',

        'permintaan'                => 'integer',

        'pemberian_program_pkd'     => 'integer',
        'pemberian_jkn'             => 'integer',

    ];

    /*
    |--------------------------------------------------------------------------
    | REPORT
    |--------------------------------------------------------------------------
    */

    public function report()
    {
        return $this->belongsTo(
            Report::class,
            'report_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROGRAM
    |--------------------------------------------------------------------------
    */

    public function program()
    {
        return $this->belongsTo(
            Program::class,
            'program_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MASTER OBAT
    |--------------------------------------------------------------------------
    */

    public function obat()
    {
        return $this->belongsTo(
            MasterDataObat::class,
            'kode_obat',
            'kode_obat'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STOK MINIMAL
    |--------------------------------------------------------------------------
    */

    public function stokMinimal()
    {
        return $this->hasOne(
            StokMinimalObat::class,
            'kode_obat',
            'kode_obat'
        );
    }
}
