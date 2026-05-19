<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Immunization extends Model
{
use HasFactory;
protected $table = "immunization_records";
protected $fillable = [
    'immunization_id',
    'patient_id',
    'encounter_id',
    'vaccine_code',
    'vaccine_name',
    'immunization_date',
    'recorded_at',
    'location_name',
    'service_provider_id',
    'service_provider_name'
];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class, 'encounter_id', 'encounter_id');
    }
}
