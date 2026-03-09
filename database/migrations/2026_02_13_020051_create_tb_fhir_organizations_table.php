<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use illuminate\Support\Str;

class CreateTbFhirOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_fhir_organization', function (Blueprint $table) {
            $table->uuid('organizationID')->default(Str::uuid());
            $table->string('fullUrl');
$table->string('resourceType');
$table->string('id');
$table->string('versionId');
$table->string('lastUpdated');
$table->string('IdentifierSystem');
$table->string('IdentifierRef')->default('-');
$table->string('IdentifierValue');
$table->string('active');
$table->string('typeCode');
$table->string('typeDisplay');
$table->string('name');
$table->string('telecomPhone');
$table->string('telecomEmail');
$table->string('addressInfo');
$table->string('addressRef');
$table->string('addressProvinceCode');
$table->string('addressCityCode');
$table->string('addressDistrictCode');
$table->string('addressVillageCode');
$table->string('addressUse');
$table->string('addressPostalCode');
$table->string('addressCountry');
$table->string('mode');
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
        Schema::dropIfExists('tb_fhir_organization');
    }
}
