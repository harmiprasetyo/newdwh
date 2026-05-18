<?php
namespace App\Services\FHIR;

use Carbon\Carbon;

class EncounterService
{
    public function map($r)
    {
        return [

            'encounter_id' => $r['id'],

            'patient_id' => $this->extractId($r['subject']['reference'] ?? null),

            'status' => $r['status'] ?? null,

            'class_code' => $r['class']['code'] ?? null,
            'class_display' => $r['class']['display'] ?? null,

            'practitioner_name' =>
                $r['participant'][0]['individual']['display'] ?? null,

            'location' =>
                $r['location'][0]['location']['display'] ?? null,

            'provider_id' =>
                $this->extractId($r['serviceProvider']['reference'] ?? null),

            'visit_type' =>
                $this->extractVisitType($r['identifier'] ?? []),

            'start_at' =>
                $this->parseDate($r['period']['start'] ?? null),

            'end_at' =>
                $this->extractEndDate($r['statusHistory'] ?? []),
        ];
    }

    private function extractId($ref)
    {
        if (!$ref) return null;
        return explode('/', $ref)[1] ?? null;
    }

    private function extractVisitType($identifiers)
    {
        foreach ($identifiers as $id) {
            if (str_contains($id['system'], 'episodeofcare')) {
                return $id['value'] ?? null;
            }
        }
        return null;
    }

    private function extractEndDate($history)
    {
        foreach ($history as $h) {
            if (($h['status'] ?? '') === 'finished') {
                return $this->parseDate($h['period']['end'] ?? null);
            }
        }
        return null;
    }

    private function parseDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : null;
    }
}
