<?php
namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FhirOrganization extends Model
{
    protected $table = 'fhir_organization';

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'identifier' => 'array',
        'type' => 'array',
        'telecom' => 'array',
        'address' => 'array',
        'raw_resource' => 'array',
        'fhir_last_updated' => 'datetime',
        'synced_at' => 'datetime',
    ];

    public function encounters(): HasMany
    {
        return $this->hasMany(
            FhirEncounter::class,
            'service_provider_id',
            'fhir_id'
        );
    }
}
