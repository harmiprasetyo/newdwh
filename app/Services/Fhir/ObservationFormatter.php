<?php

namespace App\Services\Fhir;

use Carbon\Carbon;

class ObservationFormatter
{
    /**
     * Format value hasil ObservationValueExtractor
     *
     * @param array|null $value
     * @param array $options
     * @return mixed
     */
    public function format(?array $value, array $options = [])
    {
        if (!$value) {
            return '-';
        }

        switch ($value['type']) {

            case 'Quantity':
                return $this->formatQuantity($value);

            case 'Integer':
            case 'Decimal':
            case 'String':
                return $value['value'];

            case 'Boolean':
                return $this->formatBoolean($value);

            case 'Date':
                return $this->formatDate(
                    $value,
                    $options['format'] ?? 'd/m/Y'
                );

            case 'DateTime':
                return $this->formatDate(
                    $value,
                    $options['format'] ?? 'd/m/Y H:i:s'
                );

            case 'Time':
                return $value['value'];

            case 'CodeableConcept':
                return $this->formatCodeableConcept($value);

            case 'Reference':
                return $this->formatReference($value);

            case 'Range':
                return $this->formatRange($value);

            case 'Ratio':
                return $this->formatRatio($value);

            case 'Period':
                return $this->formatPeriod(
                    $value,
                    $options['format'] ?? 'd/m/Y'
                );

            case 'Attachment':
                return $this->formatAttachment($value);

            case 'SampledData':
                return '[SampledData]';

            default:
                return '-';
        }
    }

    protected function formatQuantity(array $value): string
    {
        return trim(
            ($value['value'] ?? '') .
            ' ' .
            ($value['unit'] ?? '')
        );
    }

    protected function formatBoolean(array $value): string
    {
        return ($value['value'] ?? false)
            ? 'Ya'
            : 'Tidak';
    }

    protected function formatDate(array $value, string $format): string
    {
        if (empty($value['value'])) {
            return '-';
        }

        return Carbon::parse($value['value'])->format($format);
    }

    protected function formatCodeableConcept(array $value): string
    {
        if (!empty($value['text'])) {
            return $value['text'];
        }

        if (!empty($value['coding'])) {

            $display = collect($value['coding'])
                ->pluck('display')
                ->filter()
                ->implode(', ');

            if ($display !== '') {
                return $display;
            }

            return collect($value['coding'])
                ->pluck('code')
                ->filter()
                ->implode(', ');
        }

        return '-';
    }

    protected function formatReference(array $value): string
    {
        return $value['display']
            ?? $value['reference']
            ?? '-';
    }

    protected function formatRange(array $value): string
    {
        $low = data_get($value, 'low.value');
        $high = data_get($value, 'high.value');

        $unit = data_get($value, 'low.unit')
            ?? data_get($value, 'high.unit')
            ?? '';

        return trim("{$low} - {$high} {$unit}");
    }

    protected function formatRatio(array $value): string
    {
        $num = data_get($value, 'numerator.value');
        $den = data_get($value, 'denominator.value');

        return "{$num}/{$den}";
    }

    protected function formatPeriod(array $value, string $format): string
    {
        $start = data_get($value, 'start');
        $end = data_get($value, 'end');

        if (!$start && !$end) {
            return '-';
        }

        $start = $start
            ? Carbon::parse($start)->format($format)
            : '';

        $end = $end
            ? Carbon::parse($end)->format($format)
            : '';

        return trim($start . ' - ' . $end);
    }

    protected function formatAttachment(array $value): string
    {
        return data_get($value, 'raw.title')
            ?? data_get($value, 'raw.url')
            ?? '[Attachment]';
    }
}
