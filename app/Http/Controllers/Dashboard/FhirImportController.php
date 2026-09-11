<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Dashboard\Patient;
use App\Models\Dashboard\Encounter;
use Carbon\Carbon;

class FhirImportController extends Controller
{
    /**
     * Import Patient dan Encounter dari FHIR Server
     */
    public function import()
    {
        $token  = env('FHIR_API_TOKEN');
        $server = rtrim(env('FHIR_API_URL'), '/') . '/';

        /*
        |--------------------------------------------------------------------------
        | VALIDASI CONFIG
        |--------------------------------------------------------------------------
        */

        if (empty($token) || empty($server)) {

            return response()->json([
                'success' => false,
                'message' => 'FHIR_API_TOKEN atau FHIR_API_URL belum dikonfigurasi.'
            ], 500);

        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL PATIENT
        |--------------------------------------------------------------------------
        */

        $response = Http::withToken($token)
            ->get($server . 'Patient?_count=1000');


        if (!$response->successful()) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data Patient dari FHIR Server.',
                'status' => $response->status(),
                'response' => $response->json()
            ], 500);

        }


        $data = $response->json();


        $patientImported  = 0;
        $encounterImported = 0;


        /*
        |--------------------------------------------------------------------------
        | LOOP PATIENT
        |--------------------------------------------------------------------------
        */

        foreach ($data['entry'] ?? [] as $entry) {

            /*
            |--------------------------------------------------------------------------
            | VALIDASI RESOURCE
            |--------------------------------------------------------------------------
            */

            $resource = $entry['resource'] ?? null;

            if (!$resource) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | HANYA PATIENT
            |--------------------------------------------------------------------------
            */

            if (($resource['resourceType'] ?? null) !== 'Patient') {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | PATIENT DATA
            |--------------------------------------------------------------------------
            */

            $patientId = $resource['id'] ?? null;

            if (!$patientId) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | NIK
            |--------------------------------------------------------------------------
            */

            $nik = null;

            foreach ($resource['identifier'] ?? [] as $identifier) {

                if (
                    isset($identifier['value'])
                    && !empty($identifier['value'])
                ) {

                    $nik = $identifier['value'];

                    break;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */

            $name = $resource['name'][0]['text']
                ?? $resource['name'][0]['family']
                ?? null;


            /*
            |--------------------------------------------------------------------------
            | TANGGAL LAHIR
            |--------------------------------------------------------------------------
            */

            $birthDate = null;

            if (!empty($resource['birthDate'])) {

                try {

                    $birthDate = Carbon::parse(
                        $resource['birthDate']
                    )->format('Y-m-d');

                } catch (\Throwable $e) {

                    $birthDate = null;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | SIMPAN PATIENT
            |--------------------------------------------------------------------------
            */

            Patient::updateOrCreate(

                [
                    'patient_id' => $patientId
                ],

                [
                    'nik' => $nik,

                    'name' => $name,

                    'gender' =>
                        $resource['gender'] ?? null,

                    'birth_date' => $birthDate,

                    'raw_json' =>
                        json_encode(
                            $resource,
                            JSON_UNESCAPED_UNICODE
                        )
                ]

            );


            $patientImported++;


            /*
            |--------------------------------------------------------------------------
            | AMBIL ENCOUNTER PATIENT
            |--------------------------------------------------------------------------
            */

            $response2 = Http::withToken($token)
                ->get(
                    $server
                    . 'Encounter?patient='
                    . urlencode($patientId)
                    . '&_count=1000'
                );


            if (!$response2->successful()) {
                continue;
            }


            $data2 = $response2->json();


            /*
            |--------------------------------------------------------------------------
            | LOOP ENCOUNTER
            |--------------------------------------------------------------------------
            */

            foreach ($data2['entry'] ?? [] as $entry2) {

                $encounter = $entry2['resource'] ?? null;

                if (!$encounter) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | HANYA ENCOUNTER
                |--------------------------------------------------------------------------
                */

                if (
                    ($encounter['resourceType'] ?? null)
                    !== 'Encounter'
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | ENCOUNTER ID
                |--------------------------------------------------------------------------
                */

                $encounterId = $encounter['id'] ?? null;

                if (!$encounterId) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | PATIENT REFERENCE
                |--------------------------------------------------------------------------
                */

                $patientRef =
                    $encounter['subject']['reference']
                    ?? null;


                $patientFhirId = $patientRef
                    ? str_replace(
                        'Patient/',
                        '',
                        $patientRef
                    )
                    : null;


                $patientModel = null;

                if ($patientFhirId) {

                    $patientModel =
                        Patient::where(
                            'patient_id',
                            $patientFhirId
                        )->first();

                }


                /*
                |--------------------------------------------------------------------------
                | SERVICE PROVIDER
                |--------------------------------------------------------------------------
                |
                | TIDAK SEMUA ENCOUNTER MEMILIKI
                | serviceProvider.
                |
                */

                $serviceProviderDisplay = null;


                $serviceProviderReference =
                    $encounter['serviceProvider']['reference']
                    ?? null;


                if ($serviceProviderReference) {

                    $serviceProviderResponse =
                        Http::withToken($token)
                            ->get(
                                $server
                                . ltrim(
                                    $serviceProviderReference,
                                    '/'
                                )
                            );


                    if ($serviceProviderResponse->successful()) {

                        $serviceProviderData =
                            $serviceProviderResponse->json();


                        $serviceProviderDisplay =
                            $serviceProviderData['name']
                            ?? null;

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | LOCATION
                |--------------------------------------------------------------------------
                |
                | Tidak semua Encounter memiliki location.
                |
                */

                $locationDisplay = null;

                $locationReference =
                    $encounter['location'][0]['location']['reference']
                    ?? null;


                if ($locationReference) {

                    $locationResponse =
                        Http::withToken($token)
                            ->get(
                                $server
                                . ltrim(
                                    $locationReference,
                                    '/'
                                )
                            );


                    if ($locationResponse->successful()) {

                        $locationData =
                            $locationResponse->json();


                        /*
                        | Ambil display dari resource Location
                        */

                        $locationDisplay =
                            $locationData['name']
                            ?? null;

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | FALLBACK LOCATION DISPLAY
                |--------------------------------------------------------------------------
                */

                if (!$locationDisplay) {

                    $locationDisplay =
                        $encounter['location'][0]['location']['display']
                        ?? null;

                }


                /*
                |--------------------------------------------------------------------------
                | ENCOUNTER DATE
                |--------------------------------------------------------------------------
                */

                $encounterDate = null;


                if (
                    !empty(
                        $encounter['period']['start']
                    )
                ) {

                    try {

                        $encounterDate =
                            Carbon::parse(
                                $encounter['period']['start']
                            )->format(
                                'Y-m-d H:i:s'
                            );

                    } catch (\Throwable $e) {

                        $encounterDate = null;

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | SIMPAN ENCOUNTER
                |--------------------------------------------------------------------------
                */

                Encounter::updateOrCreate(

                    [
                        'encounter_id' => $encounterId
                    ],

                    [

                        'patient_id' =>
                            $patientModel->id
                            ?? null,

                        'service_provider' =>
                            $serviceProviderDisplay,

                        'location' =>
                            $locationDisplay,

                        'encounter_date' =>
                            $encounterDate,

                        'status' =>
                            $encounter['status']
                            ?? null,

                        'raw_json' =>
                            json_encode(
                                $encounter,
                                JSON_UNESCAPED_UNICODE
                            )

                    ]

                );


                $encounterImported++;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'message' =>
                'Import FHIR berhasil.',

            'patient_imported' =>
                $patientImported,

            'encounter_imported' =>
                $encounterImported

        ]);

    }
}

