<?php

namespace App\Models\Rme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use App\Models\Rme\Encounter;
use App\Models\Rme\Immunization;


class Patient extends Model
{
use HasFactory;

 public function province()
    {
        return $this->belongsTo(
            Province::class,
            'kode_propinsi', // FK di patients
            'code'           // PK di provinces
        );
    }

    public function city()
    {
        return $this->belongsTo(
            City::class,
            'kode_kota',
            'code'
        );
    }

    public function district()
    {
        return $this->belongsTo(
            District::class,
            'kode_kecamatan',
            'code'
        );
    }

public function encounters()
{
    return $this->hasMany(
        Encounter::class,
        'patient_id',
        'patient_id'
    );
}

public function immunizations()
{
    return $this->hasMany(
        Immunization::class,
        'patient_id',
        'patient_id'
    );
}
protected $fillable = [
        'patient_id',
        'ihs_number',
        'nik',
        'bpjs',
        'name',
        'phone',
        'email',
        'gender',
        'birth_date',
        'address',
        'kode_propinsi',
        'kode_kota',
        'kode_kecamatan'
    ];
}
