<?php
namespace App\Services;

use App\Models\Observation\AncDeliveryRecord as AncDeliveryRecord;

class FhirObservationService
{
    public function storeFromBundle($bundle)
    {
        $data = [
            'patient_id' => null,
            'encounter_id' => null,
        ];

        foreach ($bundle['entry'] as $item) {

            $obs = $item['resource'];

            // ambil patient & encounter sekali saja
            $data['patient_id'] = explode('/', $obs['subject']['reference'])[1] ?? null;
            $data['encounter_id'] = explode('/', $obs['encounter']['reference'])[1] ?? null;

            $code = $obs['code']['coding'][0]['code'];

            switch ($code) {

                case '18185-9':
                    $data['gestational_age'] = $obs['valueQuantity']['value'] ?? null;
                    break;

                case '11996-6':
                    $data['gravida'] = $obs['valueInteger'] ?? null;
                    break;

                case '64708-1':
                    $data['parity'] = $obs['valueInteger'] ?? null;
                    break;

                case '69043-8':
                    $data['abortus'] = $obs['valueInteger'] ?? null;
                    break;

                case '93857-1':
                    $data['delivery_time'] = $obs['valueDateTime'] ?? null;
                    break;

                case '249197004':
                    $data['postpartum_condition'] =
                        $obs['valueCodeableConcept']['coding'][0]['display'] ?? null;
                    break;

                case 'OC000013':
                    $data['delivery_helper'] =
                        $obs['valueCodeableConcept']['coding'][0]['display'] ?? null;
                    break;

                case '57071-3':
                    $data['delivery_method'] =
                        $obs['valueCodeableConcept']['coding'][0]['display'] ?? null;
                    break;

                case '249120008':
                    $data['stage1'] = $obs['valueDateTime'] ?? null;
                    break;

                case '249160009':
                    $data['stage2'] = $obs['valueDateTime'] ?? null;
                    break;

                case 'OC000018':
                    $data['stage3'] = $obs['valueDateTime'] ?? null;
                    break;

                case 'OC000019':
                    $data['stage4'] = $obs['valueDateTime'] ?? null;
                    break;
            }
        }

        return AncDeliveryRecord::create($data);
    }
}
