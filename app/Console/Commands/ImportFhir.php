<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportFhir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-fhir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }



    protected $signature = 'fhir:import';

    public function handle()
    {
        app(\App\Http\Controllers\Api\FhirImportController::class)
            ->import();

        $this->info('FHIR import selesai');
    }
}
