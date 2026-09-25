<?php
namespace App\Services\Fhir;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FhirClient
{
    protected $baseUrl;
    protected $token;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('FHIR_API_URL'), '/') . '/';
        $this->token = env('FHIR_API_TOKEN');
    }

    protected function getAccessToken()
    {
        return $this->token;
    }

    /**
     * =========================================================
     * SEARCH PATIENT BY NIK / NIK-IBU
     * =========================================================
     */
    public function searchPatient($identifier, $searchType = 'nik')
    {
        $identifierSystem = $searchType === 'nik_ibu'
            ? 'https://fhir.kemkes.go.id/id/nik-ibu'
            : 'https://fhir.kemkes.go.id/id/nik';

        $response = Http::withToken($this->token)
            ->acceptJson()
            ->get($this->baseUrl . 'Patient', [
                'identifier' => $identifier,
                '_count' => 100,
            ]);

        if ($response->failed()) {

            Log::error('FHIR Patient Search Failed', [
                'identifier' => $identifier,
                'search_type' => $searchType,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        $data = $response->json();

        return [
            'resource' => $data,
            'identifier' => $identifier,
            'identifier_system' => $identifierSystem,
        ];
    }

    /**
     * =========================================================
     * GET ORGANIZATION
     * =========================================================
     */
    public function getOrganization($id)
    {
        $url = $this->baseUrl . 'Organization/' . $id;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    /**
     * =========================================================
     * ENCOUNTER
     * =========================================================
     */
    public function encounter($pid)
    {
        return Http::withToken($this->token)
            ->get(
                $this->baseUrl .
                'Encounter?patient=' .
                $pid .
                '&_count=100'
            )
            ->json();
    }

    public function getEncounterByPatient($patientId)
    {
        $url = $this->baseUrl . 'Encounter';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->get($url, [
            'patient' => $patientId,
            '_count' => 100
        ]);

        if ($response->failed()) {

            Log::error($response->body());

            return null;
        }

        return $response->json();
    }

    public function getEncounterByID($encounterID)
    {
        $url = $this->baseUrl . 'Encounter/' . $encounterID;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function getImmunizationByPatient($patientId)
    {
        return Http::withToken($this->token)
            ->get(
                $this->baseUrl .
                'Immunization?patient=' .
                $patientId
            )
            ->json();
    }
}

