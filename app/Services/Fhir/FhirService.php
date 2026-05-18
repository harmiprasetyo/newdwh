<?php
namespace App\Services\Fhir;

use Illuminate\Support\Facades\Http;

class FhirService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('FHIR_API_URL');
        $this->token = env('FHIR_API_TOKEN');
    }

    public function getPatient($nik)
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . "Patient?identifier=" . $nik)
            ->json();
    }

    public function getObservation($patientId, $encounterId)
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . "Observation?patient=$patientId&encounter=$encounterId")
            ->json();
    }

    public function getCondition($encounterId)
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . "Condition?encounter=$encounterId")
            ->json();
    }
}
