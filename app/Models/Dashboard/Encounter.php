<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;
    protected $table = 'dashboard_encounters';
     protected $fillable = [
        'encounter_id',
        'patient_id',
        'service_provider',
        'location',
        'encounter_date',
        'status',
        'raw_json'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
