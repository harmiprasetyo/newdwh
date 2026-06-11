<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FhirImportService;

class ImportFhir extends Command
{
    protected $signature = 'fhir:import';
    protected $description = 'Import data FHIR';

    public function handle(FhirImportService $service)
    {
        $this->info('Start import FHIR...');

        $service->run();

        $this->info('Done!');
    }
}
