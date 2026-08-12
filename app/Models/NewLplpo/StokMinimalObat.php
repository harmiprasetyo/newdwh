<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\MasterFaskes;

class StokMinimalObat extends Model
{
    protected $table = 'master_stokminimal_obat';

    protected $fillable = [
        'kode_obat',
        'kodeFaskes',
        'stok_minimal',
        'stok_optimum',
        'obat_esensial',
        'obat_formularium_puskesmas',
        'tahun',
    ];

    protected $casts = [
        'stok_minimal' => 'integer',
        'stok_optimum' => 'integer',
        'tahun' => 'integer',
    ];

    public function obat()
    {
        return $this->belongsTo(
            MasterDataObat::class,
            'kode_obat',
            'kode_obat'
        );
    }

    public function faskes()
    {
        return $this->belongsTo(
            MasterFaskes::class,
            'kodeFaskes',
            'kodeFaskes'
        );
    }
}
