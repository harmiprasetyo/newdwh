
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fhir_encounter', function (Blueprint $table) {
            $table->string('fhir_patient_id', 100)->nullable()->index();
            $table->string('patient_display')->nullable();

            $table->string('practitioner_id', 150)->nullable();
            $table->string('practitioner_display')->nullable();

            $table->string('episode_of_care_id', 150)->nullable();

            $table->json('status_history')->nullable();
            $table->json('participant_data')->nullable();
            $table->json('location_data')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('fhir_encounter', function (Blueprint $table) {
            $table->dropIndex(['fhir_patient_id']);

            $table->dropColumn([
                'fhir_patient_id',
                'patient_display',
                'practitioner_id',
                'practitioner_display',
                'episode_of_care_id',
                'status_history',
                'participant_data',
                'location_data',
            ]);
        });
    }
};
