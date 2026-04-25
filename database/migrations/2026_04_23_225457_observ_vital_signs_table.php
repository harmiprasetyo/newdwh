<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ObservVitalSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('vital_signs', function (Blueprint $table) {
    $table->id();

    $table->string('patient_id');
    $table->string('encounter_id');

    $table->string('loinc_code')->nullable();
    $table->json('raw_fhir')->nullable();

    $table->float('systolic')->nullable();
    $table->float('diastolic')->nullable();
    $table->float('heart_rate')->nullable();
    $table->float('respiratory_rate')->nullable();
    $table->float('temperature')->nullable();

    $table->timestamps();

    $table->unique(['patient_id', 'encounter_id']);
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('vital_signs');
    }
}
