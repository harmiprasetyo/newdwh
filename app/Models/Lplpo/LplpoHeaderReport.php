<?php

namespace App\Models\Lplpo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterFaskes;


class LplpoHeaderReport extends Model
{
use HasFactory;
protected $table = 'lplpo_header_report';

    protected $fillable = [
        'kode_faskes',
        'bulan',
        'tahun',
        'final'
    ];

    public function details()
    {
        return $this->hasMany(Lplpo::class, 'header_id');
    }

    public function faskes()
{
    return $this->belongsTo(MasterFaskes::class, 'kode_faskes', 'kodeFaskes');
}
}
