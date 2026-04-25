<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PregnancyRecord extends Model
{
    use HasFactory;
    protected $fillable = [
    'patient_id','encounter_id','loinc_code','raw_fhir','gravida','parity','abortus',
    'lmp','edd','gestational_age','trimester'
];
}
