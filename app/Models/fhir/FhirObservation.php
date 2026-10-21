<?php

namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FhirObservation extends Model
{
    protected $table = 'fhir_observation';

    protected $guarded = ['id'];

    protected $casts = [
        'fhir_last_updated' => 'datetime',
        'effective_datetime' => 'datetime',
        'effective_start' => 'datetime',
        'effective_end' => 'datetime',
        'issued_at' => 'datetime',
        'value_datetime' => 'datetime',
        'value_quantity' => 'decimal:8',
        'value_boolean' => 'boolean',
        'category' => 'array',
        'value_json' => 'array',
        'interpretation' => 'array',
        'reference_range' => 'array',
        'component' => 'array',
        'performer_data' => 'array',
        'note' => 'array',
        'raw_resource' => 'array',
        'synced_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(FhirPatient::class, 'patient_id');
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(FhirEncounter::class, 'encounter_id');
    }
}
