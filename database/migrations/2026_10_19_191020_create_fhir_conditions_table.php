<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('fhir_conditions', function (Blueprint $table) {
    $table->id();
    $table->string('fhir_id')->unique();
    $table->string('version_id')->nullable();
    $table->timestamp('fhir_last_updated')->nullable();

    $table->unsignedBigInteger('patient_id')->nullable()->index();
    $table->string('fhir_patient_id')->nullable()->index();
    $table->unsignedBigInteger('encounter_id')->nullable()->index();
    $table->string('fhir_encounter_id')->nullable()->index();

    $table->string('clinical_status')->nullable();
    $table->string('verification_status')->nullable();
    $table->string('category_code')->nullable();
    $table->string('code')->nullable();
    $table->text('code_display')->nullable();
    $table->string('severity_code')->nullable();
    $table->text('severity_display')->nullable();

    $table->dateTime('onset_datetime')->nullable();
    $table->dateTime('abatement_datetime')->nullable();
    $table->dateTime('recorded_date')->nullable();

    $table->json('category')->nullable();
    $table->json('stage')->nullable();
    $table->json('body_site')->nullable();
    $table->json('evidence')->nullable();
    $table->json('note')->nullable();
    $table->json('raw_resource');

    $table->timestamp('synced_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fhir_conditions');
    }
};
