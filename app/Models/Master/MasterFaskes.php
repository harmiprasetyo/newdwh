<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\ListTypeFaskes;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class MasterFaskes extends Model
{
    protected $table = 'master_faskes';

    protected $fillable = [
        'kodeFaskes',
        'typeFaskes',
        'kodePropinsi',
        'kodeKabupaten',
        'kodeKecamatan',
        'kepemilikan',
        'namaFaskes'
    ];

    public function type()
    {
        return $this->belongsTo(ListTypeFaskes::class, 'typeFaskes');
    }

    public function provinsi()
{
    return $this->belongsTo(Province::class, 'kodePropinsi', 'code');
}

public function kota()
{
    return $this->belongsTo(City::class, 'kodeKabupaten', 'code');
}

public function kecamatan()
{
    return $this->belongsTo(District::class, 'kodeKecamatan', 'code');
}
}
