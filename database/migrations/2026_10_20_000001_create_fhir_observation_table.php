<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fhir_observation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fhir_id', 150)->unique();
            $table->string('version_id', 100)->nullable();
            $table->timestamp('fhir_last_updated')->nullable();

            // Internal database IDs and original FHIR IDs are stored separately.
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->string('fhir_patient_id', 150)->nullable()->index();
            $table->unsignedBigInteger('encounter_id')->nullable()->index();
            $table->string('fhir_encounter_id', 150)->nullable()->index();

            $table->string('identifier_system', 500)->nullable();
            $table->string('identifier_value', 255)->nullable();
            $table->string('status', 40)->nullable()->index();
            $table->json('category')->nullable();
            $table->string('code_system', 500)->nullable();
            $table->string('code', 255)->nullable()->index();
            $table->string('code_display', 1000)->nullable();

            // Effective time can be a dateTime or a Period.
            $table->timestamp('effective_datetime')->nullable()->index();
            $table->timestamp('effective_start')->nullable();
            $table->timestamp('effective_end')->nullable();
            $table->timestamp('issued_at')->nullable();

            // Common value[x] types, with value_json preserving uncommon types.
            $table->string('value_type', 40)->nullable();
            $table->decimal('value_quantity', 24, 8)->nullable();
            $table->string('value_unit', 100)->nullable();
            $table->string('value_code', 255)->nullable();
            $table->string('value_display', 1000)->nullable();
            $table->text('value_string')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->bigInteger('value_integer')->nullable();
            $table->timestamp('value_datetime')->nullable();
            $table->json('value_json')->nullable();

            $table->json('interpretation')->nullable();
            $table->json('reference_range')->nullable();
            $table->json('component')->nullable();
            $table->json('performer_data')->nullable();
            $table->json('note')->nullable();
            $table->json('raw_resource');
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->index(['fhir_patient_id', 'effective_datetime'], 'obs_patient_effective_idx');
            $table->index(['fhir_encounter_id', 'code'], 'obs_encounter_code_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fhir_observation');
    }
};
