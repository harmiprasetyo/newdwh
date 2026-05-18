<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SyncFhirPatientJob;

class TriggerController extends Controller
{
    //

    public function sync(Request $request)
{
    $request->validate([
        'nik' => 'required',
        'encounter_id' => 'nullable'
    ]);

    SyncFhirPatientJob::dispatch(
        $request->nik,
        $request->encounter_id
    );

    return response()->json([
        'status' => 'queued',
        'nik' => $request->nik,
        'encounter_id' => $request->encounter_id
    ]);
}
}
