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
        'delivery_time'
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
    ];
}
