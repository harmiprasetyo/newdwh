<?php

namespace App\Models\AdminPanel\WilayahKerja;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterPosyandu;
use Laravolt\Indonesia\Models\Village;

class WilayahKerjaPosyandu extends Model
{
    protected $table = 'master_wilayah_kerja_posyandu';

    protected $fillable = [
        'kodePosyandu',
        'village_code',
        'rw',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function posyandu()
    {
        return $this->belongsTo(
            MasterPosyandu::class,
            'kodePosyandu',
            'kodePosyandu'
        );
    }

    public function desa()
    {
        return $this->belongsTo(
            Village::class,
            'village_code',
            'code'
        );
    }
}
