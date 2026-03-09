<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_fhir_encounter extends Model
{
    use HasFactory;
    protected $table="tb_fhir_encounter";
    protected $fillable = [
        'fullUrl',
        'id',
'episodeOfcare',
'ANCSeries',
'status',
'historyStatus',
'startPeriodHS',
'classRef',
'classCode',
'classDisplay',
'PatientRef',
'PatientName',
'EOCref',
'ParticipantRef',
'ParticipantCode',
'participantDisplay',
'PractitionerRef',
'PractitionerName',
'locationRef',
'locationDisplay',
'serviceProviderRef'
    ];
}
