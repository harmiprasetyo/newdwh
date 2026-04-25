<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ObservMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('measurements', function (Blueprint $table) {
    $table->id();

    $table->string('patient_id');
    $table->string('encounter_id');

    $table->string('loinc_code')->nullable();
    $table->json('raw_fhir')->nullable();

    $table->float('height')->nullable();
    $table->float('weight')->nullable();
    $table->float('pre_weight')->nullable();

    $table->float('bmi')->nullable();
    $table->float('lila')->nullable();
    $table->float('sfh')->nullable();

    $table->string('bmi_status')->nullable();

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
        Schema::dropIfExists('measurements');
    }
}
