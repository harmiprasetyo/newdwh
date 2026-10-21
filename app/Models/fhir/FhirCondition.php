<?php
namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;

class FhirCondition extends Model
{
    protected $table = 'fhir_conditions';

    protected $guarded = ['id'];

    protected $casts = [
        'category' => 'array',
        'stage' => 'array',
        'body_site' => 'array',
        'evidence' => 'array',
        'note' => 'array',
        'raw_resource' => 'array',

        'fhir_last_updated' => 'datetime',
        'onset_datetime' => 'datetime',
        'abatement_datetime' => 'datetime',
        'recorded_date' => 'datetime',
        'synced_at' => 'datetime',
    ];
}
