<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Master\MasterFaskes;

class Report extends Model
{
    use HasFactory;

    protected $table = 'new_lplpo_reports';

    protected $fillable = [
        'kode_faskes',
        'bulan',
        'tahun',
        'nama_faskes',
        'nomor_lplpo',
        'report_status'
    ];

    protected $casts = [
        'bulan'=>'integer',
        'tahun'=>'integer'
    ];

    public function items()
    {
        return $this->hasMany(Item::class,'report_id');
    }



public function notes()
{
    return $this->hasMany(ReportNote::class,'report_id');
}

public function faskes()
{
    return $this->belongsTo(
        MasterFaskes::class,
        'kode_faskes',
        'kodeFaskes'
    );
}

public function kunjungan()
{
    return $this->hasOne(
        Kunjungan::class,
        'report_id',
        'id'
    );
}



}
