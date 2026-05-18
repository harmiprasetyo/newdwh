<?php
namespace App\Services\Fhir;

use App\Models\PregnancyRecord;
use App\Models\VitalSign;
use App\Models\Measurement;
use App\Models\Observation\AncDeliveryRecord;

class ObservationService
{
    public function process($data, $patientId, $encounterId)
    {
        $pregnancy = [];
        $vital = [];
        $measure = [];
        $delivery = [];

        foreach ($data['entry'] ?? [] as $item) {

            $r = $item['resource'];
            $code = $r['code']['coding'][0]['code'] ?? null;

            switch ($code) {

                case '11996-6':
                    $pregnancy['gravida'] = $r['valueInteger'];
                    break;

                case '8480-6':
                    $vital['systolic'] = $r['valueQuantity']['value'];
                    break;

                case '29463-7':
                    $measure['weight'] = $r['valueQuantity']['value'];
                    break;

                case '93857-1':
                    $delivery['delivery_time'] =
                        date('Y-m-d H:i:s', strtotime($r['valueDateTime']));
                    break;
            }
        }

        // inject key
        foreach ([$pregnancy, $vital, $measure, $delivery] as &$arr) {
            $arr['patient_id'] = $patientId;
            $arr['encounter_id'] = $encounterId;
        }

        PregnancyRecord::updateOrCreate(
            ['patient_id'=>$patientId,'encounter_id'=>$encounterId],
            $pregnancy
        );

        VitalSign::updateOrCreate(
            ['patient_id'=>$patientId,'encounter_id'=>$encounterId],
            $vital
        );

        Measurement::updateOrCreate(
            ['patient_id'=>$patientId,'encounter_id'=>$encounterId],
            $measure
        );

        AncDeliveryRecord::updateOrCreate(
            ['patient_id'=>$patientId,'encounter_id'=>$encounterId],
            $delivery
        );
    }
}
