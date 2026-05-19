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
     Schema::create('immunization_records', function (Blueprint $table) {
    $table->id();
    $table->string('immunization_id')->unique();
    $table->string('patient_id')->index();
    $table->string('encounter_id')->nullable();

    $table->string('vaccine_code')->nullable();
    $table->string('vaccine_name')->nullable();

    $table->dateTime('immunization_date')->nullable();
    $table->dateTime('recorded_at')->nullable();

    $table->string('location_name')->nullable();
    $table->string('service_provider_id')->nullable();
    $table->string('service_provider_name')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immunization_records');
    }
};
