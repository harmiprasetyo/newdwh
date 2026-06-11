<?php
namespace App\Services\Fhir;

use App\Models\Rme\Encounter;
use App\Services\Fhir\FhirClient;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
class EncounterService
{
    public function saveFromFhir($data)
    {
        if (!isset($data['entry'])) {
            return [];
        }

        $results = [];
        $orgCache = [];

        foreach ($data['entry'] as $entry) {

            $r = $entry['resource'] ?? null;
            if (!$r) continue;

            // =========================
            // IDENTIFIER
            // =========================
            $identifier = $r['identifier'][0]['value'] ?? null;

            // =========================
            // CLASS
            // =========================
            $classCode = $r['class']['code'] ?? null;
            $classDisplay = $r['class']['display'] ?? null;

            // =========================
            // PRACTITIONER
            // =========================
            $practitionerName = null;
            $practitionerId = null;

            if (!empty($r['participant'][0]['individual'])) {
                $practitionerName = $r['participant'][0]['individual']['display'] ?? null;

                $ref = $r['participant'][0]['individual']['reference'] ?? null;
                $practitionerId = $ref ? explode('/', $ref)[1] : null;
            }

            // =========================
            // LOCATION
            // =========================
            $locationName = null;
            $locationId = null;

            if (!empty($r['location'][0]['location'])) {
                $locationName = $r['location'][0]['location']['display'] ?? null;

                $ref = $r['location'][0]['location']['reference'] ?? null;
                $locationId = $ref ? explode('/', $ref)[1] : null;
            }

            // =========================
            // PATIENT
            // =========================
            $patientRef = $r['subject']['reference'] ?? null;
            $patientId = $patientRef ? explode('/', $patientRef)[1] : null;

            // =========================
            // TIME
            // =========================
           $start = !empty($r['period']['start'])
    ? Carbon::parse($r['period']['start'])->format('Y-m-d H:i:s')
    : null;

$end = !empty($r['period']['end'])
    ? Carbon::parse($r['period']['end'])->format('Y-m-d H:i:s')
    : null;


    // =========================
// SERVICE PROVIDER
// =========================
$serviceProviderId = null;
$serviceProviderName = null;

if (!empty($r['serviceProvider']['reference'])) {
    $ref = $r['serviceProvider']['reference'];
    $serviceProviderId = explode('/', $ref)[1];
}




if ($serviceProviderId) {

    if (!isset($orgCache[$serviceProviderId])) {
        $orgCache[$serviceProviderId] = app(FhirClient::class)
            ->getOrganization($serviceProviderId);
    }

    $serviceProviderName = $orgCache[$serviceProviderId]['name'] ?? null;
}

// ambil semua identifier
$identifiers = $r['identifier'] ?? [];

// ambil identifier utama (first value)
$identifier = collect($identifiers)
    ->pluck('value')
    ->filter()
    ->first();



            // =========================
            // SAVE
            // =========================
            $enc = Encounter::updateOrCreate(
                [
                    'encounter_id' => $r['id']
                ],
                [
                    'patient_id' => $patientId,
                    'identifier' => $identifier,
                    'identifiers' => json_encode($identifiers), // ✅ full JSON
                    'status' => $r['status'] ?? null,
                    'class_code' => $classCode,
                    'class_display' => $classDisplay,
                    'practitioner_name' => $practitionerName,
                    'practitioner_id' => $practitionerId,
                    'location_name' => $locationName,
                    'location_id' => $locationId,
                    'start' => $start,
                    'end' => $end,
                     'service_provider_id' => $serviceProviderId,
    'service_provider_name' => $serviceProviderName
                ]
            );

            $results[] = $enc;
        }

        return $results;
    }




public function getByPatient($patientId)
{


        $fhir = app(FhirClient::class)->getEncounterByPatient($patientId);

        if (!$fhir || empty($fhir['entry'])) {
            return collect();
        }



        return collect($this->saveFromFhir($fhir));
   // });
}

public function getById($encounterID)
{
   // return Cache::remember("encounter:$encounterID", 10, function () use ($encounterID) {

        // Ambil dari DB dulu
        $local = Encounter::where('encounter_id', $encounterID)->get();

        // Ambil dari FHIR
        $fhir = app(FhirClient::class)->getEncounterByID($encounterID);

        if ($fhir && !empty($fhir['entry'])) {
            // Update / insert dari FHIR
            $this->saveFromFhir($fhir);
        }

        // Return data terbaru dari DB
        return Encounter::where('encounter_id', $encounterID)->get();
   // });
}

}
