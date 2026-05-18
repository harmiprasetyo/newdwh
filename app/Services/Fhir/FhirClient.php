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

    public function encounter($pid){
        return Http::withToken($this->token)->get($this->baseUrl.'Encounter?patient='.$pid)->json();
    }

}
