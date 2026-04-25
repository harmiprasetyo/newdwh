<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;
    protected $fillable = [
    'patient_id','encounter_id','loinc_code','raw_fhir','height','weight','pre_weight',
    'bmi','lila','sfh','bmi_status'
];
}
