<?php

namespace App\Services\Fhir;

class ObservationValueExtractor
{
    /**
     * Extract value[x] dari Observation Resource
     */
    public function extract(array $resource): array
    {
        // Quantity
        if (isset($resource['valueQuantity'])) {

            return [
                'type' => 'Quantity',
                'value' => data_get($resource, 'valueQuantity.value'),
                'unit' => data_get($resource, 'valueQuantity.unit')
                    ?? data_get($resource, 'valueQuantity.code'),
                'raw' => $resource['valueQuantity'],
            ];
        }

        // Integer
        if (isset($resource['valueInteger'])) {

            return [
                'type' => 'Integer',
                'value' => $resource['valueInteger'],
            ];
        }

        // Decimal
        if (isset($resource['valueDecimal'])) {

            return [
                'type' => 'Decimal',
                'value' => $resource['valueDecimal'],
            ];
        }

        // String
        if (isset($resource['valueString'])) {

            return [
                'type' => 'String',
                'value' => $resource['valueString'],
            ];
        }

        // Boolean
        if (isset($resource['valueBoolean'])) {

            return [
                'type' => 'Boolean',
                'value' => $resource['valueBoolean'],
            ];
        }

        // Date
        if (isset($resource['valueDate'])) {

            return [
                'type' => 'Date',
                'value' => $resource['valueDate'],
            ];
        }

        // DateTime
        if (isset($resource['valueDateTime'])) {

            return [
                'type' => 'DateTime',
                'value' => $resource['valueDateTime'],
            ];
        }

        // Time
        if (isset($resource['valueTime'])) {

            return [
                'type' => 'Time',
                'value' => $resource['valueTime'],
            ];
        }

        // CodeableConcept
        if (isset($resource['valueCodeableConcept'])) {

            return [
                'type' => 'CodeableConcept',
                'coding' => data_get($resource, 'valueCodeableConcept.coding', []),
                'text' => data_get($resource, 'valueCodeableConcept.text'),
                'raw' => $resource['valueCodeableConcept'],
            ];
        }

        // Reference
        if (isset($resource['valueReference'])) {

            return [
                'type' => 'Reference',
                'reference' => data_get($resource, 'valueReference.reference'),
                'display' => data_get($resource, 'valueReference.display'),
                'raw' => $resource['valueReference'],
            ];
        }

        // Range
        if (isset($resource['valueRange'])) {

            return [
                'type' => 'Range',
                'low' => data_get($resource, 'valueRange.low'),
                'high' => data_get($resource, 'valueRange.high'),
                'raw' => $resource['valueRange'],
            ];
        }

        // Ratio
        if (isset($resource['valueRatio'])) {

            return [
                'type' => 'Ratio',
                'numerator' => data_get($resource, 'valueRatio.numerator'),
                'denominator' => data_get($resource, 'valueRatio.denominator'),
                'raw' => $resource['valueRatio'],
            ];
        }

        // Period
        if (isset($resource['valuePeriod'])) {

            return [
                'type' => 'Period',
                'start' => data_get($resource, 'valuePeriod.start'),
                'end' => data_get($resource, 'valuePeriod.end'),
                'raw' => $resource['valuePeriod'],
            ];
        }

        // Attachment
        if (isset($resource['valueAttachment'])) {

            return [
                'type' => 'Attachment',
                'raw' => $resource['valueAttachment'],
            ];
        }

        // SampledData
        if (isset($resource['valueSampledData'])) {

            return [
                'type' => 'SampledData',
                'raw' => $resource['valueSampledData'],
            ];
        }

        return [
            'type' => 'Unknown',
            'raw' => null,
        ];
    }
}
