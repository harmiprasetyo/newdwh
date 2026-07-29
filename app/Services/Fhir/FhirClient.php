<?php

namespace App\Services\Fhir;

use Illuminate\Support\Facades\Http;

class FhirClient
{
    protected $baseUrl;
    protected $token;
    protected $apiKey;

    public function __construct()
    {


        $this->baseUrl = env('FHIR_API_URL');
        $this->token = env('FHIR_API_TOKEN');

    }

    protected function getAccessToken()
    {
        // contoh static (atau ambil dari cache / oauth)
        return env('FHIR_API_TOKEN');
    }

     public function searchPatientByNik($nik)
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . "Patient?identifier=" . $nik)
            ->json();
    }

    public function getOrganization($id)
{
    $url = $this->baseUrl . '/Organization/' . $id;

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json'
    ])->get($url);

    if ($response->failed()) {
        return null;
    }

    return $response->json();
}


    public function encounter($pid){
        return Http::withToken($this->token)->get($this->baseUrl.'Encounter?patient='.$pid."&_count=100")->json();
    }

 /*   public function getEncounterByPatient($patientId)
{
    $url = $this->baseUrl . '/Encounter';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json'
    ])->get($url, [
        'patient' => $patientId
    ]);
    dd($response->json());

    if ($response->failed()) {
        return null;
    }

    return $response->json();
}*/

public function getEncounterByPatient($patientId)
{
    $url = $this->baseUrl . '/Encounter';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json'
    ])->get($url, [
        // 🔥 FIX DI SINI
        'patient' => $patientId,
        '_count' => 100
    ]);

    if ($response->failed()) {
        \Log::error($response->body());
        return null;
    }

    return $response->json();
}





public function getEncounterByID($encounterID)
{
    $url = $this->baseUrl . '/Encounter';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json'
    ])->get($url.'/'.$encounterID);

    if ($response->failed()) {
        return null;
    }

    return $response->json();
}


public function getImmunizationByPatient($patientId)
{

    return Http::withToken($this->token)->get($this->baseUrl.'Immunization?patient='.$patientId)->json();
}



}
