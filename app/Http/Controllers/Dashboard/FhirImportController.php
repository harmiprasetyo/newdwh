<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dashboard\Patient;
use App\Models\Dashboard\Encounter;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FhirImportController extends Controller
{
    //




public function import()
{

 $token = env('FHIR_API_TOKEN');
        $server = env('FHIR_API_URL');
          $response = Http::withToken($token)->get($server.'Patient/');
          $data = $response->json();

//$bundle = $request->input('data'); // FHIR Bundle

    foreach ($data['entry'] ?? [] as $entry) {

        $resource = $entry['resource'];

        // ======================
        // 🧍 PATIENT
        // ======================
        if ($resource['resourceType'] === 'Patient') {

            Patient::updateOrCreate(
                ['patient_id' => $resource['id']],
                [
                    'nik' => $resource['identifier'][0]['value'] ?? null,
                    'name' => $resource['name'][0]['text'] ?? null,
                    'gender' => $resource['gender'] ?? null,
                    'birth_date' => Carbon::parse($resource['birthDate'])->format('Y-m-d') ?? null,
                    'raw_json' => json_encode($resource)
                ]
            );
        }

        // ======================
        // 🏥 ENCOUNTER
        // ======================


         $response2 = Http::withToken($token)->get($server.'Encounter?patient='.$resource['id']);
          $data2 = $response2->json();

          foreach ($data2['entry'] ?? [] as $entry2) {
            $resource = $entry2['resource'];

        if ($resource['resourceType'] === 'Encounter') {

            $patientRef = $resource['subject']['reference'] ?? null;
            $patientFhirId = str_replace('Patient/', '', $patientRef);

            $patient = Patient::where('patient_id', $patientFhirId)->first();

            $serviceProvider = Http::withToken($token)->get($server.$resource['serviceProvider']['reference']);
            $serviceProviderData = $serviceProvider->json();
            $resource['serviceProvider']['display'] = $serviceProviderData['name'] ?? null;

             $location = Http::withToken($token)->get($server.$resource['location'][0]['location']['reference']);




            Encounter::updateOrCreate(
                ['encounter_id' => $resource['id']],
                [
                    'patient_id' => $patient->id ?? null,
                    'service_provider' => $resource['serviceProvider']['display'] ?? null,
                    'location' => $resource['location'][0]['location']['display'] ?? null,
                    'encounter_date' => Carbon::parse($resource['period']['start'])->format('Y-m-d H:i:s') ?? null,
                    'status' => $resource['status'] ?? null,
                    'raw_json' => json_encode($resource)
                ]
            );
        }
    }
    }

    return response()->json(['message' => 'Import berhasil']);
}
}

