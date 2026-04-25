<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    use HasFactory;
    protected $fillable = [
    'patient_id','encounter_id','loinc_code','raw_fhir','systolic','diastolic',
    'heart_rate','respiratory_rate','temperature'
];
}
