<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Dashboard\Patient;
use App\Models\Dashboard\Encounter;
use Carbon\Carbon;

class FhirImportService
{
    protected $token;
    protected $server;

    public function __construct()
    {
        $this->token = env('FHIR_API_TOKEN');
        $this->server = env('FHIR_API_URL');


    }

    // ======================
    // 🔁 GENERIC FETCH PAGINATION
    // ======================
    public function fetchAll($endpoint)
    {
        $url = $this->server . $endpoint;

        do {
            $res = Http::withToken($this->token)->get($url)->json();

            foreach ($res['entry'] ?? [] as $entry) {
                yield $entry['resource'];
            }

            $next = collect($res['link'] ?? [])
                ->firstWhere('relation', 'next');

            $url = $next['url'] ?? null;

        } while ($url);
    }

    // ======================
    // 🧍 IMPORT PATIENT
    // ======================
    public function importPatient()
    {
        foreach ($this->fetchAll('Patient?_count=20') as $resource) {

            Patient::updateOrCreate(
                ['patient_id' => $resource['id']],
                [
                    'nik' => $resource['identifier'][0]['value'] ?? null,
                    'name' => $resource['name'][0]['text'] ?? null,
                    'gender' => $resource['gender'] ?? null,
                    'birth_date' => isset($resource['birthDate'])
                        ? Carbon::parse($resource['birthDate'])->format('Y-m-d')
                        : null,
                    'raw_json' => json_encode($resource)
                ]
            );
        }
    }

    // ======================
    // 🏥 IMPORT ENCOUNTER
    // ======================
    public function importEncounter()
    {
        foreach ($this->fetchAll('Encounter?_count=20') as $resource) {

            $patientRef = $resource['subject']['reference'] ?? null;
            $patientFhirId = str_replace('Patient/', '', $patientRef);

            $patient = Patient::where('patient_id', $patientFhirId)->first();
            $serviceProvider = Http::withToken($this->token)->get($this->server.$resource['serviceProvider']['reference']);
            $serviceProviderData = $serviceProvider->json();
            $resource['serviceProvider']['display'] = $serviceProviderData['name'] ?? null;

            Encounter::updateOrCreate(
                ['encounter_id' => $resource['id']],
                [
                    'patient_id' => $patient->id ?? null,
                    'service_provider' => $resource['serviceProvider']['display'],
                    'location' => $resource['location'][0]['location']['display'] ?? null,
                    'encounter_date' => isset($resource['period']['start'])
                        ? Carbon::parse($resource['period']['start'])
                        : null,
                    'status' => $resource['status'] ?? null,
                    'raw_json' => json_encode($resource)
                ]
            );
        }
    }

    // ======================
    // 🚀 MAIN IMPORT
    // ======================
    public function run()
    {
        $this->importPatient();
        $this->importEncounter();
    }
}
