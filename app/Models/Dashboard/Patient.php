<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $table = 'dashboard_patients';
     protected $fillable = [
        'patient_id', 'nik', 'name', 'gender', 'birth_date','raw_json'
    ];

    public function encounters()
    {
        return $this->hasMany(Encounter::class);
    }
}
