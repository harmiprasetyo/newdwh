<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use illuminate\Support\Str;


class CreateTbFhirEncountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_fhir_encounter', function (Blueprint $table) {
            $table->uuid('encounterID')->default(Str::uuid());
            $table->string('fullUrl');
            $table->string('id');
            $table->string('episodeOfcare');
            $table->string('ANCSeries');
            $table->string('status');

$table->string('historyStatus');
$table->string('startPeriodHS');
$table->string('classRef');
$table->string('classCode');
$table->string('classDisplay');
$table->string('PatientRef');
$table->string('PatientName');
$table->string('EOCref');
$table->string('ParticipantRef');
$table->string('ParticipantCode');
$table->string('participantDisplay');
$table->string('PractitionerRef');
$table->string('PractitionerName');
$table->string('locationRef');
$table->string('locationDisplay');
$table->string('serviceProviderRef');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_fhir_encounters');
    }
}
