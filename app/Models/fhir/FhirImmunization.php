<?php
namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;

class FhirImmunization extends Model
{
    protected $table = 'fhir_immunizations';

    protected $guarded = ['id'];

    protected $casts = [
        'status_reason' => 'array',
'site' => 'array',
'route' => 'array',
'performer_data' => 'array',
'protocol_applied' => 'array',
'reaction' => 'array',
'note' => 'array',
'raw_resource' => 'array',
    ];
}
