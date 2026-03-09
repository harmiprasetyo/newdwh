<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbFhirResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_fhir_resources', function (Blueprint $table) {
            $table->uuid('fhirid')->primary();
            $table->string('fullURL')->default('-');
            //$table->string('resource_Type');
            $table->string('patient_id');
            $table->string('patient_nik');
            $table->string('patient_nik_ref');
            $table->string('patient_rmn');
            $table->string('patient_rmn_ref');
            $table->string('patient_name');
            $table->string('patient_familyname');
            $table->string('patient_gender');
            $table->string('patient_bdate');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->string('administratif_code_ref');
            $table->string('province_code');
            $table->string('province_name');
            $table->string('city_code');
            $table->string('city_name');
            $table->string('district_code');
            $table->string('district_name');
            $table->dateTime('fhir_last_update');
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
        Schema::dropIfExists('tb_fhir_resources');
    }
}
