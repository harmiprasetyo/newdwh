<?php
namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;



class FhirPatient extends Model
{
    protected $table = 'fhir_patient';

    protected $fillable = [
        'fhir_id',
        'version_id',
        'fhir_last_updated',
        'ihs_number',
        'nik',
        'nik_ibu',
        'bpjs_number',
        'active',
        'name',
        'family_name',
        'given_names',
        'gender',
        'birth_date',
        'phone',
        'email',
        'address_line',
        'province_code',
        'province_name',
        'city_code',
        'city_name',
        'district_code',
        'district_name',
        'village_code',
        'village_name',
        'rw',
        'rt',
        'raw_resource',
        'synced_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
        'fhir_last_updated' => 'datetime',
        'synced_at' => 'datetime',
        'given_names' => 'array',
        'raw_resource' => 'array',
    ];


    public function encounters(): HasMany
{
    return $this->hasMany(FhirEncounter::class, 'patient_id');
}

}
