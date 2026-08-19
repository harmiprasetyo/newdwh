<?php

namespace App\Models\WilayahKerja;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Master\MasterFaskes;
use Laravolt\Indonesia\Models\Village;

class WilayahKerjaPuskesmas extends Model
{
    protected $table = 'master_wilayah_kerja_puskesmas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'kodeFaskes',
        'kodeDesa',
    ];

    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    public function faskes(): BelongsTo
    {
        return $this->belongsTo(
            MasterFaskes::class,
            'kodeFaskes',
            'kodeFaskes'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DESA
    |--------------------------------------------------------------------------
    */

    public function desa(): BelongsTo
    {
        return $this->belongsTo(
            Village::class,
            'kodeDesa',
            'code'
        );
    }
}
