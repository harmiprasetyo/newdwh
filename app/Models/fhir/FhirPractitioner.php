<?php
namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FhirPractitioner extends Model
{
    protected $table = 'fhir_practitioner';

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'identifier' => 'array',
        'name_data' => 'array',
        'telecom' => 'array',
        'address' => 'array',
        'qualification' => 'array',
        'raw_resource' => 'array',
        'birth_date' => 'date',
        'fhir_last_updated' => 'datetime',
        'synced_at' => 'datetime',
    ];

    public function encounters(): HasMany
    {
        return $this->hasMany(
            FhirEncounter::class,
            'practitioner_id',
            'fhir_id'
        );
    }
}
