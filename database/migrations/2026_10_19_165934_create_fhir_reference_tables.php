
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fhir_organization', function (Blueprint $table) {
            $table->id();
            $table->string('fhir_id', 150)->unique();
            $table->string('name')->nullable();
            $table->boolean('active')->nullable();
            $table->json('identifier')->nullable();
            $table->json('type')->nullable();
            $table->json('telecom')->nullable();
            $table->json('address')->nullable();
            $table->json('raw_resource');
            $table->timestamp('fhir_last_updated')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('fhir_practitioner', function (Blueprint $table) {
            $table->id();
            $table->string('fhir_id', 150)->unique();
            $table->string('name')->nullable();
            $table->boolean('active')->nullable();
            $table->string('gender', 30)->nullable();
            $table->date('birth_date')->nullable();
            $table->json('identifier')->nullable();
            $table->json('name_data')->nullable();
            $table->json('telecom')->nullable();
            $table->json('address')->nullable();
            $table->json('qualification')->nullable();
            $table->json('raw_resource');
            $table->timestamp('fhir_last_updated')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('fhir_location', function (Blueprint $table) {
            $table->id();
            $table->string('fhir_id', 150)->unique();
            $table->string('name')->nullable();
            $table->boolean('active')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('mode', 50)->nullable();
            $table->json('type')->nullable();
            $table->json('physical_type')->nullable();
            $table->string('managing_organization_id', 150)->nullable();
            $table->string('part_of_id', 150)->nullable();
            $table->json('identifier')->nullable();
            $table->json('telecom')->nullable();
            $table->json('address')->nullable();
            $table->json('position')->nullable();
            $table->json('raw_resource');
            $table->timestamp('fhir_last_updated')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->index('managing_organization_id');
            $table->index('part_of_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fhir_location');
        Schema::dropIfExists('fhir_practitioner');
        Schema::dropIfExists('fhir_organization');
    }
};
