<?php
namespace App\Console\Commands;

use App\Services\Fhir\FhirImportService;
use Illuminate\Console\Command;
use Throwable;

class ImportFhirUpdated extends Command
{
    protected $signature = 'fhir:import-updated';

    protected $description =
        'Sinkronisasi resource FHIR yang baru atau diperbarui';

    public function handle(FhirImportService $service): int
    {
        $this->info('Memulai incremental import FHIR...');

        try {
            $results = $service->runIncremental();

            $failed = false;

            foreach ($results as $resource => $result) {
                if (!($result['success'] ?? false)) {
                    $failed = true;

                    $this->error(
                        "{$resource}: " .
                        ($result['error'] ?? 'Gagal')
                    );

                    continue;
                }

                $this->line(
                    "{$resource}: " .
                    ($result['processed'] ?? 0) .
                    ' data diproses'
                );
            }

            return $failed
                ? self::FAILURE
                : self::SUCCESS;

        } catch (Throwable $e) {
            report($e);

            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
