<?php
namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FhirEncounter extends Model
{
    protected $table = 'fhir_encounter';

    protected $fillable = [
        'fhir_id',
        'version_id',
        'fhir_last_updated',

        'patient_id',
        'fhir_patient_id',
        'patient_display',

        'identifier_system',
        'identifier_value',

        'class_code',
        'class_display',

        'service_provider_id',
        'service_provider',

        'location_id',
        'location',

        'practitioner_id',
        'practitioner_display',
        'episode_of_care_id',

        'status',
        'status_history',
        'participant_data',
        'location_data',

        'encounter_date',
        'encounter_end',
        'raw_resource',
        'synced_at',
    ];

    protected $casts = [
        'fhir_last_updated' => 'datetime',
        'encounter_date' => 'datetime',
        'encounter_end' => 'datetime',
        'synced_at' => 'datetime',

        'raw_resource' => 'array',
        'status_history' => 'array',
        'participant_data' => 'array',
        'location_data' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            FhirPatient::class,
            'patient_id'
        );
    }



public function organization(): BelongsTo
{
    return $this->belongsTo(
        FhirOrganization::class,
        'service_provider_id',
        'fhir_id'
    );
}

public function practitioner(): BelongsTo
{
    return $this->belongsTo(
        FhirPractitioner::class,
        'practitioner_id',
        'fhir_id'
    );
}

public function encounterLocation(): BelongsTo
{
    return $this->belongsTo(
        FhirLocation::class,
        'location_id',
        'fhir_id'
    );
}

}
