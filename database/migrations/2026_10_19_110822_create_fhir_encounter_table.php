<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fhir_encounter', function (Blueprint $table) {
            $table->id();

            // Identitas resource Encounter dari server FHIR
            $table->string('fhir_id', 100)->unique();
            $table->string('version_id', 50)->nullable();
            $table->timestamp('fhir_last_updated')->nullable();

            // Relasi ke tabel fhir_patient
            $table->foreignId('patient_id')
                ->nullable()
                ->constrained('fhir_patient')
                ->nullOnDelete();

            // Identifier Encounter
            $table->string('identifier_system', 255)->nullable();
            $table->string('identifier_value', 100)->nullable();

            // Klasifikasi kunjungan
            $table->string('class_code', 50)->nullable();
            $table->string('class_display')->nullable();

            // Data fasilitas dan lokasi
            $table->string('service_provider_id', 100)->nullable();
            $table->string('service_provider')->nullable();
            $table->string('location_id', 100)->nullable();
            $table->string('location')->nullable();

            // Data kunjungan
            $table->string('status', 50)->nullable();
            $table->dateTime('encounter_date')->nullable();
            $table->dateTime('encounter_end')->nullable();

            // Resource asli dari server FHIR
            $table->json('raw_resource')->nullable();
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            $table->index('patient_id');
            $table->index('encounter_date');
            $table->index('status');
            $table->index(['identifier_system', 'identifier_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fhir_encounter');
    }
};
