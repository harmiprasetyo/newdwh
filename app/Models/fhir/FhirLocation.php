<?php
namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FhirLocation extends Model
{
    protected $table = 'fhir_location';

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'identifier' => 'array',
        'type' => 'array',
        'physical_type' => 'array',
        'telecom' => 'array',
        'address' => 'array',
        'position' => 'array',
        'raw_resource' => 'array',
        'fhir_last_updated' => 'datetime',
        'synced_at' => 'datetime',
    ];

    public function encounters(): HasMany
    {
        return $this->hasMany(
            FhirEncounter::class,
            'location_id',
            'fhir_id'
        );
    }
}
