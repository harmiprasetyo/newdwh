<?php
namespace App\Services;

use App\Models\Condition;

class ConditionService
{
    public function process($data, $patientId, $encounterId)
    {
        foreach ($data['entry'] ?? [] as $item) {

            $r = $item['resource'];

            Condition::updateOrCreate(
                [
                    'condition_id' => $r['id']
                ],
                [
                    'patient_id' => $patientId,
                    'encounter_id' => $encounterId,
                    'code' => $r['code']['coding'][0]['code'] ?? null,
                    'display' => $r['code']['coding'][0]['display'] ?? null,
                    'onset_date' => isset($r['onsetDateTime'])
                        ? date('Y-m-d H:i:s', strtotime($r['onsetDateTime']))
                        : null
                ]
            );
        }
    }
}
