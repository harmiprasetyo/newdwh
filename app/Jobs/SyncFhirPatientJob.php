<?php
namespace App\Jobs;

use App\Services\Fhir\FhirPatientService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncFhirPatientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function backoff(): array
    {
        return [30, 60, 120];
    }

    public function handle(FhirPatientService $service): void
    {
        $result = $service->syncNextPage();

        Log::info('FHIR Patient sync selesai', $result);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('FHIR Patient sync gagal', [
            'message' => $exception->getMessage(),
        ]);
    }
}
