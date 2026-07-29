<?php

namespace App\Services\Fhir;

class ObservationParserService
{
    protected ObservationValueExtractor $extractor;

    public function __construct(
        ObservationValueExtractor $extractor
    ) {
        $this->extractor = $extractor;
    }

    /**
     * Parse Observation Bundle menjadi array yang lebih sederhana.
     *
     * @param array $bundle
     * @return array
     */
    public function parse(array $bundle): array
    {
        $results = [];

        if (empty($bundle['entry'])) {
            return $results;
        }

        foreach ($bundle['entry'] as $entry) {

            $resource = data_get($entry, 'resource');

            if (!$resource) {
                continue;
            }

            $results[] = [

                'id' => data_get($resource, 'id'),

                'category' => data_get($resource, 'category.0.coding.0.code'),

                'codes' => data_get($resource, 'code.coding',[]),

                'display' => data_get($resource, 'code.coding.0.display'),

                'status' => data_get($resource, 'status'),

                'effectiveDateTime' => data_get($resource, 'effectiveDateTime'),

                'issued' => data_get($resource, 'issued'),

                /*
                 * Semua variasi value[x]
                 * ditangani oleh extractor
                 */
                'value' => $this->extractor->extract($resource),

                /*
                 * Resource asli tetap disimpan
                 * kalau sewaktu-waktu diperlukan.
                 */
              //  'resource' => $resource,
            ];
        }

        return $results;
    }
}
