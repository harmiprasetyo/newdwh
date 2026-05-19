<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
use HasFactory;
protected $casts = [
    'identifiers' => 'array'
];
protected $fillable = [
        'encounter_id',
        'patient_id',
        'identifier',
        'status',
        'class_code',
        'class_display',
        'practitioner_name',
        'practitioner_id',
        'location_name',
        'location_id',
        'start',
        'end',
        'service_provider_id',
        'service_provider_name',
        'identifiers'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }
}
