<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\MasterFaskes;
use Laravolt\Indonesia\Models\Village;
class MasterPosyandu extends Model
{
    protected $table = 'master_posyandu';

    protected $fillable = [

        'province_code',
        'city_code',
        'district_code',
        'village_code',

        'kodeFaskes',

        'kodePosyandu',
        'namaPosyandu',

        'isActive'
    ];
    public function faskes()
    {
        return $this->belongsTo(
            MasterFaskes::class,
            'kodeFaskes',
            'kodeFaskes'
        );
    }

    public function wilayahKerja()
{
    return $this->hasMany(
        \App\Models\AdminPanel\WilayahKerja\WilayahKerjaPosyandu::class,
        'kodePosyandu',
        'kodePosyandu'
    );
}

 public function desa()
    {
        return $this->belongsTo(
            Village::class,
            'kodeDesa',
            'code'
        );
    }

}
