<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\MasterFaskes;
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
}
