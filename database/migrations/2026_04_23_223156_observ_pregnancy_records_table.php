<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ObservPregnancyRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pregnancy_records', function (Blueprint $table) {
    $table->id();

    $table->string('patient_id');
    $table->string('encounter_id');

    $table->string('loinc_code')->nullable();
    $table->json('raw_fhir')->nullable();

    $table->integer('gravida')->nullable();
    $table->integer('parity')->nullable();
    $table->integer('abortus')->nullable();

    $table->date('lmp')->nullable();
    $table->date('edd')->nullable();

    $table->float('gestational_age')->nullable();
    $table->integer('trimester')->nullable();

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
          Schema::dropIfExists('pregnancy_records');
    }
}
