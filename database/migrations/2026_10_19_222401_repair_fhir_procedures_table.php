<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fhir_procedures', function (Blueprint $table) {
            // Identitas dan metadata FHIR
            if (!Schema::hasColumn('fhir_procedures', 'fhir_id')) {
                $table->string('fhir_id')->nullable()->unique();
            }

            if (!Schema::hasColumn('fhir_procedures', 'version_id')) {
                $table->string('version_id')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'fhir_last_updated')) {
                $table->dateTime('fhir_last_updated')->nullable();
            }

            // Referensi pasien dan encounter
            if (!Schema::hasColumn('fhir_procedures', 'patient_id')) {
                $table->unsignedBigInteger('patient_id')->nullable()->index();
            }

            if (!Schema::hasColumn('fhir_procedures', 'fhir_patient_id')) {
                $table->string('fhir_patient_id')->nullable()->index();
            }

            if (!Schema::hasColumn('fhir_procedures', 'encounter_id')) {
                $table->unsignedBigInteger('encounter_id')->nullable()->index();
            }

            if (!Schema::hasColumn('fhir_procedures', 'fhir_encounter_id')) {
                $table->string('fhir_encounter_id')->nullable()->index();
            }

            // Identifier
            if (!Schema::hasColumn('fhir_procedures', 'identifier_system')) {
                $table->string('identifier_system')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'identifier_value')) {
                $table->string('identifier_value')->nullable();
            }

            // Status dan klasifikasi
            if (!Schema::hasColumn('fhir_procedures', 'status')) {
                $table->string('status')->nullable()->index();
            }

            if (!Schema::hasColumn('fhir_procedures', 'status_reason')) {
                $table->json('status_reason')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'category')) {
                $table->json('category')->nullable();
            }

            // Kode tindakan
            if (!Schema::hasColumn('fhir_procedures', 'code_system')) {
                $table->string('code_system')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'code')) {
                $table->string('code')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'code_display')) {
                $table->text('code_display')->nullable();
            }

            // Waktu pelaksanaan
            if (!Schema::hasColumn('fhir_procedures', 'performed_datetime')) {
                $table->dateTime('performed_datetime')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'performed_start')) {
                $table->dateTime('performed_start')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'performed_end')) {
                $table->dateTime('performed_end')->nullable();
            }

            // Pelaksana
            if (!Schema::hasColumn('fhir_procedures', 'performer_reference')) {
                $table->string('performer_reference')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'performer_display')) {
                $table->text('performer_display')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'performer_data')) {
                $table->json('performer_data')->nullable();
            }

            // Lokasi
            if (!Schema::hasColumn('fhir_procedures', 'location_reference')) {
                $table->string('location_reference')->nullable();
            }

            // Alasan, lokasi tubuh, hasil, komplikasi, dan catatan
            if (!Schema::hasColumn('fhir_procedures', 'reason_code')) {
                $table->json('reason_code')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'reason_reference')) {
                $table->json('reason_reference')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'body_site')) {
                $table->json('body_site')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'outcome')) {
                $table->json('outcome')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'complication')) {
                $table->json('complication')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'note')) {
                $table->json('note')->nullable();
            }

            // Resource FHIR asli dan waktu sinkronisasi
            if (!Schema::hasColumn('fhir_procedures', 'raw_resource')) {
                $table->json('raw_resource')->nullable();
            }

            if (!Schema::hasColumn('fhir_procedures', 'synced_at')) {
                $table->dateTime('synced_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Sengaja tidak menghapus kolom agar rollback tidak
        // menghilangkan data hasil sinkronisasi FHIR.
    }
};
