<?php

namespace App\Models\Observation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PncRecord extends Model
{
    use HasFactory;
     protected $fillable = [
        'patient_id',
        'encounter_id',
        'gravida',
        'parity',
        'abortus',
        'delivery_time',
        'pendarahan',
        'kondisi_perineum',
        'tanda_infeksi_perineum',
        'tanda_infeksi_luka_sc',
        'kontraksi_uteus',
        'lochia',
        'bau_lochia',
        'produksi_asi',
        'bak',
        'bab',
        'pemeriksaan_payudara'
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
    ];
}
