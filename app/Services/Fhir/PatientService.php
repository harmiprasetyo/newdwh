<?php
namespace App\Services\Fhir;

use App\Models\Rme\Patient;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class PatientService
{
    public function saveFromFhir($data)
    {
        if (!isset($data['entry'][0]['resource'])) {
            return null;
        }

        $r = $data['entry'][0]['resource'];

        // =========================
        // IDENTIFIER
        // =========================
        $ihs = null;
        $nik = null;
        $bpjs = null;

        foreach ($r['identifier'] ?? [] as $id) {
            $code = $id['type']['coding'][0]['code'] ?? null;

            if ($code == 'IHS') $ihs = $id['value'];
            if ($code == 'NIK') $nik = $id['value'];
            if ($code == 'BPJS-Kes') $bpjs = $id['value'];
        }

        // =========================
        // TELECOM
        // =========================
        $phone = null;
        $email = null;

        foreach ($r['telecom'] ?? [] as $t) {
            if ($t['system'] == 'phone') $phone = $t['value'] ?? null;
            if ($t['system'] == 'email') $email = $t['value'] ?? null;
        }

        // =========================
        // ADDRESS EXTENSION
        // =========================
$province_id = null;
$city_id = null;
$district_id = null;

       $ext = $r['address'][0]['extension'][0]['extension'] ?? [];

$kode_propinsi = null;
$kode_kota = null;
$kode_kecamatan = null;

        foreach ($ext as $e) {
            if ($e['url'] == 'province') {
                $kode_propinsi = $e['valueCoding']['code'] ?? null;
            }
            if ($e['url'] == 'city') {
                $kode_kota = $e['valueCoding']['code'] ?? null;
            }
            if ($e['url'] == 'district') {
                $kode_kecamatan = $e['valueCoding']['code'] ?? null;
            }
        }

$province_id = Province::where('code', $kode_propinsi)->value('id');
$city_id = City::where('code', $kode_kota)->value('id');
$district_id = District::where('code', $kode_kecamatan)->value('id');

        // =========================
        // SAVE DB
        // =========================
     return Patient::updateOrCreate(
    [
        'patient_id' => $r['id']
    ],
    [
        'ihs_number' => $ihs,
        'nik' => $nik,
        'bpjs' => $bpjs,
        'name' => $r['name'][0]['text'] ?? null,
        'phone' => $phone,
        'email' => $email,
        'gender' => $r['gender'] ?? null,
        'birth_date' => isset($r['birthDate'])
            ? date('Y-m-d', strtotime($r['birthDate']))
            : null,
        'address' => $r['address'][0]['line'][0] ?? null,

        // langsung simpan code
        'kode_propinsi' => $kode_propinsi,
        'kode_kota' => $kode_kota,
        'kode_kecamatan' => $kode_kecamatan
    ]
);
    }

    public function searchByNik($nik)
{
    $patient =  Patient::with([
        'province',
        'city',
        'district'
    ])->where('nik', $nik)->first();

    if ($patient) {
        return $patient;
    }

     // fallback ke FHIR
    $fhir = app(FhirClient::class)->searchPatientByNik($nik);

    if ($fhir) {
        return $this->saveFromFhir($fhir);
    }

    return null;



}
}
