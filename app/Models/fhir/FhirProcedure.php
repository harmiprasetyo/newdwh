<?php
namespace App\Models\Fhir;

use Illuminate\Database\Eloquent\Model;

class FhirProcedure extends Model
{
    protected $table = 'fhir_procedures';


    protected $guarded = ['id'];

    protected $casts = [
       'status_reason' => 'array',
'category' => 'array',
'performer_data' => 'array',
'reason_code' => 'array',
'reason_reference' => 'array',
'body_site' => 'array',
'outcome' => 'array',
'complication' => 'array',
'note' => 'array',
'raw_resource' => 'array',
    ];
}
