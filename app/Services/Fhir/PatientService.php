<?php
namespace App\Services\Fhir;

use App\Models\Rme\Patient;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class PatientService
{
    /**
     * =========================================================
     * SAVE ONE FHIR PATIENT
     * =========================================================
     */
    public function saveFromFhir($data)
    {
        if (!isset($data['resource'])) {
            return null;
        }

        $r = $data['resource'];

        return $this->savePatientResource($r);
    }

    /**
     * =========================================================
     * PARSE + SAVE PATIENT RESOURCE
     * =========================================================
     */
    protected function savePatientResource($r)
    {
        if (
            !$r ||
            ($r['resourceType'] ?? null) !== 'Patient' ||
            empty($r['id'])
        ) {
            return null;
        }

        // =====================================================
        // IDENTIFIER
        // =====================================================

        $ihs = null;
        $nik = null;
        $bpjs = null;
        $nikIbu = null;

        foreach ($r['identifier'] ?? [] as $id) {

            $code = $id['type']['coding'][0]['code'] ?? null;
            $value = $id['value'] ?? null;

            if (!$value) {
                continue;
            }

            switch ($code) {

                case 'IHS':
                    $ihs = $value;
                    break;

                case 'NIK':
                    $nik = $value;
                    break;

                case 'BPJS-Kes':
                    $bpjs = $value;
                    break;

                case 'NIK-IBU':
                    $nikIbu = $value;
                    break;
            }
        }

        // =====================================================
        // TELECOM
        // =====================================================

        $phone = null;
        $email = null;

        foreach ($r['telecom'] ?? [] as $t) {

            $system = $t['system'] ?? null;
            $value = $t['value'] ?? null;

            if ($system === 'phone' && $value) {
                $phone = $value;
            }

            if ($system === 'email' && $value) {
                $email = $value;
            }
        }

        // =====================================================
        // ADDRESS
        // =====================================================

        $kode_propinsi = null;
        $kode_kota = null;
        $kode_kecamatan = null;

        $address = $r['address'][0] ?? [];

        $extensions = $address['extension'][0]['extension'] ?? [];

        foreach ($extensions as $e) {

            $url = $e['url'] ?? null;

            if ($url === 'province') {
                $kode_propinsi =
                    $e['valueCoding']['code'] ?? null;
            }

            if ($url === 'city') {
                $kode_kota =
                    $e['valueCoding']['code'] ?? null;
            }

            if ($url === 'district') {
                $kode_kecamatan =
                    $e['valueCoding']['code'] ?? null;
            }
        }

        // =====================================================
        // SAVE
        // =====================================================

        return Patient::updateOrCreate(
            [
                'patient_id' => $r['id']
            ],
            [
                'ihs_number' => $ihs,
                'nik' => $nik,
                'bpjs' => $bpjs,
                'nik_ibu' => $nikIbu,

                'name' =>
                    $r['name'][0]['text'] ?? null,

                'phone' => $phone,
                'email' => $email,

                'gender' =>
                    $r['gender'] ?? null,

                'birth_date' =>
                    isset($r['birthDate'])
                        ? date(
                            'Y-m-d',
                            strtotime($r['birthDate'])
                        )
                        : null,

                'address' =>
                    $address['line'][0] ?? null,

                'kode_propinsi' =>
                    $kode_propinsi,

                'kode_kota' =>
                    $kode_kota,

                'kode_kecamatan' =>
                    $kode_kecamatan
            ]
        );
    }

    /**
     * =========================================================
     * CHECK WHETHER PATIENT HAS THE REQUESTED IDENTIFIER
     * =========================================================
     */
    protected function hasIdentifier(
        array $resource,
        string $searchType,
        string $identifier
    ): bool {

        $expectedCode = match ($searchType) {
            'nik' => 'NIK',
            'nik_ibu' => 'NIK-IBU',
            default => null,
        };

        if (!$expectedCode) {
            return false;
        }

        foreach ($resource['identifier'] ?? [] as $id) {

            $code = $id['type']['coding'][0]['code'] ?? null;
            $value = $id['value'] ?? null;

            if (
                $code === $expectedCode &&
                $value === $identifier
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * =========================================================
     * SEARCH PATIENT
     *
     * $searchType:
     *   nik
     *   nik_ibu
     * =========================================================
     */
    public function searchByIdentifier(
        $identifier,
        $searchType = 'nik'
    ) {

        // =====================================================
        // VALIDATE SEARCH TYPE
        // =====================================================

        if (!in_array($searchType, ['nik', 'nik_ibu'])) {
            return collect();
        }

        if (!$identifier) {
            return collect();
        }

        // =====================================================
        // SEARCH LOCAL DATABASE
        // =====================================================

        $column = $searchType === 'nik'
            ? 'nik'
            : 'nik_ibu';

        $patients = Patient::with([
            'province',
            'city',
            'district'
        ])
            ->where($column, $identifier)
            ->get();

        // =====================================================
        // FOUND LOCAL
        // =====================================================

        if ($patients->isNotEmpty()) {
            return $patients->values();
        }

        // =====================================================
        // SEARCH FHIR
        // =====================================================

        $fhir = app(FhirClient::class)
            ->searchPatient(
                $identifier,
                $searchType
            );

        if (!$fhir) {
            return collect();
        }

        // =====================================================
        // FHIR RESPONSE
        //
        // Expected:
        // [
        //     'resourceType' => 'Bundle',
        //     'type' => 'searchset',
        //     'entry' => [...]
        // ]
        //
        // Be tolerant if the Bundle is wrapped inside
        // another "resource" key.
        // =====================================================

        $entries = $fhir['entry'] ?? [];

        if (empty($entries)) {
            $entries =
                $fhir['resource']['entry'] ?? [];
        }

        if (empty($entries)) {
            return collect();
        }

        // =====================================================
        // FILTER + SAVE FHIR RESULTS
        // =====================================================

        $patients = collect();

        foreach ($entries as $entry) {

            $resource =
                $entry['resource'] ?? null;

            if (
                !$resource ||
                ($resource['resourceType'] ?? null) !== 'Patient'
            ) {
                continue;
            }

            // -------------------------------------------------
            // IMPORTANT
            //
            // FHIR dapat mengembalikan Patient yang cocok
            // dengan nilai identifier yang sama, tetapi tipe
            // identifier berbeda.
            //
            // Contoh:
            //
            // NIK     = 9104224606000005
            // NIK-IBU = 9104224606000005
            //
            // Karena itu harus difilter berdasarkan CODE,
            // bukan hanya VALUE.
            // -------------------------------------------------

            if (!$this->hasIdentifier(
                $resource,
                $searchType,
                $identifier
            )) {
                continue;
            }

            $patient =
                $this->savePatientResource($resource);

            if (!$patient) {
                continue;
            }

            // Load relasi wilayah setelah save
            $patient->load([
                'province',
                'city',
                'district'
            ]);

            $patients->push($patient);
        }

        return $patients->values();
    }

    /**
     * =========================================================
     * BACKWARD COMPATIBILITY
     *
     * Tetap bisa dipakai oleh kode lama:
     *
     * searchByNik($nik)
     * =========================================================
     */
    public function searchByNik($nik)
    {
        $patients = $this->searchByIdentifier(
            $nik,
            'nik'
        );

        return $patients->first();
    }
}

