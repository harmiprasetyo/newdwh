<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use illuminate\Support\Str;

class CreateFHIRResourcePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('FHIR_resource_patient', function (Blueprint $table) {
            $table->uuid('pid')->primray()->default(Str::uuid());
            $table->string('fullurl')->nullable();
            $table->string('resourceType')->nullable();
            $table->string('id')->nullable();
            $table->string('meta_versionId')->nullable();
            $table->string('meta_lastUpdated')->nullable();
            $table->string('identifier_use_0')->nullable();
            $table->string('identifier_system_0')->nullable();
            $table->string('identifier_value_0')->nullable();
            $table->string('identifier_use_1')->nullable();
            $table->string('identifier_system_1')->nullable();
            $table->string('identifier_value_1')->nullable();
            $table->string('name_use')->nullable();
            $table->string('name_text')->nullable();
            $table->string('name_family')->nullable();
            $table->string('telecom_system_0')->nullable();
            $table->string('telecom_value_0')->nullable();
            $table->string('telecom_use_0')->nullable();
            $table->string('telecom_system_1')->nullable();
            $table->string('telecom_value_1')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthDate')->nullable();
            $table->string('address_extension_0_url_0')->nullable();
           $table->string('address_extension_0_extension_0_url_0')->nullable();
           $table->string('address_extension_0_extension_0_valueCoding_system_0')->nullable();
           $table->string('address_extension_0_extension_0_valueCoding_code_0')->nullable();
           $table->string('address_extension_0_extension_0_valueCoding_display_0')->nullable();
           $table->string('address_extension_0_extension_1_url_0')->nullable();
           $table->string('address_extension_0_extension_1_valueCoding_system_0')->nullable();
           $table->string('address_extension_0_extension_1_valueCoding_code_0')->nullable();
           $table->string('address_extension_0_extension_1_valueCoding_display_0')->nullable();
           $table->string('address_extension_0_extension_2_url_0')->nullable();
           $table->string('address_extension_0_extension_2_valueCoding_system_0')->nullable();
           $table->string('address_extension_0_extension_2_valueCoding_code_0')->nullable();
           $table->string('address_extension_0_extension_2_valueCoding_display_0')->nullable();
           $table->string('address_use_0')->nullable();
           $table->string('mode')->nullable();
           $table->timestamp('creation_date')->useCurrent();
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
        Schema::dropIfExists('FHIR_resource_patient');
    }
}
