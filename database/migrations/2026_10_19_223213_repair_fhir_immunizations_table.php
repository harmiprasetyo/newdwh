<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'fhir_id' => fn (Blueprint $t) => $t->string('fhir_id')->nullable(),
            'version_id' => fn (Blueprint $t) => $t->string('version_id')->nullable(),
            'fhir_last_updated' => fn (Blueprint $t) => $t->dateTime('fhir_last_updated')->nullable(),

            'patient_id' => fn (Blueprint $t) => $t->unsignedBigInteger('patient_id')->nullable(),
            'fhir_patient_id' => fn (Blueprint $t) => $t->string('fhir_patient_id')->nullable(),
            'encounter_id' => fn (Blueprint $t) => $t->unsignedBigInteger('encounter_id')->nullable(),
            'fhir_encounter_id' => fn (Blueprint $t) => $t->string('fhir_encounter_id')->nullable(),

            'identifier_system' => fn (Blueprint $t) => $t->string('identifier_system')->nullable(),
            'identifier_value' => fn (Blueprint $t) => $t->string('identifier_value')->nullable(),

            'status' => fn (Blueprint $t) => $t->string('status')->nullable(),
            'status_reason' => fn (Blueprint $t) => $t->json('status_reason')->nullable(),

            'vaccine_code_system' => fn (Blueprint $t) => $t->string('vaccine_code_system')->nullable(),
            'vaccine_code' => fn (Blueprint $t) => $t->string('vaccine_code')->nullable(),
            'vaccine_display' => fn (Blueprint $t) => $t->text('vaccine_display')->nullable(),

            'occurrence_datetime' => fn (Blueprint $t) => $t->dateTime('occurrence_datetime')->nullable(),
            'recorded_date' => fn (Blueprint $t) => $t->dateTime('recorded_date')->nullable(),

            'primary_source' => fn (Blueprint $t) => $t->boolean('primary_source')->nullable(),
            'lot_number' => fn (Blueprint $t) => $t->string('lot_number')->nullable(),
            'expiration_date' => fn (Blueprint $t) => $t->date('expiration_date')->nullable(),

            'site' => fn (Blueprint $t) => $t->json('site')->nullable(),
            'route' => fn (Blueprint $t) => $t->json('route')->nullable(),

            'dose_quantity' => fn (Blueprint $t) => $t->decimal('dose_quantity', 15, 4)->nullable(),
            'dose_unit' => fn (Blueprint $t) => $t->string('dose_unit')->nullable(),

            'performer_reference' => fn (Blueprint $t) => $t->string('performer_reference')->nullable(),
            'performer_display' => fn (Blueprint $t) => $t->text('performer_display')->nullable(),
            'performer_data' => fn (Blueprint $t) => $t->json('performer_data')->nullable(),

            'protocol_applied' => fn (Blueprint $t) => $t->json('protocol_applied')->nullable(),
            'reaction' => fn (Blueprint $t) => $t->json('reaction')->nullable(),
            'note' => fn (Blueprint $t) => $t->json('note')->nullable(),

            'raw_resource' => fn (Blueprint $t) => $t->json('raw_resource')->nullable(),
            'synced_at' => fn (Blueprint $t) => $t->dateTime('synced_at')->nullable(),
        ];

        foreach ($columns as $name => $definition) {
            if (!Schema::hasColumn('fhir_immunizations', $name)) {
                Schema::table('fhir_immunizations', function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        }

        // Tambahkan unique index hanya jika belum ada.
        if (Schema::hasColumn('fhir_immunizations', 'fhir_id')) {
            $indexes = DB::select(
                'SHOW INDEX FROM fhir_immunizations'
            );

            $hasUniqueIndex = collect($indexes)->contains(
                fn ($index) =>
                    (int) $index->Non_unique === 0
                    && $index->Column_name === 'fhir_id'
            );

            if (!$hasUniqueIndex) {
                $duplicates = DB::table('fhir_immunizations')
                    ->select('fhir_id')
                    ->whereNotNull('fhir_id')
                    ->groupBy('fhir_id')
                    ->havingRaw('COUNT(*) > 1')
                    ->exists();

                if ($duplicates) {
                    throw new RuntimeException(
                        'Tidak dapat membuat unique index: fhir_id duplikat ditemukan.'
                    );
                }

                Schema::table('fhir_immunizations', function (Blueprint $table) {
                    $table->unique(
                        'fhir_id',
                        'fhir_immunizations_fhir_id_unique'
                    );
                });
            }
        }
    }

    public function down(): void
    {
        // Tidak menghapus kolom agar data hasil sinkronisasi tetap aman.
    }
};
