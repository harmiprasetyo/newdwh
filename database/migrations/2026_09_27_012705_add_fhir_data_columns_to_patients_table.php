<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->string('resource_type', 50)
                ->nullable()
                ->after('patient_id');

            $table->string('fhir_version', 50)
                ->nullable()
                ->after('resource_type');

            $table->timestamp('fhir_last_updated')
                ->nullable()
                ->after('updated_at');

            $table->timestamp('fhir_last_sync_at')
                ->nullable()
                ->after('fhir_last_updated');

            $table->string('fhir_etag', 255)
                ->nullable()
                ->after('fhir_last_sync_at');

            $table->json('fhir_meta')
                ->nullable()
                ->after('fhir_etag');

            $table->json('fhir_resource')
                ->nullable()
                ->after('fhir_meta');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->dropColumn([
                'resource_type',
                'fhir_version',
                'fhir_last_updated',
                'fhir_last_sync_at',
                'fhir_etag',
                'fhir_meta',
                'fhir_resource',
            ]);
        });
    }
};
