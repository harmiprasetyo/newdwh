<?php

namespace App\Console\Commands;

use App\Services\Fhir\FhirImportService;
use Illuminate\Console\Command;
use Throwable;

class ImportFhir extends Command
{
    protected $signature = 'fhir:import';

    protected $description = 'Import FHIR Patient dan Encounter';

    public function handle(FhirImportService $service): int
    {
        try {
            $result = $service->run();

            $this->info('Import FHIR selesai.');

           $this->line('Patient disimpan: ' . ($result['patient']['saved'] ?? 0));
$this->line('Encounter disimpan: ' . ($result['encounter']['saved'] ?? 0));
$this->line('Observation disimpan: ' . ($result['observation']['saved'] ?? 0));

$this->line(
    'Observation references diperbarui: ' .
    ($result['observation_references_updated'] ?? 0)
);



            return self::SUCCESS;
        } catch (Throwable $e) {
            report($e);

            $this->error('Import FHIR gagal: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
