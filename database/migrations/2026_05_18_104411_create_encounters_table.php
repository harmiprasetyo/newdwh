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
      Schema::create('encounters', function (Blueprint $table) {
    $table->id();

    // FHIR ID
    $table->string('encounter_id')->unique();

    // relasi ke patient
    $table->string('patient_id')->index();

    // identifier lokal (no kunjungan)
    $table->string('identifier')->nullable();

    // status
    $table->string('status')->nullable();

    // class (AMB / IMP)
    $table->string('class_code')->nullable();
    $table->string('class_display')->nullable();

    // dokter / tenaga kesehatan
    $table->string('practitioner_name')->nullable();
    $table->string('practitioner_id')->nullable();

    // lokasi
    $table->string('location_name')->nullable();
    $table->string('location_id')->nullable();

    // waktu
    $table->dateTime('start')->nullable();
    $table->dateTime('end')->nullable();

    $table->timestamps();


});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounters');
    }
};
