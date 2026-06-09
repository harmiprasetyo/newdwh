<?php

namespace App\Models\Api;

use App\Models\Master\MasterFaskes;

use Illuminate\Database\Eloquent\Model;

class MasterBaselinePemakaian extends Model
{
    protected $table = 'master_baseline_pemakaian';

    protected $fillable = [
        'kode_faskes',
        'kode_obat',
        'nama_obat',
        'bulan',
        'tahun',
        'rerata_pemakaian'
    ];

    // RELASI
    public function faskes()
    {
        return $this->belongsTo(MasterFaskes::class, 'kode_faskes', 'kodeFaskes');
    }
}
