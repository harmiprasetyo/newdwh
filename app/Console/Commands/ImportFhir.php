<?php
namespace App\Console\Commands;

use App\Services\Fhir\FhirImportService;
use Illuminate\Console\Command;
use Throwable;

class ImportFhir extends Command
{
    protected $signature = 'fhir:import';

    protected $description =
        'Import FHIR Patient, Encounter, Observation, Condition, Procedure, Immunization dan References';

    public function handle(FhirImportService $service): int
    {
        try {
            $this->info('Memulai proses import FHIR...');
            $this->newLine();

            $result = $service->run();

            $hasError = false;

            /*
            |--------------------------------------------------------------------------
            | Import Resource Utama
            |--------------------------------------------------------------------------
            */

            $resources = [
                'patient'      => 'Patient',
                'encounter'    => 'Encounter',
                'observation'  => 'Observation',
                'condition'    => 'Condition',
                'procedure'    => 'Procedure',
                'immunization' => 'Immunization',
            ];

            $this->info('=== HASIL IMPORT RESOURCE ===');

            foreach ($resources as $key => $label) {

                $data = $result[$key] ?? [];

                if (($data['success'] ?? true) === false) {
                    $hasError = true;

                    $this->error(
                        "{$label} gagal: " .
                        ($data['error'] ?? 'Unknown error')
                    );

                    continue;
                }

                $count = $data['saved']
                    ?? $data['processed']
                    ?? 0;

                $this->line(
                    "{$label} diproses: {$count}"
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Import Encounter References
            |--------------------------------------------------------------------------
            */

            $this->newLine();
            $this->info('=== HASIL IMPORT REFERENCES ===');

            $references = $result['references'] ?? null;

            if (!is_array($references)) {

                $hasError = true;

                $this->error(
                    'Hasil import references tidak tersedia.'
                );

            } elseif (!empty($references['error'])) {

                $hasError = true;

                $this->error(
                    'Import references gagal: ' .
                    $references['error']
                );

            } else {

                foreach (
                    ['organization', 'practitioner', 'location']
                    as $type
                ) {

                    $stats = $references[$type] ?? [];

                    $found = $stats['found'] ?? 0;
                    $saved = $stats['saved'] ?? 0;
                    $failed = $stats['failed'] ?? 0;

                    $this->line(
                        ucfirst($type) .
                        " | ditemukan: {$found}" .
                        " | disimpan: {$saved}" .
                        " | gagal: {$failed}"
                    );

                    if ($failed > 0) {
                        $hasError = true;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Backfill Observation References
            |--------------------------------------------------------------------------
            */

            $this->newLine();
            $this->info('=== BACKFILL REFERENCES ===');

            $this->line(
                'Observation references diperbarui: ' .
                ($result['observation_references_updated'] ?? 0)
            );

            if (!empty($result['backfill_error'])) {
                $hasError = true;

                $this->error(
                    'Backfill gagal: ' .
                    $result['backfill_error']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Final Status
            |--------------------------------------------------------------------------
            */

            $this->newLine();

            if ($hasError) {

                $this->warn(
                    'Import FHIR selesai dengan beberapa kegagalan.'
                );

                return self::FAILURE;
            }

            $this->info(
                'Seluruh proses import FHIR berhasil diselesaikan.'
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            report($e);

            $this->error(
                'Import FHIR gagal: ' . $e->getMessage()
            );

            return self::FAILURE;
        }
    }
}
