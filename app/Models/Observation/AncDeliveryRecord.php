<?php

namespace App\Models\Observation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AncDeliveryRecord extends Model
{
    use HasFactory;


    protected $fillable = [
        'patient_id',
        'encounter_id',
        'gestational_age',
        'gravida',
        'parity',
        'abortus',
        'delivery_time',
        'postpartum_condition',
        'delivery_helper',
        'delivery_method',
        'stage1',
        'stage2',
        'stage3',
        'stage4'
    ];
}
