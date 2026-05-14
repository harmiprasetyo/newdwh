<?php

namespace App\Models\Observation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeonatalRecord extends Model
{
    use HasFactory;
    protected $table = 'neonatal_records';
        protected $fillable = [
            'patient_id',
            'encounter_id',
            'jenis_kelamin',
            'berat_lahir',
            'panjang_lahir',
            'lingkar_kepala',
            'apgar_1_menit',
            'apgar_5_menit',
            'apgar_10_menit',
            'resusitasi',
            'komplikasi_neonatal'
        ];
}
