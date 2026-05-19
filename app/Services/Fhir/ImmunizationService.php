<?php
namespace App\Services\Fhir;

use App\Models\Rme\Immunization;

class ImmunizationService
{
   public function saveFromFhir($data)
{
    if (empty($data['entry'])) return [];

    $results = [];
    $orgCache = [];

    foreach ($data['entry'] as $entry) {

        $r = $entry['resource'];

        // =========================
        // BASIC
        // =========================
        $immunizationId = $r['id'];

        $patientId = explode('/', $r['patient']['reference'])[1] ?? null;
        $encounterId = isset($r['encounter']['reference'])
            ? explode('/', $r['encounter']['reference'])[1]
            : null;

        // =========================
        // VACCINE
        // =========================
        $coding = $r['vaccineCode']['coding'][0] ?? [];

        $vaccineCode = $coding['code'] ?? null;
        $vaccineName = $coding['display'] ?? null;

        // =========================
        // DATE
        // =========================
        $immunizationDate = isset($r['occurrenceDateTime'])
            ? date('Y-m-d H:i:s', strtotime($r['occurrenceDateTime']))
            : null;

        $recordedAt = isset($r['recorded'])
            ? date('Y-m-d H:i:s', strtotime($r['recorded']))
            : null;

        // =========================
        // LOCATION
        // =========================
        $locationName = $r['location']['display'] ?? null;

        // =========================
        // SERVICE PROVIDER (ORG)
        // =========================
        $serviceProviderId = null;
        $serviceProviderName = null;

        foreach ($r['performer'] ?? [] as $p) {

            $ref = $p['actor']['reference'] ?? null;

            if ($ref && str_contains($ref, 'Organization')) {

                $serviceProviderId = explode('/', $ref)[1];

                if (!isset($orgCache[$serviceProviderId])) {
                    $orgCache[$serviceProviderId] = app(FhirClient::class)
                        ->getOrganization($serviceProviderId);
                }

                $serviceProviderName =
                    $orgCache[$serviceProviderId]['name'] ?? null;

                break;
            }
        }

        // =========================
        // SAVE
        // =========================
        $im = Immunization::updateOrCreate(
            ['immunization_id' => $immunizationId],
            [
                'patient_id' => $patientId,
                'encounter_id' => $encounterId,
                'vaccine_code' => $vaccineCode,
                'vaccine_name' => $vaccineName,
                'immunization_date' => $immunizationDate,
                'recorded_at' => $recordedAt,
                'location_name' => $locationName,
                'service_provider_id' => $serviceProviderId,
                'service_provider_name' => $serviceProviderName
            ]
        );

        $results[] = $im;
    }

    return $results;
}



public function getByPatient($patientId, $force = false)
{
    if (!$force) {
        $data = Immunization::where('patient_id', $patientId)->get();

        if ($data->count()) {
            return $data;
        }
    }

    $fhir = app(FhirClient::class)
        ->getImmunizationByPatient($patientId);

    if (!$fhir || empty($fhir['entry'])) {
        return collect();
    }

    $saved = $this->saveFromFhir($fhir);

    return collect($saved);
}
}
