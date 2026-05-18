<?php
namespace App\Jobs;

use App\Services\Fhir\FhirService;
use App\Services\PatientService;
use App\Services\ObservationService;
use App\Services\ConditionService;

class SyncFhirPatientJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $nik;

    public function __construct($nik)
    {
        $this->nik = $nik;
    }

    public function handle(
        FhirService $fhir,
        PatientService $patientService,
        ObservationService $obsService,
        ConditionService $conditionService
    ) {

        // =========================
        // 1. EXTRACT PATIENT
        // =========================
        $patientData = $fhir->getPatient($this->nik);

        $patient = $patientService->saveFromFhir($patientData);

        if (!$patient) return;

        $patientId = $patient->patient_id;

        // ⚠️ ambil encounter dari sistem kamu (atau FHIR kalau ada)
        $encounterId = request()->encounter ?? null;

        // =========================
        // 2. EXTRACT OBSERVATION
        // =========================
        if ($encounterId) {
            $obsData = $fhir->getObservation($patientId, $encounterId);
            $obsService->process($obsData, $patientId, $encounterId);
        }

        // =========================
        // 3. EXTRACT CONDITION
        // =========================
        if ($encounterId) {
            $condData = $fhir->getCondition($encounterId);
            $conditionService->process($condData, $patientId, $encounterId);
        }
    }
}
